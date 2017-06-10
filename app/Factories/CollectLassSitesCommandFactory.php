<?php

namespace App\Factories;


use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectLassSitesCommand;
use App\Repository\Contracts\CacheableContact;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\LassSiteTransformer;
use GuzzleHttp\Client;

class CollectLassSitesCommandFactory extends AbstractCollectSitesCommandFactory
{

    public function createCommand(): AbstractCollectSitesCommand
    {
        $baseUrl = config('aqdc.remote_source.lass.base_url');
        $uri = config('aqdc.remote_source.lass.site_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $cacheableRepository = resolve(CacheableContact::class);

        $transformer = new LassSiteTransformer();

        return new CollectLassSitesCommand($repository, $cacheableRepository, $transformer);
    }
}