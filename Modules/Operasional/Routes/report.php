<?php

Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {

	// for report stt
	Route::get('repstt', 'LaporanController@repstt');
	Route::post('repstt', 'LaporanController@repstt');

	// Semua STT 
	Route::resource('repstt', 'LaporanController')->except("show", "destroy");
	Route::post('repstt/cetak', 'LaporanController@cari');
	Route::get('repstt/cetak_html', 'LaporanController@cetakhtml')->name('data');
	Route::get('repstt/cetak_excel', 'LaporanController@cetakexcel')->name('excel');

	// STT By Cara Bayar 
	Route::get('repsttcarabayar', 'ReportController@bycrabayar');
	Route::post('repsttcarabayar', 'ReportController@bycrabayar');
	Route::get('repsttcarabayar/cetak_html', 'ReportController@cetakhtml');
	Route::get('repsttcarabayar/cetak_excel', 'ReportController@cetakexcel');

	// Stt By Cash
	Route::get('sttcash', 'ReportController@bycash');
	Route::post('sttcash/filter', 'ReportController@filtercash');

	Route::get('sttnodm', 'ReportController@SttNoDM');
	Route::get('sttnodm/cetak', 'ReportController@cetakSttNoDM');
	Route::post('sttnodm/filter', 'ReportController@filtercash');

	// Stt Belum Sampai
	Route::get('outstandingstt', 'LaporanController@OutstandingStt')->name('filteroutstanding');
	Route::post('rekapentristatus', 'LaporanController@rekapentristatus');
	Route::post('rekapstatusbystt', 'LaporanController@rekapstatusbystt')->name("rekapstatusbystt");
	Route::post('rekapstatusbysttdetail/{id}', 'LaporanController@rekapstatusbysttdetail');

	// Stt No DM
	Route::get('stt-no-dm', 'LaporanController@SttNoDM');
	Route::get('stt-no-dm/cetak/stt-no-dm', 'LaporanController@CetakSttNoDM')->name('cetak-stt-no-dm');

	// Managerial STT
	Route::get('managerial-stt', 'ManagerialSttController@index');
	Route::get('managerial-stt/cetak', 'ManagerialSttController@cetak')->name('cetak-managerial-stt');
});