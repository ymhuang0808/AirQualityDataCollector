<?php

namespace App\Repository;


use App\AggregationLog;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Carbon\Carbon;

class AggregationLogRepository implements AggregationLogRepositoryContract
{
    /**
     * @param Carbon $lastExecuteDatetime
     * @param string $sourceType
     * @return Carbon
     */
    public function getEndDatetime(Carbon $lastExecuteDatetime, string $sourceType): Carbon
    {
        $beginDatetime = AggregationLog::where('source_type', $sourceType)
            ->where('aggregation_type', 'daily')
            ->where('start_datetime', '>', $lastExecuteDatetime->toDateTimeString())
            ->max('end_datetime');

        if (!isset($beginDatetime)) {
            return null;
        }

        return Carbon::parse($beginDatetime);
    }
}
