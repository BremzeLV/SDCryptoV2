<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\CurrencyWl;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.user-profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('user.user-profile', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $currency_pairs = CurrencyWl::pluck('currency_index');

        if(Auth::id() == $id || Auth::user()->isAdmin()){
            return view('user.edit-profile', [
                'user' => $user,
                'pairs' => $currency_pairs
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //check permission
        if(Auth::id() == $id || Auth::user()->isAdmin()) {
            $data = $request->input();
            $user = User::find($id);

            $rules = array(
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'birthdate' => 'required|date',
                'password' => 'string|min:6|confirmed',
                'gender' => 'string|max:1',
                'selected_pair' => 'nullable|exists:currency_whitelist,currency_index',
                'poloniex_key' => 'nullable|string|max:255',
                'poloniex_secret' => 'nullable|string|max:255',
            );

            $inputData = collect($user)->filter(function($item, $key) use($data){
                if(array_key_exists($key, $data) && ($item != $data[$key] || empty($data[$key]))){
                    return true;
                }
            })->map(function($item, $key) use($data){
                if($key != 'password'){
                    return $data[$key];
                }else{
                    if(!empty($data['password'])){
                        return $data['password'];
                    }
                }
            });

           // dd($inputData);

            foreach($inputData->toArray() as $key => $value){
                if(array_key_exists($key, $rules)){
                    $usedRules[$key] = $rules[$key];
                }
            }

            if(isset($inputData->password)){
                $inputData->password = bcrypt($inputData->password);
            }

            $this->validate($request, $usedRules);

            $user->fill($inputData->toArray())->save();

            return redirect('/user/'.$user->id)->withMessage('You just edited your profile!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::id() == $id || $user->isAdmin()) {
            User::find($id)->delete();
            return redirect('/home')->withMessage('Deleted your profile :(');
        }
    }
}
