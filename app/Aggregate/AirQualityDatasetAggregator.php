<?php

namespace App\Aggregate;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * AirQualityDatasetAggregator controls and executes the aggregate processor.
 * It executes hourly and daily aggregation processes and controls the processes by last execution time.
 */
class AirQualityDatasetAggregator extends AbstractAirQualityDatasetAggregator
{
    public function process()
    {
        $this->processHourly();
        $this->processDaily();
    }

    protected function processHourly()
    {
        Log::debug('processHourly() for ' . $this->source);

        $now = Carbon::now();
        $lastTimeString = $this->getHourlyLastTime();

        Log::debug('$lastTimeString = ' . $lastTimeString);

        $lastDateTime = $this->startOfHour(Carbon::parse($lastTimeString));

        Log::debug('$lastDateTime = ' . $lastDateTime);

        if ($now->diffInHours($lastDateTime) >= 1) {
            $endDateTime = $this->processor->aggregateHourly($lastDateTime);
            $this->setHourlyLastTime($endDateTime->toDateTimeString());
        } else {
            // TODO: Log the message
        }
    }

    protected function processDaily()
    {
        Log::debug('processDaily() for ' . $this->source);

        $now = Carbon::now();
        $lastTimeString = $this->getDailyLastTime();

        Log::debug('$lastTimeString = ' . $lastTimeString);

        $lastDateTime = Carbon::parse($lastTimeString)->startOfDay();

        Log::debug('$lastDateTime = ' . $lastDateTime);

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