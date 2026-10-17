<?php

namespace App\Entity;

use App\Repository\ForecastRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForecastRecordRepository::class)]
class ForecastRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $timestamp = null;

    #[ORM\Column]
    private ?float $temperature_c = null;

    #[ORM\Column(nullable: true)]
    private ?float $humidity = null;

    #[ORM\Column]
    private ?float $athmospheric_pressure = null;

    #[ORM\Column(nullable: true)]
    private ?int $wind_speed = null;

    #[ORM\Column(nullable: true)]
    private ?bool $any_precipitations_for_the_nearest_6_hours = null;

    #[ORM\ManyToOne(inversedBy: 'forecastRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTemperatureC(): ?float
    {
        return $this->temperature_c;
    }

    public function setTemperatureC(float $temperature_c): static
    {
        $this->temperature_c = $temperature_c;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(?float $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getAthmosphericPressure(): ?float
    {
        return $this->athmospheric_pressure;
    }

    public function setAthmosphericPressure(float $athmospheric_pressure): static
    {
        $this->athmospheric_pressure = $athmospheric_pressure;

        return $this;
    }

    public function getWindSpeed(): ?int
    {
        return $this->wind_speed;
    }

    public function setWindSpeed(?int $wind_speed): static
    {
        $this->wind_speed = $wind_speed;

        return $this;
    }

    public function isAnyPrecipitationsForTheNearest6Hours(): ?bool
    {
        return $this->any_precipitations_for_the_nearest_6_hours;
    }

    public function setAnyPrecipitationsForTheNearest6Hours(?bool $any_precipitations_for_the_nearest_6_hours): static
    {
        $this->any_precipitations_for_the_nearest_6_hours = $any_precipitations_for_the_nearest_6_hours;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
