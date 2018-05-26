<?php

namespace App\Http\Controllers;

use App\Custom\Analysis;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Custom\Poloniex;
use App\Custom\AddressByIp;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\TickData;

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

        $tickModel = new TickData();
        $transactions = new Transaction();

        $userDash = $transactions
            ->where('user_id', '=', Auth::id())
            ->where('closed', '=', '0')
            ->get();
        $tickData = $tickModel->getLatestTick();

        return view('home', [
            'snapshot' => $tickData,
            'userDash' => $userDash
        ]);

    }
}
