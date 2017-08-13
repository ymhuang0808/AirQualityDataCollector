<?php

namespace App\Repository\Contracts;


interface AggregatableDatasetRepositoryContract
{
    /**
     * Get site site id and mini datetime since the published datetime
     *
     * @param $datetime
     * @return mixed
     */
    public function getSiteIdAndMinDatetimeSincePublishedDatetime($datetime);

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
}