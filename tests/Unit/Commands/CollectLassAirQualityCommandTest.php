<?php

use App\Commands\CollectLassAirQualityCommand;
use App\Events\CollectAirQualityCompletedEvent;
use App\Repository\SimpleArrayCacheRepository;
use App\Site;
use App\Transformers\LassAirQualityTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CollectLassAirQualityCommandTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $mockDatasetRepository;

    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $this->mockDatasetRepository = Mockery::mock(\App\Repository\Contracts\DatasetRepositoryContract::class);

        $this->mockDatasetRepository
            ->shouldReceive('getBasedUrl')
            ->once()
            ->withNoArgs()
            ->andReturn('https://fake.lass.dataset');

        $this->mockDatasetRepository
            ->shouldReceive('getPath')
            ->once()
            ->withNoArgs()
            ->andReturn('/all-endpoint');

        $this->transformer = new LassAirQualityTransformer();
    }

    public function testExecute()
    {
        Event::fake();

        $this->createSitesWithLassAirQuality();

        $fakeResponse = $this->getFakeJsonStrByFileName('lass_sites_air_quality.json');

        $this->mockDatasetRepository
            ->shouldReceive('getAll')
            ->once()
            ->withNoArgs()
            ->andReturn($fakeResponse);

        $simpleArrayCacheRepository = new SimpleArrayCacheRepository();

        $command = new CollectLassAirQualityCommand(
            $this->mockDatasetRepository,
            $simpleArrayCacheRepository,
            $this->transformer
        );
        $command->execute();

        $this->assertDatabaseHasAirQuality('FT1_392', 38, 35, 25.7, 99.9, '2017-04-08 07:32:56');
        $this->assertDatabaseHasAirQuality('FT1_0447A', 39, 33, 27.0, 72.0, '2017-04-08 07:33:34');
        $this->assertDatabaseHasAirQuality('FT1_censer', 33, 30, 32.8, 54.7, '2017-04-08 07:28:05');

        Event::assertDispatched(CollectAirQualityCompletedEvent::class, function (CollectAirQualityCompletedEvent $event) {
           return $event->dataset->count() === 3 && $event->type === 'lass';
        });
    }

    public function testExecuteWithCacheablility()
    {
        Event::fake();

        $this->createSitesWithLassAirQuality2();

        $this->mockDatasetRepository
            ->shouldReceive('getAll')
            ->never();

        $mockCacheRepository = Mockery::mock(\App\Repository\Contracts\CacheableContact::class);

        $mockCacheRepository->shouldReceive('isHit')
            ->with('lass-dataset-url:https://fake.lass.dataset/all-endpoint')
            ->andReturn(true);

        $fakeCachedData = $this->getFakeJsonStrByFileName('lass_sites_air_quality_2.json');
        $mockCacheRepository->shouldReceive('getItemByKey')
            ->with('lass-dataset-url:https://fake.lass.dataset/all-endpoint')
            ->andReturn($fakeCachedData);

        $command = new CollectLassAirQualityCommand(
            $this->mockDatasetRepository,
            $mockCacheRepository,
            $this->transformer
        );
        $command->execute();

        $this->assertDatabaseHasAirQuality('WF_1182189', 37, 31, 27.8, 68.3, '2017-04-08 06:51:53');
        $this->assertDatabaseHasAirQuality('NUK_RD5F_inside', 11, 10, 29.5, 57.3, '2017-04-08 07:03:29');
        $this->assertDatabaseHasAirQuality('Quchi', 62, 48, 29.9, 82.1, '2017-04-08 07:03:38');
        $this->assertDatabaseHasAirQuality('FT1_399', 22, 19, 27.98, 69.53, '2017-04-08 07:03:32');

        // TODO: Testing for the event dispatching
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function assertDatabaseHasAirQuality(
        $siteName,
        $expectedPm10,
        $expectedPm25,
        $expectedTemp,
        $expectedHumidity,
        $expectedPublishedDatetime
    )
    {
        $site = Site::where('name', $siteName)->first();

        $this->assertDatabaseHas('lass_datasets', [
            'site_id' => $site->id,
            'pm10' => $expectedPm10,
            'pm25' => $expectedPm25,
            'temperature' => $expectedTemp,
            'humidity' => $expectedHumidity,
            'published_datetime' => $expectedPublishedDatetime,
        ]);
    }

    protected function getFakeJsonStrByFileName($name)
    {
        $jsonFilePath = realpath(__DIR__ . '/../../data/' . $name);
        $jsonString = file_get_contents($jsonFilePath);

        return json_decode($jsonString);
    }

    protected function createSitesWithLassAirQuality()
    {
        $items = [
            [
                'name' => 'FT1_392',
                'source_type' => 'lass',
            ],
            [
                'name' => 'FT1_0447A',
                'source_type' => 'lass',
            ],
            [
                'name' => 'FT1_censer',
                'source_type' => 'lass',
            ],
        ];

        foreach ($items as $item) {
            factory(App\Site::class)->create($item);
        }
    }

    protected function createSitesWithLassAirQuality2()
    {
        $items = [
            [
                'name' => 'FT1_399',
                'source_type' => 'lass',
            ],
            [
                'name' => 'Quchi',
                'source_type' => 'lass',
            ],
            [
                'name' => 'NUK_RD5F_inside',
                'source_type' => 'lass',
            ],
            [
                'name' => 'WF_1182189',
                'source_type' => 'lass',
            ],
        ];

        foreach ($items as $item) {
            factory(App\Site::class)->create($item);
        }
    }
}