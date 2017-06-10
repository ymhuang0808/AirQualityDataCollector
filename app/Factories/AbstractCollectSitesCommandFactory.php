<?php

namespace App\Factories;


use App\Commands\AbstractCollectSitesCommand;

abstract class AbstractCollectSitesCommandFactory
{
    abstract public function createCommand(): AbstractCollectSitesCommand;
}