<?php

namespace App\Controller;

use App\Entity\ForecastRecord;
use App\Entity\Location;
use App\Repository\ForecastRecordRepository;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(string $city, LocationRepository $locationRepository,WeatherUtil $util): Response
    {
        $location = $locationRepository->findOneBy(['city' => $city]);
        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
//            'controller_name' => 'WeatherController', // , odkomentować jak coś nie zadziała
            'location' => $location,
            'measurements' => $measurements

        ]);
    }
}
