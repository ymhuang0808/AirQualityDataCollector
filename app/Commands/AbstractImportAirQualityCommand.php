<?php

namespace App\Commands;


use App\Repository\AirQualityRepositoryContract;
use App\Transformers\AbstractAqdcTransformer;

abstract class AbstractImportAirQualityCommand implements CommandContract
{
    protected $airQualityRepository;
    protected $transformer;

    public function __construct(AirQualityRepositoryContract $airQualityRepository, AbstractAqdcTransformer $transformer)
    {
        $this->airQualityRepository = $airQualityRepository;
        $this->transformer = $transformer;
    }
}
