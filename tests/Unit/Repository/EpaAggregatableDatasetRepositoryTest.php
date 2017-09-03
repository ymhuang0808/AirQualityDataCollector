<?php

namespace tests\Unit\Aggregate;


use App\Repository\EpaAggregatableDatasetRepository;
use App\Repository\LassAggregatableDatasetRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class EpaAggregatableDatasetRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    use AggregatableTestDataTrait;

    /** @var  LassAggregatableDatasetRepository */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpEpaDatabase();

        $this->repository = new EpaAggregatableDatasetRepository();
    }

    public function testSingleField01GetAvgFieldBetweenPublishedDatetime()
    {
        $actual = $this->repository->getAvgFieldBetweenPublishedDatetime(['pm25'], '2017-09-02 07:00:00', '2017-09-02 07:59:00');

        $this->assertCount(1, $actual);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[0]);

        $this->assertEquals(1, $actual[0]->site_id);
        $this->assertEquals(63.5, $actual[0]->avg_value_0);
    }

    public function testMultipleFieldsGetAvgFieldBetweenPublishedDatetime()
    {
        $actual = $this->repository->getAvgFieldBetweenPublishedDatetime(['pm25', 'pm10'], '2017-09-02 13:00:00', '2017-09-02 13:59:59');

        $this->assertCount(2, $actual);
        $this->assertObjectHasAttribute('site_id', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[0]);
        $this->assertObjectHasAttribute('avg_value_1', $actual[0]);
        $this->assertObjectHasAttribute('site_id', $actual[1]);
        $this->assertObjectHasAttribute('avg_value_0', $actual[1]);
        $this->assertObjectHasAttribute('avg_value_1', $actual[1]);


        $this->assertEquals(1, $actual[0]->site_id);
        $this->assertEquals(47.5, $actual[0]->avg_value_0);
        $this->assertEquals(46.5, $actual[0]->avg_value_1);
        $this->assertEquals(2, $actual[1]->site_id);
        $this->assertEquals(66, $actual[1]->avg_value_0);
        $this->assertEquals(54.5, $actual[1]->avg_value_1);
    }

}