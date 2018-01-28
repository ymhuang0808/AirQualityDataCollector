<?php

namespace Test\Unit\Repository;


use App\Repository\SiteAggregationMeasurementRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
        $repository = new SiteAggregationMeasurementRepository();
        $site = $repository->setStartDateTimeCondition('2018-01-05 00:00:00')
            ->setEndDateTimeCondition('2018-01-05 23:59:59')
            ->setPeriodTypeCondition(0)
            ->setSiteIdCondition(1)
            ->setLimit(30)
            ->get();
        /** @var \Illuminate\Support\Collection $result */
        $result = $site->aggregationMetric;

        $this->assertCount(24, $result);
        $this->assertEquals('2018-01-05 00:00:00', $result->first()->start_datetime);
        $this->assertEquals('2018-01-05 00:59:59', $result->first()->end_datetime);
        $this->assertEquals(['pm25' => 41, 'pm10' => 39], $result->first()->values);

        $this->assertEquals('2018-01-05 23:00:00', $result->last()->start_datetime);
        $this->assertEquals('2018-01-05 23:59:59', $result->last()->end_datetime);
        $this->assertEquals(['pm25' => 14, 'pm10' => 11], $result->last()->values);
    }
}