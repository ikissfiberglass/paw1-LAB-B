<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Add a short description for your command',
)]
class WeatherLocationCommand{
    public function __construct(private readonly LocationRepository $locationRepository, private readonly WeatherUtil $weatherUtil)
    {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);
//        if (null === $location) {}
        $measurements = $this->weatherUtil->getWeatherForLocation($location);
//        $io->note(sprintf('The value of $id is: %s', var_export($id, true)));
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $io->writeln(sprintf('Location: %s', $location->getCity()));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getTimestamp()->format('Y-m-d'),
            $m->getTemperatureC(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
