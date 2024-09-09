<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Panggil SendMail yang telah dibuat
use App\Mail\SendMail;
// Panggil support email dari Laravel
use Illuminate\Support\Facades\Mail;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\SttModel;
use App\Perusahaan;
use DB;
use Illuminate\Support\Facades\Redirect;
use App\Model\Pelanggan;
use Exception;

class EmailController extends Controller
{
    public function index($id,$id_perush)
    {
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        
        for ($i=0; $i < count($id); $i++) {
            $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id[$i])->get()->first();
            $data["detail"] = HistoryStt::with("status")->where("id_stt", $id[$i])->get();
            $pelanggan = Pelanggan::findOrFail($data["data"]->id_plgn);
            $nama = $pelanggan->nm_pelanggan;
            $email = $pelanggan->email;
            $kirim = Mail::to($email)->send(new SendMail($nama,$data,$email));
            
            if($kirim){
                echo "Email telah dikirim";
            }
        }   
    }

    public function tes()
    {
        try {
            $data = "tes";
            $nama = "Irsyad";
            $email = "cfcyoga@gmail.com";
            $kirim = Mail::to($email)->send(new SendMail($nama,$data,$email));
            
            if($kirim){
                echo "Email telah dikirim";
            }
        } catch (Exception $e) {
            echo "pesan ".$e->getMessage();
        }
    }

    public function wa()
    {
        $data = [
            'phone' => '+6289699480617', // Receivers phone
            'body' => 'Hello, Tes Whatsapp!', // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        $token = 'jhlzect7zym1y93k';
        $instanceId = '203340';
        $url = 'https://eu127.chat-api.com/instance'.$instanceId.'/message?token='.$token;
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);
        echo $result;
        }    
}
