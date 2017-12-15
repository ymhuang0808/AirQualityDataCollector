<?php

namespace App\Repository;


class LassAggregatableDatasetRepository extends AbstractAggretableDatasetRepository
{
    use GenericAggregatableDatasetRepositoryTrait;

    protected $table = 'lass_datasets';

    protected $sourceType = 'lass';
}