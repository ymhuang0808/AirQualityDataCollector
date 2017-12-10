<?php

namespace Test\Unit\Managers;


use App\ArchivedMeasurements;
use App\Helpers\Facades\ClassMappingHelpers;
use App\LassDataset;
use App\Managers\ArchivedMeasurementsManager;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use anlutro\LaravelSettings\Facade as Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AggregatableTestDataTrait;
use Tests\TestCase;

class ArchivedMeasurementsManagerTest extends TestCase
{
    use AggregatableTestDataTrait;
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testSetSourceType()
    {
        $repository = $this->createMockAggregationLogRepository();
        $repository->expects($this->never())
            ->method('getEndDatetime');

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($repository);
        $result = $archivedMeasurementsManager->setSourceType('airbox');

        $this->assertInstanceOf(ArchivedMeasurementsManager::class, $result);
        $this->assertEquals('airbox', $archivedMeasurementsManager->getSourceType());
    }

    /**
     * Test for process()
     *
     * Archived the LassDataset with the published_datetime from 2017-07-23 00:00:00 to 2017-07-23 17:21:31
     */
    public function testProcess()
    {
        // Setup the dependencies
        $repository = $this->createMockAggregationLogRepository();
        $repository->expects($this->once())
            ->method('getEndDatetime')
            ->with($this->callback(function ($lastExecuteDateTime) {
                /** @var Carbon $lastExecuteDateTime */
                return $lastExecuteDateTime->timestamp == 1500768000;
            }), 'lass')
            ->will($this->returnValue(Carbon::create(2017, 07, 23, 17, 21,31)));

        // Set the Carbon current date time
        $knownDate = Carbon::create(2017, 07, 25, 00, 31);
        Carbon::setTestNow($knownDate);

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurement.last_execute_datetime.lass', 0)
            ->andReturn(1500768000);

        Setting::shouldReceive('set')
            ->once()
            ->with('archived_measurement.last_execute_datetime.lass', $knownDate->timestamp)
            ->andReturnUndefined();

        // Archives the LassDataset measurements
        ClassMappingHelpers::shouldReceive('getModelBySourceType')
            ->once()
            ->with('lass')
            ->andReturn(LassDataset::class);

        // Create faked LASS dataset
        $this->setUpLassDatabase();

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($repository);
        $archivedMeasurementsManager->setSourceType('lass')->process(5);

        // Assert the ArchivedMeasurements
        $this->assertEquals(12, ArchivedMeasurements::all()->count());

        // Assert LassDataset
        $this->assertEquals(7, LassDataset::all()->count());
    }

    public function testProcessWithLastExecuteDateTimeIsGreaterThanEndDateTime()
    {
        // Setup the dependencies
        $repository = $this->createMockAggregationLogRepository();
        $repository->expects($this->once())
            ->method('getEndDatetime')
            ->with($this->callback(function ($lastExecuteDateTime) {
                /** @var Carbon $lastExecuteDateTime */
                return $lastExecuteDateTime->timestamp == 1500768000;
            }), 'lass')
            ->will($this->returnValue(Carbon::create(2017, 07, 22, 10, 00,00)));

        Setting::shouldReceive('get')
            ->once()
            ->with('archived_measurement.last_execute_datetime.lass', 0)
            ->andReturn(1500768000);

        Setting::shouldReceive('set')
            ->never();

        // Archives the LassDataset measurements
        ClassMappingHelpers::shouldReceive('getModelBySourceType')
            ->never();

        // Create faked LASS dataset
        $this->setUpLassDatabase();

        $archivedMeasurementsManager = new ArchivedMeasurementsManager($repository);
        $result = $archivedMeasurementsManager->setSourceType('lass')->process(5);

        $this->assertFalse($result);

        // Assert the ArchivedMeasurements
        $this->assertEquals(0, ArchivedMeasurements::all()->count());

        // Assert LassDataset
        $this->assertEquals(19, LassDataset::all()->count());
    }

    protected function createMockAggregationLogRepository()
    {
        $repository = $this->getMockBuilder(AggregationLogRepositoryContract::class)
            ->setMethods(['getEndDatetime'])
            ->getMock();

        return $repository;
    }
}