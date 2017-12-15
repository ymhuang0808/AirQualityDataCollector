<?php

namespace App\Repository;


use Illuminate\Support\Facades\DB;

class EpaAggregatableDatasetRepository extends AbstractAggretableDatasetRepository
{
    use GenericAggregatableDatasetRepositoryTrait;

    protected $table = 'epa_datasets';

    protected $sourceType = 'epa';

}