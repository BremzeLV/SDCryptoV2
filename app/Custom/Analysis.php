<?php
namespace App\Custom;

use App\TickData;

class Analysis {

    public function calculateBollingerBands($pair, $days){
        $tickData = new TickData();

        $date = \Carbon\Carbon::today()->subDays($days);
        $pairs = $tickData
            ->where('pair', '=', $pair)
            ->where('created_at', '>=', $date)->pluck('last');
        $count = $pairs->count();

        $multiplied = $pairs->map(function ($item) {
            return $item * 10000000;
        });

        $bands = trader_bbands($multiplied->toArray(), $count, 2.0, 2.0, 1);
        $bands = array(
            $bands[0][$count-1]/10000000,
            $bands[1][$count-1]/10000000,
            $bands[2][$count-1]/10000000
        );

        return $bands;
    }

}
