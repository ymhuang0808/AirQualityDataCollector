<?php

namespace App\Commands;


use App\Repository\Contracts\DatasetRepositoryContract;
use App\Transformers\AbstractAqdcTransformer;

abstract class AbstractImportSitesCommand implements CommandContract
{
    protected $sourceType;

    protected $datasetRepository;

    protected $transformer;


    public function __construct(DatasetRepositoryContract $datasetRepository, AbstractAqdcTransformer $transformer)
    {
        $this->datasetRepository = $datasetRepository;
        $this->transformer = $transformer;
    }

    public function setTransformer(AbstractAqdcTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function setSourceType(string $sourceType)
    {
        $this->sourceType = $sourceType;
    }
}
