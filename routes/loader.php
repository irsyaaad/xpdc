<?php
// route for loader data
Route::group(['prefix' => '/loader', 'middleware' => 'auth'], function() {
	Route::get('/getWilayah', 'P_wilayah@loader');
});