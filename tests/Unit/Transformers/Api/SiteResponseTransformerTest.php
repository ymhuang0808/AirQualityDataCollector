<?php

use App\Transformers\Api\SiteResponseTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Tests\TestCase;

class SiteResponseTransformerTest extends TestCase
{
    use DatabaseMigrations;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = new SiteResponseTransformer();
    }

    public function testTransformWithOnlyEpaSite()
    {
        $site = $this->createEpaTestData();
        $actual = $this->transformer->transform($site);

        $this->assertEquals('竹山', $actual['name']);
        $this->assertEquals('Zhushan', $actual['eng_name']);
        $this->assertEquals([
            'latitude' => 23.7563890000,
            'longitude' => 120.6773060000,
        ], $actual['coordinates']);
        $this->assertEquals('一般測站', $actual['type']);
        $this->assertEquals('epa', $actual['source_type']);
        $this->assertArrayNotHasKey('county_id', $actual);
        $this->assertArrayNotHasKey('township', $actual);
        $this->assertArrayNotHasKey('created_at', $actual);
        $this->assertArrayNotHasKey('updated_at', $actual);
    }

    public function testTransformWithEpaSiteAndIncludeCounty()
    {
        $site = $this->createEpaTestData();

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        $resource = new Item($site, $this->transformer);

        $actual = $manager->parseIncludes(['county'])
            ->createData($resource)
            ->toArray();

        $this->assertArrayHasKey('county', $actual);
        $this->assertEquals('南投縣', $actual['county']['name']);
    }

    public function testTransformWithEpaSiteAndIncludeTownship()
    {
        $site = $this->createEpaTestData();

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        $resource = new Item($site, $this->transformer);

        $actual = $manager->parseIncludes(['township'])
            ->createData($resource)
            ->toArray();

        $this->assertArrayHasKey('township', $actual);
        $this->assertEquals('竹山鎮', $actual['township']['name']);
    }

    protected function createEpaTestData()
    {
        $county = factory(\App\County::class)->create([
            'name' => '南投縣',
        ]);

        $township = factory(App\Township::class)->create([
            'name' => '竹山鎮',
        ]);

        $site = factory(\App\Site::class)->create([
            'name' => '竹山',
            'eng_name' => 'Zhushan',
            'area_name' => '中部空品區',
            'coordinates' => [
                'latitude' => 23.7563890000,
                'longitude' => 120.6773060000,
            ],
            'type' => '一般測站',
            'source_type' => 'epa',
            'county_id' => $county->id,
            'township_id' => $township->id,
        ]);

        factory(\App\EpaDataset::class)->create([
            'site_id' => $site->id,
        ]);

        return $site;
    }
}