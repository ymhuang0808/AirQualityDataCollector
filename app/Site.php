<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    const UNIQUE_KEYS = [
        'name',
        'source_type'
    ];

    protected $table = 'sites';

    protected $fillable = [
        'name',
        'eng_name',
        'address',
        'type',
        'area_name',
        'coordinates',
        'source_type',
    ];

    public function epaDatasets()
    {
        return $this->hasMany('App\EpaDataset');
    }

    public function county()
    {
        return $this->belongsTo('App\County');
    }

    public function township()
    {
        return $this->belongsTo('App\Township');
    }

    public function setCoordinatesAttribute($value)
    {
        $this->attributes['coordinates'] = serialize($value);
    }

    public function getCoordinatesAttribute($value)
    {
        return unserialize($value);
    }
}
