<?php

namespace App\Helpers;


class SourceHelpers
{
    public function getAllSource()
    {
        $allSource = config('aqdc.remote_source');

        return array_keys($allSource);
    }

    public function exists($source)
    {
        return in_array($source, $this->getAllSource());
    }
}