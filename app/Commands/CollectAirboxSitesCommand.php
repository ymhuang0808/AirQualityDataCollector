<?php

namespace App\Commands;


class CollectAirboxSitesCommand extends AbstractCollectLassCommunitySitesCommand
{
    protected $sourceType = 'airbox';
    protected $prefixCacheKey = 'airbox-dataset-url';
}