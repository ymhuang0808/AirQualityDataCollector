<?php

namespace App\Commands;


use App\County;
use App\Site;
use App\Township;
use App\Transformers\RemoteModel;

class ImportEpaSitesCommand extends AbstractImportSitesCommand
{
    protected $uniqueKeys = ['name', 'source_type'];

    public function execute()
    {
        $result = $this->sitesRepository->getAll();
        $siteObjArray = $result->result->records;

        foreach ($siteObjArray as $siteObj) {
            $remoteModel = $this->transformer->transform($siteObj);
            $uniqueKeyValues = $this->getUniqueKeyValues($remoteModel);

            $site = Site::firstOrNew($uniqueKeyValues);
            $site->fill($this->getFieldsExceptUniqueKeyValues($remoteModel));

            /* @var County */
            $county = $remoteModel->relationships['county'];

            /* @var Township */
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
        return array_only($remoteModel->fields, $this->uniqueKeys);
    }

    /**
     * @param RemoteModel $remoteModel
     * @return array
     */
    protected function getFieldsExceptUniqueKeyValues(RemoteModel $remoteModel): array
    {
        return array_except($remoteModel->fields, $this->uniqueKeys);
    }
}
