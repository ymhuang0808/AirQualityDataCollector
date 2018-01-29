<?php

namespace App\Commands;


use App\Events\CollectAirQualityCompletedEvent;
use App\Exceptions\TransformException;
use App\LassDataset;
use App\Site;
use App\Transformers\RemoteModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class CollectLassAirQualityCommand extends AbstractCollectLassCommunityAirQualityCommand
{
    protected $prefixCacheKey = 'lass-dataset-url';

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

        $lassDatasetCollection = collect();

        foreach ($airQualityObjArray as $airQualityObj) {
            try {
                /** @var RemoteModel $remoteModel */
                $remoteModel = $this->transformer->transform($airQualityObj);
                $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

                $lassDataset = LassDataset::firstOrNew($uniqueKeyValues);
                $lassDataset->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

                /** @var Site $site */
                $site = $remoteModel->relationships['site'];

                $lassDataset->site()->associate($site);
                $lassDataset->save();
            } catch (QueryException $exception) {
                Log::error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
                continue;
            } catch (TransformException $exception) {
                Log::error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
                continue;
            }

            $lassDatasetCollection->push($lassDataset);
        }

        event(new CollectAirQualityCompletedEvent($lassDatasetCollection, 'lass'));
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