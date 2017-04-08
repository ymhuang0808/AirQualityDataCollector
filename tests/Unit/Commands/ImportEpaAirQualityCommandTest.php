<?php

use App\Commands\ImportEpaAirQualityCommand;
use App\Site;
use App\Transformers\EpaAirQualityTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ImportEpaAirQualityCommandTest extends TestCase
{
    use DatabaseMigrations;

    protected $stubAirQualityRepository;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $jsonFilePath = realpath(__DIR__ . '/../../data/epa_air_quality.json');
        $jsonString = file_get_contents($jsonFilePath);
        $fakeResponse = json_decode($jsonString);

        $this->stubAirQualityRepository = Mockery::mock(\App\Repository\AirQualityRepositoryContract::class, [
            'getAll' => $fakeResponse,
        ]);
        $this->transformer = new EpaAirQualityTransformer();

        $this->createSites();
    }

    public function testExecute()
    {
        $command = new ImportEpaAirQualityCommand($this->stubAirQualityRepository, $this->transformer);
        $command->execute();

        $this->assertDatabaseAirQuality('竹山', 58, 73, 50, '2017-04-04 09:00:00');
        $this->assertDatabaseAirQuality('中壢', 47, 52, 25, '2017-04-04 09:00:00');
        $this->assertDatabaseAirQuality('三重', 46, 57, 18, '2017-04-04 09:00:00');
    }

    protected function createSites()
    {
        $items = [
            [
                'name' => '竹山',
            ],
            [
                'name' => '中壢',
            ],
            [
                'name' => '三重',
            ],
        ];

        foreach ($items as $item) {
            factory(App\Site::class)->create($item);
        }
    }

    protected function assertDatabaseAirQuality(
        $siteName,
        $expectedPsi,
        $expectedPm10,
        $expectedPm25,
        $expectedPublishedDatetime)
    {
        $site = Site::where('name', $siteName)->first();

        $this->assertDatabaseHas('epa_datasets', [
            'psi' => $expectedPsi,
            'site_id' => $site->id,
            'pm10' => $expectedPm10,
            'pm25' => $expectedPm25,
            'published_datetime' => $expectedPublishedDatetime,
        ]);
    }
}