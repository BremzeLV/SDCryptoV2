<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickData extends Model
{
    protected $table = 'tick_data';

    protected $guarded = [
        'id'
    ];

    /**
     * Gets latest tick from database.
     *
     * @return object
     */
    public function getLatestTick(){

        return $this->whereRaw('tick_data.id IN (select MAX(tick_data.id) FROM tick_data GROUP BY tick_data.pair)')
            ->where('listed', '=', '1')
            ->leftJoin('currency_whitelist', 'tick_data.pair', '=', 'currency_whitelist.currency_index')
            ->get();

        //return $this->groupBy('pair')->orderBy('id', 'desc')->get();
    }
}
