<?php

namespace App\Repository;


class AirboxAggregatableDatasetRepository extends AbstractAggretableDatasetRepository
{
    use GenericAggregatableDatasetRepositoryTrait;

    protected $table = 'airbox_datasets';

    protected $sourceType = 'airbox';

}