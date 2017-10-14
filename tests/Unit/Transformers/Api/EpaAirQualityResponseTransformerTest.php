<?php

use App\Transformers\Api\EpaAirQualityResponseTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EpaAirQualityResponseTransformerTest extends TestCase
{
    use DatabaseMigrations;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = new EpaAirQualityResponseTransformer();
    }

    public function testTransform()
    {
        $epaDataset = factory(App\EpaDataset::class)->create();
        $actual = $this->transformer->transform($epaDataset);

        $this->assertCount(19 , $actual);

        $this->assertArrayHasKey('id', $actual);
        $this->assertArrayHasKey('aqi', $actual);
        $this->assertArrayHasKey('so2', $actual);
        $this->assertArrayHasKey('co', $actual);
        $this->assertArrayHasKey('co_8hr', $actual);
        $this->assertArrayHasKey('o3', $actual);
        $this->assertArrayHasKey('o3_8hr', $actual);
        $this->assertArrayHasKey('pm10', $actual);
        $this->assertArrayHasKey('pm10_avg', $actual);
        $this->assertArrayHasKey('pm25', $actual);
        $this->assertArrayHasKey('pm25_avg', $actual);
        $this->assertArrayHasKey('wind_speed', $actual);
        $this->assertArrayHasKey('wind_direction', $actual);
        $this->assertArrayHasKey('nox', $actual);
        $this->assertArrayHasKey('no', $actual);
        $this->assertArrayHasKey('pollutant', $actual);
        $this->assertArrayHasKey('status', $actual);
        $this->assertArrayHasKey('published_datetime', $actual);

        $this->assertArrayNotHasKey('site_id', $actual);
        $this->assertArrayNotHasKey('updated_at', $actual);
        $this->assertArrayNotHasKey('created_at', $actual);
    }
}