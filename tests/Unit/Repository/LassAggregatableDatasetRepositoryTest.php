<?php

namespace tests\Unit\Aggregate;


use App\Repository\LassAggregatableDatasetRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class LassAggregatableDatasetRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    use AggregatableTestDataTrait;

    /** @var  LassAggregatableDatasetRepository */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();

        $this->repository = new LassAggregatableDatasetRepository();
    }

    public function testSingleField01GetAvgFieldBetweenPublishedDatetime()
    {
        $actual = $this->repository->getAvgFieldBetweenPublishedDatetime(['pm25'], '2017-07-23 10:00:00', '2017-07-23 11:00:00');

        $this->assertCount(2, $actual);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[0]);
        $this->assertObjectHasAttribute('site_id', $actual[1]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[1]);

        $this->assertEquals(1, $actual[0]->site_id);
        $this->assertEquals(16, $actual[0]->avg_value_0);
        $this->assertEquals(2, $actual[1]->site_id);
        $this->assertEquals(41.03, $actual[1]->avg_value_0);
    }

    public function testSingleField02GetAvgFieldBetweenPublishedDatetime()
    {
        $actual = $this->repository->getAvgFieldBetweenPublishedDatetime(['pm25'], '2017-07-23 11:00:00', '2017-07-23 12:00:00');

        $this->assertCount(1, $actual);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[0]);

        $this->assertEquals(2, $actual[0]->site_id);
        $this->assertEquals(49.74, $actual[0]->avg_value_0);
    }

    public function testMultipleFieldsGetAvgFieldBetweenPublishedDatetime()
    {
        $actual = $this->repository->getAvgFieldBetweenPublishedDatetime(['pm25', 'pm10'], '2017-07-23 10:00:00', '2017-07-23 11:00:00');

        $this->assertCount(2, $actual);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_1', $actual[0]);
        $this->assertObjectHasAttribute('site_id', $actual[1]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[1]);
        $this->assertObjectHasAttribute('avg_value_1', $actual[1]);

        $this->assertEquals(1, $actual[0]->site_id);
        $this->assertEquals(16, $actual[0]->avg_value_0);
        $this->assertEquals(9.2, $actual[0]->avg_value_1);
        $this->assertEquals(2, $actual[1]->site_id);
        $this->assertEquals(41.03, $actual[1]->avg_value_0);
        $this->assertEquals(28.745, $actual[1]->avg_value_1);
    }
}