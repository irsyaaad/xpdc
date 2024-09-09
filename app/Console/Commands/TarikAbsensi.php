<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

class TarikAbsensi extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'tarik:absensi';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Tarik Absensi Otomatis';

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
        //echo "uji coba cron";
        $this->testing();
        // $this->Lala();

        //$this->insertData();
    }

    public function insertData()
    {
        $date_now = date("Y-m-d");
        $date_yesterday = date('Y-m-d', strtotime('-1 days', strtotime( $date_now )));

        $mesin = Mesinfinger::select("id_mesin", "id_perush")->get();

        $a_data = [];
        foreach($mesin as $key => $value){
            $data = $this->getCurl($value->id_mesin, $date_yesterday,$date_now);
            $a_absen = $this->arrGet($data, $value->id_perush, $value->id_mesin);

            if($a_absen!=null){
                foreach($a_absen as $key1 => $value1){
                    $a_data[$key1] = $value1;
                }
            }
        }

        DB::beginTransaction();

        try {
            // insert into absen

            foreach($a_data as $key => $value){
                $absen = [];
                $cek = Absensi::where("id_absen", $value["id_absen"])->get()->first();

                $absen["id_absen"] = $value["id_absen"];
                $absen["id_karyawan"] = $value["id_karyawan"];
                $absen["id_perush"] = $value["id_perush"];
                $absen["status_datang"] = $value["status_datang"];
                $absen["status_pulang"] = $value["status_pulang"];
                $absen["status"] = $value["status"];
                $absen["id_finger"] = $value["id_finger"];
                $absen["tgl_absen"] = $value["tgl_absen"];
                $absen["jam_datang"] = $value["jam_datang"];
                $absen["jam_pulang"] = $value["jam_pulang"];
                $absen["created_at"] = $value["created_at"];
                $absen["updated_at"] = $value["updated_at"];

                // jika ada absensi akan di update
                if($cek){
                    Absensi::where("id_absen", $absen["id_absen"])->update($absen);
                }

                // jika belum ada absensi akan dibuatkan baru
                if(!$cek){
                    Absensi::insert($absen);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }

    }

    public function arrGet($data, $id_perush, $id_mesin)
    {
        $a_data = [];
        foreach($data as $key => $value){
            $tgl = date("Y-m-d", strtotime($value["scan_date"]));
            $a_data[$tgl."/".$value["pin"]][$key] = date("H:i:s", strtotime($value["scan_date"]));
        }
        $d_data = [];
        foreach($a_data as $key => $value){
            $id = explode("/", $key);
            $karyawan = Karyawan::where("id_finger", $id[1])->where("id_mesin", $id_mesin)->where("id_perush", $id_perush)->get()->first();

            if($karyawan!=null){
                $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();
                if($setting!=null){
                    $id_finger = $id[1];
                    $tgl_absen = $id[0];
                    $jam_datang = "";
                    $jam_pulang = "";
                    $status_datang = 0;
                    $status_pulang = 0;
                    $status = 0;

                    foreach($value as $key2 => $value2){
                        if($value2<$setting->jam_masuk){
                            $jam_datang = $value2;
                        }

                        if($value2>$setting->jam_masuk and $value2<$setting->jam_terlambat){
                            $jam_datang = $value2;
                        }

                        if($value2 > $setting->jam_terlambat and $value2 < $setting->jam_toleransi){
                            $jam_datang = $value2;
                        }

                        if($value2>$setting->jam_istirahat and $value2<$setting->jam_pulang){
                            $jam_pulang = $value2;
                        }

                        if($value2>$setting->jam_pulang){
                            $jam_pulang = $value2;
                        }

                    }

                    if($jam_datang < $setting->jam_masuk){
                        $status_datang = 1;
                    }

                    if($jam_datang>$setting->jam_masuk and $jam_datang<$setting->jam_terlambat){
                        $status_datang = 0;
                    }

                    if($jam_datang > $setting->jam_terlambat and $jam_datang < $setting->jam_toleransi){
                        $status_datang = 2;
                    }

                    if($jam_pulang < $setting->jam_pulang){
                        $status_pulang = 5;
                    }

                    if($jam_datang=="" and $jam_pulang!=""){
                        $status_datang = 3;
                    }

                    if($jam_datang!="" and $jam_pulang==""){
                        $status_pulang = 4;
                    }

                    if($jam_datang=="" and $jam_pulang==""){
                        $status = 1;
                    }

                    if($jam_datang!="" and $jam_pulang!=""){
                        $status = 0;
                    }

                    if($jam_datang > $setting->jam_toleransi){
                        $status = 0;
                    }

                    $day = strtolower(date("D", strtotime($tgl_absen)));
                    if($day=="sat" and $jam_pulang >= $setting->jam_sabtu){
                        $status_pulang = 0;
                    }

                    if($jam_datang==""){
                        $jam_datang = "00:00:00";
                    }

                    if($jam_pulang==""){
                        $jam_pulang = "00:00:00";
                    }

                    if($jam_datang < $setting->jam_toleransi){
                        $d_data[$key]["id_absen"] = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));
                        $d_data[$key]["id_karyawan"] = $karyawan->id_karyawan;
                        $d_data[$key]["id_perush"] = $karyawan->id_perush;
                        $d_data[$key]["status_datang"] = $status_datang;
                        $d_data[$key]["status_pulang"] = $status_pulang;
                        $d_data[$key]["status"] = $status;
                        $d_data[$key]["id_finger"] = $id_finger;
                        $d_data[$key]["tgl_absen"] = date("Y-m-d", strtotime($tgl_absen));
                        $d_data[$key]["jam_datang"] = date("H:i:s", strtotime($jam_datang));
                        $d_data[$key]["jam_pulang"] = date("H:i:s", strtotime($jam_pulang));
                        $d_data[$key]["created_at"] = date("Y-m-d H:i:s");
                        $d_data[$key]["updated_at"] = date("Y-m-d H:i:s");
                    }
                }
            }
        }

        return $d_data;
    }

    public function getCurl($id_mesin, $start, $end)
    {
        $tgl1= (String)$start;
        $tgl2= (String)$end;

        $mesin = MesinFinger::findOrFail($id_mesin);

        $url = 'http://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"'.$mesin->id_mesin.'", "cloud_id":"'.$mesin->cloud_id.'", "start_date":"'.$tgl1.'", "end_date":"'.$tgl2.'"}';
        $authorization = "Authorization: Bearer ".$mesin->authorization;

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
        if(!isset($data["data"])){
            return redirect()->back()->with('error', 'Data Finger tidak ada');
        }
        $data = $data["data"];

        $a_result = [];
        foreach($data as $key => $value){
            $a_result[$key] = $value;
            $a_result[$key]["id_mesin"] = $mesin->id_mesin;
        }
        return $a_result;
    }

    public function testing()
    {
        $mesin = Absensi::AllMesinFinger();
        $date_now = date("Y-m-d");
        // $date_now = "2022-11-05";
        $date_yesterday = date('Y-m-d', strtotime('-1 days', strtotime( $date_now )));
        $a_data = [];

        $id = 0;
        foreach ($mesin as $key => $value) {
            $url = 'http://developer.fingerspot.io/api/get_attlog';
            $data = '{"trans_id":"'.$key.'", "cloud_id":"'.$value->cloud_id.'", "start_date":"'.$date_yesterday.'", "end_date":"'.$date_now.'"}';
            $authorization = "Authorization: Bearer ".$value->authorization;

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
            echo count($data["data"])."<br>";
            foreach ($data["data"] as $key2 => $value2) {
                $date = date("YmdHis", strtotime($value2["scan_date"]));
                $a_data[$date."/".$value2["pin"]."/".$value->cloud_id]["cloud_id"] = $value->cloud_id;
                $a_data[$date."/".$value2["pin"]."/".$value->cloud_id]["pin"] = $value2["pin"];
                $a_data[$date."/".$value2["pin"]."/".$value->cloud_id]["scan_date"] = $value2["scan_date"];
                $id++;
            }
        }

        $a_data = json_encode($a_data, true);
        $file_name = "Absensi.txt";
        Storage::put($file_name, $a_data);
        Log::info('Create File');
    }

    public function Lala()
    {
        $tgl1= date("2023-08-03");
        $tgl2= date("2023-08-03");

        $mesin = MesinFinger::findOrFail(1);

        $url = 'http://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"'.$mesin->id_mesin.'", "cloud_id":"'.$mesin->cloud_id.'", "start_date":"'.$tgl1.'", "end_date":"'.$tgl2.'"}';
        $authorization = "Authorization: Bearer ".$mesin->authorization;
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
        // print_r($data);

        foreach ($data["data"] as $key => $value) {
            $a_data[$value["pin"]][]=$value["scan_date"];
        }
        print_r($a_data);
        $a_data = json_encode($a_data, true);
        $file_name = "Absensi.txt";
        Storage::put($file_name, $a_data);
        Log::info('Create File');

    }
}
