<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;

abstract class AbstractCollectAirQualityCommandFactory
{
    abstract public function createCommand(): AbstractCollectAirQualityCommand;
}