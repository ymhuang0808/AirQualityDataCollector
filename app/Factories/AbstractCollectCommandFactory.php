<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\AbstractCollectSitesCommand;

abstract class AbstractCollectCommandFactory
{
    abstract public function createSitesCommand(): AbstractCollectSitesCommand;

    abstract public function createAirQualityCommand(): AbstractCollectAirQualityCommand;
}