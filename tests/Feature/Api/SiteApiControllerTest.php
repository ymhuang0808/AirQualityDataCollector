<?php

namespace Tests\Feature\Api;


use App\EpaDataset;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SiteApiControllerTest extends TestCase
{
    use DatabaseMigrations;

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
                    'address',
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
                    'address',
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
                    'address',
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
                        'psi',
                        'so2',
                        'co',
                        'o3',
                        'pm10',
                        'pm25',
                        'no2',
                        'wind_speed',
                        'wind_direction',
                        'fpmi',
                        'nox',
                        'no',
                        'major_pollutant',
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

        $response->assertStatus(422);
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
