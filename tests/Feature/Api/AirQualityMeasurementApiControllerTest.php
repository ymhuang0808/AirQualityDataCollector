<?php

namespace Tests\Feature\Api;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class AirQualityMeasurementApiControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpEpaDatabase();
        $this->setUpAirboxDatabase();
        $this->setUpLassDatabase();
    }

    public function testGetAllGeoJson()
    {
        $response = $this->get('/api/measurements/all/geojson');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'measurements' => [
                    'epa' => [
                        'geojson' => [
                            'type',
                            'features' => [
                                '*' => [
                                    'type',
                                    'geometry' => [
                                        'type',
                                        'coordinates',
                                    ],
                                    'properties' => [
                                        'so2',
                                        'co',
                                        'o3',
                                        'pm10',
                                        'pm25',
                                        'wind_speed',
                                        'wind_direction',
                                        'nox',
                                        'no',
                                        'pollutant',
                                        'status',
                                        'site_id',
                                        'aqi',
                                        'co_8hr',
                                        'o3_8hr',
                                        'pm10_avg',
                                        'pm25_avg',
                                        'no2',
                                        'published_datetime',
                                        'name',
                                        'humanized_name',
                                        'humanized_eng_name',
                                        'address',
                                        'type',
                                        'area_name',
                                        'source_type'
                                    ],
                                ],
                            ]
                        ],
                    ],
                    'airbox' => [
                        'geojson' => [
                            'type',
                            'features' => [
                                '*' => [
                                    'type',
                                    'geometry' => [
                                        'type',
                                        'coordinates',
                                    ],
                                    'properties',
                                ],
                            ]
                        ],
                    ],
                    'lass' => [
                        'geojson' => [
                            'type',
                            'features' => [
                                '*' => [
                                    'type',
                                    'geometry' => [
                                        'type',
                                        'coordinates',
                                    ],
                                    'properties',
                                ],
                            ]
                        ],
                    ],
                ],
                'meta' => [
                    'source',
                ],
            ]);
    }
}