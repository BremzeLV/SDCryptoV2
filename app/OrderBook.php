<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBook extends Model
{
    protected $table = 'order_book';

    protected $guarded = [
        'id'
    ];

}
