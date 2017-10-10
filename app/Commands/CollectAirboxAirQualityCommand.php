<?php

namespace App\Commands;


use App\AirboxDataset;
use App\Events\CollectAirQualityCompletedEvent;
use App\Site;
use App\Transformers\RemoteModel;

class CollectAirboxAirQualityCommand extends AbstractCollectLassCommunityAirQualityCommand
{
    protected $prefixCacheKey = 'airbox-dataset-url';

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

        $airboxDatasetCollection = collect();

        foreach ($airQualityObjArray as $airQualityObj) {
            /** @var RemoteModel $remoteModel */
            $remoteModel = $this->transformer->transform($airQualityObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $airboxDataset = AirboxDataset::firstOrNew($uniqueKeyValues);
            $airboxDataset->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /** @var Site $site */
            $site = $remoteModel->relationships['site'];

            $airboxDataset->site()->associate($site);
            $airboxDataset->save();

            $airboxDatasetCollection->push($airboxDataset);
        }

        event(new CollectAirQualityCompletedEvent($airboxDatasetCollection, 'airbox'));
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

        return array_only($keyValues, AirboxDataset::UNIQUE_KEYS);
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, AirboxDataset::UNIQUE_KEYS);
    }
}