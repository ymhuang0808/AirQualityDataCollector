<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectEpaAirQualityCommand;
use App\Commands\CollectEpaSitesCommand;
use App\Transformers\EpaAirQualityTransformer;
use App\Transformers\EpaSiteTransformer;
use GuzzleHttp\Client;

class CollectEpaDatasetCommandFactory extends AbstractCollectCommandFactory
{
    use RemoteRepositoryTraits;

    public function __construct()
    {
        $this->baseUrl = config('aqdc.remote_source.epa.base_url');
        $this->siteUri = config('aqdc.remote_source.epa.site_uri');
        $this->airQualityUri = config('aqdc.remote_source.epa.air_quality_uri');

        $this->httpClient = new Client();
    }

    public function createSitesCommand(): AbstractCollectSitesCommand
    {
        $repository = $this->getRemoteSitesRepository();
        $transformer = new EpaSiteTransformer();

        return new CollectEpaSitesCommand($repository, $transformer);
    }

    public function createAirQualityCommand(): AbstractCollectAirQualityCommand
    {
        $repository = $this->getRemoteAirQualityRepository();
        $transformer = new EpaAirQualityTransformer();

        return new CollectEpaAirQualityCommand($repository, $transformer);
    }
}