<?php

namespace App\Archive;


use Carbon\Carbon;

interface ArchiveMeasurementsProcessorContract
{
    /**
     * Sets model class
     *
     * @param $class
     * @return mixed
     */
    public function setModelClass($class);

    /**
     * Archives the measurements by date time
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param int $chunckCount
     * @return mixed
     */
    public function process(Carbon $start, Carbon $end, int $chunckCount = 100);
}