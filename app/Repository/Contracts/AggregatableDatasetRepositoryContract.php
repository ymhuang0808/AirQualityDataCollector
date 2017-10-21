<?php

namespace App\Repository\Contracts;


interface AggregatableDatasetRepositoryContract
{
    /**
     * Get average field between published datetime
     *
     * @param array $fieldNames
     * @param $start
     * @param $end
     * @return mixed
     * @internal param string $fieldName
     * @internal param int $siteId
     */
    public function getAvgFieldBetweenPublishedDatetime(array $fieldNames, $start, $end);

    /**
     * Get the source type of measurements
     *
     * @return string
     */
    public function getSourceType(): string ;
}