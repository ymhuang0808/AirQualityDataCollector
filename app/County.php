<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $fillable = ['name'];

    public function sites()
    {
        return $this->hasMany('App\Site');
    }
}
