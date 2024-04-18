<?php

use Illuminate\Support\Facades\Route;

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
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/deposit', function () {
    return view('deposit');
})->name('deposit');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

        Route::get('/deposit', function () {
        return view('deposit');
    })->name('deposit');

    // The route that the button calls to initialize payment

Route::post('pay', 'App\Http\Controllers\ChapaController@initialize')->name('pay');

// The callback url after a payment
Route::get('callback/{reference}', 'App\Http\Controllers\ChapaController@callback')->name('callback');

Route::get('/withdraw', 'App\Http\Controllers\ChapaController@withdrawOptions')->name('withdraw.options');
Route::post('/withdraw', 'App\Http\Controllers\ChapaController@initiateWithdrawal')->name('withdraw.initiate');


});
