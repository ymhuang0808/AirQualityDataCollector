<?php

use App\Commands\CollectEpaAirQualityCommand;
use App\Events\CollectAirQualityCompletedEvent;
use App\Site;
use App\Transformers\EpaAirQualityTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CollectEpaAirQualityCommandTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $stubDatasetRepository;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $jsonFilePath = realpath(__DIR__ . '/../../data/epa_air_quality.json');
        $jsonString = file_get_contents($jsonFilePath);
        $fakeResponse = json_decode($jsonString);

        $this->stubDatasetRepository = Mockery::mock(\App\Repository\Contracts\DatasetRepositoryContract::class);
        $this->stubDatasetRepository->shouldReceive('getAll')->andReturn($fakeResponse);

        $this->transformer = new EpaAirQualityTransformer();

        $this->createSites();
    }

    public function testExecute()
    {
        Event::fake();

        $command = new CollectEpaAirQualityCommand($this->stubDatasetRepository, $this->transformer);
        $command->execute();

        $this->assertDatabaseAirQuality('竹山', 58, 73, 50, '2017-04-04 09:00:00');
        $this->assertDatabaseAirQuality('中壢', 47, 52, 25, '2017-04-04 09:00:00');
        $this->assertDatabaseAirQuality('三重', 46, 57, 18, '2017-04-04 09:00:00');

        Event::assertDispatched(CollectAirQualityCompletedEvent::class, function (CollectAirQualityCompletedEvent $event) {
           return $event->dataset->count() === 3;
        });
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