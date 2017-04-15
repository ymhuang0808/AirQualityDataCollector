<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LassDataset extends Model
{
    const UNIQUE_KEYS = [
        'site_id',
        'published_datetime',
    ];

    protected $table = 'lass_datasets';

    protected $fillable = [
        'pm25',
        'pm10',
        'temperature',
        'humidity',
        'published_datetime',
    ];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }
}
