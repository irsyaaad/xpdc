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

Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {
    Route::resource('asuransi', 'AsuransiController')->except('show');
    Route::get('asuransi/{id}/show', 'AsuransiController@show');
    Route::get('asuransi/get-stt-asuransi/{id}', 'AsuransiController@search_stt');
    Route::resource('invoice-asuransi', 'InvoiceController')->except('show');
    Route::resource('perusahaan-asuransi', 'PerusahaanAsuransiController')->except('show');
    Route::resource('bayar-broker-asuransi', 'AsuransiBiayaPayController')->except('show');
    Route::resource('tarif-asuransi', 'TarifAsuransiController')->except('show');
    Route::resource('jurnal-masuk-asuransi', 'JurnalMasukController')->except('show');
    Route::get('jurnal-masuk-asuransi/{id}/show', 'JurnalMasukController@show');
    Route::resource('jurnal-keluar-asuransi', 'JurnalKeluarController')->except('show');
    Route::get('jurnal-keluar-asuransi/{id}/show', 'JurnalKeluarController@show');
    Route::resource('memorial-asuransi', 'MemorialController')->except('show');
    Route::get('tarif-asuransi/get-tarif-asuransi/{id}', 'TarifAsuransiController@get_tarif');
    Route::get('invoice-asuransi/{id}/show', 'InvoiceController@show');
    Route::get('invoice-asuransi/add-stt/{id}', 'InvoiceController@add_stt');
    Route::post('invoice-asuransi/save-draft/{id}', 'InvoiceController@save_draft');
    Route::delete('invoice-asuransi/{id}/delete-stt', 'InvoiceController@delete_stt');
    Route::post('invoice-asuransi/{id}/bayar', 'InvoiceController@bayar');
    Route::resource('invoice-asuransi-pay', 'InvoicePayController');
    Route::resource('jurnal-asuransi', 'JurnalAsuransiController');
    Route::get('neraca-asuransi', 'JurnalAsuransiController@neraca')->name('neraca');
    Route::get('neraca-asuransi/show', 'JurnalAsuransiController@neraca_show')->name('neraca-show');
    Route::get('neraca-asuransi/show-detail', 'JurnalAsuransiController@neraca_show_detail')->name('showneracadetail-asuransi');
    Route::get('rugilaba-asuransi', 'JurnalAsuransiController@rugilaba')->name('rugilaba');
    Route::get('piutang-pelanggan-asuransi', 'LaporanController@index');
});
