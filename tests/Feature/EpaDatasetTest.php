<?php

namespace Test\Feature;


use App\EpaDataset;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class EpaDatasetTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpEpaDatabase();
    }

    public function testScopeLatest()
    {
        $result = EpaDataset::latest()->get();

        $this->assertCount(2, $result);

        $site1 = $result->get(0);

        $this->assertEquals(95, $site1->pm25);
        $this->assertEquals(88, $site1->pm10);
        $this->assertEquals(1, $site1->site_id);
        $this->assertEquals('2017-09-03 07:10:13', $site1->published_datetime);

        $site2 = $result->get(1);

        $this->assertEquals(36, $site2->pm25);
        $this->assertEquals(37, $site2->pm10);
        $this->assertEquals(2, $site2->site_id);
        $this->assertEquals('2017-09-03 19:44:33', $site2->published_datetime);
    }
}