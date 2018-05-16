<?php

namespace App\Console\Commands;

use App\Custom\Analysis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Custom\Poloniex;
use App\TickData;

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
        $anal = new Analysis();

        $data = $connect->get_ticker();

        foreach($data as $key => $item){
            $bolinger = $anal->calculateBollingerBands($key, 1);

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
    }
}
