<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyWl extends Model
{
    protected $table = 'currency_whitelist';

    protected $guarded = [
        'id'
    ];

    public function isListed($pair){
        return $this->where('currency_index', '=' ,$pair)->where('listed', '=', '1')->firstOrFail();
    }
}
