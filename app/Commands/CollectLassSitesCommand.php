<?php

namespace App\Commands;


use App\Events\CollectSiteCompletedEvent;
use App\Repository\Contracts\CacheableContact;
use App\Repository\Contracts\DatasetRepositoryContract;
use App\Site;
use App\Transformers\AbstractAqdcTransformer;
use App\Transformers\RemoteModel;

class CollectLassSitesCommand extends AbstractCollectSitesCommand
{
    protected $cacheRepository;

    public function __construct(DatasetRepositoryContract $datasetRepository, CacheableContact $cacheRepository, AbstractAqdcTransformer $transformer)
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

        event(new CollectSiteCompletedEvent($siteCollection, 'lass'));
    }

    protected function generateCacheKey(): string
    {
        $baseUrl = $this->datasetRepository->getBasedUrl();
        $path = $this->datasetRepository->getPath();
        $key = 'lass-dataset-url:' . $baseUrl . $path;

        return $key;
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_only($remoteModel->fields, Site::UNIQUE_KEYS);
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, Site::UNIQUE_KEYS);
    }
}