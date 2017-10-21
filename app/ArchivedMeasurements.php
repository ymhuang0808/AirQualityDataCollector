<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivedMeasurements extends Model
{
    protected $table = 'archived_measurements';

    protected $fillable = [
        'values',
        'published_datetime',
    ];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function getValuesAttribute($value)
    {
        return unserialize($value);
    }

    public function setValuesAttribute($value)
    {
        $this->attributes['values'] = serialize($value);
    }
}
