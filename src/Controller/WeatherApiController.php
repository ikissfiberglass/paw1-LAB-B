<?php

namespace App\Controller;

use App\Entity\ForecastRecord;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;


final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(WeatherUtil $weatherUtil, #[MapQueryParameter] string $city, #[MapQueryParameter] string $country, #[MapQueryParameter] string $format, #[MapQueryParameter] bool $twig = false): Response
    {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', ['city' => $city, 'country' => $country, 'measurements' => $measurements]);
            }


            return $this->render('weather_api/index.json.twig', ['city' => $city, 'country' => $country, 'measurements' => $measurements]);

        }


        if ($format === 'csv') {

            $lines = [];
            $lines[] = 'city,country,date,celsius, fahrenheit';

            foreach ($measurements as $fr) {
                $lines[] = sprintf(
                    '%s,%s,%s,%s, %s',
                    $city,
                    $country,
                    $fr->getTimestamp()->format('Y-m-d'),
                    $fr->getTemperatureC(),
                    $fr->getTemperatureF()
                );
            }

            return new Response(
                implode("\n", $lines),
                200,
                ['Content-Type' => 'text/csv']
            );
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(
                fn (ForecastRecord $fr) => [
                    'date' => $fr->getTimestamp()->format('Y-m-d'),
                    'celsius' => $fr->getTemperatureC(),
                    'fahrenheit' => $fr->getFahrenheit(),
                ],
                $measurements
            ),
        ]);

    }
}
