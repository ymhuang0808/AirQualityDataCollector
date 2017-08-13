<?php

namespace App\Aggregate;


interface AggregateProcessorContract
{
    public function aggregateHourly($lastTime);
}