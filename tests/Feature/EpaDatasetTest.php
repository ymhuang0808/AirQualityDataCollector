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
        $result = EpaDataset::latest()->first();

        $this->assertEquals(36, $result->pm25);
        $this->assertEquals(37, $result->pm10);
        $this->assertEquals(2, $result->site_id);
        $this->assertEquals('2017-09-03 19:44:33', $result->published_datetime);
    }
}