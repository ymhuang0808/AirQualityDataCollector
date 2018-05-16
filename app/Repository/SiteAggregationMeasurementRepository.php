<?php

namespace App\Repository;


use App\Repository\Contracts\AggregationMeasurementRepositoryContract;
use App\Site;

class SiteAggregationMeasurementRepository implements AggregationMeasurementRepositoryContract
{
    protected $siteId;

    protected $startDatetime;

    protected $endDatetime;

    protected $periodType;

    protected $limit;

    protected $orderByDirection = 'desc';

    public function setSiteIdCondition(int $id)
    {
        $this->siteId = $id;
        return $this;
    }

    public function setStartDateTimeCondition(string $start)
    {
        $this->startDatetime = $start;
        return $this;
    }

    public function setEndDateTimeCondition(string $end)
    {
        $this->endDatetime = $end;
        return $this;
    }

    public function setPeriodTypeCondition(int $periodType)
    {
        $this->periodType = $periodType;
        return $this;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOrderByDirection(string $orderByDirection)
    {
        $this->orderByDirection = $orderByDirection;
        return $this;
    }

    public function get(): Site
    {
        $startDateTime = $this->startDatetime;
        $endDatetime = $this->endDatetime;
        $periodType = $this->periodType;
        $limit = $this->limit;
        $orderByDirection = $this->orderByDirection;

        /** @var \App\Site $site */
        $site = Site::with(['aggregationMetric' => function ($query) use ($startDateTime, $endDatetime, $periodType, $limit, $orderByDirection) {
            $table = $query->getRelated()->getTable();
            $query->where($table . '.start_datetime', '>=', $startDateTime)
                ->where($table . '.end_datetime', '<=', $endDatetime)
                ->where($table . '.period_type', $periodType)
                ->orderBy($table . '.start_datetime', $orderByDirection)
                ->limit($limit);
        }])->findOrFail($this->siteId);

        return $site;
    }
}