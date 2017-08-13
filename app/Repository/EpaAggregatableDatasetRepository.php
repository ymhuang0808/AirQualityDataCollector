<?php

namespace App\Repository;


use App\Repository\Contracts\AggregatableDatasetRepositoryContract;

class EpaAggregatableDatasetRepository implements AggregatableDatasetRepositoryContract
{

    public function getSiteIdAndMinDatetimeSincePublishedDatetime($datetime)
    {
        // TODO: Implement getByPublishedDatetime() method.
    }

    /**
     * Get average field between published datetime
     *
     * @param array|string $fieldNames
     * @param int $start
     * @param $end
     * @return mixed
     * @internal param int $siteId
     */
    public function getAvgFieldBetweenPublishedDatetime(array $fieldNames, $start, $end)
    {
        // TODO: Implement getAvgFieldBetweenPublishedDatetime() method.
    }
}