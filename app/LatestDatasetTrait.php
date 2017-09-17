<?php

namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait LatestDatasetTrait
{
    public function scopeLatest(Builder $query, string $before = '')
    {
        if (isset($before)) {
            $maxQuery = $query->where('published_datetime', '>=', $before);
        }

        try {
            $max = $maxQuery->max('published_datetime');
        } catch (ModelNotFoundException $exception) {
            return $query;
        }

        return $query->where('published_datetime', $max);
    }
}