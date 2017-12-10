<?php

namespace App\Commands;


use App\EpaDataset;
use App\Events\CollectAirQualityCompletedEvent;
use App\Exceptions\TransformException;
use App\Transformers\RemoteModel;
use Illuminate\Support\Facades\Log;

/**
 * Collect EAP Air quality dataset from repository. And the transformer transforms the raw data into `RemoteModel`.
 * `RemoteModel` helps save the data into Laravel Model easily.
 *
 * @package App\Commands
 */
class CollectEpaAirQualityCommand extends AbstractCollectAirQualityCommand
{
    public function execute()
    {
        $result = $this->datasetRepository->getAll();
        $airQualityObjArray = $result->result->records;

        $epaDatasetItems = [];

        foreach ($airQualityObjArray as $airQualityObj) {
            try {
                /** @var RemoteModel $remoteModel */
                $remoteModel = $this->transformer->transform($airQualityObj);
            } catch (TransformException $exception) {
                Log::error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
                continue;
            }

            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $epaDataset = EpaDataset::firstOrNew($uniqueKeyValues);
            $epaDataset->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /** @var Site $site */
            $site = $remoteModel->relationships['site'];

            $epaDataset->site()->associate($site);
            $epaDataset->save();

            $epaDatasetItems[] = $epaDataset;
        }

        $epDatasetCollection = collect($epaDatasetItems);

        // Raise an event
        event(new CollectAirQualityCompletedEvent($epDatasetCollection, 'epa'));
    }

    /**
     * It helps get the unique fields from `RemoteModel` to create a unique `EpaDataset` model.
     *
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getUniqueKeyValues(RemoteModel $remoteModel): array
    {
        $keyValues = $remoteModel->fields;
        $site = $remoteModel->relationships['site'];
        $keyValues['site_id'] = $site->id;

        return array_only($keyValues, EpaDataset::UNIQUE_KEYS);
    }

    /**
     * In `RemoteModel`, except the unique fields, other fields are filled into model.
     *
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, EpaDataset::UNIQUE_KEYS);
    }
}
