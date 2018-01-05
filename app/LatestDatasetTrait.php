<?php

namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait LatestDatasetTrait
{
    public function scopeLatest(Builder $query, string $before = '')
    {
        $table = $this->table;

        $query->leftJoin("$table AS t2", function ($leftJoin) use ($table, $before) {
            $leftJoin->on("$table.site_id", '=', 't2.site_id')
                ->on("$table.published_datetime", '<', 't2.published_datetime');

            if (!empty($before)) {
                // TODO
            }
        });
        $query->whereNull('t2.published_datetime');
        $query->select("$table.*");

        $sql = $query->toSql();

        return $query;
    }
}