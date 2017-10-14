<?php

namespace Tests\Feature\Api;


use App\EpaDataset;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SiteApiControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testGetAll()
    {
        $this->createEpaTestData();

        $response = $this->json('GET', '/api/site/all');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                ],
            ],
        ]);
    }

    public function testGetAllIncludeCounty()
    {
        $this->createEpaTestData();

        $response =  $this->json('GET', '/api/site/all?include=county');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                    'county' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ]);
    }

    public function testGetAllIncludeTownship()
    {
        $this->createEpaTestData();

        $response =  $this->json('GET', '/api/site/all?include=township');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                    'township' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ]);
    }

    public function testGetAllIncludeCountyAndTownship()
    {
        $this->createEpaTestData();

        $response =  $this->json('GET', '/api/site/all?include=county,township');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                    'county' => [
                        'id',
                        'name',
                    ],
                    'township' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ]);
    }

    public function testGetAllIncludeAirQuality()
    {
        $this->createEpaTestData();

        $response =  $this->json('GET', '/api/site/all?include=air_quality');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'address',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                    'air_quality' => [
                        'id',
                        'aqi',
                        'so2',
                        'co',
                        'co_8hr',
                        'o3',
                        'o3_8hr',
                        'pm10',
                        'pm10_avg',
                        'pm25',
                        'pm25_avg',
                        'no2',
                        'wind_speed',
                        'wind_direction',
                        'nox',
                        'no',
                        'pollutant',
                        'status',
                        'published_datetime',
                    ],
                ],
            ],
        ]);
    }

    public function testGetAllWithCacheability()
    {
        $epaDataset = factory(EpaDataset::class, 4)->create();
        $fakeSites = $this->getSitesByEpaDatasets($epaDataset);

        Cache::shouldReceive('remember')
            ->once()
            ->with('model-site:all', 5, \Mockery::type('Callable'))
            ->andReturn($fakeSites);

        $response =  $this->json('GET', '/api/site/all');

        $response->assertStatus(200);

        $fakeSites->each(function ($site) use ($response) {
           $response->assertJsonFragment([
              'name' => $site->name,
           ]);
        });
    }

    public function testGetAllWithNotInIncludeValue()
    {
        $this->createEpaTestData();

        $response =  $this->json('GET', '/api/site/all?include=abc');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'eng_name',
                    'type',
                    'area_name',
                    'coordinates' => [
                        'latitude',
                        'longitude',
                    ],
                    'source_type',
                ],
            ],
        ]);
    }

    protected function createEpaTestData()
    {
        $epaDataset = factory(EpaDataset::class)->create();

        return $epaDataset;
    }

    protected function getSitesByEpaDatasets($epaDataset)
    {
        return $epaDataset->map(function ($item) {
            return $item->site;
        });
    }
}
