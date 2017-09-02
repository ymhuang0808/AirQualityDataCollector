<?php

namespace App\Aggregate;


use App\Repository\Contracts\AggregatableDatasetRepositoryContract;

abstract class AbstractAggregateProcessor implements AggregateProcessorContract
{
    use AggregateProcessHelpers;

    /**
     * Aggregated fields in dataset
     *
     * @var array
     */
    protected $fields = [];

    public function __construct(AggregatableDatasetRepositoryContract $aggregatableRepository)
    {
        $this->aggregatableRespository = $aggregatableRepository;
    }

    /**
     * Set aggregated fields
     *
     * @param array $fields
     * @return self
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get aggregated fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}