<?php

namespace App\Commands;


use App\EpaDataset;
use App\Transformers\RemoteModel;

class ImportEpaAirQualityCommand extends AbstractImportAirQualityCommand
{
    public function execute()
    {
        $result = $this->datasetRepository->getAll();
        $airQualityObjArray = $result->result->records;

        foreach ($airQualityObjArray as $airQualityObj) {
            $remoteModel = $this->transformer->transform($airQualityObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $epaDataset = EpaDataset::firstOrNew($uniqueKeyValues);
            $epaDataset->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /* @var Site $site */
            $site = $remoteModel->relationships['site'];

            $epaDataset->site()->associate($site);
            $epaDataset->save();
        }
    }

    protected function getUniqueKeyValues(RemoteModel $remoteModel): array
    {
        $keyValues = $remoteModel->fields;
        $site = $remoteModel->relationships['site'];
        $keyValues['site_id'] = $site->id;

        return array_only($keyValues, EpaDataset::UNIQUE_KEYS);
    }

    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, EpaDataset::UNIQUE_KEYS);
    }
}
