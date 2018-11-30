<?php

namespace Test\Unit\Archive;


use App\Archive\ArchiveMeasurementsProcessorContract;
use App\Helpers\Facades\ClassMappingHelpers;
use App\Jobs\ArchiveMeasurementsJob;
use App\LassDataset;
use App\Archive\ArchivedMeasurementsManager;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use anlutro\LaravelSettings\Facade as Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Bus;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class ArchivedMeasurementsManagerTest extends TestCase
{
    use AggregatableTestDataTrait;
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Test for setSourceType()
     */
    public function testSetSourceType()
    {
        $processor = $this->createMockArchiveMeasurementsProcessor();
        $processor->expects($this->never())
            ->method('setModelClass');
        $processor->expects($this->never())
            ->method('process');

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($processor);
        $result = $archivedMeasurementsManager->setSourceType('airbox');

        $this->assertInstanceOf(ArchivedMeasurementsManager::class, $result);
        $this->assertEquals('airbox', $archivedMeasurementsManager->getSourceType());
    }

    /**
     * Test for archiveAll()
     *
     * Archives the LassDataset with the published_datetime from 2017-07-23 00:00:00 to 2017-07-23 17:21:31
     * The testing case checks that the the archive measurements process receives correct date time parameters and
     * the archived_measurements.last_execute_datetime.lass setting value should be set into current timestamp.
     */
    public function testArchiveAll()
    {
        $expectedEndDateTime = Carbon::create(2017, 07, 23, 17, 21,31);

        // Setup the dependencies
        $processor = $this->createMockArchiveMeasurementsProcessor();
        $processor->expects($this->once())
            ->method('setModelClass')
            ->with(\App\LassDataset::class)
            ->will($this->returnSelf());
        $processor->expects($this->once())
            ->method('process')
            ->with(
                $this->callback(function (Carbon $start) {
                    return $start->getTimestamp() == 1500768000;
                }),
                $this->callback(function (Carbon $end) use ($expectedEndDateTime) {
                    return $end->getTimestamp() == $expectedEndDateTime->getTimestamp();
                }),
                $this->callback(function (int $chunkCount) {
                    return $chunkCount == 5;
                })
            );

        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        Setting::shouldReceive('load')
            ->with(true)
            ->twice();

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_execute_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 00:00:00');

        Setting::shouldReceive('get')
            ->once()
            ->with('aggregate.lass.daily.air_quality', '2017-01-01 00:00:00')
            ->andReturn($expectedEndDateTime->toDateTimeString());

        Setting::shouldReceive('set')
            ->atLeast()
            ->with('archived_measurements.last_execute_datetime.lass', $knownDate->toDateTimeString())
            ->andReturnUndefined();

        Setting::shouldReceive('save')
            ->once()
            ->withNoArgs();

        // Archives the LassDataset measurements
        ClassMappingHelpers::shouldReceive('getModelBySourceType')
            ->once()
            ->with('lass')
            ->andReturn(LassDataset::class);

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($processor);
        $result = $archivedMeasurementsManager->setSourceType('lass')->archiveAll(5);

        $this->assertTrue($result);
    }

    /**
     * Test for archiveAll()
     *
     * The testing case checks last execution date time is greater than end date time. The archive
     * measurements processor should not process and archiveAll() should return false.
     */
    public function testArchiveAllWithLastExecuteDateTimeIsGreaterThanEndDateTime()
    {
        // Setup the dependencies
        $processor = $this->createMockArchiveMeasurementsProcessor();
        $processor->expects($this->never())
            ->method('setModelClass');
        $processor->expects($this->never())
            ->method('process');

        Setting::shouldReceive('load')
            ->with(true)
            ->twice();

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_execute_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 00:00:00');

        Setting::shouldReceive('get')
            ->once()
            ->with('aggregate.lass.daily.air_quality', '2017-01-01 00:00:00')
            ->andReturn('2017-07-22 10:00:00');

        Setting::shouldReceive('set')
            ->never();

        // Archives the LassDataset measurements
        ClassMappingHelpers::shouldReceive('getModelBySourceType')
            ->never();

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($processor);
        $result = $archivedMeasurementsManager->setSourceType('lass')->archiveAll(5);

        $this->assertFalse($result);
    }

    /**
     * Test for dispatchJob()
     *
     * The testing case checks that the \App\Jobs\ArchiveMeasurementsJob is dispatched with correct parameters and
     * the archived_measurements.last_job_dispatch_datetime.lass setting value should be set into current timestamp
     */
    public function testDispatchJob()
    {
        $expectedEndDateTime = Carbon::create(2017, 07, 23, 17, 21,31);

        // Setup the dependencies
        $processor = $this->createMockArchiveMeasurementsProcessor();
        $processor->expects($this->never())
            ->method('setModelClass');
        $processor->expects($this->never())
            ->method('process');

        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        Setting::shouldReceive('load')
            ->times(3)
            ->with(true);

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_execute_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 00:00:00');

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_job_dispatch_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-01-01 00:00:00');

        Setting::shouldReceive('get')
            ->once()
            ->with('aggregate.lass.daily.air_quality', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 17:21:31');

        Setting::shouldReceive('set')
            ->once()
            ->with('archived_measurements.last_job_dispatch_datetime.lass', $knownDate->toDateTimeString())
            ->andReturnUndefined();

        Setting::shouldReceive('save')
            ->once()
            ->withNoArgs();


        // Fake the dispatching job
        Bus::fake();

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($processor);
        $result = $archivedMeasurementsManager->setSourceType('lass')->dispatchJob();

        $this->assertTrue($result);

        Bus::assertDispatched(ArchiveMeasurementsJob::class, function (ArchiveMeasurementsJob $job) use ($expectedEndDateTime) {
            return $job->getSource() == 'lass';
        });
    }

    /**
     * Test for dispatchJob() when a job is not processed
     */
    public function testDispatchJobWhenJobIsNotProcessed()
    {
        $expectedEndDateTime = Carbon::create(2017, 07, 23, 17, 21,31);

        // Setup the dependencies
        $processor = $this->createMockArchiveMeasurementsProcessor();
        $processor->expects($this->never())
            ->method('setModelClass');
        $processor->expects($this->never())
            ->method('process');

        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        Setting::shouldReceive('load')
            ->times(3)
            ->with(true);

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_execute_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 00:00:00');

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurements.last_job_dispatch_datetime.lass', '2017-01-01 00:00:00')
            ->andReturn('2017-07-23 00:01:40');


        Setting::shouldReceive('get')
            ->once()
            ->with('aggregate.lass.daily.air_quality', '2017-01-01 00:00:00')
            ->andReturn($expectedEndDateTime->toDateTimeString());

        Setting::shouldReceive('set')
            ->never()
            ->with('archived_measurements.last_job_dispatch_datetime.lass', $knownDate->timestamp);


        // Fake the dispatching job
        Bus::fake();

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($processor);
        $result = $archivedMeasurementsManager->setSourceType('lass')->dispatchJob();

        $this->assertFalse($result);

        Bus::assertNotDispatched(ArchiveMeasurementsJob::class);
    }

    protected function createMockAggregationLogRepository()
    {
        $repository = $this->getMockBuilder(AggregationLogRepositoryContract::class)
            ->setMethods(['getEndDatetime'])
            ->getMock();

        return $repository;
    }

    protected function createMockArchiveMeasurementsProcessor()
    {
        $processor = $this->getMockBuilder(ArchiveMeasurementsProcessorContract::class)
            ->setMethods(['setModelClass', 'process'])
            ->getMock();

        return $processor;
    }
}