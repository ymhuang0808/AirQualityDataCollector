<?php

namespace App\Commands;


use App\Repository\Contracts\DatasetRepositoryContract;
use App\Transformers\AbstractAqdcTransformer;

abstract class AbstractCollectAirQualityCommand implements CommandContract
{
    protected $datasetRepository;
    protected $transformer;

    public function __construct(DatasetRepositoryContract $datasetRepository, AbstractAqdcTransformer $transformer)
    {
        $this->datasetRepository = $datasetRepository;
        $this->transformer = $transformer;
    }
}
