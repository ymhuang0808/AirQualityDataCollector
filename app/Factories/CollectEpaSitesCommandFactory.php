<?php

namespace App\Factories;


use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectEpaSitesCommand;
use App\Repository\RemoteGenericDatasetRepository;
use App\Transformers\EpaSiteTransformer;
use GuzzleHttp\Client;

class CollectEpaSitesCommandFactory extends AbstractCollectSitesCommandFactory
{

    public function createCommand(): AbstractCollectSitesCommand
    {
        $baseUrl = config('aqdc.remote_source.epa.base_url');
        $uri = config('aqdc.remote_source.epa.site_uri');

        $httpClient = new Client();

        $repository = new RemoteGenericDatasetRepository($baseUrl, $httpClient);
        $repository->setPath($uri);

        $transformer = new EpaSiteTransformer();

        return new CollectEpaSitesCommand($repository, $transformer);
    }
}