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

    /**
     * Create new Poloniex instance
     *
     * @param  string $api_key
     * @param  string $api_secret
     * @return null
     */
    public function __construct($api_key='', $api_secret='') {

        //checking for user poloniex
        if($api_key == '' && $api_secret == '' && Auth::user()){

            $userKey = Auth::user()->poloniex_key;
            $userSecret = Auth::user()->poloniex_secret;

            if($userKey !== null && $userSecret !== null){

                $this->api_key = $userKey;
                $this->api_secret = $userSecret;

            }

        }else{

            $this->api_key = $api_key;
            $this->api_secret = $api_secret;

        }

    }

    /**
     * Selects public or private Poloniex link and bilds query url from $req array.
     *
     * @param  array $req
     * @param  boolean $public
     * @return array
     * @throws Exception
     */
    private function query($req = array(), $public = false) {

        $client = new \GuzzleHttp\Client();

        // request link
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1].substr($mt[0], 2, 6);
        $post_data = http_build_query($req, '', '&');

        //switching public or private api url
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

            return ['error' => 'Cant decode array values'];

        }else{

            return $decoded;

        }

    }

    /**
     * Gets user balaces from Poloniex api
     *
     * @return array
     */
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

    /**
     * Gets tradehistory from Poloniex api
     *
     * @param  string $pair
     * @return array
     */
    public function getMyTradeHistory($pair) {

        return $this->query(
            array(
                'command' => 'returnTradeHistory',
                'currencyPair' => strtoupper($pair)
            )
        );

    }

    /**
     * Buys currency from Poloniex.
     *
     * @param  string $pair
     * @param  float $rate
     * @param  float $amount
     * @return array
     */
    public function buy($pair, $rate, $amount) {

        return $this->query(
            array(
                'command' => 'buy',
                'currencyPair' => strtoupper($pair),
                'rate' => $rate,
                'amount' => $amount,
            )
        );

    }

    /**
     * Sells currency from Poloniex.
     *
     * @param  string $pair
     * @param  float $rate
     * @param  float $amount
     * @return array
     */
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

    /**
     * Gets tick data from Poloniex api.
     *
     * @param  string $pair
     * @return array
     */
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

    /**
     * Gets trade history from currency pair
     *
     * @param  string $pair
     * @return array
     */
    public function getTradeHistory($pair) {

        $url = array(
            'command' => 'returnTradeHistory',
            'currencyPair'=> strtoupper($pair)
        );

        $trades = $this->query($url, true);

        return $trades;

    }

    /**
     * Gets currency order book from Poloniex api
     *
     * @param  string $pair
     * @return array
     */
    public function getOrderBook($pair) {

        $url = array(
            'command' => 'returnOrderBook',
            'currencyPair'=> strtoupper($pair)
        );

        $orders = $this->query($url, true);

        return $orders;

    }

    /**
     * Gets all trading pairs form poloniex
     *
     * @return array
     */
    public function getTradingPairs() {

        $url = array(
            'command' => 'returnTicker'
        );

        $tickers = $this->query($url, true);

        return array_keys($tickers);

    }

    /**
     * Gets currency info from poloniex api.
     *
     * @return array
     */
    public function getCurrencyInfo(){

        $url = array(
            'command' => 'returnCurrencies'
        );

        $info = $this->query($url, true);

        return $info;

    }

}