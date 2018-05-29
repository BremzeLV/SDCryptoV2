<?php
namespace App\Custom;

use GuzzleHttp;
use Exception;

class AddressByIp {
    /**
     * IPstack address API key
     *
     * @var string
     */
    protected $api_key = '1aef93b4efde0dcee7ce7a8dbbfc2b4d';

     /**
      * IPStack request link
      *
      * @var object
      */
    protected $api_link = 'http://api.ipstack.com/';

    /**
     * Request address data
     *
     * @param  string $ip
     * @return array
     * @trows Exception
     */
    public function getAddress($ip){

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET',  $this->api_link.$ip.'?access_key='.$this->api_key);
        $result = json_decode($res->getBody());

        if(isset($result->success)){
            throw new Exception('Error '.$result->error->code . ': ' . $result->error->info);
        }

        return $result;

    }

}
