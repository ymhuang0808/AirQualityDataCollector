<?php

namespace App\Commands;


use App\Repository\SitesRepositoryContract;
use App\Transformers\AbstractAqdcTransformer;

abstract class AbstractImportSitesCommand implements ImportSitesCommandContract
{
    protected $sourceType;

    protected $sitesRepository;

    protected $transformer;


    public function __construct(SitesRepositoryContract $sitesRepository, AbstractAqdcTransformer $transformer)
    {
        $this->sitesRepository = $sitesRepository;
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
