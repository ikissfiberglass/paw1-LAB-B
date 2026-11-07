<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
#[AsCommand(
    name: 'weather:location:find',
    description: 'Add a short description for your command',
)]
class WeatherLocationFindCommand{

    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('country code')] string $country,
        #[Argument('city name')] string $city,
    ): int {
//        $io->note(sprintf('The value of $country is: %s', var_export($country, true)));
//        $io->note(sprintf('The value of $city is: %s', var_export($city, true)));
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
//
//        return Command::SUCCESS;
        $location = $this->locationRepository // tu trzeba zwrócić uwagę jak coś znowu nie zadziała, troche tricky
            ->findByCountryAndCity($country, $city);

        if ($location === null) {
            $io->error('Location not found');

            return Command::FAILURE;
        }

        $io->table(
            ['ID', 'City', 'Country', 'Latitude', 'Longitude'],
            [[
                $location->getId(),
                $location->getCity(),
                $location->getCountry(),
                $location->getLatitude(),
                $location->getLongitude(),
            ]]
        );

        return Command::SUCCESS;
    }
}
