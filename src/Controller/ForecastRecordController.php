<?php

namespace App\Controller;

use App\Entity\ForecastRecord;
use App\Entity\Location;
use App\Form\ForecastRecordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForecastRecordController extends AbstractController
{
    #[Route('/location/{locationId}/forecast/new', name: 'app_forecast_new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_FORECASTRECORD_NEW')]
    public function new(Request $request, int $locationId, EntityManagerInterface $entityManager): Response
    {
        $location = $entityManager->getRepository(Location::class)->find($locationId);
        if (!$location) {
            throw $this->createNotFoundException('Location not found.');
        }

        $forecast = new ForecastRecord();
        $forecast->setLocation($location);

        $form = $this->createForm(ForecastRecordType::class, $forecast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($forecast);
            $entityManager->flush();

            return $this->redirectToRoute('app_location_edit', ['id' => $location->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/forecast_new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/location/{locationId}/forecast/{id}/edit', name: 'app_forecast_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_FORECASTRECORD_EDIT')]
    public function edit(Request $request, int $locationId, ForecastRecord $forecast, EntityManagerInterface $entityManager): Response
    {
        $location = $entityManager->getRepository(Location::class)->find($locationId);
        if (!$location) {
            throw $this->createNotFoundException('Location not found.');
        }

        $form = $this->createForm(ForecastRecordType::class, $forecast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather', ['city' => $location->getCity()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/forecast_edit.html.twig', [
            'location' => $location,
            'forecast' => $forecast,
            'form' => $form,
        ]);
    }

    #[Route('/location/{locationId}/forecast/{id}/delete', name: 'app_forecast_delete', methods: ['POST'])]
    #[isGranted('ROLE_FORECASTRECORD_DELETE')]
    public function delete(Request $request, int $locationId, ForecastRecord $forecast, EntityManagerInterface $entityManager): Response
    {
        $location = $entityManager->getRepository(Location::class)->find($locationId);
        if (!$location) {
            throw $this->createNotFoundException('Location not found.');
        }

        if ($this->isCsrfTokenValid('delete'.$forecast->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forecast);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather', ['city' => $location->getCity()], Response::HTTP_SEE_OTHER);
    }
}
