<?php

//Neraca
Route::get('neraca', 'NeracaController@index');
Route::get('neraca/cetak/pdf', 'NeracaController@cetak')->name('cetakneraca');
Route::get('neraca/cetak/excel', 'NeracaController@excel')->name('excelneraca');
Route::get('neraca/show', 'NeracaController@show')->name('showneraca');
Route::get('neraca/showdetail', 'NeracaController@showdetail')->name('showneracadetail');
Route::get('neraca/cetakdetailtransaksi', 'NeracaController@cetakdetailtransaksi')->name('cetaktransaksikeuangan');

//NeracaByDetail
Route::get('neracadetail', 'NeracaByDetailController@index');
Route::get('neracadetail/cetak/pdf', 'NeracaByDetailController@cetak')->name('cetakneracadetail');
Route::get('neracadetail/cetak/excel', 'NeracaByDetailController@excel');

//Rugi Laba
Route::get('rugilaba', 'RugiLabaController@index');
Route::get('rugilaba/show', 'RugiLabaController@show')->name('showrugilaba');
Route::get('rugilaba/showdetail', 'NeracaController@showdetail')->name('showrugilabadetail');
Route::get('rugilaba/cetak/pdf', 'RugiLabaController@cetak')->name('cetakrugilaba');
Route::get('rugilaba/cetak/excel', 'RugiLabaController@excel')->name('excelrugilaba');

//Master Cashflow
Route::resource('mastercashflow', 'MasterCashFlowController')->except("show");
Route::post('mastercashflow/filter', 'MasterCashFlowController@filter');
Route::get('mastercashflow/filter', 'MasterCashFlowController@filter');

Route::resource('mastercashflowperush', 'MasterCashflowPerushController')->except("show");
Route::get('mastercashflowperush/generate', 'MasterCashflowPerushController@generate');

//Cash Flow
Route::get('cashflow', 'CashFlowController@index');
Route::get('cashflow/show', 'CashFlowController@show')->name('showcashflow');
Route::get('cashflow/{id}/showdetail', 'CashFlowController@showdetail');
Route::get('cashflow/cetak/pdf', 'CashFlowController@cetak')->name('cetakcashflow');
Route::get('cashflow/cetak/excel', 'CashFlowController@excel')->name('excelcashflow');

//Cash Flow Detail
Route::get('cashflowdetail', 'CashFlowController@CashFlowDetail');
Route::get('cashflowdetail/cetak/pdf', 'CashFlowController@cetakcashflowdetail');
Route::get('cashflowdetail/cetak/excel', 'CashFlowController@excelcashflowdetail');

//Cash Flow Harian
Route::get('cashflowharian', 'CashFlowController@CashflowHarian');
Route::get('cashflowharian/cetak/pdf', 'CashFlowController@cetakcashflowharian');
Route::get('cashflowharian/cetak/excel', 'CashFlowController@excelcashflowharian')->name('excelcashflow');

//Jurnal
Route::resource('jurnal', 'JurnalController')->except("show");
Route::post('jurnal/filter', 'JurnalController@filter');
Route::get('jurnal/filter', 'JurnalController@filter');
Route::get('jurnal/cetak', 'JurnalController@cetak')->name('cetakjurnal');
Route::get('jurnal/excel', 'JurnalController@excel');

//Buku Besar
Route::resource('bukubesar', 'BukuBesarController')->except("show");
Route::get('bukubesar/{id}/show', 'BukuBesarController@show');
Route::get('bukubesar/{id}/showdetail', 'BukuBesarController@showdetail');
Route::get('bukubesar/showdetail', 'NeracaController@showdetail')->name('showbukubesar');

//NeracaByPerkiraan
Route::get('neracabyperkiraan', 'NeracaController@NeracaByPerkiraan');
Route::post('neracabyperkiraan/filter', 'NeracaController@filterNeracaByPerkiraan');
Route::get('neracabyperkiraan/filter', 'NeracaController@filterNeracaByPerkiraan');
Route::get('neracabyperkiraan/cetak', 'NeracaController@cetakNeracaByPerkiraan');
Route::get('neracabyperkiraan/excel', 'NeracaController@excelNeracaByPerkiraan');

//RugiLabaPerkiraan
Route::get('rugilababyperkiraan', 'NeracaController@RugiLabaByPerkiraan');
Route::post('rugilababyperkiraan/filter', 'NeracaController@filterRugiLabaByPerkiraan');
Route::get('rugilababyperkiraan/filter', 'NeracaController@filterRugiLabaByPerkiraan');
Route::get('rugilababyperkiraan/cetak', 'NeracaController@cetakRugiLabaByPerkiraan');
Route::get('rugilababyperkiraan/excel', 'NeracaController@excelRugiLabaByPerkiraan');

//RugiLabaPertahun
Route::get('rugilabapertahun', 'RugiLabaTahunanController@index');
Route::post('rugilabapertahun/filter', 'RugiLabaTahunanController@filter');
Route::get('rugilabapertahun/filter', 'RugiLabaTahunanController@filter');
Route::get('rugilabapertahun/cetak', 'RugiLabaTahunanController@cetak')->name('cetakrugilabapertahun');
Route::get('rugilabapertahun/excel', 'RugiLabaTahunanController@excel')->name('excelrugilabapertahun');

// RugiLaba Konsolidasi
Route::get('rugilabakonsolidasi', 'RugiLabaKonsolidasiController@index');
Route::post('rugilabakonsolidasi/filter', 'RugiLabaKonsolidasiController@filter');
Route::get('rugilabakonsolidasi/filter', 'RugiLabaKonsolidasiController@filter');
Route::get('rugilabakonsolidasi/cetak', 'RugiLabaKonsolidasiController@cetak')->name('cetakrugilabakonsolidasi');
Route::get('rugilabakonsolidasi/excel', 'RugiLabaKonsolidasiController@excel')->name('excelrugilabakonsolidasi');

//RugiLabaProyeksi
Route::get('rugilabaproyeksi', 'RugiLabaProyeksiController@index');
Route::get('rugilabaproyeksi/filter', 'RugiLabaProyeksiController@filter');
Route::post('rugilabaproyeksi/filter', 'RugiLabaProyeksiController@filter');
Route::get('rugilabaproyeksi/cetak', 'RugiLabaProyeksiController@cetak')->name('cetakrugilabaproyeksi');
Route::get('rugilabaproyeksi/excel', 'RugiLabaProyeksiController@excel')->name('excelrugilabaproyeksi');

Route::get('rugilabadetail', 'RugiLabaByDetailController@index')->name("rugilabadetail");
Route::get('rugilabadetail/cetak/pdf', 'RugiLabaByDetailController@cetak')->name('cetakrugilabadetail');
Route::get('rugilabadetail/cetak/excel', 'RugiLabaByDetailController@excel');

//Tutup Buku
Route::resource('tutupbuku', 'TutupBukuController')->except("show");
Route::get('tutupbuku/tutupbuku', 'TutupBukuController@store');
Route::post('tutupbuku/savesaldo', 'TutupBukuController@savesaldo');
Route::post('tutupbuku/editsaldo', 'TutupBukuController@editsaldo');
Route::post('tutupbuku/filter', 'TutupBukuController@filter');
Route::get('tutupbuku/filter', 'TutupBukuController@filter');

#Omset
//Stt By Cara Bayar
Route::get('sttbycarabayar', 'OmsetController@SttCaraBayar');

//Stt by Users
Route::get('sttbyusers', 'OmsetController@byUsers');

//Stt by DM
Route::get('sttbydm', 'OmsetController@byDM');

// Omset by Tarif
Route::get('omsetbytarif', 'OmsetController@byTarif');


//Stt by Pelanggan
Route::get('omsetbypelanggan', 'OmsetController@byPelanggan');
Route::post('omsetbypelanggan/filter', 'OmsetController@filterbyPelanggan');
Route::get('omsetbypelanggan/filter', 'OmsetController@filterbyPelanggan');
Route::get('omsetbypelanggan/cetak', 'OmsetController@cetakByPelanggan')->name('cetak-bypelanggan');
Route::get('omsetbypelanggan/excel', 'OmsetController@cetakOmsetByPelangganexcel');

//Stt by Group Pelanggan
Route::get('bygrouppelanggan', 'OmsetController@byGroupPelanggan');
Route::post('bygrouppelanggan/filter', 'OmsetController@filterbygrouppelanggan');
Route::get('bygrouppelanggan/filter', 'OmsetController@filterbygrouppelanggan');
Route::get('bygrouppelanggan/cetak', 'OmsetController@cetakOmsetByGroupPelanggan')->name('cetak-bygrouppelanggan');
Route::get('bygrouppelanggan/excel', 'OmsetController@cetakOmsetByGroupPelangganexcel');

//Omset By Cara Bayar
Route::get('omsetbycarabayar', 'OmsetController@OmsetbyCaraBayar');

//Biaya By DM
Route::get('biayabydm', 'OmsetController@BiayaByDM');
Route::get('biayabydm/cetak', 'OmsetController@cetakBiayaByDM');
Route::get('biayabydm/excel', 'OmsetController@cetakBiayaByDMexcel');
Route::get('biayabydm/show', 'OmsetController@BiayaByDMshow')->name('showbiayabydm');

//Biaya By DM
Route::get('biayabydmvendor', 'OmsetController@BiayaByDMVendor');
Route::get('biayabydmvendor/show', 'OmsetController@BiayaByDMshowvendor')->name('showbiayabydmvendor');

//Omset Vs Biaya
Route::get('omsetvsbiaya', 'OmsetController@ProyeksiBiayaVsOmset');
Route::post('omsetvsbiaya/filter', 'OmsetController@ProyeksiBiayaVsOmset');
Route::get('omsetvsbiaya/filter', 'OmsetController@ProyeksiBiayaVsOmset');
Route::get('omsetvsbiaya/cetak', 'OmsetController@cetakOmsetVsBiaya')->name('cetakomsetvsbiaya');
Route::get('omsetvsbiaya/excel', 'OmsetController@cetakOmsetVsBiayaexcel');

//Omset By Tipe Kirim
Route::get('omsetbytipekirim', 'OmsetController@OmsetByTipeKirim');
Route::get('omsetbytipekirim/{id}/show', 'OmsetController@showOmsetByTipeKirim');


//Omset By Layanan
Route::get('omsetbylayanan', 'OmsetController@OmsetByLayanan');
Route::get('omsetbylayanan/{id}/show', 'OmsetController@showOmsetByLayanan');

//Stt By Region
Route::get('sttbyregion', 'OmsetController@SttByRegion');
Route::get('printbyregion', 'OmsetController@printbyregion');
Route::get('sttbyregion/{id}/show', 'OmsetController@showSttByRegion');

// Omset By Region
Route::get('omsetbyregion', 'OmsetController@OmsetByRegion');
Route::get('omsetbyregion/detail', 'OmsetController@detailOmsetByRegion')->name('detail-omset-by-region');

//Stt Ada AWB
Route::get('sttadaawb', 'OmsetController@SttAdaAWB');
Route::get('sttadaawb/{id}/show', 'OmsetController@showSttAdaAWB');

//Prestasi Penagihan
Route::get('prestasipenagihanomset', 'OmsetController@PrestasiPenagihan');
Route::get('prestasipenagihanomset/cetak', 'OmsetController@cetakPrestasiPenagihan')->name('cetak-prestasi-penagihan');

//Prestasi Marketing
Route::get('prestasimarketing', 'IndexPrestasiController@PrestasiMarketing');
Route::get('detailprestasimarketing', 'IndexPrestasiController@detailprestasimarketing');

// Analisa Customer Retention
Route::get('analisapelanggan', 'IndexPrestasiController@AnalisaPelanggan');
Route::post('analisapelanggan/filter', 'IndexPrestasiController@AnalisaPelanggan');
Route::get('analisapelanggan/detail', 'IndexPrestasiController@DetailAnalisaPelanggan')->name('detail-analisa-pelanggan');
Route::get('analisapelanggan/detail-aktif', 'IndexPrestasiController@AnalisaPelangganAktif')->name('detail-pelanggan-aktif');
Route::get('analisapelanggan/cetak-pelanggan-aktif', 'IndexPrestasiController@CetakAnalisaPelangganAktif')->name('cetak-pelanggan-aktif');
Route::get('analisapelanggan/cetak', 'IndexPrestasiController@CetakAnalisaPelanggan')->name('cetak-analisa-pelanggan');
Route::get('analisapelanggan/excel', 'IndexPrestasiController@ExcelAnalisaPelanggan')->name('excel-analisa-pelanggan');

//Lama Hari Stt
Route::get('lamaharistt', 'OmsetController@LamaHariSTT');
Route::post('lamaharistt/filter', 'OmsetController@filterlamaharistt');
Route::get('lamaharistt/filter', 'OmsetController@filterlamaharistt');
Route::get('lamaharistt/cetak', 'OmsetController@cetakLamaHariStt')->name('cetak-lama-hari-stt');
Route::get('lamaharistt/excel', 'OmsetController@cetakLamaHariSttexcel');

//Lama Hari Stt
Route::get('lamaharisttbygroup', 'OmsetController@LamaHariSTTbyGroup');
Route::post('lamaharisttbygroup/filter', 'OmsetController@filterlamaharisttbygroup');
Route::get('lamaharisttbygroup/filter', 'OmsetController@filterlamaharisttbygroup');
Route::get('lamaharisttbygroup/cetak', 'OmsetController@cetakLamaHariSttbyGroup');
Route::get('lamaharisttbygroup/excel', 'OmsetController@cetakLamaHariSttbyGroupexcel');

//Omset by TipeKirim
Route::get('omsetbytipekirim', 'OmsetController@OmsetByTipeKirim');
Route::post('omsetbytipekirim/filter', 'OmsetController@filteromsetvsbiaya');
Route::get('omsetbytipekirim/filter', 'OmsetController@filteromsetvsbiaya');
Route::get('omsetbytipekirim/cetak', 'OmsetController@cetakOmsetVsBiaya');
Route::get('omsetbytipekirim/excel', 'OmsetController@cetakOmsetVsBiayaexcel');

//Omset Vs Cash In
Route::resource('omsetvscashin', 'OmsetVsCashInController')->except("show");
Route::post('omsetvscashin/filter', 'OmsetVsCashInController@index');
Route::get('omsetvscashin/filter', 'OmsetVsCashInController@index');
Route::get('omsetvscashin/show', 'OmsetVsCashInController@show')->name('show-saldo-awal-piutang');
Route::get('omsetvscashin/showtotalomset', 'OmsetVsCashInController@showTotalOmset')->name('show-total-omset');

//Biaya By DM
Route::get('omzetvsbiayadmvendor', 'ReportController@index');
Route::get('omzetvsbiayadmvendor/{id}', 'ReportController@show');

//Hutang Vendor
Route::get('hutangvendor', 'OmsetController@HutangVendor');
Route::get('hutangvendor/detail', 'OmsetController@DetailHutangVendor')->name('detailhutangvendor');

// SLA
Route::get('slastt', 'SLAController@index');
Route::get('slastt/detail', 'SLAController@detail')->name('show-detail-sla-dm-trucking');
Route::get('sladmvendor', 'SLAController@DmVendor');
Route::get('sladmvendor/detail', 'SLAController@DmVendorDetail')->name('show-detail-sla-dm-vendor');
