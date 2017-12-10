<?php

namespace App\Factories;


use App\Repository\Contracts\DatasetRepositoryContract;
use App\Repository\RemoteGenericDatasetRepository;

trait RemoteRepositoryTraits
{
    protected $baseUrl;

    protected $siteUri;

    protected $airQualityUri;

    protected $httpClient;

    protected $remoteRepository;

    protected $options;

    protected function getRemoteAirQualityRepository(): DatasetRepositoryContract
    {
        if ($this->siteUri == $this->airQualityUri) {
            if (isset($this->remoteRepository)) {
                return $this->remoteRepository;
            }
        }

        $this->remoteRepository = new RemoteGenericDatasetRepository($this->baseUrl, $this->httpClient);
        $this->remoteRepository->setPath($this->airQualityUri);

        return $this->remoteRepository;
    }

    protected function getRemoteSitesRepository(): DatasetRepositoryContract
    {
        if ($this->siteUri == $this->airQualityUri) {
            if (isset($this->remoteRepository)) {
                return $this->remoteRepository;
            }
        }

        $this->remoteRepository = new RemoteGenericDatasetRepository($this->baseUrl, $this->httpClient);

        if (isset($this->options)) {
            $this->remoteRepository->setOptions($this->options);
        }

        $this->remoteRepository->setPath($this->siteUri);

        return $this->remoteRepository;
    }
}