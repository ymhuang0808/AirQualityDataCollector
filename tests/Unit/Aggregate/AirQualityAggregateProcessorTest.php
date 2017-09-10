<?php

namespace tests\Unit\Repository;


use App\Aggregate\AirQualityAggregateProcessor;
use App\AggregationMetric;
use App\Repository\LassAggregatableDatasetRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class AirQualityAggregateProcessorTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    /** @var  AirQualityAggregateProcessor $processor */
    protected $processor;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();

        $repository = new LassAggregatableDatasetRepository();
        $this->processor = new AirQualityAggregateProcessor($repository);
    }

    public function testAggregateHourlyWhenCurrentDateTimeMinuteSecondIsZero()
    {
        $knownNow = Carbon::create(2017, 07, 23, 13,00, 00);
        Carbon::setTestNow($knownNow);

        $endDateTime = $this->processor
                            ->setFields([
                                'pm25',
                                'pm10',
                            ])
                            ->aggregateHourly('2017-07-23 10:00:00');
        $this->assertInstanceOf(\Carbon\Carbon::class, $endDateTime);
        $this->assertEquals(2017, $endDateTime->year);
        $this->assertEquals(07, $endDateTime->month);
        $this->assertEquals(23, $endDateTime->day);
        $this->assertEquals(13, $endDateTime->hour);
        $this->assertEquals(0, $endDateTime->minute);
        $this->assertEquals(0, $endDateTime->second);
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
        $this->assertEquals(4, AggregationMetric::all()->count());
    }

    public function testAggregateHourlyWhenCurrentDateTimetMinuteSecondIsNotZero()
    {
        $knownNow = Carbon::create(2017, 07, 23, 13,10, 00);
        Carbon::setTestNow($knownNow);

        $this->processor
            ->setFields([
                'pm25',
                'pm10',
            ])
            ->aggregateHourly('2017-07-23 10:00:00');
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
        $this->assertEquals(4, AggregationMetric::all()->count());
    }

    public function testAggregateDaily()
    {
        $knownNow = Carbon::create(2017, 07, 24, 00,00, 00);
        Carbon::setTestNow($knownNow);

        $this->processor
            ->setFields([
                'pm25',
                'pm10',
            ])
            ->aggregateDaily('2017-07-23 00:00:00');
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
    }
}