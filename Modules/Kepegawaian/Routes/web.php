<?php

Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {
	Route::resource('kepegawaian', 'KepegawaianController');

	Route::resource('jamkerja', 'SettingJamController');
	Route::get('jamkerja/filter', 'SettingJamController@filter');
	Route::post('jamkerja/filter', 'SettingJamController@filter');

	Route::resource('absensi', 'AbsensiController')->except("show");
	Route::get('absensi/filter', 'AbsensiController@index');
	Route::get('absensi/getkaryawan/{id}', 'AbsensiController@getkaryawan');
	Route::get('absensi/testing', 'AbsensiController@testing');
	Route::get('absensi/syncabsensi', 'AbsensiController@syncabsensi');
	Route::get('absensi/inject', 'AbsensiController@inject');
	Route::post('absensi/inject', 'AbsensiController@inject');
	Route::post('absensi/{id}/pindah', 'AbsensiController@pindah');
	Route::post('absensi/download-by-mesin', 'AbsensiController@download_by_mesin');
	Route::get('absensi/log', 'AbsensiController@Log')->name('show-log');

	Route::resource('jenisperijinan', 'JenisPerijinanController')->except("show");
	Route::get('jenisperijinan/{id}/getjenis', 'JenisPerijinanController@getjenis');

	// perijinan
	Route::resource('perijinan', 'PerijinanController')->except("show");
	Route::get('perijinan/filter', 'PerijinanController@index');
	Route::get('perijinan/{id?}/konfirmasi', 'PerijinanController@konfirmasi');
	Route::post('perijinan/allkonfirmasi', 'PerijinanController@allkonfirmasi');
	Route::get('perijinan/refresh', 'PerijinanController@refresh');
	Route::get('perijinan/creategroup', 'PerijinanController@creategroup');
	Route::post('perijinan/creategroup', 'PerijinanController@creategroup');
	Route::post('perijinan/savegroup', 'PerijinanController@savegroup');
	Route::get('perijinan/{id}/getdetail', 'PerijinanController@getdetail');
	Route::get('perijinan/{id}/terima', 'PerijinanController@terima');
	Route::get('perijinan/{id}/tolak', 'PerijinanController@tolak');

	// laporan perijinan
	Route::get('laporanperijinan', 'PerijinanController@Laporan');
	Route::get('laporanperijinan/{id?}/konfirmasi', 'PerijinanController@konfirmasi');
	Route::get('laporanperijinan/{id?}/detail', 'PerijinanController@detail');
	Route::get('laporanperijinan/filter', 'PerijinanController@Laporan');
	Route::get('laporanperijinan/cetak', 'PerijinanController@cetaklaporan');
	Route::get('laporanperijinan/excel', 'PerijinanController@excellaporan');

	// laporan statistik kehadiran
	Route::get('statistikkehadiran', 'AbsensiController@statistik');
	Route::get('statistikkehadiran/filter', 'AbsensiController@statistik');
	Route::get('statistikkehadiran/cetak', 'AbsensiController@cetakstatistik');
	Route::get('statistikkehadiran/excel', 'AbsensiController@excelstatistik');

	// laporan kehadiran
	Route::get('laporankehadiran', 'AbsensiController@laporan');
	Route::get('laporankehadiran/filter', 'AbsensiController@laporan');
	Route::get('laporankehadiran/cetak', 'AbsensiController@cetaklaporankehadiran');
	Route::get('laporankehadiran/excel', 'AbsensiController@excellaporankehadiran');

	// laporan prosentase
	Route::get('prosentasekehadiran', 'AbsensiController@prosentase');
	Route::post('prosentasekehadiran', 'AbsensiController@prosentase');

	// laporan absensi
	Route::get('laporanabsensi', 'AbsensiController@laporanabsensi');
	Route::get('laporanabsensi/filter', 'AbsensiController@laporanabsensi');
	Route::get('laporanabsensi/cetak', 'AbsensiController@cetaklaporanabsensi');
	Route::get('laporanabsensi/excel', 'AbsensiController@excellaporanabsensi');

	// setting jumlah denda
	Route::resource('settingdenda', 'SettingDendaController')->except("show");
	Route::get('settingdenda/filter', 'SettingDendaController@index');
	Route::post('settingdenda/copy', 'SettingDendaController@copy');

	Route::get('dendakehadiran', 'SettingDendaController@denda');
	Route::get('dendakehadiran/filter', 'SettingDendaController@denda');
	Route::get('dendakehadiran/cetak', 'SettingDendaController@cetakdenda');
	Route::get('dendakehadiran/excel', 'SettingDendaController@exceldenda');

	// setting hari libur
	Route::resource('settingharilibur', 'SettingHariLiburController')->except("show");
	Route::get('settingharilibur/filter', 'SettingHariLiburController@index');
	Route::post('settingharilibur/copy', 'SettingHariLiburController@copy');

	// laporan jam bekerja
	Route::get('laporanjamkerja', 'SettingJamController@jamkerja');
	Route::get('laporanjamkerja/filter', 'SettingJamController@jamkerja');
	Route::get('laporanjamkerja/cetak', 'SettingJamController@cetakjamkerja');
	Route::get('laporanjamkerja/excel', 'SettingJamController@exceljamkerja');
	Route::get('jamkehadiran', 'SettingJamController@jamkehadiran');
	Route::get('laporanjamkerja/allcabang', 'SettingJamController@allcabang');

	// laporan jam kerja cabang
	Route::get('jamkerjacabang', 'SettingJamController@jamkerjacabang');
	Route::get('jamkerjacabang/filter', 'SettingJamController@jamkerjacabang');

	// mesin finger from cloud
	Route::resource('mesinfinger', 'MesinFingerController')->except("show");
	Route::get('mesinfinger/filter', 'MesinFingerController@filter');
	Route::post('mesinfinger/filter', 'MesinFingerController@filter');
	//Jabatan
	Route::resource('jabatan', 'JabatanController')->except("show");

	//Gaji Karyawan
	Route::resource('gajikaryawan', 'GajiKaryawanController')->except("show");
	Route::post('gajikaryawan/generate', 'GajiKaryawanController@generate');
	Route::get('gajikaryawan/generate', 'GajiKaryawanController@generate');
	Route::post('gajikaryawan/generate-denda', 'GajiKaryawanController@generate_denda');
	Route::get('gajikaryawan/generate-denda', 'GajiKaryawanController@generate_denda');
	Route::get('gajikaryawan/filter', 'GajiKaryawanController@filter');
	Route::post('gajikaryawan/filter', 'GajiKaryawanController@filter');
	Route::get('gajikaryawan/cetak', 'GajiKaryawanController@cetak')->name('cetakgaji');
	Route::get('gajikaryawan/cetakall', 'GajiKaryawanController@cetakall')->name('cetakgajiall');
	Route::get('gajikaryawan/excel', 'GajiKaryawanController@excel')->name("excelgaji");
	Route::get('gajikaryawan/excelall', 'GajiKaryawanController@excelall')->name("excelallgaji");
	Route::get('gajikaryawan/{id?}/slipgaji', 'GajiKaryawanController@slipgaji');
	Route::get('gajikaryawan/{id}/detail', 'GajiKaryawanController@show');
	Route::post('gajikaryawan/approve', 'GajiKaryawanController@approve');
	Route::get('gajikaryawan/refresh', 'GajiKaryawanController@refresh');
	Route::get('rekap-gajikaryawan', 'GajiKaryawanController@rekapGaji');
	Route::get('rekap-gajikaryawan/cetak', 'GajiKaryawanController@cetakRekapGaji')->name('cetak-rekap-gaji');

	//Status Karyawan
	Route::resource('statuskaryawan', 'StatusKaryawanController')->except("show");
	//Status Karyawan
	Route::resource('marketing', 'MarketingController')->except("show");
	Route::get('marketing/filter', 'MarketingController@index');
	Route::get('getmarketing/{id}', 'MarketingController@getMarketing');

	//Laporan Satus Karyawan
	Route::get('laporanstatuskaryawan', 'StatusKaryawanController@laporanStatusKaryawan');
	Route::get('laporanstatuskaryawan/filter', 'StatusKaryawanController@laporanStatusKaryawan');

	// piutang karyawan
	Route::resource('piutangkaryawan', 'PiutangKaryawanController')->except("show");
	Route::post('piutangkaryawan/{id}/bayar', 'PiutangKaryawanController@bayar');
	Route::post('piutangkaryawan/{id}/edit-bayar', 'PiutangKaryawanController@edit_bayar');
	Route::post('piutangkaryawan/{id}/approve', 'PiutangKaryawanController@approve');
	Route::get('piutangkaryawan/{id}/detail', 'PiutangKaryawanController@show');
	// get mesin finger
	Route::get('getmesinfinger/{id}', 'MesinFingerController@getMesinFinger');
	Route::get('getJamKerja/{id}', 'SettingJamController@getJamKerja');

	Route::resource('penilaiankpi', 'MasterPenilaianControlller');
	Route::resource('objectivekpi', 'ObjectiveController');

	// busdev
	Route::resource('hargavendor', 'HargaVendorController')->except("show");
	Route::get('hargavendor/{id}/detail', 'HargaVendorController@show');
	Route::post('hargavendor/{id}/savedetail', 'HargaVendorController@savedetail');
	Route::put('hargavendor/{id}/updatedetail', 'HargaVendorController@updatedetail');
	Route::get('hargavendor/{id}/getdetail', 'HargaVendorController@getdetail');
	Route::post('hargavendor/{id}/import', 'HargaVendorController@import');
	Route::get('hargavendor/{id}/templatedirect', 'HargaVendorController@templatedirects');
	Route::delete('hargavendor/{id}/deletedirect', 'HargaVendorController@destroydirect');


	// laporan busdev
	Route::get('laporanimporbusdev', 'HargaVendorController@laporanimpor');
	Route::get('hargavendor/{id}/cetaklaporanimpor', 'HargaVendorController@cetakimport');
	Route::get('riwayatpencarian', 'HargaVendorController@riwayatcari');
	
	// direct import
	Route::get('datadirect', 'HargaVendorController@datadirect');
	Route::get('batchdeleteform', 'HargaVendorController@deleteform');
	Route::post('hargavendor/{id}/importbaru', 'HargaVendorController@importbaru');
	Route::post('hargavendor/{id}/batchdeleting', 'HargaVendorController@batchdelete');


	// Activity Marketing
	Route::resource('activity-marketing', 'MarketingActivityController')->except('show');
	Route::get('activity-marketing/{id}/show', 'MarketingActivityController@show');
});

/// without checkin middleware
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
	Route::resource('suratperingatan', 'SuratPeringatanController')->except("show");
	Route::get('suratperingatan/{id}/cetak', 'SuratPeringatanController@cetak');
});

Route::group(['prefix' => 'public'], function () {
	Route::get('jamkerja', 'PublicController@jamkerja');
	Route::post('attlog', 'GetAttLogController@save_log')->name('getattlog');
	Route::get('attlog', 'GetAttLogController@save_log')->name('getattlog');
	Route::get('image', 'GetAttLogController@show_image')->name('show_image');
});
