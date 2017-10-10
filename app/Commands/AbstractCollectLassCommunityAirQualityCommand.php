<?php

namespace App\Commands;


use App\Events\CollectAirQualityCompletedEvent;
use App\LassDataset;
use App\Repository\Contracts\CacheableContact;
use App\Repository\Contracts\DatasetRepositoryContract;
use App\Site;
use App\Transformers\AbstractAqdcTransformer;
use App\Transformers\RemoteModel;

abstract class AbstractCollectLassCommunityAirQualityCommand extends AbstractCollectAirQualityCommand
{
    protected $prefixCacheKey;

    protected $cacheRepository;

    public function __construct(DatasetRepositoryContract $datasetRepository, CacheableContact $cacheRepository, AbstractAqdcTransformer $transformer)
    {
        parent::__construct($datasetRepository, $transformer);

        $this->cacheRepository = $cacheRepository;
    }

    protected function generateCacheKey(): string
    {
        $baseUrl = $this->datasetRepository->getBasedUrl();
        $path = $this->datasetRepository->getPath();
        $key = $this->prefixCacheKey . ':' . $baseUrl . $path;

        return $key;
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    abstract protected function getUniqueKeyValues(RemoteModel $remoteModel): array;

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    abstract protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array;
}