<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    const UNIQUE_KEYS = [
        'name',
        'source_type'
    ];

    /*
     * Available source types
     */
    const EPA_SOURCE_TYPE = 'epa';

    const LASS_SOURCE_TYPE = 'lass';

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

    protected $hidden = [
       'county_id',
        'township_id',
    ];

    public function epaDatasets()
    {
        return $this->hasOne('App\EpaDataset');
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
