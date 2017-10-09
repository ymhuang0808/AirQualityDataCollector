<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirboxDataset extends Model
{
    use LatestDatasetTrait;

    const UNIQUE_KEYS = [
        'site_id',
        'published_datetime',
    ];

    protected $table = 'airbox_datasets';

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
