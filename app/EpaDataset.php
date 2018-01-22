<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EpaDataset extends Model implements ModelMeasurementContract
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
        'pm25_avg',
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

    public function getMeasurementPayload(): array
    {
        return self::makeHidden([
            'id',
            'published_datetime',
            'updated_at',
            'created_at',
            'site',
        ])->toArray();
    }

    public function getPublishedDateTime(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->published_datetime);
    }

    public function getSite(): Site
    {
        return $this->site;
    }
}
