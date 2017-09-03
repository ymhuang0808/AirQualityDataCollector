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