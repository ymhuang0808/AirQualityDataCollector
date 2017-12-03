<?php

namespace App\Managers;


use App\ArchivedMeasurements;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Carbon\Carbon;
use Setting;
use ClassMappingHelpers;

/**
 * To reduce the number of measurements, ArchivedMeasurementsManager archives source types of measurements.
 */
class ArchivedMeasurementsManager implements ArchivedMeasurementsManagerInterface
{
    protected $aggregationLogRepository;

    protected $sourceType;

    public function __construct(AggregationLogRepositoryContract $aggregationLogRepository)
    {
        $this->aggregationLogRepository = $aggregationLogRepository;
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

    public function process(int $chunkCount = 100)
    {
        $settingName = 'archived_measurement.last_execute_datetime.' . $this->sourceType;
        $defaultLastExecuteTimestamp = 0;
        $lastExecuteTimestamp = Setting::get($settingName, $defaultLastExecuteTimestamp);
        $lastExecuteDateTime = Carbon::createFromTimestamp((int) $lastExecuteTimestamp);
        $endDatetime = $this
            ->aggregationLogRepository
            ->getEndDatetime($lastExecuteDateTime, $this->sourceType);

        $resultMeasurements = collect();

        // Get the measurements
        $modelClass = ClassMappingHelpers::getModelBySourceType($this->sourceType);
        $modelClass::where('published_datetime', '>', $lastExecuteDateTime->toDateTimeString())
            ->where('published_datetime', '<=', $endDatetime->toDateTimeString())
            ->chunk($chunkCount, function ($measurements) use ($resultMeasurements) {
                /** @var \App\ModelMeasurementContract $measurement */
                foreach ($measurements as $measurement) {
                    $payload = $measurement->getMeasurementPayload();
                    $publishedDatetime = $measurement->getPublishedDateTime();

                    // Saved into the archived_measurements
                    ArchivedMeasurements::create([
                       'values' => $payload,
                       'published_datetime' => $publishedDatetime,
                       'site_id' => $measurement->getSite()->id,
                    ]);

                    $resultMeasurements->push($measurement);
                }
            });

        $resultMeasurements->each(function ($item) {
            $item->delete();
        });

        // Save the end time
        $timestamp = Carbon::create()->timestamp;
        Setting::set($settingName, $timestamp);
    }
}
