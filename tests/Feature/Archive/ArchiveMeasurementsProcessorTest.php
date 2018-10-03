<?php

namespace Test\Feature\Archive;


use App\Archive\ArchiveMeasurementsProcessor;
use App\ArchivedMeasurements;
use App\LassDataset;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class ArchiveMeasurementsProcessorTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use AggregatableTestDataTrait;

    /** @var ArchiveMeasurementsProcessor */
    protected $processor;

    protected function setUp()
    {
        parent::setUp();
        $this->processor = new ArchiveMeasurementsProcessor();
    }

    /**
     * Test for process()
     *
     * The testing case checks the number of archived_measurements and total number of lass_dataset
     */
    public function testProcessWithLassModelClass()
    {
        // Create faked LASS dataset
        $this->setUpLassDatabase();

        $startDateTime = Carbon::createFromTimestamp(1500768000);
        $endDateTime = Carbon::create(2017, 07, 23, 17, 21,31);

        $this->processor
            ->setModelClass(LassDataset::class)
            ->process($startDateTime, $endDateTime, 2);

        // Assert the ArchivedMeasurements
        $this->assertEquals(12, ArchivedMeasurements::all()->count());

        // Assert LassDataset
        $this->assertEquals(7, LassDataset::all()->count());
    }
}