<?php

namespace App\Commands;


use App\LassDataset;
use App\Repository\Contracts\CacheableContact;
use App\Repository\Contracts\DatasetRepositoryContract;
use App\Site;
use App\Transformers\AbstractAqdcTransformer;
use App\Transformers\RemoteModel;

class CollectLassAirQualityCommand extends AbstractCollectAirQualityCommand
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

        $airQualityObjArray = $result->feeds;

        foreach ($airQualityObjArray as $airQualityObj) {
            /** @var RemoteModel $remoteModel */
            $remoteModel = $this->transformer->transform($airQualityObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $lassDataset = LassDataset::firstOrNew($uniqueKeyValues);
            $lassDataset->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /** @var Site $site */
            $site = $remoteModel->relationships['site'];

            $lassDataset->site()->associate($site);
            $lassDataset->save();
        }
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
        $keyValues = $remoteModel->fields;
        $site = $remoteModel->relationships['site'];
        $keyValues['site_id'] = $site->id;

        return array_only($keyValues, LassDataset::UNIQUE_KEYS);
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, LassDataset::UNIQUE_KEYS);
    }
}