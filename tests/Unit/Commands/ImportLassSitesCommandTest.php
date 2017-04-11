<?php

use App\Commands\ImportLassSitesCommand;
use App\Repository\SimpleArrayCacheRepository;
use App\Site;
use App\Transformers\LassSiteTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ImportLassSitesCommandTest extends TestCase
{
    use DatabaseMigrations;

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

        $this->transformer = new LassSiteTransformer();
    }

    public function testExecuteWithSimpleArrayCacheRepository()
    {
        $fakeResponse = $this->getFakeJsonStrByFileName('lass_sites_air_quality.json');

        $this->mockDatasetRepository
            ->shouldReceive('getAll')
            ->once()
            ->withNoArgs()
            ->andReturn($fakeResponse);

        $simpleArrayCacheRepository = new SimpleArrayCacheRepository();

        $command = new ImportLassSitesCommand(
                $this->mockDatasetRepository,
                $simpleArrayCacheRepository,
                $this->transformer);
        $command->execute();

        $this->assertDatabaseHasSites('FT1_392', 120.642577, 24.22674);
        $this->assertDatabaseHasSites('FT1_0447A', 120.7422, 24.2569);
        $this->assertDatabaseHasSites('FT1_censer', 120.544097, 24.089958);

        $this->assertEquals(3, Site::all()->count());
    }

    public function testExecuteCacheablility()
    {
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

        $command = new ImportLassSitesCommand($this->mockDatasetRepository, $mockCacheRepository, $this->transformer);
        $command->execute();

        $this->assertDatabaseHasSites('WF_1182189', 120.6632, 24.146976);
        $this->assertDatabaseHasSites('NUK_RD5F_inside', 120.283864, 22.734325);
        $this->assertDatabaseHasSites('Quchi', 121.547141, 24.922562);
        $this->assertDatabaseHasSites('FT1_399', 120.532833333, 24.0641883333);

        $this->assertEquals(4, Site::all()->count());
    }

    protected function assertDatabaseHasSites($expectedSiteName, $expectedLongitude, $expectedLatitude)
    {
        $this->assertDatabaseHas('sites', [
            'name' => $expectedSiteName,
        ]);

        $site = Site::where('name', $expectedSiteName)
            ->where('source_type', 'lass')
            ->first();

        $this->assertEquals($expectedLongitude, $site->coordinates['longitude']);
        $this->assertEquals($expectedLatitude, $site->coordinates['latitude']);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function getFakeJsonStrByFileName($name)
    {
        $jsonFilePath = realpath(__DIR__ . '/../../data/' . $name);
        $jsonString = file_get_contents($jsonFilePath);

        return json_decode($jsonString);
    }
}