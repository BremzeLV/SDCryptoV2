<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickData extends Model
{
    protected $table = 'tick_data';

    protected $guarded = [
        'id'
    ];

}
