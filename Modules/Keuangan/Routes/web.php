<?php

Route::group(['prefix' => '/', 'middleware' => ['auth','role']], function() {
	include 'laporan.php';
	Route::resource('keuangan', 'KeuanganController');

	Route::resource('groupbiaya', 'GroupBiayaController')->except("show");
	Route::get('groupbiaya/filter', 'GroupBiayaController@filter');
	Route::post('groupbiaya/filter', 'GroupBiayaController@filter');
	Route::post('groupbiaya/page', 'GroupBiayaController@index');
	Route::get('groupbiaya/page', 'GroupBiayaController@index');
	
	// status invoice
	Route::resource('statusinvoice', 'StatusInvoiceController');

	// AC
	Route::resource('masterac', 'MasterACController')->except("show");
	Route::post('masterac/filter', 'MasterACController@filter');
	Route::get('masterac/filter', 'MasterACController@filter');

	// AC
	Route::resource('acperush', 'ACPerushController')->except("show");
	Route::post('acperush/filter', 'ACPerushController@filter');
	Route::get('acperush/filter', 'ACPerushController@filter');
	Route::get('acperush/generate', 'ACPerushController@generate');

	//setting biaya
	Route::resource('settingbiaya', 'SettingBiayaController')->except("show");
	// setting group pelanggan
	Route::resource('settinglayanan', 'SettingGroupLayananController')->except("show");
	// setting handling
	Route::resource('settinghandling', 'SettingHandlingController');
	// setting handling
	Route::resource('settingbiayavendor', 'SettingBiayaVendorController');
	Route::resource('settingpacking', 'SettingPackingController');

	//setting biaya perush
	Route::resource('settingbiayaperush', 'SettingBiayaPerushController')->except("show");
	Route::get('settingbiayaperush/generate', 'SettingBiayaPerushController@generate');

	// setting handling perush
	Route::resource('settinghandlingperush', 'SettingHandlingPerushController')->except("show");
	Route::get('settinghandlingperush/generate', 'SettingHandlingPerushController@generate');

	// settin group layanan perush
	Route::resource('settinglayananperush', 'SettingLayananPerushController')->except("show");
	Route::get('settinglayananperush/generate', 'SettingLayananPerushController@generate');

	// Setting Invoice Cabang
	Route::resource('settinginvoicecabang', 'SettingInvoiceCabangController')->except("show");

	//setting packing perush
	Route::resource('settingpackingperush', 'SettingPackingPerushController')->except("show");
	Route::get('settingpackingperush/generate', 'SettingPackingPerushController@generate');

	// pendapatan
	Route::resource('pendapatan', 'PendapatanController')->except("show");
	Route::get('pendapatan/{id}/show', 'PendapatanController@show');
	Route::get('pendapatan/getac/{id}', 'PendapatanController@getac');
	Route::get('pendapatan/filter', 'PendapatanController@index');
	Route::get('pendapatan/{id}/cetak', 'PendapatanController@cetak');
	Route::get('pendapatan/cetakall', 'PendapatanController@cetakall')->name('cetakallpendapatan');

	// detail pendapatan
	Route::post('pendapatan/savedetail', 'PendapatanController@savedetail');
	Route::get('pendapatan/{id?}/editdetail', 'PendapatanController@editdetail');
	Route::post('pendapatan/updatedetail/{id?}', 'PendapatanController@updatedetail');
	Route::delete('pendapatan/{id}/deletedetail', 'PendapatanController@deletedetail');

	// pengeluaran
	Route::resource('pengeluaran', 'PengeluaranController')->except("show");
	Route::get('pengeluaran/{id}/show', 'PengeluaranController@show');
	Route::get('pengeluaran/{id}/cetak', 'PengeluaranController@cetak');
	Route::get('pengeluaran/filter', 'PengeluaranController@index');
	Route::get('pengeluaran/cetakall', 'PengeluaranController@cetakall')->name('cetakallpengeluaran');
	
	//detail pengeluaran
	Route::post('pengeluaran/savedetail', 'PengeluaranController@savedetail');
	Route::get('pengeluaran/{id?}/editdetail', 'PengeluaranController@editdetail');
	Route::post('pengeluaran/updatedetail/{id?}', 'PengeluaranController@updatedetail');
	Route::delete('pengeluaran/{id}/deletedetail', 'PengeluaranController@deletedetail');
	
	// memorial
	Route::resource('memorial', 'MemorialController')->except("show");
	Route::get('memorial/{id}/detail', 'MemorialController@show');
	Route::get('memorial/filter', 'MemorialController@index');

	//detail memorial
	Route::post('memorial/savedetail', 'MemorialController@savedetail');
	Route::get('memorial/{id?}/editdetail', 'MemorialController@editdetail');
	Route::post('memorial/updatedetail/{id?}', 'MemorialController@updatedetail');
	Route::delete('memorial/{id}/deletedetail', 'MemorialController@deletedetail');

	//Invoice Cabang Controller
	Route::resource('invoicecabang', 'InvoiceCabangController');

	// Invoice Asuransi	
	Route::resource('invoiceasuransi', 'InvoiceAsuransiController')->except("show");
	Route::get('invoiceasuransi/{id?}/show', 'InvoiceAsuransiController@show');
	Route::get('invoiceasuransi/{id?}/asuransi', 'InvoiceAsuransiController@tambahasuransi');
	Route::post('invoiceasuransi/{id?}/asuransi', 'InvoiceAsuransiController@tambahasuransi');
	Route::post('invoiceasuransi/{id?}/savedraft', 'InvoiceAsuransiController@savedraft');
	Route::post('invoiceasuransi/{id?}/hapusdraft', 'InvoiceAsuransiController@hapusdraft');
	Route::get('invoiceasuransi/{id?}/cetak', 'InvoiceAsuransiController@CetakInvoice');
	Route::get('invoiceasuransi/{id?}/send', 'InvoiceAsuransiController@send');
	Route::post('invoiceasuransi/bayar/{id?}', 'PembayaranAsuransiController@store');

	// Pembayaran Asuransi
	Route::resource('pembayaranasuransi', 'PembayaranAsuransiController')->except("show");
	Route::post('pembayaranasuransi/store/{id}', 'PembayaranAsuransiController@store');
	
	// piutang pelanggan
	Route::resource('piutangpelanggan', 'PiutangPelangganController')->except("show");
	Route::get('piutangpelanggan/{id?}/show', 'PiutangPelangganController@show');
	Route::post('piutangpelanggan/{id?}/filtershow', 'PiutangPelangganController@filtershow');
	Route::get('piutangpelanggan/{id?}/cetak_pdf', 'PiutangPelangganController@cetak');
    Route::get('piutangpelanggan/cetaksemuadata', 'PiutangPelangganController@cetaksemua');
    Route::get('piutangpelanggan/cetaklunas', 'PiutangPelangganController@cetaklunas');
    Route::get('piutangpelanggan/cetakbelumlunas', 'PiutangPelangganController@cetakbelumlunas');

	//piutang cabang
	Route::resource('piutangcabang', 'PiutangController')->except("show");
	
	// pembayaran order
	Route::resource('pembayaran', 'PembayaranController')->except("show");
	Route::get('pembayaran/{id?}/konfirmasi', 'PembayaranController@konfirmasi');
	Route::get('pembayaran/{id?}/show', 'PembayaranController@show');
	Route::post('pembayaran/create', 'PembayaranController@create');
	Route::get('pembayaran/{id?}/bayar', 'PembayaranController@bayar');
	Route::post('pembayaran/store/{id}', 'PembayaranController@store');
	Route::get('pembayaran/{id?}/print', 'PembayaranController@print');
	Route::get('pembayaran/filter', 'PembayaranController@index');
	Route::get('pembayaran/cetakall', 'PembayaranController@cetak')->name('cetakallpembayaran');
	
	// set bayar packing
	Route::resource('bayarpacking', 'BayarPackingController')->except("show");
	// biaya hpp
	Route::resource('biayahpp', 'BiayaHppController')->except("show");
	Route::get('biayahpp/{id}/detail', 'BiayaHppController@show');
	Route::get('biayahpp/{id}/bayar', 'BiayaHppController@bayar');
	Route::get('biayahpp/{id}/print', 'BiayaHppController@print');
	Route::get('biayahpp/{id}/setbayar', 'BiayaHppController@bayar');
	Route::post('biayahpp/approve/{id}', 'BiayaHppController@approve');
	Route::post('biayahpp/batalapprove/{id}', 'BiayaHppController@batalapprove');
	Route::post('biayahpp/konfirmasi/{id}', 'BiayaHppController@konfirmasi');
	Route::post('biayahpp/approvevendor/{id}', 'BiayaHppController@approvevendor');
	Route::get('biayahpp/filter', 'BiayaHppController@index');
	Route::get('biayahpp/{id}/listbayar', 'BiayaHppController@listbayar');
	Route::get('biayahpp/{id}/cetakbayar', 'BiayaHppController@cetakbayar');
	Route::post('biayahpp/{id}/updatebayar', 'HppVendorController@updatebayar');
	
	// biaya hpp vendor
	Route::resource('biayahppvendor', 'HppVendorController')->except("show");
	Route::get('biayahppvendor/{id}/detail', 'HppVendorController@show');
	Route::get('biayahppvendor/{id}/bayar', 'HppVendorController@bayar');
	Route::get('biayahppvendor/{id}/print', 'HppVendorController@print');
	Route::get('biayahppvendor/{id}/setbayar', 'HppVendorController@bayar');
	Route::post('biayahppvendor/approve/{id}', 'HppVendorController@approve');
	
	Route::post('biayahppvendor/konfirmasi/{id}', 'HppVendorController@konfirmasi');
	Route::post('biayahppvendor/batalapprove/{id}', 'HppVendorController@batalapprove');

	Route::get('biayahppvendor/{id}/listbayar', 'HppVendorController@listbayar');
	Route::post('biayahppvendor/{id}/updatebayar', 'HppVendorController@updatebayar');

	Route::post('biayahppvendor/confirmbayar/{id}', 'HppVendorController@confirmbayar');
	Route::post('biayahppvendor/batalbayar/{id}', 'HppVendorController@batalbayar');
	Route::get('biayahppvendor/filter', 'HppVendorController@index');

	// biaya handling
	Route::resource('biayahandling', 'HandlingHppController')->except("show");
	Route::get('biayahandling/{id}/bayar', 'HandlingHppController@bayar');
	Route::post('biayahandling/{id}/savebiaya', 'HandlingHppController@savebiaya');
	Route::post('biayahandling/{id}/updatebiaya', 'HandlingHppController@updatebiaya');
	Route::delete('biayahandling/{id}/deletebiaya', 'HandlingHppController@deletebiaya');

	Route::get('biayahandling/{id}/show', 'HandlingHppController@show');
	Route::post('biayahandling/filter', 'HandlingHppController@filter');
	Route::get('biayahandling/filter', 'HandlingHppController@filter');

	//Invoice Handling
	Route::resource('invoicehandling', 'InvoiceHandlingController')->except("show");
	Route::get('invoicehandling/{id?}/show', 'InvoiceHandlingController@show');
	Route::get('invoicehandling/{id?}/send', 'InvoiceHandlingController@send');
	Route::get('invoicehandling/{id?}/batalkirim', 'InvoiceHandlingController@batalkirim');
	Route::get('invoicehandling/{id?}/cetak', 'InvoiceHandlingController@CetakInvoice');
	Route::get('invoicehandling/{id?}/proyeksi', 'InvoiceHandlingController@proyeksi');
	Route::post('invoicehandling/{id?}/savebiaya', 'InvoiceHandlingController@savebiaya');
	Route::put('invoicehandling/{id?}/updatebiaya', 'InvoiceHandlingController@updatebiaya');
	Route::delete('invoicehandling/{id?}/deletebiaya', 'InvoiceHandlingController@deletebiaya');
	Route::post('invoicehandling/{id?}/kirim', 'InvoiceHandlingController@kirim');
	Route::get('invoicehandling/{id?}/showbayar', 'InvoiceHandlingController@showbayar');
	Route::put('invoicehandling/{id?}/konfirmasibayar', 'InvoiceHandlingController@konfirmasibayar');
	Route::get('invoicehandling/getDm/{id}', 'InvoiceHandlingController@getDm');
	Route::get('invoicehandling/reset', 'InvoiceHandlingController@reset');
	Route::post('invoicehandling/filter', 'InvoiceHandlingController@filter');
	Route::get('invoicehandling/filter', 'InvoiceHandlingController@filter');
	Route::post('invoicehandling/page', 'InvoiceHandlingController@page');
	Route::get('invoicehandling/page', 'InvoiceHandlingController@page');
	Route::get('invoicehandling/{id?}/ttd', 'InvoiceHandlingController@ttd');
	Route::get('invoicehandling/generatettd/{id?}', 'InvoiceHandlingController@generatettd');
	Route::post('invoicehandling/savettd', 'InvoiceHandlingController@savettd');

	// invoice handling terima
	Route::resource('invoicehandlingterima', 'InvoiceHandlingTerimaController')->except("show");
	Route::get('invoicehandlingterima/{id?}/show', 'InvoiceHandlingTerimaController@show');
	Route::post('invoicehandlingterima/{id?}/terima', 'InvoiceHandlingTerimaController@terima');
	Route::get('invoicehandlingterima/{id?}/showbayar', 'InvoiceHandlingTerimaController@showbayar');
	Route::get('invoicehandlingterima/{id?}/cetak', 'InvoiceHandlingTerimaController@cetakInvoice');
	Route::get('invoicehandlingterima/{id?}/ttd', 'InvoiceHandlingTerimaController@ttd');
	Route::post('invoicehandlingterima/savettd', 'InvoiceHandlingTerimaController@savettd');
	Route::post('invoicehandlingterima/filter', 'InvoiceHandlingTerimaController@filter');
	Route::get('invoicehandlingterima/filter', 'InvoiceHandlingTerimaController@filter');
	
	//Proyeksi
	Route::resource('proyeksi', 'ProyeksiController')->except("show");
	Route::get('proyeksi-by-tahun', 'ProyeksiController@SettingByTahun');
	Route::post('proyeksi-by-tahun/save', 'ProyeksiController@saveSettingByTahun');
	Route::post('proyeksi/editsaldo', 'ProyeksiController@editsaldo');
	Route::get('proyeksi/generate', 'ProyeksiController@generate');

	//Budgeting
	Route::resource('budgeting', 'BudgetingController')->except("show");
	Route::get('budgeting/data', 'BudgetingController@data')->name('budgeting-data');
	Route::post('budgeting/update-budgeting', 'BudgetingController@updateBudgeting');
	Route::post('budgeting/delete-budgeting', 'BudgetingController@deleteBudgeting');
	Route::post('budgeting/copy-budgeting', 'BudgetingController@copyBudgeting');
	Route::get('laporanbudgeting', 'BudgetingController@LaporanBudgeting');
	Route::get('laporanbudgeting/cetak', 'BudgetingController@cetakLaporanBudgeting')->name('cetak-laporan-budgeting');
	
	// setting limit piutang
	Route::resource('limitpiutang', 'SettingLimitPiutangController');
	Route::get('ceklimit/{id}', 'SettingLimitPiutangController@ceklimit');
	
	// Stt Belom Bayar
	Route::get('sttbelumbayar', 'PembayaranController@sttbelumbayar');
	Route::get('sttbelumbayar/cetak', 'PembayaranController@cetaksttbelumlunas');
	Route::get('sttbelumbayar/filter', 'PembayaranController@sttbelumbayar');
	
	// for laporan module
	//Laporan Pendapatan
	Route::get('laporan', 'LaporanPendapatanController@laporan');
	Route::resource('laporanpendapatan', 'LaporanPendapatanController')->except("show");
	//Laporan Pengeluaran
	Route::resource('laporanpengeluaran', 'LaporanPengeluaranController')->except("show");
	//Laporan Pembayaran
	Route::resource('laporanpembayaran', 'LaporanPembayaranController')->except("show");
	//Laporan Biaya Hpp
	Route::resource('laporanbiayahpp', 'LaporanBiayaHppController')->except("show");
	
	// proyeksi piutang
	Route::resource('proyeksipiutang', 'ProyeksiPiutangController');
	Route::post('proyeksipiutang/{id}/savedetail', 'ProyeksiPiutangController@savedetail');
	Route::post('proyeksipiutang/{id}/deletedetail', 'ProyeksiPiutangController@deletedetail');
	
	Route::get('repproyeksipiutang', 'ProyeksiPiutangController@repproyeksipiutang');
	Route::get('repproyeksipiutang/{id}/reppdetail', 'ProyeksiPiutangController@reppdetail');

	// Invoice Pelanggan
	Route::resource('invoice', 'InvoiceController');
	Route::get('invoice/{id?}/show', 'InvoiceController@show');
	Route::get('invoice/{id?}/stt', 'InvoiceController@tambahstt');
	Route::post('invoice/{id?}/stt', 'InvoiceController@tambahstt');
	Route::post('invoice/{id?}/savedraft', 'InvoiceController@savedraft');
	Route::post('invoice/{id?}/hapusdraft', 'InvoiceController@hapusdraft');

	// penerbitan invoice
	Route::get('invoice/{id?}/batal', 'InvoiceController@batal');
	Route::get('invoice/{id?}/send', 'InvoiceController@send');
	Route::post('invoice/bayar', 'InvoiceController@bayar');
	Route::post('invoice/{id?}/bayarall', 'InvoiceController@bayarall');
	Route::post('invoice/bayarstt', 'InvoiceController@bayarstt');
	Route::post('invoice/setppn', 'InvoiceController@setppn');
	Route::get('invoice/{id?}/cetak', 'InvoiceController@CetakInvoice');
});
