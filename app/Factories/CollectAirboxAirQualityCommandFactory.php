<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\CollectAirboxAirQualityCommand;
use App\Commands\CollectLassAirQualityCommand;
use App\Repository\Contracts\CacheableContract;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\AirboxAirQualityTransformer;
use App\Transformers\LassAirQualityTransformer;
use GuzzleHttp\Client;

class CollectAirboxAirQualityCommandFactory extends AbstractCollectAirQualityCommandFactory
{

    public function createCommand(): AbstractCollectAirQualityCommand
    {
        $baseUrl = config('aqdc.remote_source.airbox.base_url');
        $uri = config('aqdc.remote_source.airbox.air_quality_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $cacheableRepository = resolve(CacheableContract::class);

        $transformer = new AirboxAirQualityTransformer();

        return new CollectAirboxAirQualityCommand($repository, $cacheableRepository, $transformer);
    }
}