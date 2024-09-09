<?php

namespace App\Traits;

use Auth;
use DB;
use Modules\Asuransi\Entities\JurnalAsuransi;

trait SaveToJurnalAsuransi
{
    public function save_to_jurnal($setting_pelanggan = null, $setting_perusahaan = null, $asuransi = null, $tgl_transaksi = null)
    {
        // dd($asuransi);
        try {
            DB::beginTransaction();
            $jurnalAsuransi = [];
            $jurnalAsuransi[0]['id_debet'] = $setting_pelanggan->ac_piutang;
            $jurnalAsuransi[0]['id_kredit'] = $setting_pelanggan->ac_pendapatan;
            $jurnalAsuransi[0]['nominal'] = $asuransi->nominal_jual;
            $jurnalAsuransi[0]['info_debet'] = 'Piutang Asuransi STT NO ' . $asuransi->id_stt;
            $jurnalAsuransi[0]['info_kredit'] = 'Pendapatan Asuransi STT NO ' . $asuransi->id_stt;
            $jurnalAsuransi[0]['table_reference'] = 't_asuransi';
            $jurnalAsuransi[0]['id_referance'] = $asuransi->id_asuransi;
            $jurnalAsuransi[0]['kode_referance'] = $asuransi->id_stt;
            $jurnalAsuransi[0]['id_user'] = Auth::user()['id_user'];
            $jurnalAsuransi[0]['tgl_transaksi'] = isset($tgl_transaksi) ? $tgl_transaksi : date('Y-m-d');

            $jurnalAsuransi[1]['id_debet'] = $setting_perusahaan->ac_biaya;
            $jurnalAsuransi[1]['id_kredit'] = $setting_perusahaan->ac_hutang;
            $jurnalAsuransi[1]['nominal'] = $asuransi->nominal_beli;
            $jurnalAsuransi[1]['info_debet'] = 'Biaya Asuransi STT NO ' . $asuransi->id_stt;
            $jurnalAsuransi[1]['info_kredit'] = 'Hutang Asuransi STT NO ' . $asuransi->id_stt;
            $jurnalAsuransi[1]['table_reference'] = 't_asuransi';
            $jurnalAsuransi[1]['id_referance'] = $asuransi->id_asuransi;
            $jurnalAsuransi[1]['kode_referance'] = $asuransi->id_stt;
            $jurnalAsuransi[1]['id_user'] = Auth::user()['id_user'];
            $jurnalAsuransi[1]['tgl_transaksi'] = isset($tgl_transaksi) ? $tgl_transaksi : date('Y-m-d');
            // dd($jurnalAsuransi);
            JurnalAsuransi::insert($jurnalAsuransi);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }

    public function delete_from_jurnal($table_reference, $id_referance)
    {
        try {
            DB::beginTransaction();
            JurnalAsuransi::where("table_reference", $table_reference)
                ->where("id_referance", $id_referance)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }

    public function save_pay_to_jurnal($setting = null, $asuransi = null, $akun = null, $tgl_transaksi)
    {
        // dd($setting, $asuransi);
        try {
            DB::beginTransaction();
            $jurnalAsuransi = [];
            $jurnalAsuransi[0]['id_debet'] = $akun;
            $jurnalAsuransi[0]['id_kredit'] = $setting->ac_piutang;
            $jurnalAsuransi[0]['nominal'] = $asuransi->n_bayar;
            $jurnalAsuransi[0]['info_debet'] = $asuransi->info;
            $jurnalAsuransi[0]['info_kredit'] = $asuransi->info;
            $jurnalAsuransi[0]['table_reference'] = 't_asuransi_pay';
            $jurnalAsuransi[0]['id_referance'] = $asuransi->id_asuransi_pay;
            $jurnalAsuransi[0]['kode_referance'] = $asuransi->no_kwitansi;
            $jurnalAsuransi[0]['id_user'] = Auth::user()['id_user'];
            $jurnalAsuransi[0]['tgl_transaksi'] = isset($tgl_transaksi) ? date('Y-m-d') : $tgl_transaksi;
            // dd($jurnalAsuransi);
            JurnalAsuransi::insert($jurnalAsuransi);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }

    public function save_pay_biaya_to_jurnal($setting = null, $asuransi = null, $akun = null, $tgl_transaksi)
    {
        // dd($setting, $asuransi);
        try {
            DB::beginTransaction();
            $jurnalAsuransi = [];
            $jurnalAsuransi[0]['id_debet'] = $setting->ac_hutang;
            $jurnalAsuransi[0]['id_kredit'] = $akun;
            $jurnalAsuransi[0]['nominal'] = $asuransi->n_bayar;
            $jurnalAsuransi[0]['info_debet'] = $asuransi->info;
            $jurnalAsuransi[0]['info_kredit'] = $asuransi->info;
            $jurnalAsuransi[0]['table_reference'] = 't_asuransi_biaya_pay';
            $jurnalAsuransi[0]['id_referance'] = $asuransi->id_asuransi_biaya_pay;
            $jurnalAsuransi[0]['kode_referance'] = $asuransi->no_bayar;
            $jurnalAsuransi[0]['id_user'] = Auth::user()['id_user'];
            $jurnalAsuransi[0]['tgl_transaksi'] = isset($tgl_transaksi) ? date('Y-m-d') : $tgl_transaksi;
            // dd($jurnalAsuransi);
            JurnalAsuransi::insert($jurnalAsuransi);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }

    public function save_jurnal_to_table_jurnal($id_debet, $id_kredit, $nominal, $info, $jenis, $id, $kode, $tgl_transaksi)
    {
        switch ($jenis) {
            case 'masuk':
                $table_reference = 'keu_pendapatan_det';
                break;
            case 'keluar':
                $table_reference = 'keu_pengeluaran_det';
                break;
            case 'memo':
                $table_reference = 'keu_memorial';
                break;
            default:
                $table_reference = 'keu_pendapatan_det|keu_pengeluaran_det|keu_memorial';
                break;
        }

        try {
            DB::beginTransaction();
            $jurnalAsuransi = [];
            $jurnalAsuransi[0]['id_debet'] = $id_debet;
            $jurnalAsuransi[0]['id_kredit'] = $id_kredit;
            $jurnalAsuransi[0]['nominal'] = $nominal;
            $jurnalAsuransi[0]['info_debet'] = $info;
            $jurnalAsuransi[0]['info_kredit'] = $info;
            $jurnalAsuransi[0]['table_reference'] = $table_reference;
            $jurnalAsuransi[0]['id_referance'] = $id;
            $jurnalAsuransi[0]['kode_referance'] = $kode;
            $jurnalAsuransi[0]['id_user'] = Auth::user()['id_user'];
            $jurnalAsuransi[0]['tgl_transaksi'] = isset($tgl_transaksi) ? date('Y-m-d') : $tgl_transaksi;
            // dd($jurnalAsuransi);
            JurnalAsuransi::insert($jurnalAsuransi);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }
}
