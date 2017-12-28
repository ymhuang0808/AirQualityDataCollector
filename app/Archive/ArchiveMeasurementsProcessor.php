<?php

namespace App\Archive;


use App\ArchivedMeasurements;
use Carbon\Carbon;
use Setting;

/**
 * Class ArchiveMeasurementsProcessor
 * @package App\Archive
 */
class ArchiveMeasurementsProcessor implements ArchiveMeasurementsProcessorContract
{
    protected $modelClass;

    /**
     * Sets model class
     *
     * @param $class
     * @return ArchiveMeasurementsProcessor
     */
    public function setModelClass($class)
    {
        $this->modelClass = $class;

        return $this;
    }

    /**
     * Archives the measurements by date time
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param int $chunkCount
     * @return mixed
     */
    public function process(Carbon $start, Carbon $end, int $chunkCount = 100)
    {
        $resultMeasurements = collect();

        $this->modelClass::where('published_datetime', '>', $start->toDateTimeString())
            ->where('published_datetime', '<=', $end->toDateTimeString())
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
    }
}