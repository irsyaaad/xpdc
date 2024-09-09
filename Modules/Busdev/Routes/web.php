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

Route::group(['prefix' => '/', 'middleware' => ['auth','role']], function() {
    Route::get('/busdev', 'BusdevController@index');
    Route::resource('vendorbusdev', 'VendorBusdevController')->except("show");
    // Route::get('vendorbusdev', 'VendorBusdevController@index')->name('vendorbusdev.index');
    Route::delete('vendorbusdev/{id}/deletevendor', 'VendorBusdevController@deletevendor');
    Route::get('hargavendor/{id}/getharga', 'VendorBusdevController@getharga');
    Route::post('hargavendor/{id}/saveimport', 'VendorBusdevController@saveimport');
    Route::delete('hargavendor/{id}/deletedetail', 'VendorBusdevController@deletedetail');
});