<?php
namespace App\Custom;

use App\TickData;
use App\Custom\Poloniex;
use App\Transaction;
use App\User;
use Auth;

class Analysis {
    protected $taxMake = 0.001;
    protected $taxTake = 0.002;
    protected $profitPercentage = 0.01;
    public $marketTrend;
    public $pairTrend;
    public $poloniex;
    public $tickData;
    public $buyAmount = [
        0.05, 0.03, 0.02
    ];

    public function __construct($config = null){
        $this->tickData = new TickData();

        if(isset($config)){
            if($config['marketTrend'] == true){
                $this->marketTrend = $this->marketTrend();
            }
        }

    }

    public function calculateBollingerBands($pair, $days){

        $tickData = $this->tickData;

        $date = \Carbon\Carbon::today()->subDays($days);
        $pairs = $tickData
            ->where('pair', '=', $pair)
            ->where('created_at', '>=', $date)->pluck('last');
        $count = $pairs->count();

        $multiplied = $pairs->map(function ($item) {
            return $item * 10000000;
        });

        if($count < 2){
            $bands = array(0, 0, 0);
        }else{
            $bands = trader_bbands($multiplied->toArray(), $count, 2.0, 2.0, 1);
            $bands = array(
                $bands[0][$count-1]/10000000,
                $bands[1][$count-1]/10000000,
                $bands[2][$count-1]/10000000
            );
        }

        return $bands;

    }

    private function marketTrend(){

        $tickData = $this->tickData;
        $marketTrend = $tickData->getLatestTick();

        $count      = $marketTrend->count();
        $countPlus  = 0;
        $countMinus = 0;
        $trendReturn = null;

        collect($marketTrend)->each(function($item) use (&$countPlus, &$countMinus){
            if($item->percent_change > 0){
                $countPlus++;
            }else{
                $countMinus++;
            }
        });

        $plusPercentage = $countPlus / $count * 100;
        $minusPercentage = $countMinus / $count * 100;

        if($plusPercentage > $minusPercentage){
            $trendReturn = true;
        }else{
            $trendReturn = false;
        }

        return [
            'trend' => $trendReturn,
            'plus' => $plusPercentage,
            'minus' => $minusPercentage
        ];

    }

    public function pairTrend($pair){

        $tickData = $this->tickData;
        $values = $tickData->where('pair', '=', $pair)->orderBy('id', 'desc')->limit(100)->get();

        $trendReturn = null;
        $plusPrice = 0;
        $minusPrice = 0;
        $count = $values->count();

        collect($values)->each(function($item) use (&$plusPrice, &$minusPrice){
            if($item->percent_change > 0){
                $plusPrice++;
            }else{
                $minusPrice++;
            }
        });

        $plusPercentage = $plusPrice / $count * 100;
        $minusPercentage = $minusPrice / $count * 100;

        if($plusPercentage > $minusPercentage){
            $trendReturn = true;
        }else{
            $trendReturn = false;
        }

        $this->pairTrend = [
            'trend' => $trendReturn,
            'plus' => $plusPercentage,
            'minus' => $minusPercentage,
            'last' => $values->first()
        ];

        return $this->pairTrend;
    }

    public function pairPricePercentage($lastTick){
        $baseHeight = $lastTick->upper_boliband - $lastTick->lower_boliband;
        $basePrice = $lastTick->last - $lastTick->lower_boliband;
        $percentage = $basePrice / $baseHeight * 100;

        $profit_at = ($lastTick->last * ($this->taxMake + $this->taxTake)) + $lastTick->last;

        return [
            'last_percentage' => $percentage,
            'profits' => $profit_at
        ];
    }

    public function nextBuy($transactions, $userBalance){
        if($transactions['open_buys']->count() <= 3){
            //checking percantages
            $preventedPercentage = $transactions['transactionM']->pluck('percentage_step')->toArray();
            $availablePercantage = array_diff($this->buyAmount, $preventedPercentage);

            //getting largest percentage
            if(empty($availablePercantage)){
                return false;
            }else{
                $percentageBase = reset($availablePercantage);
            }

            //base currency
            $amount = $userBalance[$transactions['currency_index'][0]] ?? null;

            if($amount > 0){

                $price = $amount * $percentageBase;

                return [
                    'percentage_step' => $percentageBase,
                    'amount_to_buy' => round($price / $transactions['last_price']['last'], 8),
                    'predicted_sell' => round(
                        ($transactions['last_price']['last'] * $this->profitPercentage) + $transactions['last_price']['last'],
                        8
                    )
                ];

            }else{
                return [
                    'error' => 'You broke :('
                ];
            }

        }

        return false;

    }

    public function sellAllICan($transactions){
        $poloniex   = $this->poloniex;
        $pair       = $transactions['last_price']->pair;

        if($transactions['open_buys']->count() > 0){

            collect($transactions['open_buys']->get())->each(function($item) use($transactions, $poloniex, $pair){

                //check if pricesatisfies and sells
                if($item->predicted_sell >= $transactions['last_price']->last){
                    $sold = $poloniex->sell($pair, $transactions['last_price']->last, $item->amount);

                    if($sold){

                        $close = Transaction::find($item->id);
                        $close->closed = 1;
                        $close->save();

                    } else {
                        return [
                            'error' => 'Cant sell your balance'
                        ];
                    }

                }

            });

        } else {
            return false;
        }

    }

    public function calculateStep($userId){

        //checking user data
        $user = User::findOrFail($userId);

        $this->poloniex = new Poloniex($user->poloniex_key, $user->poloniex_secret);

        if(!isset($user->selected_pair)){
            return [
                'error' => 'No selected pair',
            ];
        }else if(!isset($user->poloniex_key) || !isset($user->poloniex_secret)){
            return [
                'error' => 'No Poloniex connections',
            ];
        }

        $poloniex = $this->poloniex;

        //getting market and pair trends
        $marketTrend = $this->marketTrend;
        $pairTrend   = $this->pairTrend($user->selected_pair);
        $priceAnal   = $this->pairPricePercentage($pairTrend['last']);
        $bought      = false;

        $userBalance = $poloniex->getBalances();

        //transaction data
        $transactionsM = Transaction::where('user_id', '=', $user->id)
            ->where('closed', '=', '0')
            ->where('currency_index', '=', $user->selected_pair);

        $transactions = [
            'currency_index' => explode('_', $user->selected_pair), //currency splited
            'open_buys' => $transactionsM->where('action', '=','buy'),       //all currently oppened buys
            'spent_on_pair' => $transactionsM->where('action', '=','buy')->sum('price'),  //all spented money
            'last_price' => $pairTrend['last'],  //last price
            'transactionM' => $transactionsM, //transaction model
        ];

        $this->sellAllICan($transactions);

        $buy = $this->nextBuy($transactions, $userBalance);

        //transactions and its 2.30am
        //checking bolibands
        if($priceAnal['last_percentage'] < 0){
            ///price smaller than boliband

            if(
                ($marketTrend['trend'] && $pairTrend['minus'] < 80) ||
                (!$marketTrend['trend'] && $pairTrend['plus'] > 50)
            )
            {

                $bought = $poloniex->buy($user->selected_pair, $transactions['last_price']->last, $buy['amount_to_buy']);

            }

        }

        else if($priceAnal['last_percentage'] < 20){

            if(
                ($marketTrend['trend'] && $pairTrend['minus'] < 60) ||
                (!$marketTrend['trend'] && $pairTrend['plus'] > 60)
            ){

                $bought = $poloniex->buy($user->selected_pair, $transactions['last_price']->last, $buy['amount_to_buy']);

            }

        }else if($priceAnal['last_percentage'] < 35) {
            if(
                ($marketTrend['trend'] && $pairTrend['minus'] < 20) ||
                (!$marketTrend['trend'] && $pairTrend['plus'] > 85)
            ){

                $bought = $poloniex->buy($user->selected_pair, $transactions['last_price']->last, $buy['amount_to_buy']);

            }
        }

        if($bought){

            Transaction::create([
                'user_id'           => $user->id,
                'currency_index'    => $user->selected_pair,
                'action'            => 'buy',
                'price'             => $transactions['last_price']->last,
                'amount'            => $buy['amount_to_buy'],
                'percentage_step'   => $buy['percentage_step'],
                'predicted_sell'    => $buy['predicted_sell'],
            ]);

        }

    }

}
