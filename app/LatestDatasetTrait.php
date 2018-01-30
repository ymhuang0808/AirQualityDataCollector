<?php

namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait LatestDatasetTrait
{
    public function scopeLatest(Builder $query, string $before = '')
    {
        $table = $this->table;
        // Get all columns name in the table
        $columns = Schema::getColumnListing($table);
        $selectedColumns = collect($columns)->map(function ($column) use ($table) {
            return "$table.$column";
        })->all();
        $subQueryColumnString = collect($columns)
            ->map(function ($column) {
            if ($column == 'published_datetime') {
                return 'MAX(`published_datetime`) AS published_datetime';
            }
            return "`$column`";
        })->implode(', ');

        $query->from(DB::raw("(SELECT $subQueryColumnString FROM $table GROUP BY `site_id`) AS d"))
            ->join($table, function (JoinClause $join) use ($table) {
                $join->on("$table.site_id", '=', 'd.site_id')
                    ->on("$table.published_datetime", '=', 'd.published_datetime');
            })
            ->select($selectedColumns);

        return $query;
    }
}