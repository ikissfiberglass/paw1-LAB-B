<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ForecastRecord;
use App\Entity\Location;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherUtil
{
    public function __construct(private readonly HttpClientInterface $httpClient,) {}

    /**
     * @return ForecastRecord[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $response = $this->httpClient->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => (float) $location->getLatitude(),
                'longitude' => (float) $location->getLongitude(),
                'hourly' => 'temperature_2m,relative_humidity_2m,pressure_msl,wind_speed_10m,precipitation_probability',
                'timezone' => 'auto',
                'forecast_days' => 1,
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            return [];
        }

        $data = $response->toArray();
        $hourly = $data['hourly'] ?? [];
        $times = $hourly['time'] ?? [];
        $temperatures = $hourly['temperature_2m'] ?? [];
        $humidity = $hourly['relative_humidity_2m'] ?? [];
        $pressure = $hourly['pressure_msl'] ?? [];
        $wind = $hourly['wind_speed_10m'] ?? [];
        $precipitationProbability = $hourly['precipitation_probability'] ?? [];

        $records = [];

        foreach ($times as $index => $time) {
            if (!isset($temperatures[$index])) {
                continue;
            }

            $record = new ForecastRecord();
            $record->setTimestamp(new \DateTime($time));
            $record->setTemperatureC((float) $temperatures[$index]);
            $record->setHumidity(isset($humidity[$index]) ? (float) $humidity[$index] : null);
            $record->setAthmosphericPressure(isset($pressure[$index]) ? (float) $pressure[$index] : 0.0);
            $record->setWindSpeed(isset($wind[$index]) ? (int) round((float) $wind[$index]) : null);
            $record->setAnyPrecipitationsForTheNearest6Hours(
                isset($precipitationProbability[$index]) && (float) $precipitationProbability[$index] > 0.0
            );
            $record->setLocation($location);

            $records[] = $record;
        }

        return $records;
    }

    public function getWeatherForCountryAndCity(string $countryCode, string $city): array{
        $response = $this->httpClient->request('GET', 'https://geocoding-api.open-meteo.com/v1/search', [
            'query' => [
                'name' => $city,
                'country_code' => $countryCode,
                'count' => 1,
                'language' => 'en',
                'format' => 'json',
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            return [];
        }

        $data = $response->toArray();
        $results = $data['results'] ?? [];

        if (!$results) {
            return [];
        }

        $location = new Location();
        $location->setCity($results[0]['name'] ?? $city);
        $location->setCountry($results[0]['country_code'] ?? $countryCode);
        $location->setLatitude((string) ($results[0]['latitude'] ?? 0));
        $location->setLongitude((string) ($results[0]['longitude'] ?? 0));

        return $this->getWeatherForLocation($location);
    }
}

