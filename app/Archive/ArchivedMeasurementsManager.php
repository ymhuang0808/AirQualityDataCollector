<?php

namespace App\Archive;


use App\Jobs\ArchiveMeasurementsJob;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Setting;
use ClassMappingHelpers;

/**
 * To reduce the number of measurements, ArchivedMeasurementsManager archives source types of measurements.
 */
class ArchivedMeasurementsManager implements ArchivedMeasurementsManagerContract
{
    const LAST_EXECUTE_TIMESTAMP_SETTING_PARENT_NAME = 'archived_measurements.last_execute_datetime.';

    const LAST_JOB_DISPATCH_DATETIME_SETTING_PARENT_NAME = 'archived_measurements.last_job_dispatch_datetime.';

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
        /** @var Carbon $endDatetime */
        $endDatetime = $this
            ->aggregationLogRepository
            ->getEndDatetime($lastExecuteDateTime, $this->sourceType);

        Log::debug('ArchivedMeasurementsManager - sourceType = ' . $this->getSourceType());
        Log::debug('ArchivedMeasurementsManager - $endDateTime = ' . (is_null($endDatetime)) ? 'NULL' : $endDatetime->toDateTimeString());
        Log::debug('ArchivedMeasurementsManager - $lastExecuteDateTime = ' . $lastExecuteDateTime->toDateTimeString());

        // Check if the latest aggregation time is less than last execution time
        if (is_null($endDatetime) || $lastExecuteDateTime >= $endDatetime) {
            return false;
        }

        $modelClass = ClassMappingHelpers::getModelBySourceType($this->sourceType);
        $this->processor
            ->setModelClass($modelClass)
            ->process($lastExecuteDateTime, $endDatetime, $chunkCount);

        $this->setArchivedDateTime();

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

        Log::debug('ArchivedMeasurementsManager::dispatchJob() sourceType = ' . $this->getSourceType());
        Log::debug('ArchivedMeasurementsManager $endDateTime = ' . (is_null($endDatetime)) ? 'NULL' : $endDatetime->toDateTimeString());
        Log::debug('ArchivedMeasurementsManager $lastExecuteDateTime = ' . $lastExecuteDateTime->toDateTimeString());

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

        $this->setLastJobDispatchDateTime();

        return true;
    }

    /**
     * Set archived date time
     *
     * @param Carbon|null $datetime
     * @return mixed
     */
    public function setArchivedDateTime(Carbon $datetime = null)
    {
        if (is_null($datetime)) {
            $dateTimeString = Carbon::now()->toDateTimeString();
        } else {
            $dateTimeString = $datetime->toDateTimeString();
        }

        $name = $this->getLastExecuteTimestampSettingName();
        Setting::set($name, $dateTimeString);
        Setting::save();
    }

    protected function setLastJobDispatchDateTime(Carbon $datetime = null)
    {
        if (is_null($datetime)) {
            $dateTimeString = Carbon::now()->toDateTimeString();
        } else {
            $dateTimeString = $datetime->toDateTimeString();
        }

        $name = $this->getLastJobDispatchDateTimeSettingName();
        Setting::set($name, $dateTimeString);
        Setting::save();
    }

    protected function getLastJobDispatchDateTime(): Carbon
    {
        Setting::load(true);

        $defaultLastJobDispatchTimestamp = '2017-01-01 00:00:00';
        $lastJobDispatchDateTimeString = Setting::get($this->getLastJobDispatchDateTimeSettingName(), $defaultLastJobDispatchTimestamp);
        $lastJobDispatchDateTime  = Carbon::createFromFormat('Y-m-d H:i:s', $lastJobDispatchDateTimeString);

        return $lastJobDispatchDateTime;
    }

    protected function getLastExecuteDateTime(): Carbon
    {
        Setting::load(true);

        $defaultLastExecuteDateTimeString = '2017-01-01 00:00:00';
        $lastExecuteDateTimeString = Setting::get($this->getLastExecuteTimestampSettingName(), $defaultLastExecuteDateTimeString);
        $lastExecuteDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastExecuteDateTimeString);

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
