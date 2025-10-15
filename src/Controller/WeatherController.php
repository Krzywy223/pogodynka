<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

final class WeatherController extends AbstractController
{
    #[Route(
        '/weather/{city}/{country?}',
        name: 'app_weather',
        requirements: [
            'city' => '[\p{L}\p{M}\-\'\.\s]+',
            'country' => '[A-Za-z]{2}'
        ]
    )]
    public function city(
        #[MapEntity(expr: 'repository.findOneByCityAndOptionalCountry(city, country)')]
        Location $location,
        MeasurementRepository $repository
    ): Response {
        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location'     => $location,
            'measurements' => $measurements,
        ]);
    }
}
