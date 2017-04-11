<?php

use App\Commands\ImportEpaSitesCommand;
use App\County;
use App\Site;
use App\Township;
use App\Transformers\EpaSiteTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ImportEpaSitesCommandTest extends TestCase
{
    use DatabaseMigrations;

    protected $stubSitesRepository;
    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $jsonFilePath = realpath(__DIR__ . '/../../data/epa_sites.json');
        $jsonString = file_get_contents($jsonFilePath);
        $fakeResponse = json_decode($jsonString);

        $this->stubSitesRepository = Mockery::mock(\App\Repository\Contracts\DatasetRepositoryContract::class, [
            'getAll' => $fakeResponse,
        ]);
        $this->transformer = new EpaSiteTransformer();
    }

    public function testExecute()
    {
        $command = new ImportEpaSitesCommand($this->stubSitesRepository, $this->transformer);
        $command->execute();

        $this->assertDatabaseHasSites('臺東縣', '臺東市', '臺東', 'Taitung', '花東空品區', '一般測站', 121.1504500000, 22.7553580000);
        $this->assertDatabaseHasSites('臺南市', '中西區', '臺南', 'Tainan', '雲嘉南空品區', '一般測站', 120.2026170000, 22.9845810000);

        $this->assertEquals(7, Site::all()->count());
        $this->assertEquals(5, County::all()->count());
        $this->assertEquals(7, Township::all()->count());
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * Provide a assertion for checking the database has correct values
     *
     * @param string $expectedCountyName
     * @param string $expectedTownshipName
     * @param string $expectedSiteName
     * @param string $expectedSiteEngName
     * @param string $expectedAreaName
     * @param string $expectedType
     * @param float $expectedLongitude
     * @param float $expectedLatitude
     */
    protected function assertDatabaseHasSites(
        string $expectedCountyName,
        string $expectedTownshipName,
        string $expectedSiteName,
        string $expectedSiteEngName,
        string $expectedAreaName,
        string $expectedType,
        float $expectedLongitude,
        float $expectedLatitude)
    {
        $this->assertDatabaseHas('counties', [
            'name' => $expectedCountyName,
        ]);
        $this->assertDatabaseHas('townships', [
            'name' => $expectedTownshipName,
        ]);

        $county = County::where('name', $expectedCountyName)->first();
        $township = Township::where('name', $expectedTownshipName)->first();

        $this->assertDatabaseHas('sites', [
            'name' => $expectedSiteName,
            'eng_name' => $expectedSiteEngName,
            'area_name' => $expectedAreaName,
            'type' => $expectedType,
            'county_id' => $county->id,
            'township_id' => $township->id,
        ]);

        $site = Site::where('name', $expectedSiteName)
            ->where('source_type', 'epa')
            ->first();

        $this->assertEquals($expectedLongitude, $site->coordinates['longitude']);
        $this->assertEquals($expectedLatitude, $site->coordinates['latitude']);
    }
}
