<?php

namespace App\Repository;


use App\Repository\Contracts\AggregatableDatasetRepositoryContract;

abstract class AbstractAggretableDatasetRepository implements AggregatableDatasetRepositoryContract
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Source type
     *
     * @var string
     */
    protected $sourceType;

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

    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}