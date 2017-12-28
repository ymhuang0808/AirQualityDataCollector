<?php

namespace Test\Feature\Jobs;


use App\Archive\ArchiveMeasurementsProcessorContract;
use App\ArchivedMeasurements;
use App\Jobs\ArchiveMeasurementsJob;
use App\LassDataset;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\AggregationLogTableTestDataTrait;
use Tests\TestCase;
use Setting;

class ArchiveMeasurementsJobTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregationLogTableTestDataTrait;
    use AggregatableTestDataTrait;

    protected $processor;

    protected function setUp()
    {
        parent::setUp();
        $this->processor = resolve(ArchiveMeasurementsProcessorContract::class);
    }

    public function testHandleLassSource()
    {
        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        $this->setUpLassDatabase();

        $end = Carbon::create(2017, 07, 23, 17, 21,31)->getTimestamp();
        $archiveMeasurementsJob = new ArchiveMeasurementsJob('lass', 1500768000, $end);
        $archiveMeasurementsJob->handle($this->processor);

        // Assert the ArchivedMeasurements
        $this->assertEquals(12, ArchivedMeasurements::all()->count());

        // Assert LassDataset
        $this->assertEquals(7, LassDataset::all()->count());

        // Assert the last execution timestamp had been set into archived_measurements.last_execute_timestamp.lass
        $this->assertEquals($knownDate->getTimestamp(), Setting::get('archived_measurements.last_execute_timestamp.lass'));
    }
}