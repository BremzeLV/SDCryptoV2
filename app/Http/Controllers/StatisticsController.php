<?php

namespace App\Http\Controllers;

use App\CurrencyWl;
use Illuminate\Http\Request;
use App\TickData;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('statistic.basic-stats');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
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
            );

            array_push($tickDataArray, $array);
        }

       // dd($tickDataArray);

        return view('currency.statistic.crypto-stats', array(
            'tickData' => json_encode($tickDataArray),
            'pair' => $pair,
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
