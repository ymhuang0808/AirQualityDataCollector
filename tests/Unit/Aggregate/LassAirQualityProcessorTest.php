<?php

namespace tests\Unit\Aggregate;


use App\Aggregate\LassAirQualityProcessor;
use App\AggregationMetric;
use App\Repository\LassAggregatableDatasetRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class LassAirQualityProcessorTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    /** @var  LassAirQualityProcessor $processor */
    protected $processor;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();

        $repository = new LassAggregatableDatasetRepository();
        $this->processor = new LassAirQualityProcessor($repository);
    }

    public function testAggregateHourly()
    {
        $knownNow = Carbon::create(2017, 07, 23, 13,00, 00);
        Carbon::setTestNow($knownNow);

        $this->processor->aggregateHourly('2017-07-23 10:00:00');
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 10:00:00',
            'end_datetime' => '2017-07-23 11:00:00',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '16.0',
                'pm10' => '9.2',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 1,
            'start_datetime' => '2017-07-23 12:00:00',
            'end_datetime' => '2017-07-23 13:00:00',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '33.2',
                'pm10' => '28.7',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 10:00:00',
            'end_datetime' => '2017-07-23 11:00:00',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '41.03',
                'pm10' => '28.745',
            ]),
        ]);
        $this->assertDatabaseHas('aggregation_metrics', [
            'site_id' => 2,
            'start_datetime' => '2017-07-23 11:00:00',
            'end_datetime' => '2017-07-23 12:00:00',
            'period_type' => AggregationMetric::PERIOD_TYPE_HOURLY,
            'values' => serialize([
                'pm25' => '49.74',
                'pm10' => '34.055',
            ]),
        ]);
        $this->assertEquals(4, AggregationMetric::all()->count());
    }

}