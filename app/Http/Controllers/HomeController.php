<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Custom\Poloniex;
use App\Custom\AddressByIp;
use Illuminate\Support\Facades\Storage;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /* $eth = DB::table('eth_tick')->orderBy('id', 'desc')->first();
        $ltc = DB::table('ltc_tick')->orderBy('id', 'desc')->first();*/


       /* dd(\Request::ip());*/

//        $poloniex = new Poloniex('M29V6198-XK4W3A1D-BO8HDM60-PZMF60NI','a4d79441d4fc6c63195acb3a4186ca6ccf714b1d29acdf1b1cab08019526557e4ae37174d5eba9900b54be8660003a42ee08f6bafc69db325ef72f672e9cccb1');
//        dd($poloniex->get_balances());
        /*$user = Auth::user();
        dd(Storage::disk('local')->exists('public/avatars/'.$user->avatar));*/

        return view('home', [
            'data' => null,
            'ltc' => null,
        ]);
    }
}
