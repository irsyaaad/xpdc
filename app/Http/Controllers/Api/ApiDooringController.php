<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use DB;
use Illuminate\Http\Request;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\Notifikasi;
use Modules\Operasional\Entities\SttModel;

class ApiDooringController extends Controller
{

    public function get_stt(Request $request)
    {
        $request->validate([
            'LSJ-API-KEY' => 'required',
            'ID_PERUSH_TUJU' => 'required',
            'NOSTT' => 'required',
        ]);

        $data = DaftarMuat::get_stt_dm_tiba($request->ID_PERUSH_TUJU, $request->NOSTT);
        $result = [];

        if (count($data) > 0) {
            $data_stt = [];
            foreach ($data as $key => $data) {
                $data_stt[] = [
                    'ID_STT' => $data->kode_stt,
                    'NO_AWB' => $data->no_awb,
                    'TGL_MSUK' => $data->tgl_masuk,
                    'ID_PERUSH_ASAL' => $data->id_perush_asal,
                    'ID_PERUSH_TUJU' => $data->id_perush_tj,
                    'CABANG_ASAL' => $data->perusahaan_asal,
                    'KODE_CABANG_ASAL' => '',
                    'CABANG_TUJUAN' => $data->perusahaan_tujuan,
                    'KODE_CABANG_TUJUAN' => 'VLP',
                    'PENGIRIM_NM' => $data->pengirim_nm,
                    'PENGIRIM_TELP' => $data->telp,
                    'PENGIRIM_ALM' => $data->pengirim_alm,
                    'PENGIRIM_KODEPOS' => $data->pengirim_kodepos,
                    'KOTA_ASAL' => $data->asal,
                    'PENGIRIM_ID_REGION' => $data->pengirim_id_region,
                    'TERIMA_NM' => $data->penerima_nm,
                    'TERIMA_TELP' => $data->penerima_telp,
                    'TERIMA_ALM' => $data->penerima_alm,
                    'TERIMA_KODEPOS' => $data->penerima_kodepos,
                    'KOTA_TUJUAN' => $data->tujuan,
                    'TERIMA_ID_REGION' => $data->penerima_id_region,
                    'N_KIRIM' => $data->n_berat . 'KG',
                    'N_KOLI' => $data->n_koli,
                ];
            }
            $result = [
                'status' => 1,
                'message' => 'success',
                'data' => [
                    'data_stt' => $data_stt,
                ],
            ];
        } else {
            $result = [
                'status' => 0,
                'message' => 'failed',
                'data' => [],
            ];
        }

        return response()->json($result);
    }

    public function update_stt(Request $request)
    {
        $request->validate([
            'LSJ-API-KEY' => 'required',
            'ID_PERUSH_TUJU' => 'required',
            'NOSTT' => 'required',
            'KODE_STATUS' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $stt = SttModel::where('kode_stt', $request->NOSTT)->first();
            $history = [];
            $history['id_stt'] = $stt->id_stt;
            $history["id_status"] = $request->KODE_STATUS == 'SSK' ? 7 : 6;
            $history["kode_status"] = $request->KODE_STATUS == 'SSK' ? 7 : 15;
            $history["id_user"] = 1;
            $history["keterangan"] = isset($request->INFO_STATUS) ? $request->INFO_STATUS : null;
            $history["nm_status"] = $request->KODE_STATUS == 'SSK' ? 'Berhasil Sampai Di Alamat Tujuan' : 'PROSES DOORING KE ALAMAT PENERIMA';
            $history["place"] = null;
            $history["id_wil"] = null;
            $history["tgl_update"] = date("Y-m-d H:i:s");
            $history["nm_user"] = $request->NAMA_SUPIR;
            $history["nm_penerima"] = isset($request->NAMA_PENERIMA) ? $request->NAMA_PENERIMA : null;
            $history["created_at"] = date("Y-m-d H:i:s");
            $history["updated_at"] = date("Y-m-d H:i:s");
            $history["foto_dooring"] = $request->FOTO_DOORING;

            HistoryStt::insert($history);

            $sttUpdate = SttModel::findOrFail($stt->id_stt);
            $sttUpdate->id_status = $request->KODE_STATUS == 'SSK' ? 7 : 6;
            $sttUpdate->save();

            $perusahaan = Perusahaan::findOrFail($stt->id_perush_asal);

            $pesan = "Hi {$stt->pengirim_nm}, \n";
            $pesan .= "STT : {$stt->kode_stt}, untuk {$stt->penerima_nm} telah *{$history["nm_status"]}* pada tanggal : " . dateindo(date("Y-m-d H:i:s"));
            $pesan .= "\n\n - " . $perusahaan->nm_perush . " -";
            $pesan .= "\n\n_Pesan ini dikirim otomatis oleh sistem_";
            $pesan .= "\n_Informasi detail klik";
            if (!empty($perusahaan->website)) {
                $pesan .= " {$perusahaan->website}";
            }
            $pesan .= "_\n_Customer Support {$perusahaan->telp_cs}_";

            $notifikasi = new Notifikasi();
            $notifikasi->pesan = $pesan;
            $notifikasi->id_user = 1;
            $notifikasi->id_perush = $perusahaan->id_perush;
            $notifikasi->device_id = $perusahaan->device_id;
            $notifikasi->is_kirim = false;
            $notifikasi->no_hp_customer = detect_chat_id($stt->pengirim_telp);
            $notifikasi->foto = isset($request->FOTO_DOORING) ? $request->FOTO_DOORING : null;

            $notifikasi->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        $result = [
            'status' => DB::transactionLevel() == 0 ? true : false,
            'message' => 'success',
            'data' => [
                'data_stt' => SttModel::where('kode_stt', $request->NOSTT)->first(),
            ],
        ];

        return response()->json($result);
    }

}
