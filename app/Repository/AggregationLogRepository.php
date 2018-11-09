<?php

namespace App\Repository;


use App\AggregationLog;
use App\Repository\Contracts\AggregationLogRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AggregationLogRepository implements AggregationLogRepositoryContract
{
    /**
     * @param Carbon $lastExecuteDatetime
     * @param string $sourceType
     * @return Carbon | null
     */
    public function getEndDatetime(Carbon $lastExecuteDatetime, string $sourceType)
    {
        $beginDatetime = AggregationLog::where('source_type', $sourceType)
            ->where('aggregation_type', 'daily')
            ->where('start_datetime', '>', $lastExecuteDatetime->toDateTimeString())
            ->max('end_datetime');

        if (!isset($beginDatetime)) {
            Log::debug('AggregationLogRepository $beginDatetime = ' . $beginDatetime);
            return null;
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $beginDatetime);
    }
}
