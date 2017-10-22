<?php

namespace App\Repository\Contracts;


use Carbon\Carbon;

interface AggregationLogRepositoryContract
{
    public function getBeginDatetime(Carbon $lastExecuteDatetime, string $sourceType): Carbon;
}
