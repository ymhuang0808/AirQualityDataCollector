<?php

namespace Test\Unit\Repository;


use App\Repository\SiteAggregationMeasurementRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\AggregationMetricTableTestDataTrait;
use Tests\TestCase;

class SiteAggregationMeasurementRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    use AggregationMetricTableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setupAggregationMetricHourly();
    }

    public function testGet()
    {
        DB::enableQueryLog();

        $repository = new SiteAggregationMeasurementRepository();
        $site = $repository->setStartDateTimeCondition('2018-01-05 00:00:00')
            ->setEndDateTimeCondition('2018-01-05 23:59:59')
            ->setPeriodTypeCondition(0)
            ->setSiteIdCondition(1)
            ->setLimit(30)
            ->setOrderByDirection('asc')
            ->get();
        /** @var \Illuminate\Support\Collection $result */
        $result = $site->aggregationMetric;

        $this->assertCount(24, $result);
        $this->assertEquals('2018-01-05T00:00:00+00:00', $result->first()->start_datetime->toIso8601String());
        $this->assertEquals('2018-01-05T00:59:59+00:00', $result->first()->end_datetime->toIso8601String());
        $this->assertEquals(['pm25' => 41, 'pm10' => 39], $result->first()->values);

        $this->assertEquals('2018-01-05T23:00:00+00:00', $result->last()->start_datetime->toIso8601String());
        $this->assertEquals('2018-01-05T23:59:59+00:00', $result->last()->end_datetime->toIso8601String());
        $this->assertEquals(['pm25' => 14, 'pm10' => 11], $result->last()->values);
    }
}