<?php

namespace App\Aggregate;


use App\AggregationMetric;
use App\Events\AirQualityMeasurementAggregationCompleted;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AirQualityAggregateProcessor extends AbstractAggregateProcessor
{
    /**
     * Aggregate the dataset hourly
     *
     * @param $lastTime
     * @return Carbon
     */
    public function aggregateHourly($lastTime): Carbon
    {
        Log::debug('--- begin aggregateHourly() ---');
        Log::debug('$lastTime = ' . $lastTime);

        $now = Carbon::now();
        $startDateTime = Carbon::parse($lastTime);
        $beginTimeWindow = $startDateTime->copy();
        $endTimeWindow = $startDateTime->copy()->addHour()->subSecond();

        Log::debug('$beginTimeWindow = ' . $beginTimeWindow->toDateTimeString());
        Log::debug('$endTimeWindow = ' . $endTimeWindow->toDateTimeString());

        // TODO: refactor it in to a class or procedure
        // Process each time period
        while ($beginTimeWindow->lessThan($now) && $endTimeWindow->lessThanOrEqualTo($now)) {
            // Get aggregated result
            $result = $this->processAggregatedFields($beginTimeWindow, $endTimeWindow);
            $this->createAggregationMetric($result, AggregationMetric::PERIOD_TYPE_HOURLY);

            $beginTimeWindow = $endTimeWindow->addSecond();
            // Slide the hourly period to fetch data
            $endTimeWindow = $beginTimeWindow->copy()->addHour()->subSecond();
        }

        $endDateTime = $beginTimeWindow;
        $sourceType = $this->getSourceType();

        event(new AirQualityMeasurementAggregationCompleted('hourly', $sourceType, $startDateTime, $endDateTime));

        Log::debug('aggregateHourly() completed, sourceType = ' . $this->sourceType . ', endDateTime = ' . $endDateTime);
        Log::debug('--- end aggregateHourly() ---');

        return $endDateTime;
    }

    /**
     * Aggregate the dataset daily
     *
     * @param $lastTime The date format
     * @return Carbon
     */
    public function aggregateDaily($lastTime): Carbon
    {
        Log::debug('--- begin aggregateDaily() ---');
        Log::debug('$lastTime = ' . $lastTime);

        $today = Carbon::now()->startOfDay();
        $startDateTime = Carbon::parse($lastTime);
        $beginTimeWindow = $startDateTime->copy();
        $nextDatetime = $startDateTime->copy()->addDay()->subSecond();

        Log::debug('$beginTimeWindow = ' . $beginTimeWindow->toDateTimeString());
        Log::debug('$nextDatetime = ' . $nextDatetime->toDateTimeString());

        // Process each time period
        while ($beginTimeWindow->lessThan($today) && $nextDatetime->lessThanOrEqualTo($today)) {
            // Slide the hourly period to fetch data
            $nextDatetime = $beginTimeWindow->copy()->addDay()->subSecond();

            // Get aggregated result
            $result = $this->processAggregatedFields($beginTimeWindow, $nextDatetime);
            $this->createAggregationMetric($result, AggregationMetric::PERIOD_TYPE_DAILY);

            $beginTimeWindow = $nextDatetime->addSecond();
        }

        $endDateTime = $beginTimeWindow;
        $sourceType = $this->getSourceType();

        event(new AirQualityMeasurementAggregationCompleted('daily', $sourceType, $startDateTime, $endDateTime));

        Log::debug('aggregateDaily() completed, sourceType = ' . $this->sourceType . ', endDateTime = ' . $endDateTime);
        Log::debug('--- end aggregateDaily() ---');

        return $endDateTime;
    }

}