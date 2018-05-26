<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Custom\Poloniex;
use App\OrderBook;

class GetCryptoOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCryptoData:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Maintains OrderBook table
     *
     * @return null
     */
    public function handle()
    {
        $connect = new Poloniex();
        $data = $connect->getOrderBook('all');

        $query = array();
        $time = date('Y-m-d H:i:s');

        foreach($data as $key => $item){

            foreach($item['asks'] as $asks){
                $insert['pair'] = $key;
                $insert['type'] = 'asks';
                $insert['price'] = $asks[0];
                $insert['quantity'] = $asks[1];
                $insert['seq'] = $item['seq'];
                $insert['created_at'] = $time;

                $query[] = $insert;
            }

            foreach($item['bids'] as $asks){
                $insert['pair'] = $key;
                $insert['type'] = 'bids';
                $insert['price'] = $asks[0];
                $insert['quantity'] = $asks[1];
                $insert['seq'] = $item['seq'];
                $insert['created_at'] = $time;

                $query[] = $insert;
            }

        }

        OrderBook::insert($query);

        OrderBook::where('created_at', '<', date('Y-m-d H:i:s', strtotime('-10 minutes')))->delete();
    }
}
