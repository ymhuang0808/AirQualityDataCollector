<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\CollectEpaAirQualityCommand;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\EpaAirQualityTransformer;
use GuzzleHttp\Client;

class CollectEpaAirQualityCommandFactory extends AbstractCollectAirQualityCommandFactory
{

    public function createCommand(): AbstractCollectAirQualityCommand
    {
        $baseUrl = config('aqdc.remote_source.epa.base_url');
        $uri = config('aqdc.remote_source.epa.air_quality_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $transformer = new EpaAirQualityTransformer();

       return new CollectEpaAirQualityCommand($repository, $transformer);
    }
}