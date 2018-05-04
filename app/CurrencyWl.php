<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyWl extends Model
{
    protected $table = 'currency_whitelist';

    protected $guarded = [
        'id'
    ];
}
