<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggregationLog extends Model
{
    protected $table = 'aggregation_logs';

    protected $fillable = [
        'aggregation_type',
        'source_type',
        'start_datetime',
        'end_datetime',
        'message',
        'level',
    ];

    protected $casts = [
        'level' => 'integer'
    ];
}
