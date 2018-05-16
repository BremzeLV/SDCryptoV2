<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class TickData extends Model
{
    protected $table = 'tick_data';

    protected $guarded = [
        'id'
    ];

    public function getLatestTick(){
        return $this->whereRaw('tick_data.id IN (select MAX(tick_data.id) FROM tick_data GROUP BY tick_data.pair)')
           /* ->where('listed', '=', '1')
            ->leftJoin('currency_whitelist', 'tick_data.pair', '=', 'currency_whitelist.currency_index')*/
            ->orderBy('last', 'desc')->where('pair', 'like', 'BTC%')->get();  //TODO change this
    }
}
