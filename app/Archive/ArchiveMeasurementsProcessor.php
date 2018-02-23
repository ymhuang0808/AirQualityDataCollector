<?php

namespace App\Archive;


use App\ArchivedMeasurements;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
        Log::debug('=== ArchiveMeasurementsProcessor::process() ===');
        Log::debug('$start = ' . $start->toDateTimeString() . ', $end = ' . $end->toDateTimeString());

        $measurements = $this->getArchivedMeasurementsBetween($start, $end, $chunkCount);

        while ($measurements->count() > 0) {
            $measurements->each(function ($measurement) {
                $payload = $measurement->getMeasurementPayload();
                $publishedDatetime = $measurement->getPublishedDateTime();

                // Saved into the archived_measurements
                ArchivedMeasurements::create([
                    'values' => $payload,
                    'published_datetime' => $publishedDatetime,
                    'site_id' => $measurement->getSite()->id,
                ]);
                $measurement->delete();
            });

            $measurements = $this->getArchivedMeasurementsBetween($start, $end, $chunkCount);
        }

    }

    protected function getArchivedMeasurementsBetween(Carbon $start, Carbon $end, int $chunkCount = 100)
    {
        /** @var Collection $measurements */
        $measurements = $this->modelClass::where('published_datetime', '>', $start->toDateTimeString())
            ->where('published_datetime', '<=', $end->toDateTimeString())
            ->take($chunkCount)
            ->get();

        return $measurements;
    }
}