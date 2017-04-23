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

        $this->assertCount(16 , $actual);

        $this->assertArrayHasKey('id', $actual);
        $this->assertArrayHasKey('psi', $actual);
        $this->assertArrayHasKey('so2', $actual);
        $this->assertArrayHasKey('co', $actual);
        $this->assertArrayHasKey('o3', $actual);
        $this->assertArrayHasKey('pm10', $actual);
        $this->assertArrayHasKey('pm25', $actual);
        $this->assertArrayHasKey('wind_speed', $actual);
        $this->assertArrayHasKey('wind_direction', $actual);
        $this->assertArrayHasKey('fpmi', $actual);
        $this->assertArrayHasKey('nox', $actual);
        $this->assertArrayHasKey('no', $actual);
        $this->assertArrayHasKey('major_pollutant', $actual);
        $this->assertArrayHasKey('status', $actual);
        $this->assertArrayHasKey('published_datetime', $actual);

        $this->assertArrayNotHasKey('site_id', $actual);
        $this->assertArrayNotHasKey('updated_at', $actual);
        $this->assertArrayNotHasKey('created_at', $actual);
    }
}