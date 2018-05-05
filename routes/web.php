<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    //Route::get('/user/{id?}', 'UserController@index')->name('user/user');
    Route::resource('user', 'UserController');
    Route::resource('statistic', 'StatisticsController');
    Route::resource('currency-whitelist', 'CurrencyWhitelistController');
});


/*Route::middleware(['auth'])->group(function () {
    Route::get('/', 'HomeController@index');
    Route::get('signout', 'HomeController@signout');
    Route::resource('guides', 'GuideController');
    Route::resource('objects', 'ObjectController');
    Route::resource('profile', 'ProfileController');
    Route::resource('groups', 'GroupController');
});*/
