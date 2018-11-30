<?php

namespace Test\Feature\Jobs;


use App\AggregationLog;
use App\Archive\ArchivedMeasurementsManagerContract;
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

    public function testHandleLassSource()
    {
        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        $this->setUpLassDatabase();
        $this->setUpLassAggregationLog();

        Setting::set('aggregate.lass.daily.air_quality', '2017-07-23 23:59:59');
        Setting::save();

        $manager = resolve(ArchivedMeasurementsManagerContract::class);
        $archiveMeasurementsJob = new ArchiveMeasurementsJob('lass');
        $archiveMeasurementsJob->handle($manager);

        // Assert the ArchivedMeasurements
        $this->assertEquals(16, ArchivedMeasurements::all()->count());

        // Assert LassDataset
        $this->assertEquals(3, LassDataset::all()->count());

        // Assert the last execution timestamp had been set into archived_measurements.last_execute_datetime.lass
        $this->assertEquals($knownDate->toDateTimeString(), Setting::get('archived_measurements.last_execute_datetime.lass'));
    }

    protected function setUpLassAggregationLog()
    {
        factory(AggregationLog::class)->create([
            'aggregation_type' => 'daily',
            'source_type' => 'lass',
            'start_datetime' => '2017-07-22 00:00:00',
            'end_datetime' => '2017-07-22 23:59:59',
            'message' => 'Aggregation completed',
            'level' => 200,
        ]);

        factory(AggregationLog::class)->create([
            'aggregation_type' => 'daily',
            'source_type' => 'lass',
            'start_datetime' => '2017-07-23 00:00:00',
            'end_datetime' => '2017-07-23 23:59:59',
            'message' => 'Aggregation completed',
            'level' => 200,
        ]);
    }
}