<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Operasional\Entities\Notifikasi;

class KirimNotifikasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:notifikasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim Notifikasi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendNotification();
    }

    public function sendNotification()
    {
        $batasan_waktu = 180; // 3 menit;
        $time_start = microtime(true);
        $data = Notifikasi::where('is_kirim', false)->get();

        if (!empty($data) && count($data) > 0) {

            try {
                DB::beginTransaction();

                foreach ($data as $key => $value) {
                    if (!empty($value->device_id)) {
                        /**
                         * Sementara kirim ke Nomerku dulu.
                         *
                         * @return mixed
                         */
                        if (isset($value->foto)) {
                            // $hasil = $this->sendImage(6289699480617, $value->pesan, $value->device_id, $value->foto);
                            $hasil = $this->sendImage($value->no_hp_customer, $value->pesan, $value->device_id, $value->foto);
                        } else {
                            // $hasil = $this->sendsms2wa(6289699480617, $value->pesan, $value->device_id);
                            $hasil = $this->sendsms2wa($value->no_hp_customer, $value->pesan, $value->device_id);
                        }

                        $hasil = json_decode($hasil);
                        // dd($hasil);
                        Log::info('Device ' . $value->device_id . ' Message : ' . json_encode($hasil));
                        if ($hasil->status == 200 || $hasil->status == 500) {
                            $notif = Notifikasi::findOrFail($value->id);
                            $notif->is_kirim = true;
                            $notif->save();
                        }
                    }

                    $current_time = microtime(true);
                    $interval = $current_time - $time_start;

                    if ($interval >= $batasan_waktu) {
                        break;
                    }
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                echo $e->getMessage();
            }
        } else {
            echo 'No Notification Has been sent ...';
        }

        Log::info('Send Notification Has been Running ...');
    }

    public function sendsms2wa($nomor, $isi = "", $device)
    {
        $isi = trim(preg_replace('/\n+/', '\n', $isi));
        $key = '571398cbf06cd891286cf93bb1a221696ff25f320631b956099ef7cd7f651841';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.whatspie.com/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "device": "' . $device . '",
                "receiver": "' . $nomor . '",
                "type": "chat",
                "message": "' . $isi . '",
                "simulate_typing": 1
              }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $key,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $isi . PHP_EOL . $response . PHP_EOL;
        // print_r($response);
        return $response;
    }

    public function sendImage($nomor, $isi = "", $device, $foto)
    {
        $arr_foto = json_decode($foto);
        $isi = trim(preg_replace('/\n+/', '\n', $isi));
        $key = '571398cbf06cd891286cf93bb1a221696ff25f320631b956099ef7cd7f651841';

        foreach ($arr_foto as $value) {
            $foto = 'https://app.lsjexpress.co.id/public/image?url=' . $value;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.whatspie.com/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "device": "' . $device . '",
                    "receiver": "' . $nomor . '",
                    "type": "image",
                    "message": "' . $isi . '",
                    "file_url": "' . $foto . '",
                    "simulate_typing": 1
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer ' . $key,
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;
        }

        return $response;
    }

    public function list_group()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.whatspie.com/groups?device=6282320000091',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer 571398cbf06cd891286cf93bb1a221696ff25f320631b956099ef7cd7f651841',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
