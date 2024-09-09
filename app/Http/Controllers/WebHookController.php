<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\SettingJam;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Perijinan;
use App\Libraries\Excel_reader;
use App\Libraries\SpreadsheetReader;
use App\Libraries\SimpleXLSX;
use DB;
use Auth;
use Exception;
use File;
use Illuminate\Support\Facades\Storage;
use App\User;
use Hash;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use DateTime;
use Carbon\Carbon;
use Session;
use Modules\Kepegawaian\Entities\MesinFinger;
use Validator;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function webhookHandler(Request $request){
        $originalData = file_get_contents('php://input');
        $decodedData = json_decode($originalData, true);
        
        $file_name = "AttLog.txt";
        // Storage::put($file_name, json_encode($request->all()));
        Storage::append($file_name, json_encode($decodedData));
        Log::info('Update file');
        
        Log::info('Webhook received', $decodedData);
        
        // Process the webhook data as needed
        
        // Return a response
        return response()->json(['success' => true]);
    }
    
    public function getData($id = null) {
        $mesins  = MesinFinger::where("cloud_id", $id)->get();
        $mesinId    = $mesins->pluck('id_mesin');
        $karyawan   = $this->getKaryawan($mesinId);
        $mapKaryawan = [];
        
        $absensi = $this->tarikAbsensi($id);
        
        foreach ($karyawan as $key => $value) {
            if (isset($value->id_finger)) {
                $mapKaryawan[$value->id_finger] = $value;
            }
        }
        dd($karyawan);
    }
    
    private function getKaryawan($mesinId) {
        return DB::table('m_karyawan as kry')
        ->select('kry.id_karyawan','kry.id_finger', 'kry.nm_karyawan','jam.jam_masuk','jam.jam_pulang','jam.jam_terlambat')
        ->join('s_jam_kerja as jam','kry.id_jam_kerja','=','jam.id_setting')
        ->whereIn('kry.id_mesin', $mesinId)
        ->whereNotNull('kry.id_finger')
        ->get();
    }
    
    private function tarikAbsensi($cloudId) {
        $tgl1= date("2023-08-29");
        $tgl2= date("2023-08-29");
        $url = 'http://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"'.$cloudId.'", "start_date":"'.$tgl1.'", "end_date":"'.$tgl2.'"}';
        $authorization = "Authorization: Bearer HLT79UO4FK3AHF2C";
        $a_data = []; $b_data = [];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($result, true);
        $dataMap = [];
        foreach ($data['data'] as $key => $value) {
            $date = strtotime($value['scan_date']);
            $date = date('H:i:s', $date);
            $dataMap[$value['pin']][] = $date;
        }
        return $dataMap;
    }
}
