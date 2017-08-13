<?php

namespace App\Repository;


use App\Repository\Contracts\AggregatableDatasetRepositoryContract;
use Illuminate\Support\Facades\DB;

class LassAggregatableDatasetRepository extends AbstractAggretableDatasetRepository implements AggregatableDatasetRepositoryContract
{
    protected $table = 'lass_datasets';

    /**
     * @param $datetime
     * @return mixed
     */
    public function getSiteIdAndMinDatetimeSincePublishedDatetime($datetime)
    {
        // Find the dataset items with MIN published_datetime GROUP by site_id
        $minDataset = DB::table($this->table)
            ->select(DB::raw('min(published_datetime) as min_datetime, site_id'))
            ->where('published_datetime', '>', $datetime)
            ->groupBy('site_id')
            ->get();

        return $minDataset;
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
        // Query the dataset by site_id and period with AVG()
        $rawString = $this->buildRawString($fieldNames);
        $value = DB::table('lass_datasets')
            ->select('site_id', DB::raw($rawString))
            ->groupBy('site_id')
            ->whereBetween('published_datetime', [$start, $end])
            ->get();

        return $value;
    }

    /**
     * @param array $fieldNames
     * @return string
     */
    protected function buildRawString(array $fieldNames)
    {
        $rawStringItems = [];
        $count = 0;

        foreach ($fieldNames as $field) {
            $rawStringItems[] = sprintf('AVG(%s) as avg_value_%d', $field, $count);
            $count++;
        }

        $rawString = implode(', ', $rawStringItems);

        return $rawString;
    }
}