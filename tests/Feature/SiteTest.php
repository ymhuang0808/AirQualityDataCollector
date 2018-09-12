<?php

namespace Test\Feature;


use App\Site;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpLassDatabase();
        $this->setUpEpaDatabase();
    }

    public function testDatasetRelationship()
    {
        $site1 = Site::find(1);
        $result1 = $site1->lassDataset()->latestBySite()->first();

        $this->assertEquals(35.2, $result1->pm25);
        $this->assertEquals(21.6, $result1->pm10);
        $this->assertEquals('2017-07-24 00:05:12', $result1->published_datetime);
    }
}