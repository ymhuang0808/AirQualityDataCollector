<?php

namespace Tests\Unit\Api;


use App\LassDataset;
use App\Transformers\Api\LassAirQualityResponseTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LassAirQualityResponseTransformerTest extends TestCase
{
    use DatabaseMigrations;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = new LassAirQualityResponseTransformer();
    }

    public function testTransform()
    {
        $lassDataset = factory(LassDataset::class)->create();
        $actual = $this->transformer->transform($lassDataset);

        $this->assertCount(6, $actual);

        $this->assertArrayHasKey('id', $actual);
        $this->assertArrayHasKey('pm25', $actual);
        $this->assertArrayHasKey('pm10', $actual);
        $this->assertArrayHasKey('temperature', $actual);
        $this->assertArrayHasKey('humidity', $actual);
        $this->assertArrayHasKey('published_datetime', $actual);
    }
}