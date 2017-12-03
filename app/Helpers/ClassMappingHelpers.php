<?php

namespace App\Helpers;


class ClassMappingHelpers
{
    protected static $remoteSource;

    public function __construct()
    {
        static::loadRemoteSource();
    }

    public function getModelBySourceType(string $sourceType): string
    {
        return static::$remoteSource[$sourceType]['model'];
    }

    protected static function loadRemoteSource()
    {
        if (!isset(static::$remoteSource)) {
            static::$remoteSource = config('aqdc.remote_source');
        }
    }
}
