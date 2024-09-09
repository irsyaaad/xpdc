<?php
// route for administrator
Route::group(['prefix' => '/', 'middleware' => 'auth'], function() {

	Route::get('/dashboard', 'P_module@dashboard')->name('admin.module');
	Route::get('/getmenu/{id}', 'P_module@getmenu');
	Route::get('/changerole/{id}', 'P_roleuser@changerole');
	Route::get('/changeperush/{id}', 'P_roleuser@changeperush');
	Route::get('/choose/{id}', 'P_module@choose');
	Route::get('/is_read/{id}', 'P_module@is_read');
	Route::get('/is_readhandling/{id}', 'P_module@is_readhandling');
	Route::get('/kirim-email', 'EmailController@tes');
	Route::get('/wa', 'EmailController@wa');

	$module = \App\Models\Module::where(DB::raw('lower(nm_module)'), "LIKE", "%".strtolower("administrator")."%")->get()->first();

	//$controller = \App\Menu::whereNotNull("controller")->whereNotNull("route")->where("id_module", $module->id_module)->get();

	$controller = \App\Models\Menu::whereNotNull("controller")->whereNotNull("route")->get();

	foreach($controller as $key => $value){
		$dir = $_SERVER['DOCUMENT_ROOT'];
		$dir = str_replace("public", "", $dir);
		$cek = $dir."/app/Http/Controllers/".$value->controller.".php";

		if(file_exists($cek)){
			Route::resource('/'.$value->route, $value->controller, ['except' => 'show'])->middleware('role');
			Route::get($value->route."/{id?}/show", $value->controller.'@show')->name($value->route.".detail")->middleware('role');
			Route::get($value->route."/filter", $value->controller.'@filter')->name($value->route.".filter")->middleware('role');
		}
	}
	
	Route::get('pelanggan/import', 'P_pelanggan@import')->middleware('role');
	Route::post('pelanggan/import', 'P_pelanggan@import')->middleware('role');
	Route::post('wilayah/save_wilayah', 'P_wilayah@store');
	Route::get('pelanggan/{id}/setakses', 'P_pelanggan@setakses')->middleware('role');
	Route::get('menus/generate/arrange', 'P_menu@arrange')->middleware('role');
	Route::get('menus/{id}/goup', 'P_menu@goup')->middleware('role');
	Route::get('menus/{id}/godown', 'P_menu@godown')->middleware('role');
	Route::get('menus/tes', 'P_menu@temp')->middleware('role');
	Route::get('menus/generateTemp', 'P_menu@generateTemp')->middleware('role');
	Route::get('tarif/{id}/createproyeksi', 'P_tarif@createproyeksi')->middleware('role');
	Route::get('tarif/{id}/editproyeksi', 'P_tarif@editproyeksi')->middleware('role');
	Route::get('karyawan/{id}/setakses', 'KaryawanController@setakses')->middleware('role');
	Route::post('karyawan/{id}/setakses', 'KaryawanController@setakses')->middleware('role');
	Route::get('karyawan/{id}/detail', 'KaryawanController@detail_karyawan')->middleware('role');
	Route::get('karyawan/{id}/set-gaji', 'KaryawanController@set_gaji')->middleware('role');
	Route::post('karyawan/save-detail-gaji', 'KaryawanController@save_detail_gaji')->middleware('role');
	Route::post('authborongan/create', 'AuthBoronganController@create');
	Route::get('profile', 'UserController@profile');
	Route::post('saveprofile', 'UserController@saveprofile');
});
