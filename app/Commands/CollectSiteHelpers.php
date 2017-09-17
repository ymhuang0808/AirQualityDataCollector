<?php

namespace App\Commands;


use App\Site;
use App\Transformers\RemoteModel;

trait CollectSiteHelpers
{
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