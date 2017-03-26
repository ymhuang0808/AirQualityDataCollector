<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
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
