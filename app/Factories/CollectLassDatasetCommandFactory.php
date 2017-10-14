<?php

namespace App\Factories;


use App\Commands\AbstractCollectAirQualityCommand;
use App\Commands\AbstractCollectSitesCommand;
use App\Commands\CollectLassAirQualityCommand;
use App\Commands\CollectLassSitesCommand;
use App\Repository\Contracts\CacheableContract;
use App\Transformers\LassAirQualityTransformer;
use App\Transformers\LassSiteTransformer;
use GuzzleHttp\Client;

class CollectLassDatasetCommandFactory extends AbstractCollectCommandFactory
{
    use RemoteRepositoryTraits;

    /** @var CacheableContract $cacheableRepository */
    protected $cacheableRepository;

    public function __construct()
    {
        $this->baseUrl = config('aqdc.remote_source.lass.base_url');
        $this->siteUri = config('aqdc.remote_source.lass.site_uri');
        $this->airQualityUri = config('aqdc.remote_source.lass.air_quality_uri');

        $this->httpClient = new Client();
        $this->cacheableRepository = resolve(CacheableContract::class);
    }

    public function createSitesCommand(): AbstractCollectSitesCommand
    {
        $repository = $this->getRemoteSitesRepository();
        $transformer = new LassSiteTransformer();

        return new CollectLassSitesCommand($repository, $this->cacheableRepository, $transformer);
    }

    public function createAirQualityCommand(): AbstractCollectAirQualityCommand
    {
        $repository = $this->getRemoteAirQualityRepository();
        $transformer = new LassAirQualityTransformer();

        return new CollectLassAirQualityCommand($repository, $this->cacheableRepository, $transformer);
    }
}