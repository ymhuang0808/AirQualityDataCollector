<?php

namespace App\Aggregate;


use Carbon\Carbon;

interface AggregateProcessorContract
{
    /**
     * Set aggregated fields
     *
     * @param array $fields
     * @return self
     */
    public function setFields(array $fields);

    /**
     * Get aggregated fields
     *
     * @return array
     */
    public function getFields(): array;

    /**
     * Aggregate the dataset hourly
     *
     * @param $lastTime
     * @return \Carbon\Carbon   The end time of this aggregation process
     */
    public function aggregateHourly($lastTime): Carbon;

    /**
     * Aggregate the dataset daily
     *
     * @param $lastTime The date format
     * @return \Carbon\Carbon   The end time of this aggregation process
     */
    public function aggregateDaily($lastTime): Carbon;

}