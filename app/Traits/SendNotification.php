<?php

namespace App\Traits;

use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Illuminate\Support\Facades\Log;

trait SendNotification
{
    public function send_to_group($id_stt)
    {
        $stt = SttModel::with("asal", "tujuan")->where('id_stt', $id_stt)->first();
        $perusahaan = Perusahaan::findOrFail($stt->id_perush_asal);
        $whastapp_group = isset($perusahaan->whatsapp_group) ? $perusahaan->whatsapp_group : 15948;
        $message = '*STT* dengan : \n';
        $message .= 'Kode STT : ' . $stt->kode_stt . ' \n';
        $message .= 'Nama Pengirim : ' . $stt->pengirim_nm . ' \n';
        $message .= 'Asal : ' . $stt->asal->nama_wil . ' \n';
        $message .= 'Tujuan : ' . $stt->tujuan->nama_wil . ' \n';
        $message .= 'Koli : ' . $stt->n_koli . ' \n';
        $message .= 'Berat :' . $stt->n_berat . 'Kg ';
        $message .= '' . $stt->n_volume . 'Kgv ';
        $message .= '' . $stt->n_kubik . 'M3 \n';
        $message .= 'Omset : ' . toRupiah($stt->c_total) . ' \n';
        $message .= 'Telah dibuat oleh ' . $stt->user->nm_user . ', dimohon kpd Team Operasional untuk menindaklanjuti STT tersebut \n\n';
        $message .= '_Pesan ini dikirim otomatis oleh Sistem Operasional Lsj Express_';

        // $message = trim(preg_replace('/\n+/', '\n', $message));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.whatspie.com/groups/' . $whastapp_group . '/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "message": "Send Notification",
            "type": "chat",
            "params": {
                "text": "' . $message . '",
                "mentions": [
                "62856123456@s.whatsapp.net"
                ]
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json; charset=utf-8',
                'Authorization: Bearer 571398cbf06cd891286cf93bb1a221696ff25f320631b956099ef7cd7f651841',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;

        Log::info('Send Notification to Group ...');
        return $stt;
    }
}
