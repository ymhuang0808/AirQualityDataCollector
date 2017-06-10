<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\CollectLassAirQualityCommand;
use App\Repository\Contracts\CacheableContact;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\LassAirQualityTransformer;
use GuzzleHttp\Client;

class CollectLassAirQualityCommandFactory extends AbstractCollectAirQualityCommandFactory
{

    public function createCommand(): AbstractCollectAirQualityCommand
    {
        $baseUrl = config('aqdc.remote_source.lass.base_url');
        $uri = config('aqdc.remote_source.lass.air_quality_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $cacheableRepository = resolve(CacheableContact::class);

        $transformer = new LassAirQualityTransformer();

        return new CollectLassAirQualityCommand($repository, $cacheableRepository, $transformer);
    }
}