<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectAirboxAirQualityCommand;
use App\Commands\CollectAirboxSitesCommand;
use App\Repository\Contracts\CacheableContract;
use App\Transformers\AirboxAirQualityTransformer;
use App\Transformers\AirboxSiteTransformer;
use GuzzleHttp\Client;

class CollectAirboxDatasetCommandFactory extends AbstractCollectCommandFactory
{
    use RemoteRepositoryTraits;

    /** @var CacheableContract $cacheableRepository */
    protected $cacheableRepository;

    public function __construct()
    {
        $this->baseUrl = config('aqdc.remote_source.airbox.base_url');
        $this->siteUri = config('aqdc.remote_source.airbox.site_uri');
        $this->airQualityUri = config('aqdc.remote_source.airbox.air_quality_uri');

        $this->httpClient = new Client();
        $this->cacheableRepository = resolve(CacheableContract::class);
    }

    public function createSitesCommand(): AbstractCollectSitesCommand
    {
        $repository = $this->getRemoteSitesRepository();
        $transformer = new AirboxSiteTransformer();

        return new CollectAirboxSitesCommand($repository, $this->cacheableRepository, $transformer);
    }

    public function createAirQualityCommand(): AbstractCollectAirQualityCommand
    {
        $repository = $this->getRemoteAirQualityRepository();
        $transformer = new AirboxAirQualityTransformer();

        return new CollectAirboxAirQualityCommand($repository, $this->cacheableRepository, $transformer);
    }
}