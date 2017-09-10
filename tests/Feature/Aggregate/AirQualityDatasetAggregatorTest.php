<?php

namespace Test\Feature\Aggregate;


use anlutro\LaravelSettings\Facade as Setting;
use App\Aggregate\AirQualityAggregateProcessor;
use App\Aggregate\AirQualityDatasetAggregator;
use App\AggregationMetric;
use App\Repository\LassAggregatableDatasetRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class AirQualityDatasetAggregatorTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    /**
     * @var \App\Aggregate\AggregatorContract
     */
    protected $aggregator;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();
        $repository = new LassAggregatableDatasetRepository();
        $processor = new AirQualityAggregateProcessor($repository);
        $processor->setFields(['pm25', 'pm10']);
        $this->aggregator = new AirQualityDatasetAggregator($processor);
    }

    public function testProcessHourlyDateTimeMinuteAndSecondIsZero()
    {
        $knownNow = Carbon::create(2017, 07, 23, 13,00, 00);
        Carbon::setTestNow($knownNow);
        Setting::set('aggregate.lass.hourly.air_quality', '2017-07-23 10:00:00');

        $this->aggregator->setSource('lass')->process();

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
        $this->assertEquals(4, AggregationMetric::where('period_type', AggregationMetric::PERIOD_TYPE_HOURLY)->count());
    }

    public function testProcessHourlyDateTimeMinuteAndSecondIsNotZero()
    {
        $knownNow = Carbon::create(2017, 07, 23, 13,01, 37);
        Carbon::setTestNow($knownNow);
        Setting::set('aggregate.lass.hourly.air_quality', '2017-07-23 10:33:21');

        $this->aggregator->setSource('lass')->process();

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
        $this->assertEquals(4, AggregationMetric::where('period_type', AggregationMetric::PERIOD_TYPE_HOURLY)->count());
    }

    public function testProcessDailyDateTimeMinuteAndSecondIsZero()
    {
        $knownNow = Carbon::create(2017, 07, 24, 01, 00, 00);
        Carbon::setTestNow($knownNow);
        Setting::set('aggregate.lass.hourly.air_quality', '2017-07-24 01:00:00');
        Setting::set('aggregate.lass.daily.air_quality', '2017-07-23 23:00:00');

        $this->aggregator->setSource('lass')->process();

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
            'site_id' => 2,
            'start_datetime' => '2017-07-23 00:00:00',
            'end_datetime' => '2017-07-23 23:59:59',
            'period_type' => AggregationMetric::PERIOD_TYPE_DAILY,
            'values' => serialize([
                'pm25' => '43.92',
                'pm10' => '31.1957142857143',
            ]),
        ]);
        $this->assertEquals(2, AggregationMetric::where('period_type', AggregationMetric::PERIOD_TYPE_DAILY)->count());
    }
}