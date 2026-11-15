<?php

namespace App\Tests\Entity;

use App\Entity\ForecastRecord;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(float $celsius, float $expectedFahrenheit): void
    {
        $measurement = new ForecastRecord();

        $measurement->setTemperatureC($celsius);

        $this->assertEqualsWithDelta(
            $expectedFahrenheit,
            $measurement->getFahrenheit(),
            0.0001
        );
    }
    public static function dataGetFahrenheit(): array {

        return [
            [0.0, 32.0],
            [-100.0, -148.0],
            [100.0, 212.0],
            [0.5, 32.9],
            [1.0, 33.8],
            [10.0, 50.0],
            [20.0, 68.0],
            [36.6, 97.88],
            [-40.0, -40.0],
            [50.5, 122.9],
        ];
    }
}
