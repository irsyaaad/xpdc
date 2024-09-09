<?php
include 'select2.php';
include 'report.php';
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
	// for handling
	Route::resource('dmhandling', 'HandlingController')->except("show");
	Route::get('dmhandling/filter', 'HandlingController@index');
	Route::get('dmhandling/{id?}/show', 'HandlingController@show');
	Route::post('dmhandling/store', 'HandlingController@store');
	Route::get('dmhandling/import/{id?}', 'HandlingController@import');
	Route::post('dmhandling/import/{id?}', 'HandlingController@import');
	Route::post('dmhandling/doimport/{id}', 'HandlingController@doimport');
	Route::get('dmhandling/{id}/proyeksi', 'HandlingController@proyeksi');
	Route::post('dmhandling/{id}/setberangkat', 'HandlingController@setberangkat');
	Route::post('dmhandling/sampai/{id}', 'HandlingController@sampai');
	Route::post('dmhandling/{id}/setselesai', 'HandlingController@setselesai');
	Route::get('dmhandling/getstttiba', 'HandlingController@getstttiba');
	Route::post('dmhandling/{id}/updatestt', 'HandlingController@updatestt');
	Route::get('dmhandling/{id?}/cetak', 'HandlingController@cetak');
	Route::delete('dmhandling/{id}/deletestt', 'HandlingController@deletestt');

	// for biaya handling
	Route::post('dmhandling/{id}/savebiaya', 'HandlingController@savebiaya');
	Route::get('dmhandling/{id}/showbiaya', 'HandlingController@showbiaya');
	Route::delete('dmhandling/{id}/deletebiaya', 'HandlingController@deletebiaya');
	Route::put('dmhandling/{id}/updatebiaya', 'HandlingController@updatebiaya');
	Route::get('dmhandling/getdm/{id}', 'HandlingController@getdm');
	Route::get('dmhandling/gethandling', 'HandlingController@gethandling');

	// for proyeksi handling
	Route::resource('proyeksihandling', 'ProyeksiHandlingController')->except("show");
	Route::get('proyeksihandling/{id?}/show', 'ProyeksiHandlingController@show');
	Route::post('proyeksihandling/savedetail', 'ProyeksiHandlingController@savedetail');
	Route::get('proyeksihandling/showdetail/{id}', 'ProyeksiHandlingController@showdetail');
	Route::delete('proyeksihandling/deletedetail/{id}', 'ProyeksiHandlingController@deletedetail');
	Route::put('proyeksihandling/editdetail/{id}', 'ProyeksiHandlingController@editdetail');
	Route::post('proyeksihandling/filter', 'ProyeksiHandlingController@filter');
	Route::get('proyeksihandling/filter', 'ProyeksiHandlingController@filter');

	//Operasional
	Route::resource('operasional', 'OperasionalController');
	Route::post('operasional/filter','OperasionalController@filter');
	Route::post('operasional/filterdm','OperasionalController@filterdm');

	Route::resource('tipekirim', 'TipeKirimController')->except("show");
	Route::get('tipekirim/filter', 'TipeKirimController@index');

	Route::get('stt/{id?}/cetak_pdf', 'SttController@cetak_pdf');
	Route::get('stt/{id?}/cetak_tnp_tarif', 'SttController@cetak_pdf');
	Route::get('stt/{id?}/cetak_kosong', 'SttController@cetak_kosong');
	Route::get('stt/{id?}/label', 'SttController@Labeling');
	Route::get('stt/{id?}/new-label', 'SttController@NewLabeling');
	Route::get('getppn', 'SttController@getppn');

	Route::resource('statusstt', 'StatusSttController');

	Route::resource('statusdm', 'StatusDMController');

	Route::resource('carabayar', 'CaraBayarController');

	Route::resource('packing', 'PackingController')->except("show");
	Route::get('packing/filter', 'PackingController@index');

	//Route Operasional => Armada
	Route::resource('armada', 'ArmadaController')->except("show");
	Route::get('armada/filter', 'ArmadaController@index');

	Route::resource('armadagroup', 'ArmadaGroupController')->except("show");
	Route::get('armadagroup/filter', 'ArmadaGroupController@index');

	Route::resource('kapalperush', 'KapalPerushController')->except("show");
	Route::get('kapalperush/filter', 'KapalPerushController@index');

	Route::resource('kapal', 'KapalController')->except("show");
	Route::get('kapal/filter', 'KapalController@index');

	Route::resource('sopir', 'SopirController')->except("show");
	Route::get('sopir/{id}/setAkses', 'SopirController@setAkses');
	Route::get('sopir/filter', 'SopirController@index');
	Route::get('sopir/{id}/show', 'SopirController@show');

	// for daftar muat (DM) trucking
	Route::resource('dmtrucking', 'DMTruckingController')->except("show");
	Route::get('dmtrucking/{id?}/show', 'DMTruckingController@show');
	Route::post('dmtrucking/getstt', 'DMTruckingController@getstt');
	Route::get('dmtrucking/{id?}/detail', 'DMTruckingController@detail');
	Route::post('dmtrucking/{id?}/detail', 'DMTruckingController@detail');
	Route::get('dmtrucking/{ide}/{id}/detailstt', 'DMTruckingController@detailstt');
	Route::get('dmtrucking/{id?}/proyeksi', 'DMTruckingController@proyeksi');
	Route::post('dmtrucking/saveproyeksi/{id}', 'DMTruckingController@saveproyeksi');
	Route::get('dmtrucking/{id}/showproyeksi', 'DMTruckingController@showproyeksi');
	Route::put('dmtrucking/updateproyeksi/{id}', 'DMTruckingController@updateproyeksi');
	Route::delete('dmtrucking/{id}/deleteproyeksi', 'DMTruckingController@deleteproyeksi');
	Route::get('dmtrucking/{id}/cetak', 'DMTruckingController@cetakDM');
	Route::get('dmtrucking/{id}/cetak-no-keterangan', 'DMTruckingController@cetakDM');
	Route::get('dmtrucking/{id}/cetaknotarif', 'DMTruckingController@cetakDMNoTarif');
	Route::get('dmtrucking/{id}/cetaklistbarcode', 'DMTruckingController@cetakDMBarcode');
	Route::get('dmtrucking/{id?}/import', 'DMTruckingController@import');
	Route::get('dmtrucking/{id?}/generateproyeksi', 'DMTruckingController@generateproyeksi');
	Route::post('dmtrucking/{id?}/import', 'DMTruckingController@import');
	Route::get('dmtrucking/{id?}/counting', 'DMTruckingController@counting');
	Route::get('dmtrucking/{id}/showstt', 'DMTibaController@showstt');
	Route::get('dmtrucking/filter', 'DMTruckingController@index');
	Route::get('dmtrucking/getdm', 'DMTruckingController@getdm');
	Route::get('dmtrucking/getdmtiba', 'DMTruckingController@getdmtiba');
	Route::get('dmtrucking/{id}/updatestatus', 'DMTruckingController@updatestatus')->name("updatestatus");
	Route::post('dmtrucking/saveupdatestatus', 'DMTruckingController@saveupdatestatus')->name("saveupdatestatus");
	Route::post('dmtrucking/saveupdatestatusajax', 'DMTruckingController@saveupdatestatusajax')->name("saveupdatestatusajax");
	Route::post('dmvendor/editupdatestatusajax', 'DMVendorController@editupdatestatusajax')->name("editupdatestatusajax");
	Route::delete('dmtrucking/{id?}/deletehistory', 'DMTruckingController@deletehistory');

	// for all dm
	Route::post('dmtrucking/{id?}/savekoli', 'DMTruckingController@savekoli');
	Route::delete('dmtrucking/{id?}/deletestt', 'DMTruckingController@deletestt');
	Route::delete('dmtrucking/{id?}/deletekoli', 'DMTruckingController@deletekoli');
	Route::get('dmtrucking/get-all-dm', 'DMTruckingController@get_all_dm');

	// for daftar muat (DM) container
	Route::resource('dmcontainer', 'DMContainerController')->except("show");
	Route::get('dmcontainer/{id?}/show', 'DMContainerController@show');
	Route::post('dmcontainer/getstt', 'DMContainerController@getstt');
	Route::get('dmcontainer/{id?}/detail', 'DMContainerController@detail');
	Route::post('dmcontainer/{id?}/detail', 'DMContainerController@detail');
	Route::get('dmcontainer/{ide?}/{id?}/detailstt', 'DMContainerController@detailstt');
	Route::get('dmcontainer/{id?}/proyeksi', 'DMTruckingController@proyeksi');
	Route::get('dmcontainer/{id}/cetak', 'DMContainerController@cetakDM');
	Route::get('dmcontainer/{id}/cetaknotarif', 'DMTruckingController@cetakDMNoTarif');
	Route::get('dmcontainer/{id}/cetaklistbarcode', 'DMContainerController@cetakDMBarcode');
	Route::get('dmcontainer/{id}/showstt', 'DMTibaController@showstt');
	Route::get('dmcontainer/filter', 'DMContainerController@index');
	Route::get('dmcontainer/getdm', 'DMContainerController@getdm');

	// for daftar muat (DM) vendor
	Route::resource('dmvendor', 'DMVendorController')->except("show");
	Route::get('dmvendor/{id?}/show', 'DMVendorController@show');
	Route::post('dmvendor/getstt', 'DMVendorController@getstt');
	Route::get('dmvendor/{id?}/detail', 'DMVendorController@detail');
	Route::post('dmvendor/{id?}/detail', 'DMVendorController@detail');
	Route::get('dmvendor/{ide}/{id}/detailstt', 'DMVendorController@detailstt');
	Route::get('dmvendor/{id?}/proyeksi', 'DMVendorController@proyeksi');
	Route::get('dmvendor/{id?}/proyeksi', 'DMVendorController@proyeksi');
	Route::get('dmvendor/{id?}/updateStatus', 'DMVendorController@updateStatus');
	Route::post('dmvendor/sampai', 'DMVendorController@sampai');
	Route::get('dmvendor/{id}/cetak', 'DMVendorController@cetakDM');
	Route::get('dmvendor/{id}/cetak-no-keterangan', 'DMVendorController@cetakDM');
	Route::get('dmvendor/{id}/cetaknotarif', 'DMTruckingController@cetakDMNoTarif');
	Route::get('dmvendor/{id}/cetaklistbarcode', 'DMVendorController@cetakDMBarcode');
	Route::get('dmvendor/{id}/showstt', 'DMTibaController@showstt');
	Route::get('dmvendor/filter', 'DMVendorController@index');
	Route::get('dmvendor/detaildm/{id?}', 'DMVendorController@detaildm');
	Route::post('dmvendor/saveproyeksi/{id}', 'DMVendorController@saveproyeksi');
	Route::get('dmvendor/{id}/showproyeksi', 'DMVendorController@showproyeksi');
	Route::put('dmvendor/{id}/updateproyeksi', 'DMVendorController@updateproyeksi');
	Route::get('dmvendor/getdmvendor', 'DMTruckingController@getdmvendor');

	// for dm kota kota
	Route::resource('dmkota', 'DMKotaController')->except("show");
	Route::get('dmkota/{id?}/show', 'DMKotaController@show');
	Route::get('dmkota/{id?}/detail', 'DMTruckingController@detail');
	Route::post('dmkota/{id?}/detail', 'DMTruckingController@detail');
	Route::get('dmkota/{ide}/{id}/detailstt', 'DMTruckingController@detailstt');
	Route::get('dmkota/{id?}/proyeksi', 'DMTruckingController@proyeksi');
	Route::post('dmkota/saveproyeksi/{id}', 'DMTruckingController@saveproyeksi');
	Route::get('dmkota/{id}/showproyeksi', 'DMTruckingController@showproyeksi');
	Route::put('dmkota/updateproyeksi/{id}', 'DMTruckingController@updateproyeksi');
	Route::delete('dmkota/{id}/deleteproyeksi', 'DMTruckingController@deleteproyeksi');
	Route::get('dmkota/filter', 'DMTruckingController@index');
	Route::get('dmkota/{id?}/counting', 'DMTruckingController@counting');
	Route::get('dmkota/{id}/showstt', 'DMTibaController@showstt');
	Route::post('dmkota/updatestatus/{id}', 'DMKotaController@updatestatus');
	Route::post('dmkota/sampai', 'DMVendorController@sampai');
	Route::get('dmkota/filter', 'DMKotaController@index');
	Route::get('dmkota/getdm', 'DMKotaController@getdm');

	// route for dm tiba -- for request
	Route::resource('dmtiba', 'DMTibaController')->except("show", "destroy");
	Route::get('dmtiba/filter', 'DMTibaController@index');
	Route::get('dmtiba/{id?}/show', 'DMTibaController@show');
	Route::get('dmtiba/{id}/showstt', 'DMTibaController@showstt');
	Route::get('dmtiba/{ide}/{id}/detailstt', 'DMTibaController@detailstt');
	Route::post('dmtiba/updatestatus/{id}', 'DMTibaController@updatestatus');
	Route::post('dmtiba/updatestatusdm/{id}', 'DMTibaController@updatestatusdm');
	Route::get('dmtiba/{id}/detailven', 'DMTibaController@detailven');
	Route::post('dmtiba/{id}/import', 'DMTibaController@import');
	Route::get('dmtiba/{id}/import', 'DMTibaController@import');
	Route::post('dmtiba/doimport', 'DMTibaController@doimport');
	Route::get('dmtiba/{id}/terima', 'DMTibaController@terima');
	Route::get('dmtiba/{id}/cetaktally', 'DMTibaController@cetaktally');
	Route::get('dmtiba/{id}/cetak', 'DMTibaController@cetakDM');
	Route::get('dmtiba/{id}/cetaknotarif', 'DMTibaController@cetakDM');
	Route::get('dmtiba/{id}/cetaklistbarcode', 'DMTibaController@cetakDMBarcode');
	Route::post('dmtiba/{id}/updatestt', 'DMTibaController@updatestt');
	Route::post('dmtiba/sampai', 'DMVendorController@sampai');
	Route::post('dmtiba/penerusan', 'DMVendorController@penerusan');

	// claim dm vendor
	Route::get('dmtiba/claimvendor', 'DMTibaController@claimvendor');
	Route::resource('tarifpelanggan', 'TarifPelangganController')->except("show");
	Route::get('tarifpelanggan/{id?}/show', 'TarifPelangganController@show');
	Route::get('tarifpelanggan/{id?}/create', 'TarifPelangganController@create');
	Route::get("tarifpelanggan/filter", 'TarifPelangganController@index');
	Route::post('tarifpelanggan/{id?}/filtershow', 'TarifPelangganController@filtershow');

	//Tarif Handling
	Route::resource('tarifhandling', 'TarifHandlingController')->except("show");
	Route::get("tarifhandling/page", 'TarifHandlingController@index');
	Route::get("tarifhandling/filter", 'TarifHandlingController@filter');
	Route::post("tarifhandling/page", 'TarifHandlingController@page');
	Route::post("tarifhandling/filter", 'TarifHandlingController@filter');

	// for tarif vendor
	Route::resource('tarifvendor', 'TarifVendorController')->except("show");
	Route::get('tarifvendor/{id?}/show', 'TarifVendorController@show');
	Route::post('tarifvendor/{id?}/filtershow', 'TarifVendorController@filtershow');
	Route::get('tarifvendor/{id?}/create', 'TarifVendorController@create');
	Route::get("tarifvendor/filter", 'TarifVendorController@index');

	// tarif proyeksi
	Route::resource('tarifproyeksi', 'TarifProyeksiController')->except("show");
	Route::get('tarifproyeksi/{id}/show', 'TarifProyeksiController@show');
	Route::get('tarifproyeksi/filter', 'TarifProyeksiController@index');
	// for detail proyeksi
	Route::post('tarifproyeksi/savedetail', 'TarifProyeksiController@savedetail');
	Route::get('tarifproyeksi/showdetail/{id}', 'TarifProyeksiController@showdetail');
	Route::delete('tarifproyeksi/deletedetail/{id}', 'TarifProyeksiController@deletedetail');
	Route::put('tarifproyeksi/editdetail/{id}', 'TarifProyeksiController@editdetail');

	// tarif proyeksi vendor
	Route::resource('proyeksivendor', 'ProyeksiVendorController')->except("show");
	Route::get('proyeksivendor/{id}/show', 'ProyeksiVendorController@show');
	Route::get('proyeksivendor/filter', 'ProyeksiVendorController@index');
	// for detail proyeksi vendor
	Route::post('proyeksivendor/savedetail', 'ProyeksiVendorController@savedetail');
	Route::get('proyeksivendor/showdetail/{id}', 'ProyeksiVendorController@showdetail');
	Route::delete('proyeksivendor/deletedetail/{id}', 'ProyeksiVendorController@deletedetail');
	Route::put('proyeksivendor/editdetail/{id}', 'ProyeksiVendorController@editdetail');

	// for handling vendor
	Route::resource('handlingvendor', 'HandlingVendorController')->except("show");
	Route::get('handlingvendor/getdm', 'HandlingVendorController@gethandling');
	Route::get('handlingvendor/filter', 'HandlingVendorController@index');

	Route::get('handlingvendor/{id?}/show', 'HandlingController@show');
	Route::get('handlingvendor/import/{id?}', 'HandlingController@import');
	Route::post('handlingvendor/import/{id?}', 'HandlingController@import');
	Route::post('handlingvendor/doimport/{id}', 'HandlingController@doimport');
	Route::get('handlingvendor/{id}/proyeksi', 'HandlingController@proyeksi');
	Route::delete('handlingvendor/{id}/deletestt', 'HandlingController@deletestt');
	Route::post('handlingvendor/{id}/setberangkat', 'HandlingController@setberangkat');
	Route::post('handlingvendor/sampai/{id}', 'HandlingController@sampai');
	Route::post('handlingvendor/{id}/setselesai', 'HandlingController@setselesai');
	Route::get('handlingvendor/getstttiba', 'HandlingController@getstttiba');
	Route::post('handlingvendor/{id}/updatestt', 'HandlingController@updatestt');

	// this for handling stt dikirim melalui vendor
	Route::resource('handlingkirim', 'HandlingKirimanController')->except("show");
	Route::get('handlingkirim/filter', 'HandlingKirimanController@index');
	Route::get('handlingkirim/getdm', 'HandlingKirimanController@getdm');
	Route::get('handlingkirim/import/{id?}', 'HandlingKirimanController@import');
	Route::post('handlingkirim/import/{id?}', 'HandlingKirimanController@import');
	Route::post('handlingkirim/doimport/{id}', 'HandlingKirimanController@doimport');
	Route::get('handlingkirim/getstttiba', 'HandlingKirimanController@getstttiba');
	Route::post('handlingkirim/sampai/{id}', 'HandlingKirimanController@sampai');

	Route::post('dmhandling/{id}/savebiaya', 'HandlingController@savebiaya');
	Route::get('handlingkirim/{id?}/show', 'HandlingController@show');
	Route::get('handlingkirim/{id}/proyeksi', 'HandlingController@proyeksi');
	Route::delete('handlingkirim/{id}/deletestt', 'HandlingController@deletestt');
	Route::post('handlingkirim/{id}/setberangkat', 'HandlingController@setberangkat');
	Route::post('handlingkirim/{id}/setselesai', 'HandlingController@setselesai');
	Route::post('handlingkirim/{id}/updatestt', 'HandlingController@updatestt');

	// this for stt dikirim
	Route::resource('stt', 'SttController')->except('show');
	Route::get('stt/{id?}/show', 'SttController@show');
	Route::get('stt/{id?}/tracking', 'SttController@tracking');
	Route::post('stt/savedetail', 'SttController@savedetail');
	Route::post('stt/updatestt/{id}', 'SttController@updatestt');
	Route::delete('stt/deletestt/{id}', 'SttController@deletestt');
	// Route::post('stt/getdata', 'ImportDataController@create');
	// Route::post('stt/savedata', 'ImportDataController@store');
	// Route::post('stt/finish', 'ImportDataController@finish');
	Route::get('stt/{id?}/packing', 'PackingBarangController@packing');
	Route::post('stt/goAuthBorongan', 'SttController@goAuthBorongan');
	Route::get('stt/refresh', 'SttController@refresh');
	Route::get('stt/filter', 'SttController@index');
	Route::post('getPostTarif', 'TarifProyeksiController@getPostTarif');
	Route::get('stt/import', 'SttController@import');
	Route::get('stt/{id}/showimport', 'SttController@showimport');
	Route::post('stt/{id}/saveimport', 'SttController@saveimport');
	
	// this for stt diterima
	Route::resource('sttterima', 'SttDiterimaController')->except('show');
	Route::get('sttterima/{id}/show', 'SttDiterimaController@show');
	Route::get('sttterima/cetak', 'SttDiterimaController@cetak');
	Route::get('sttterima/filter/', 'SttDiterimaController@index');

	// this route from stt kembali
	Route::resource('sttkembali', 'SttKembaliController')->except('show');
	Route::get('sttkembali/{id}/show', 'SttKembaliController@show');
	Route::post('sttkembali/{id}/addstt', 'SttKembaliController@addstt');
	Route::get('sttkembali/{id}/deletestt', 'SttKembaliController@deletestt');
	Route::get('sttkembali/{id}/deletedokumen', 'SttKembaliController@deleteDokumen');
	Route::get('sttkembali/{id}/sendstt', 'SttKembaliController@sendstt');
	Route::get('sttkembali/deletestt/{id}', 'SttKembaliController@deletestt');
	Route::get('sttkembali/{id}/cetak', 'SttKembaliController@cetak');
	Route::get('sttkembali/get-agenda', 'SttKembaliController@getAgenda')->name('get-agenda-stt-kembali');
	Route::post('sttkembali/update-dokumen', 'SttKembaliController@update_dokumen')->name('update-dokumen');
	Route::get('rekapitulasi-stt-kembali', 'SttKembaliController@rekapitulasi_stt_kembali')->name('rekapitulasi-stt-kembali');
	Route::get('rekapitulasi-stt-kembali/detail', 'SttKembaliController@detail_rekapitulasi_stt_kembali')->name('detail-rekapitulasi-stt-kembali');
	Route::get('rekapitulasi-stt-kembali-by-dokumen', 'SttKembaliController@rekapitulasi_stt_kembali_by_dokumen')->name('rekapitulasi-stt-kembali-by-dokumen');
	Route::get('rekapitulasi-stt-kembali-by-dokumen/detail', 'SttKembaliController@detail_rekapitulasi_stt_kembali_by_dokumen')->name('detail-rekapitulasi-stt-kembali-by-dokumen');

	// this route for stt kembali as penerima
	Route::resource('sttkembaliterima', 'SttKembaliTerimaController');
	Route::get('sttkembaliterima/{id}/terima', 'SttKembaliTerimaController@terima');
	Route::post('sttkembaliterima/filter', 'SttKembaliTerimaController@filter');
	Route::get('sttkembaliterima/filter', 'SttKembaliTerimaController@filter');

	//Asuransi
	// Route::resource('perusahaanasuransi', 'PerusahaanAsuransiController')->except('show');
	// Route::resource('tarifasuransi', 'TarifAsuransiController');
	// Route::resource('asuransistt','AsuransiSttController')->except('show');
	// Route::get('asuransistt/{id}/show', 'AsuransiSttController@show');
	// Route::post('asuransistt/save','AsuransiSttController@save')->name('simpanasuransi');
	// Route::post('asuransistt/{id}/handle', 'AsuransiSttController@handle');
	// Route::get('asuransistt/{id}/handle', 'AsuransiSttController@handle');
	// Route::get('asuransistt/import', 'AsuransiSttController@import');
	// Route::get('asuransistt/cetak', 'AsuransiSttController@cetak')->name('cetakdataasuransi');
	// Route::get('asuransistt/{id}/updatestatus', 'AsuransiSttController@updatestatus');
	// Route::get('liststtasuransi', 'AsuransiSttController@list');

	// route for packing
	Route::resource('tarifpacking', 'TarifPackingController')->except('show');
	Route::post('tarifpacking/gettarifpacking', 'TarifPackingController@gettarifpacking');

	Route::resource('packingbarang', 'PackingBarangController')->except('show');
	Route::get('packingbarang/showimport', 'PackingBarangController@showimport');
	Route::get('packingbarang/import/{id?}', 'PackingBarangController@import');
	Route::get('packingbarang/{id}/editdetail', 'PackingBarangController@editdetail');
	Route::post('packingbarang/{id}/updatedetail', 'PackingBarangController@updatedetail');
	Route::post('packingbarang/doimport/{id?}', 'PackingBarangController@doimport');
	Route::delete('packingbarang/deletedetail/{id?}', 'PackingBarangController@deletedetail');

	Route::resource('sttawb', 'SttAwbController')->except('show');
	Route::get('sttawb/filter', 'SttAwbController@index');
	Route::get('sttawb/{id}/show', 'SttAwbController@show');
	Route::post('sttawb/updatestatus', 'SttAwbController@updatestatus');
	Route::get('sttawb/getSttAwb', 'SttAwbController@getSttAwb');
	Route::get('sttawb/getDmAwb', 'SttAwbController@getDmAwb');
	
	//status wajib
	Route::resource('settingstatus', 'SettingStatusWajibController')->except('show');
	Route::get('laporanstatuswajib', 'SettingStatusWajibController@laporan');
	Route::get('laporanstatuswajib/{id}/detailpengirim', 'SettingStatusWajibController@detailpengirim');
	Route::get('laporanstatuswajib/{id}/detailpenerima', 'SettingStatusWajibController@detailpenerima');
	Route::get('laporanstatuswajib/{id}/detailstatusstt', 'SettingStatusWajibController@detailstatusstt');

	//Komplain
	Route::resource('complain', 'KomplainController')->except('show');
	Route::get('complain/{id}/show', 'KomplainController@show');
	Route::post('complain/save-process', 'KomplainController@save_process');
	Route::get('complain/{id}/delete', 'KomplainController@destroy');
	Route::get('complain/get-complain', 'KomplainController@getComplain')->name('get-complain');
});
