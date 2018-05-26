<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurrencyWl;
use Auth;
use Illuminate\Validation\Rule;

class CurrencyWhitelistController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display Currency Whitelist list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('currency.whitelist.whitelist-show', [
            'currency' => CurrencyWl::all()
        ]);
    }

    /**
     * Show currency whitelist creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('currency.whitelist.whitelist-create');

    }

    /**
     * Save currency pair whiltelist
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        $rules = $rules = array(
            'currency_index' => 'required|string|max:10|unique:currency_whitelist',
            'listed' => [
                'required',
                Rule::in(['0', '1']),
            ],
        );

        $this->validate($request, $rules);

        CurrencyWl::create([
            'currency_index' => $data['currency_index'],
            'listed' => $data['listed']
        ]);

        return view('currency.whitelist.whitelist-show', [
            'currency' => CurrencyWl::all()
        ]);

    }

    /**
     * update currency whitelist
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $currency = CurrencyWl::findOrFail($id);

        if($currency->listed){
            $currency->listed = 0;
        } else {
            $currency->listed = 1;
        }

        $save = $currency->save();

        if($save){
            return redirect('/currency-whitelist')->withSuccess('Edited '. $currency->currency_index .' successfully!');
        }else{
            return redirect('/currency-whitelist')->with('errors', 'Error editing '. $currency->currency_index .'!');
        }


    }

    /**
     * Remove currency whitelist from database.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = CurrencyWl::find($id)->delete();

        if($deleted){
            return redirect('/currency-whitelist')->withSuccess('Deleted currency');
        } else {
            return redirect('/currency-whitelist')->with('errors', 'Error editing '. $currency->currency_index .'!');
        }

    }

}
