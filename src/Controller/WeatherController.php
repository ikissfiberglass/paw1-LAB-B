<?php

namespace App\Controller;

use App\Entity\ForecastRecord;
use App\Entity\Location;
use App\Repository\ForecastRecordRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(string $city, LocationRepository $locationRepository, ForecastRecordRepository $repository): Response
    {
//        $measurements = $repository->findByLocation($location);
//        dump($measurements);
//        die();

        $location = $locationRepository->findOneBy(['city' => $city]);
        $measurements = $repository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            'controller_name' => 'WeatherController',
            'location' => $location,
            'measurements' => $measurements

        ]);
    }
}
