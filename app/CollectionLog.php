<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionLog extends Model
{
    protected $fillable = [
        'level',
        'channel',
        'source_type',
        'count',
        'message',
    ];
}
