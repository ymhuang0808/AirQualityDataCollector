<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LassDataset extends Model implements ModelMeasurementContract
{
    use LatestDatasetTrait;

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

    public function getMeasurementPayload(): array
    {
        return self::makeHidden([
            'id',
            'published_datetime',
            'updated_at',
            'created_at',
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
