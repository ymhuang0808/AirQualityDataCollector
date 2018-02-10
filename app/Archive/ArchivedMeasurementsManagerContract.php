<?php

namespace App\Archive;


use Carbon\Carbon;

interface ArchivedMeasurementsManagerContract
{
    /**
     * Sets the the source type of archived measurements
     *
     * @param string $sourceType
     * @return self
     */
    public function setSourceType(string $sourceType);

    /**
     * Gets the source type of archived measurements
     *
     * @return string
     */
    public function getSourceType(): string ;

    /**
     * Archives all processed measurements
     *
     * @param int $chunkCount
     */
    public function archiveAll(int $chunkCount = 100);

    /**
     * Dispatch jobs queue
     *
     * @return bool
     */
    public function dispatchJob(): bool;

    /**
     * Set archived date time
     *
     * @param Carbon|null $datetime
     * @return mixed
     */
    public function setArchivedDateTime(Carbon $datetime = null);
}