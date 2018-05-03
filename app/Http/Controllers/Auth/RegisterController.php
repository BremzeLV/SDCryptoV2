<?php

namespace App\Http\Controllers\Auth;

use App\Address;
use App\Custom\AddressByIp;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'birthdate' => 'required|date|before:today',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'string|max:1',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       // dd($data['image']);

       /* if (isset($data['image'])){
            $data['image']->move(public_path('/uploads/users/'.$this->id.'/').'profile.png');
        }*/

        $address = AddressByIp::getAddress(\Request::ip());

        Address::create([
            'ip' => $address->ip,
            'country_name' => $address->country_name,
            'country_code' => $address->country_code,
            'region_name' => $address->region_name,
            'region_code' => $address->region_code,
            'city' => $address->city,
            'zip' => $address->zip,
        ]);

        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'username' => $data['username'],
            'gender' => $data['gender'],
            'birthdate' => $data['birthdate'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
