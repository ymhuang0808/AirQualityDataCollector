<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggregationMetric extends Model
{
    const PERIOD_TYPE_HOURLY = 0;
    const PERIOD_TYPE_DAILY = 1;

    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'site_id',
        'values',
        'period_type',
    ];

    protected $dates = [
        'start_datetime',
        'end_datetime',
    ];

    public function setValuesAttribute($value)
    {
        $this->attributes['values'] = serialize($value);
    }

    public function getValuesAttribute()
    {
        return unserialize($this->attributes['values']);
    }

    public function sites()
    {
        return $this->hasMany('App\Site');
    }
}
