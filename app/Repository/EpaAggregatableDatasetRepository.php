<?php

namespace App\Repository;


use Illuminate\Support\Facades\DB;

class EpaAggregatableDatasetRepository extends AbstractAggretableDatasetRepository
{
    protected $table = 'epa_datasets';

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
        // Query the dataset by site_id and period with AVG()
        $rawString = $this->buildRawString($fieldNames);
        $value = DB::table($this->table)
            ->select('site_id', DB::raw($rawString))
            ->groupBy('site_id')
            ->whereBetween('published_datetime', [$start, $end])
            ->get();

        return $value;
    }
}