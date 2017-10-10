<?php

namespace App\Commands;


use App\Events\CollectSiteCompletedEvent;
use App\Repository\Contracts\CacheableContact;
use App\Repository\Contracts\DatasetRepositoryContract;
use App\Site;
use App\Transformers\AbstractAqdcTransformer;
use App\Transformers\RemoteModel;

class CollectLassSitesCommand extends AbstractCollectLassCommunitySitesCommand
{
    protected $prefixCacheKey = 'lass-dataset-url';
}