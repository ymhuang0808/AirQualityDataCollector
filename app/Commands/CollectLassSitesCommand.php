<?php

namespace App\Commands;


class CollectLassSitesCommand extends AbstractCollectLassCommunitySitesCommand
{
    protected $prefixCacheKey = 'lass-dataset-url';
}