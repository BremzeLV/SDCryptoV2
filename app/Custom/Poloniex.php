<?php
namespace App\Custom;

use GuzzleHttp;
use Exception;
use Auth;

class Poloniex {
    protected $api_key;
    protected $api_secret;
    protected $trading_url = "https://poloniex.com/tradingApi";
    protected $public_url = "https://poloniex.com/public";

    public function __construct($api_key='', $api_secret='') {

        if($api_key == '' && $api_secret == '' && Auth::user()){
            $userKey = Auth::user()->poloniex_key;
            $userSecret = Auth::user()->poloniex_secret;

            if($userKey !== null && $userSecret !== null){
                $this->api_key = $userKey;
                $this->api_secret = $userSecret;
            }else{
                return redirect('user')->with('status', 'Enter Poloniex credentials!!');
            }
        }else{
            $this->api_key = $api_key;
            $this->api_secret = $api_secret;
        }
    }

    private function query($req = array(), $public = false) {

        $client = new \GuzzleHttp\Client();

        // request link
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1].substr($mt[0], 2, 6);
        $post_data = http_build_query($req, '', '&');

        if(!$public){
            //private
            $url = $this->trading_url;
            $res = $client->request('POST',  $url, [
                'headers' => [
                    'Key' => $this->api_key,
                    'Sign' => hash_hmac('sha512', $post_data, $this->api_secret),
                ],
                'form_params' => $req
            ]);
        }else{
            //public
            $url = $this->public_url;
            $res = $client->request('GET',  $url.'?'.$post_data);
        }

        $res_code = $res->getStatusCode();
        $res = $res->getBody();

        $decoded = json_decode($res, true);

        if (isset($decoded['error'])) throw new Exception('Error: '.$decoded['error']);

        if (!$decoded){
            return false;
        }else{
            return $decoded;
        }
    }

    public function getBalances() {
        $returned = $this->query(
            array(
                'command' => 'returnBalances'
            )
        );

        $balances = collect($returned)->filter(function ($item) {
            return $item > 0;
        });

        return $balances;
    }

    public function getOpenOrders($pair) {
        return $this->query(
            array(
                'command' => 'returnOpenOrders',
                'currencyPair' => strtoupper($pair)
            )
        );
    }

    public function getMyTradeHistory($pair) {
        return $this->query(
            array(
                'command' => 'returnTradeHistory',
                'currencyPair' => strtoupper($pair)
            )
        );
    }

    public function buy($pair, $rate, $amount) {
        return $this->query(
            array(
                'command' => 'buy',
                'currencyPair' => strtoupper($pair),
                'rate' => $rate,
                'amount' => $amount,
                'fillOrKill' => 1
            )
        );
    }

    public function sell($pair, $rate, $amount) {
        return $this->query(
            array(
                'command' => 'sell',
                'currencyPair' => strtoupper($pair),
                'rate' => $rate,
                'amount' => $amount
            )
        );
    }

    public function cancelOrder($pair, $order_number) {
        return $this->query(
            array(
                'command' => 'cancelOrder',
                'currencyPair' => strtoupper($pair),
                'orderNumber' => $order_number
            )
        );
    }

    public function withdraw($currency, $amount, $address) {
        return $this->query(
            array(
                'command' => 'withdraw',
                'currency' => strtoupper($currency),
                'amount' => $amount,
                'address' => $address
            )
        );
    }

    public function getTicker($pair = "ALL") {
        $pair = strtoupper($pair);
        $prices = $this->query(array('command'=>'returnTicker'), true);
        if($pair == "ALL"){
            return $prices;
        }else{
            $pair = strtoupper($pair);
            if(isset($prices[$pair])){
                return $prices[$pair];
            }else{
                return array();
            }
        }
    }

    public function getTradeHistory($pair) {
        $url = array(
            'command' => 'returnTradeHistory',
            'currencyPair'=> strtoupper($pair)
        );
        $trades = $this->query($url, true);
        return $trades;
    }

    public function getOrderBook($pair) {
        $url = array(
            'command' => 'returnOrderBook',
            'currencyPair'=> strtoupper($pair)
        );
        $orders = $this->query($url, true);
        return $orders;
    }

    public function getVolume() {
        $url = array(
            'command' => 'return24hVolume'
        );
        $volume = $this->query($url, true);
        return $volume;
    }

    public function getTradingPairs() {
        $url = array(
            'command' => 'returnTicker'
        );
        $tickers = $this->query($url, true);
        return array_keys($tickers);
    }

    public function getCurrencyInfo(){
        $url = array(
            'command' => 'returnCurrencies'
        );
        $info = $this->query($url, true);
        return $info;
    }

    public function getTotalBtcBalance() {
        $balances = $this->getBalances();
        $prices = $this->getTicker();

        $tot_btc = 0;

        foreach($balances as $coin => $amount){
            $pair = "BTC_".strtoupper($coin);

            //balance to btc
            if($amount > 0){
                if($coin != "BTC"){
                    $tot_btc += $amount * $prices[$pair];
                }else{
                    $tot_btc += $amount;
                }
            }

            // opened orders
            if($coin != "BTC"){
                $open_orders = $this->getOpenOrders($pair);
                foreach($open_orders as $order){
                    if($order['type'] == 'buy'){
                        $tot_btc += $order['total'];
                    }elseif($order['type'] == 'sell'){
                        $tot_btc += $order['amount'] * $prices[$pair];
                    }
                }
            }
        }

        return $tot_btc;
    }

}
