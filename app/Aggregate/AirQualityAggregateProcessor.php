<?php

namespace App\Aggregate;


use App\AggregationMetric;
use Carbon\Carbon;

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
        $now = Carbon::now();
        $startDateTime = Carbon::parse($lastTime);
        $nextDatetime = $startDateTime->copy()->addHour();

        // TODO: refactor it in to a class or procedure
        // Process each time period
        while ($startDateTime->lessThan($now) && $nextDatetime->lessThanOrEqualTo($now)) {
            // Get aggregated result
            $result = $this->processAggregatedFields($startDateTime, $nextDatetime);
            $this->createAggregationMetric($result, AggregationMetric::PERIOD_TYPE_HOURLY);

            $startDateTime = $nextDatetime;
            // Slide the hourly period to fetch data
            $nextDatetime = $startDateTime->copy()->addHour();
        }

        $endDateTime = $startDateTime;

        // TODO: after the aggregation finished, it should dispatch an event

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
        $today = Carbon::now()->startOfDay();
        $startDateTime = Carbon::parse($lastTime);
        $nextDatetime = $startDateTime->copy()->addDay();

        // Process each time period
        while ($startDateTime->lessThan($today) && $nextDatetime->lessThanOrEqualTo($today)) {
            // Slide the hourly period to fetch data
            $nextDatetime = $startDateTime->copy()->addDay();

            // Get aggregated result
            $result = $this->processAggregatedFields($startDateTime, $nextDatetime);

            $this->createAggregationMetric($result, AggregationMetric::PERIOD_TYPE_DAILY);

            $startDateTime = $nextDatetime;
        }

        $endDateTime = $startDateTime;

        // TODO: after the aggregation finished, it should dispatch an event

        return $endDateTime;
    }

}