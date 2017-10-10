<?php

namespace App\Factories;


use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectAirboxSitesCommand;
use App\Repository\Contracts\CacheableContract;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\AirboxSiteTransformer;
use GuzzleHttp\Client;

class CollectAirboxSitesCommandFactory extends AbstractCollectSitesCommandFactory
{

    public function createCommand(): AbstractCollectSitesCommand
    {
        $baseUrl = config('aqdc.remote_source.airbox.base_url');
        $uri = config('aqdc.remote_source.airbox.site_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $cacheableRepository = resolve(CacheableContract::class);

        $transformer = new AirboxSiteTransformer();

        return new CollectAirboxSitesCommand($repository, $cacheableRepository, $transformer);
    }
}