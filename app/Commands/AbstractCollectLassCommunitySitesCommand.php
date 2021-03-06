<?php

namespace App\Commands;


use App\Events\CollectSiteCompletedEvent;
use App\Repository\Contracts\CacheableContract;
use App\Repository\Contracts\DatasetRepositoryContract;
use App\Site;
use App\Transformers\AbstractAqdcTransformer;
use App\Transformers\RemoteModel;

abstract class AbstractCollectLassCommunitySitesCommand extends AbstractCollectSitesCommand
{
    use CollectSiteHelpers;

    protected $prefixCacheKey;

    protected $cacheRepository;

    public function __construct(DatasetRepositoryContract $datasetRepository, CacheableContract $cacheRepository, AbstractAqdcTransformer $transformer)
    {
        parent::__construct($datasetRepository, $transformer);

        $this->cacheRepository = $cacheRepository;
    }

    public function execute()
    {
        $key = $this->generateCacheKey();

        if ($this->cacheRepository->isHit($key)) {
            $result = $this->cacheRepository->getItemByKey($key);
        } else {
            $result = $this->datasetRepository->getAll();
            $this->cacheRepository->setItemByKey($key, $result);
        }

        $siteObjArray = $result->feeds;
        $siteCollection = collect();

        foreach ($siteObjArray as $siteObj) {
            $remoteModel = $this->transformer->transform($siteObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $site = Site::firstOrNew($uniqueKeyValues);
            $site->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            $site->save();

            $siteCollection->push($site);
        }

        event(new CollectSiteCompletedEvent($siteCollection, $this->sourceType));
    }

    protected function generateCacheKey(): string
    {
        $baseUrl = $this->datasetRepository->getBasedUrl();
        $path = $this->datasetRepository->getPath();
        $key = $this->prefixCacheKey . ':' . $baseUrl . $path;

        return $key;
    }
}