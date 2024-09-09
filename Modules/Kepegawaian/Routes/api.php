<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/kepegawaian', 'middleware' => 'cors'], function() {
    Route::get('hargavendor', 'HargaVendorController@api');
	Route::get('wilayah', 'HargaVendorController@wilayah');
	Route::get('vendor', 'HargaVendorController@vendor');
});