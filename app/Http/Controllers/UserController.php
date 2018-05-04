<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
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
    public function edit($id) //TODO make secure
    {
        $user = User::findOrFail($id);

        if(Auth::id() == $id || Auth::user()->isAdmin()){
            return view('user.edit-profile', [
                'user' => $user
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
        if(Auth::id() == $id || Auth::user()->isAdmin()) {
            $data = $request->input();
            //$data['password'] = bcrypt($data['password']);

            $rules = $rules = array(
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'birthdate' => 'required|date',
                /* 'password' => 'string|min:6|confirmed',*/
                'gender' => 'string|max:1',
                'poloniex_key' => 'string|max:255',
                'poloniex_secret' => 'string|max:255',
            );

            $this->validate($request, $rules);

            $user = User::find($id);
            $user->fill($data)->save();

            return redirect('/user')->withMessage('You just edited your profile!');
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
