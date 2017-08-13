<?php

namespace App\Aggregate;


use App\AggregationMetric;
use App\Repository\Contracts\AggregatableDatasetRepositoryContract;
use Carbon\Carbon;

class LassAirQualityProcessor implements AggregateProcessorContract
{
    /** @var  \App\Repository\Contracts\AggregatableDatasetRepositoryContract $aggregatableRespository  */
    protected $aggregatableRespository;

    public function __construct(AggregatableDatasetRepositoryContract $aggregatableRepository)
    {
        $this->aggregatableRespository = $aggregatableRepository;
    }

    /**
     * Aggregated fields in dataset
     *
     * @var array
     */
    protected $fields = [
        'pm25',
        'pm10',
    ];

    /**
     * Aggregate the dataset hourly
     *
     * @param $lastTime
     */
    public function aggregateHourly($lastTime)
    {
        $now = Carbon::now();

        $startDateTime = Carbon::parse($lastTime);
        // TODO: refactor it in to a class or procedure
        // Process each time period
        do {
            // Slide the hourly period to fetch data
            $nextDatetime = $startDateTime->copy()->addHour();

            // Get aggregated result
            $result = $this->processAggregatedFields($startDateTime, $nextDatetime);
            $this->createAggregationMetric($result, AggregationMetric::PERIOD_TYPE_HOURLY);

            $startDateTime = $nextDatetime;
        } while ($nextDatetime < $now);
    }

    /**
     * Generates aggregated data
     *
     * The aggregated data structure looks like:
     *  [
     *      'site_id' => 1,
     *      'start_datetime' => '2017-08-12 11:00:00',
     *      'end_datetime' => '2017-08-12 12:00:00',
     *      'value' => [
     *          'pm25' => 31,
     *          'pm10' => 29,
     *      ]
     *  ]
     *
     * @param $start
     * @param $end
     * @return array
     */
    protected function processAggregatedFields(Carbon $start, Carbon $end) {
        $result = [];
        $start_datetime_string = $start->toDateTimeString();
        $end_datetime_string = $end->toDateTimeString();
        $avgValues = $this->aggregatableRespository->getAvgFieldBetweenPublishedDatetime($this->fields, $start_datetime_string, $end_datetime_string);

        foreach ($avgValues as $value) {
            $dataMetrics = [];

            foreach ($this->fields as $index => $fieldName) {
                $dataMetrics[$fieldName] = $value->{'avg_value_' . $index};
            }

            $result[] = [
                'site_id' => $value->site_id,
                'start_datetime' => $start_datetime_string,
                'end_datetime' => $end_datetime_string,
                'values' => $dataMetrics,
            ];
        }

        return $result;
    }

    protected function createAggregationMetric(array $aggregations, int $periodType)
    {
        foreach ($aggregations as $aggregationItem) {
            $aggregationItem['values'] = serialize($aggregationItem['values']);
            $aggregationItem += [
                'period_type' => $periodType,
            ];
            AggregationMetric::create($aggregationItem);
        }
    }
}