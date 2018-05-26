<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\Poloniex;
use Illuminate\Support\Facades\DB;
use App\Currency;

class GetCurrencyInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCurrencyInfo:info';

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
     * Gets cryptocurrency information
     *
     * @return null
     */
    public function handle()
    {
        $poloniex = new Poloniex();

        collect($poloniex->getCurrencyInfo())->map(function($item, $key){
            Currency::updateOrCreate(
                ['abbreviation' => $key],
                [
                    'abbreviation' => $key,
                    'name' => $item['name'],
                    'disabled' => $item['disabled'],
                    'taxFee' => $item['txFee'],
                ]
            );
        });

    }
}
