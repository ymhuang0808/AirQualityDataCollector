<?php

namespace App\Commands;


use App\County;
use App\Events\CollectSiteCompletedEvent;
use App\Site;
use App\Township;
use App\Transformers\RemoteModel;

class CollectEpaSitesCommand extends AbstractCollectSitesCommand
{
    use CollectSiteHelpers;

    public function execute()
    {
        $result = $this->datasetRepository->getAll();
        $siteObjArray = $result->result->records;

        $sites = [];

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

            $sites[] = $site;
        }

        $siteCollection = collect($sites);

        event(new CollectSiteCompletedEvent($siteCollection, 'epa'));
    }
}
