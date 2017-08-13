<?php

namespace App\Aggregate;


use App\EpaDataset;

class EpaAirQualityProcessor implements AggregateProcessorContract
{
    public function aggregateHourly($lastTime)
    {
        $dataset = DB::table('epa_datasets')
            ->where('published_datetime', '>', $lastTime);
    }
}