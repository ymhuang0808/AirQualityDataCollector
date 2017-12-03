<?php

namespace App\Repository\Contracts;


use Carbon\Carbon;

interface AggregationLogRepositoryContract
{
    public function getEndDatetime(Carbon $lastExecuteDatetime, string $sourceType): Carbon;
}
