<?php

namespace App\Repository\Contracts;


use App\Site;

interface AggregationMeasurementRepositoryContract
{
    public function setSiteIdCondition(int $id);

    public function setStartDateTimeCondition(string $start);

    public function setEndDateTimeCondition(string $end);

    public function setPeriodTypeCondition(int $period);

    public function setLimit(int $limit);

    public function get(): Site ;
}