<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpaDataset extends Model
{
    use LatestDatasetTrait;

    const UNIQUE_KEYS = [
        'site_id',
        'published_datetime',
    ];

    /**
     * Table name
     * @var string
     */
    protected $table = 'epa_datasets';

    protected $fillable = [
        'aqi',
        'so2',
        'co',
        'co_8hr',
        'o3',
        'o3_8hr',
        'pm10',
        'pm10_avg',
        'pm25',
        'pm25_avg'.
        'no2',
        'wind_speed',
        'wind_direction',
        'nox',
        'no',
        'pollutant',
        'status',
        'published_datetime',
    ];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

}
