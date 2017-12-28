<?php

namespace App\Archive;


use App\Jobs\ArchiveMeasurementsJob;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Carbon\Carbon;
use Setting;
use ClassMappingHelpers;

/**
 * To reduce the number of measurements, ArchivedMeasurementsManager archives source types of measurements.
 */
class ArchivedMeasurementsManager implements ArchivedMeasurementsManagerContract
{
    const LAST_EXECUTE_TIMESTAMP_SETTING_PARENT_NAME = 'archived_measurements.last_execute_timestamp.';

    const LAST_JOB_DISPATCH_DATETIME_SETTING_PARENT_NAME = 'archived_measurements.last_job_dispatch_timestamp.';

    protected $aggregationLogRepository;

    protected $processor;

    protected $sourceType;

    public function __construct(AggregationLogRepositoryContract $aggregationLogRepository, ArchiveMeasurementsProcessorContract $processor)
    {
        $this->aggregationLogRepository = $aggregationLogRepository;
        $this->processor = $processor;
    }

    public function setSourceType(string $sourceType)
    {
        $this->sourceType = $sourceType;
        return $this;
    }

    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    public function archiveAll(int $chunkCount = 100)
    {
        $lastExecuteDateTime = $this->getLastExecuteDateTime();
        $endDatetime = $this
            ->aggregationLogRepository
            ->getEndDatetime($lastExecuteDateTime, $this->sourceType);

        // Check if the latest aggregation time is less than last execution time
        if (is_null($endDatetime) || $lastExecuteDateTime >= $endDatetime) {
            return false;
        }

        $modelClass = ClassMappingHelpers::getModelBySourceType($this->sourceType);
        $this->processor
            ->setModelClass($modelClass)
            ->process($lastExecuteDateTime, $endDatetime, $chunkCount);

        // Save the end time
        $timestamp = Carbon::now()->getTimestamp();
        Setting::set($this->getLastExecuteTimestampSettingName(), $timestamp);

        return true;
    }

    /**
     * Dispatch bulk jobs queue
     *
     * @return bool
     */
    public function dispatchJob(): bool
    {
        $lastExecuteDateTime = $this->getLastExecuteDateTime();
        $lastJobDispatchDateTime = $this->getLastJobDispatchDateTime();
        $endDatetime = $this
            ->aggregationLogRepository
            ->getEndDatetime($lastExecuteDateTime, $this->sourceType);

        // Check if the latest aggregation time is less than last execution time
        if (is_null($endDatetime) || $lastExecuteDateTime >= $endDatetime) {
            return false;
        }

        // To avoid dispatching duplicated jobs, checks if the last execution time is less than
        // or equals to the last job dispatch time
        if ($lastExecuteDateTime->lessThanOrEqualTo($lastJobDispatchDateTime)) {
            return false;
        }

        ArchiveMeasurementsJob::dispatch($this->sourceType, $lastExecuteDateTime->getTimestamp(), $endDatetime->getTimestamp())
            ->delay(now()->addMinutes(1));

        $timestamp = Carbon::now()->getTimestamp();
        $settingName = $this->getLastJobDispatchDateTimeSettingName();
        Setting::set($settingName, $timestamp);

        return true;
    }

    protected function getLastJobDispatchDateTime(): Carbon
    {
        $defaultLastJobDispatchTimestamp = 0;
        $lastJobDispatchTimestamp = Setting::get($this->getLastJobDispatchDateTimeSettingName(), $defaultLastJobDispatchTimestamp);
        $lastJobDispatchDateTime  = Carbon::createFromTimestamp((int) $lastJobDispatchTimestamp);

        return $lastJobDispatchDateTime;
    }

    protected function getLastExecuteDateTime(): Carbon
    {
        $defaultLastExecuteTimestamp = 0;
        $lastExecuteTimestamp = Setting::get($this->getLastExecuteTimestampSettingName(), $defaultLastExecuteTimestamp);
        $lastExecuteDateTime = Carbon::createFromTimestamp((int) $lastExecuteTimestamp);

        return $lastExecuteDateTime;
    }

    protected function getLastExecuteTimestampSettingName(): string
    {
        return self::LAST_EXECUTE_TIMESTAMP_SETTING_PARENT_NAME . $this->sourceType;
    }

    protected function getLastJobDispatchDateTimeSettingName(): string
    {
        return self::LAST_JOB_DISPATCH_DATETIME_SETTING_PARENT_NAME . $this->sourceType;
    }
}
