<?php

namespace App\Console\Commands;

use App\Custom\Analysis;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Custom\Poloniex;
use App\TickData;
use Auth;

class GetCryptoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getCryptoData:tickData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get actual values for crypto';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connect = new Poloniex();
        $anal = new Analysis(['marketTrend' => true]);

        $data = $connect->getTicker();

        foreach($data as $key => $item){
            $bolinger = $anal->calculateBollingerBands($key, 10);

            TickData::create([
                'pair' => $key,
                'last' => $item['last'],
                'lowest_ask' => $item['lowestAsk'],
                'highest_bid' => $item['highestBid'],
                'percent_change' => $item['percentChange'],
                'base_volume' => $item['baseVolume'],
                'quote_volume' => $item['quoteVolume'],
                'day_high' => $item['high24hr'],
                'day_low' => $item['low24hr'],
                'is_frozen' => $item['isFrozen'],
                'upper_boliband' => $bolinger[0],
                'current_boliband' => $bolinger[1],
                'lower_boliband' => $bolinger[2],
            ]);
        }

        $users = User::whereNotNull('poloniex_key')
        ->whereNotNull('poloniex_secret')
        ->whereNotNull('selected_pair')
            ->where('id', '=', '1')
        ->get();

        $users->each(function($item) use($anal) {
            $anal->calculateStep($item->id);
        });
    }
}
