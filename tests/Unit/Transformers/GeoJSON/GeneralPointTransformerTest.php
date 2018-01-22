<?php

namespace tests\Unit\Transformers\GeoJSON;


use App\Transformers\GeoJSON\GeneralPointTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GeneralPointTransformerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $transformer;
    protected $site;
    protected $measurement;

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = new GeneralPointTransformer();
    }

    public function testTransform()
    {
        $this->site = factory(\App\Site::class)->create();
        $this->measurement = factory(\App\LassDataset::class)->create([
            'id' => 88,
            'pm25' => 20.18,
            'pm10' => 18.9,
            'temperature' => 10.1,
            'humidity' => 88.0,
            'site_id' => $this->site->id,
            'published_datetime' => '2018-01-08 17:22:09',
            'updated_at' => '2018-01-08 17:25:09',
            'created_at' => '2018-01-08 17:25:09',
        ]);

        $result = $this->transformer->transform($this->measurement);

        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('geometry', $result);
        $this->assertArrayHasKey('type', $result['geometry']);
        $this->assertArrayHasKey('coordinates', $result['geometry']);
        $this->assertArrayHasKey('properties', $result);

        $expectedCoordinates = [
            $this->site->coordinates['longitude'],
            $this->site->coordinates['latitude'],
        ];
        $this->assertEquals($expectedCoordinates, $result['geometry']['coordinates']);

        $this->assertArrayNotHasKey('id', $result['properties']);
        $this->assertArrayNotHasKey('site', $result['properties']);
        $this->assertArrayNotHasKey('updated_at', $result['properties']);
        $this->assertArrayNotHasKey('created_at', $result['properties']);

        $this->assertEquals(20.18, $result['properties']['pm25']);
        $this->assertEquals(18.9, $result['properties']['pm10']);
        $this->assertEquals(10.1, $result['properties']['temperature']);
        $this->assertEquals(88.0, $result['properties']['humidity']);
        $this->assertEquals('2018-01-08T17:22:09+00:00', $result['properties']['published_datetime']);
    }
}