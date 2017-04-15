<?php

namespace App\Commands;


use App\County;
use App\Site;
use App\Township;
use App\Transformers\RemoteModel;

class CollectEpaSitesCommand extends AbstractCollectSitesCommand
{
    public function execute()
    {
        $result = $this->datasetRepository->getAll();
        $siteObjArray = $result->result->records;

        foreach ($siteObjArray as $siteObj) {
            $remoteModel = $this->transformer->transform($siteObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $site = Site::firstOrNew($uniqueKeyValues);
            $site->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /* @var County $county */
            $county = $remoteModel->relationships['county'];

            /* @var Township $township */
            $township = $remoteModel->relationships['township'];

            $site->county()->associate($county);
            $site->township()->associate($township);
            $site->save();
        }
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
