<?php

namespace App\Repository;


class EpaAggregatableDatasetRepository extends AbstractAggretableDatasetRepository
{
    use GenericAggregatableDatasetRepositoryTrait;

    protected $table = 'epa_datasets';

    protected $sourceType = 'epa';

}