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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$router->post('whitegetwilayah', 'Api\PublicController@getWilayah');
$router->post('whitegetprovinsi', 'Api\PublicController@getProvinsi');
$router->post('whitegetkabupaten', 'Api\PublicController@getKabupaten');
$router->post('whitegetkecamatan', 'Api\PublicController@getKecamatan');

$router->post('login', 'Api\LoginController@getLogin');
$router->post('checktoken', 'Api\LoginController@CheckRequestToken');
$router->post('reqtoken', 'Api\LoginController@RequestToken');


// tes webhook lewat Api
$router->get('webhook', 'Api\WebhookController@get_data');

// get data local
$router->post('getperush', 'Api\MasterController@getPerush');
$router->post('getrole', 'Api\MasterController@getRole');
$router->post('getpelanggan', 'Api\MasterController@getPelanggan');
$router->post('getdriver', 'Api\MasterController@getDriver');
$router->post('getlistlaryawan', 'Api\MasterController@getListKaryawan');
$router->post('getwilayah', 'Api\MasterController@getWilayah');
$router->post('getprovinsi', 'Api\MasterController@getProvinsi');
$router->post('getkabupaten', 'Api\MasterController@getKabupaten');
$router->post('getkecamatan', 'Api\MasterController@getKecamatan');
$router->post('getlistvendor', 'Api\MasterController@getListVendor');
$router->post('stt/track', 'Api\MasterController@track');
$router->post('stt/getkodestt', 'Api\MasterController@getkodestt');
$router->post('pelanggan/checkunique', 'Api\MasterController@checkunique');
$router->post('pelanggan/insert', 'Api\MasterController@insertpelanggan');
$router->post('pelanggan/sync', 'Api\MasterController@syncpelanggan');

// dooring
$router->post('dooring/getstttiba', 'Api\ApiDooringController@get_stt');
$router->post('dooring/updatestt', 'Api\ApiDooringController@update_stt');
