<?php

namespace App\Repository\Contracts;


use App\Site;

interface AggregationMeasurementRepositoryContract
{
    /**
     * @param int $id
     * @return self
     */
    public function setSiteIdCondition(int $id);

    /**
     * @param string $start
     * @return self
     */
    public function setStartDateTimeCondition(string $start);

    /**
     * @param string $end
     * @return self
     */
    public function setEndDateTimeCondition(string $end);

    /**
     * @param int $period
     * @return self
     */
    public function setPeriodTypeCondition(int $period);

    /**
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit);

    public function setOrderByDirection(string $orderByDirection);

    public function get(): Site ;
}