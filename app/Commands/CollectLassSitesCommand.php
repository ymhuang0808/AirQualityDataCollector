<?php

namespace App\Commands;


class CollectLassSitesCommand extends AbstractCollectLassCommunitySitesCommand
{
    protected $sourceType = 'lass';
    protected $prefixCacheKey = 'lass-dataset-url';
}