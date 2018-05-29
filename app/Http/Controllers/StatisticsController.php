<?php

namespace App\Http\Controllers;

use App\CurrencyWl;
use Illuminate\Http\Request;
use App\TickData;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    /**
     * Display currency pair statistic.
     *
     * @param  string $pair
     * @return \Illuminate\Http\Response
     */
    public function show($pair)
    {
        $currencyWl = new CurrencyWl;
        $currencyWl->isListed($pair);

        $tickData = TickData::select('created_at', 'last', 'base_volume')
            ->where('pair', '=', $pair)
            ->orderBy('created_at', 'asc')
            ->get();
        $orderBook = DB::table('order_book')->where('pair', '=', $pair)->get();

        $tickDataArray = array();

        foreach($tickData as $item){

            $array = array(
                $item->created_at->timestamp*1000,
                $item->last,
                $item->base_volume,
                $item->current_boliband,
                $item->upper_boliband,
                $item->lower_boliband,
            );

            array_push($tickDataArray, $array);

        }

       // dd($tickDataArray);

        return view('currency.statistic.crypto-stats', array(
            'tickData' => json_encode($tickDataArray),
            'pair' => $pair,
        ));
    }

}
