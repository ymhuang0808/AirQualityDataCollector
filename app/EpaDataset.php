<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpaDataset extends Model
{
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
        'psi',
        'so2',
        'co',
        'o3',
        'pm10',
        'pm25',
        'no2',
        'wind_speed',
        'wind_direction',
        'fpmi',
        'nox',
        'no',
        'major_pollutant',
        'status',
        'published_datetime',
    ];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }
}
