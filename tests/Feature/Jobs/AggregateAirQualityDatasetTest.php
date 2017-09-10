<?php

namespace Test\Feature\Jobs;


use anlutro\LaravelSettings\Facade as Setting;
use App\Aggregate\AggregateFactory;
use App\Aggregate\AirQualityDatasetAggregator;
use App\Aggregate\Contracts\AggregateFactoryContract;
use App\Aggregate\Contracts\AggregatorContract;
use App\AggregationMetric;
use App\Jobs\AggregateAirQualityDataset;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class AggregateAirQualityDatasetTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    use AggregatableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpLassDatabase();
        $this->setUpEpaDatabase();
    }

    public function testHandleWithAllResource()
    {
        $knownNow = Carbon::create(2017, 9, 3, 1, 00, 00);
        Carbon::setTestNow($knownNow);
        Setting::set('aggregate.lass.hourly.air_quality', '2017-07-23 10:00:00');
        Setting::set('aggregate.lass.daily.air_quality', '2017-07-23 23:00:00');
        Setting::set('aggregate.epa.hourly.air_quality', '2017-09-02 01:00:10');
        Setting::set('aggregate.epa.daily.air_quality', '2017-09-02 03:00:03');

        $job = new AggregateAirQualityDataset('all');
        /** @var AggregatorContract $aggregator */
        $aggregator = new AirQualityDatasetAggregator();
        /** @var AggregateFactoryContract $factory */
        $factory = new AggregateFactory();
        $job->handle($aggregator, $factory);

        // Check the hourly aggregated data in LASS
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 10:00:00',
            'end_datetime' => '2017-07-23 10:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '16.0',
                'pm10' => '9.2',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 12:00:00',
            'end_datetime' => '2017-07-23 12:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '33.2',
                'pm10' => '28.7',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 12:00:00',
            'end_datetime' => '2017-07-23 12:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '33.2',
                'pm10' => '28.7',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 20:00:00',
            'end_datetime' => '2017-07-23 20:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '45.9',
                'pm10' => '38.9',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-24 00:00:00',
            'end_datetime' => '2017-07-24 00:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '34.85',
                'pm10' => '22.2',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 10:00:00',
            'end_datetime' => '2017-07-23 10:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '41.03',
                'pm10' => '28.745',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 11:00:00',
            'end_datetime' => '2017-07-23 11:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '49.74',
                'pm10' => '34.055',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 17:00:00',
            'end_datetime' => '2017-07-23 17:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '43.22',
                'pm10' => '38.71',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 23:00:00',
            'end_datetime' => '2017-07-23 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '49.27',
                'pm10' => '29.17',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-24 00:00:00',
            'end_datetime' => '2017-07-24 00:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '44.1',
                'pm10' => '28.78',
            ]),
        ]);
        // Check the daily aggregated data in LASS
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 00:00:00',
            'end_datetime' => '2017-07-23 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_DAILY,
            'values' => serialize([
                'pm25' => '20.0857142857143',
                'pm10' => '15.2',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-24 00:00:00',
            'end_datetime' => '2017-07-24 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_DAILY,
            'values' => serialize([
                'pm25' => '34.85',
                'pm10' => '22.2',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 00:00:00',
            'end_datetime' => '2017-07-23 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_DAILY,
            'values' => serialize([
                'pm25' => '43.92',
                'pm10' => '31.1957142857143',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-24 00:00:00',
            'end_datetime' => '2017-07-24 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_DAILY,
            'values' => serialize([
                'pm25' => '44.1',
                'pm10' => '28.78',
            ]),
        ]);
        // Check the daily aggregated data in EPA
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 3,
            'start_datetime' => '2017-09-02 03:00:00',
            'end_datetime' => '2017-09-02 03:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '45.0',
                'pm10' => '49.0',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 3,
            'start_datetime' => '2017-09-02 07:00:00',
            'end_datetime' => '2017-09-02 07:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '63.5',
                'pm10' => '66.5',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 3,
            'start_datetime' => '2017-09-02 08:00:00',
            'end_datetime' => '2017-09-02 08:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '19.0',
                'pm10' => '112.0',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 4,
            'start_datetime' => '2017-09-02 13:00:00',
            'end_datetime' => '2017-09-02 13:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '66.0',
                'pm10' => '54.5',
            ]),
        ]);
    }
}