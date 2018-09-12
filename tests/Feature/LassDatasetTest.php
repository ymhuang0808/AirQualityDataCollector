<?php

namespace Test\Feature;


use App\LassDataset;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class LassDatasetTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();
    }

    public function testScopeLatest()
    {
        $result = LassDataset::latestBySite()->get();

        $this->assertCount(2, $result);

        $site1 = $result->get(0);
        $this->assertEquals(35.2, $site1->pm25);
        $this->assertEquals(21.6, $site1->pm10);
        $this->assertEquals(1, $site1->site_id);
        $this->assertEquals('2017-07-24 00:05:12', $site1->published_datetime);

        $site2 = $result->get(1);
        $this->assertEquals(44.1, $site2->pm25);
        $this->assertEquals(28.78, $site2->pm10);
        $this->assertEquals(2, $site2->site_id);
        $this->assertEquals('2017-07-24 00:19:18', $site2->published_datetime);
    }
}