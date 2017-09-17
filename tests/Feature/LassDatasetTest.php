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
        $result = LassDataset::latest()->first();

        $this->assertEquals(44.1, $result->pm25);
        $this->assertEquals(28.78, $result->pm10);
        $this->assertEquals(2, $result->site_id);
        $this->assertEquals('2017-07-24 00:19:18', $result->published_datetime);
    }
}