<?php

namespace App\Aggregate;


use Carbon\Carbon;

class AirQualityDatasetAggregator extends AbstractAirQualityDatasetAggregator
{
    protected $availableSource = [
        'epa',
        'lass',
    ];

    public function process()
    {
        $this->processHourly();
        $this->processDaily();
    }

    protected function processHourly()
    {
        $now = Carbon::now();
        $lastTimeString = $this->getHourlyLastTime();
        $lastDateTime = $this->startOfHour(Carbon::parse($lastTimeString));

        if ($now->diffInHours($lastDateTime) >= 1) {
            $endDateTime = $this->processor->aggregateHourly($lastDateTime);
            $this->setHourlyLastTime($endDateTime->toDateTimeString());
        } else {
            // TODO: Log the message
        }
    }

    protected function processDaily()
    {
        $now = Carbon::now();
        $lastTimeString = $this->getDailyLastTime();
        $lastDateTime = Carbon::parse($lastTimeString)->startOfDay();

        if ($now->diffInDays($lastDateTime) >= 1) {
            $endDateTime = $this->processor->aggregateDaily($lastDateTime);
            $this->setDailyLastTime($endDateTime->toDateTimeString());
        } else {
            // TODO: Log the message
        }
    }

    protected function startOfHour(Carbon $dateTime)
    {
        return $dateTime->setTime($dateTime->hour, 0, 0);
    }
}