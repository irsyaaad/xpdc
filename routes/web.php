<?php
// route for admin
include 'admin.php';
include 'except.php';
include 'loader.php';

Route::get('/', 'Auth\LoginController@showLogin')->name('login');
Route::group(['prefix' => '/auth'], function() {
	
	Route::get('/', 'Auth\LoginController@showLogin')->name('login');
	Route::get('/login', 'Auth\LoginController@showLogin');
	Route::post('/login', 'Auth\LoginController@login');
	
	//Route::get('/register', 'Auth\RegisterController@showRegister')->name('register');
	//Route::post('/register', 'Auth\RegisterController@create');
	
	// for logout
	Route::get('/logout/{id?}', 'UserController@logout');
});

// route for log laravel
$router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
    $router->get('logs', 'LogViewerController@index');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "All cache cleared";
});

Route::get('/webhook', 'WebHookController@webhookHandler');
Route::get('/webhook/sync/{id}', 'WebHookController@getData');
Route::get('public/wilayah', 'P_wilayah@public');
Route::get('public/getwilayah/{id}', 'P_wilayah@getwil');
