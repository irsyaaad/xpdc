<?php

//  this route for select option

Route::get('getTipeKirim', 'SelectController@getTipeKirim')->middleware("auth");
Route::get('getCaraBayar', 'SelectController@getCaraBayar')->middleware("auth");
Route::get('getPacking', 'SelectController@getPacking')->middleware("auth");
Route::get('getKapalPerush', 'SelectController@getKapalPerush')->middleware("auth");
Route::get('ChainArmada/{id?}', 'SelectController@ChainArmada')->middleware("auth");

Route::post('getTarifPost', 'SelectController@getTarifPost')->middleware("auth");
Route::get('getSttKoli/{id?}', 'SttController@getSttKoli')->middleware("auth");
Route::get('getSttPerush/{id?}/{id_dm}', 'SttController@getSttPerush')->middleware("auth");
Route::post('gettarifvendor', 'SelectController@gettarifvendor')->middleware("auth");
Route::get('getgrouparmada', 'SelectController@getgrouparmada')->middleware("auth");
Route::get('getstt', 'SttController@getStt')->middleware("auth");
Route::get('getasuransistt', 'AsuransiSttController@getAsuransiStt')->middleware("auth");
Route::get('getdataasuransistt/{id?}', 'AsuransiSttController@getDataSttAsuransi')->middleware("auth");
Route::get('getMinTarif/{id}', 'SelectController@getMinTarif')->middleware("auth");