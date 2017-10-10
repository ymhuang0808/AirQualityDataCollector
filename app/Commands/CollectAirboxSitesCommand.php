<?php

namespace App\Commands;


class CollectAirboxSitesCommand extends AbstractCollectLassCommunitySitesCommand
{
    protected $prefixCacheKey = 'airbox-dataset-url';
}