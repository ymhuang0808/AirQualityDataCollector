<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggregationMetric extends Model
{
    const PERIOD_TYPE_HOURLY = 0;
    const PERIOD_TYPE_DAILY = 1;
    const PERIOD_TYPE_WEEKLY = 2;
    const PERIOD_TYPE_MONTHLY = 3;

    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'site_id',
        'values',
        'period_type',
    ];

    public function sites()
    {
        return $this->hasMany('App\Site');
    }
}
