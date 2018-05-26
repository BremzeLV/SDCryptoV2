<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyWl extends Model
{
    protected $table = 'currency_whitelist';

    protected $guarded = [
        'id'
    ];

    /**
     * Checks if currency is listed
     *
     * @param  string $pair
     * @return object
     */
    public function isListed($pair){

        return $this->where('currency_index', '=' ,$pair)->where('listed', '=', '1')->firstOrFail();

    }
}
