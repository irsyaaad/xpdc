<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\SettingJam;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use DB;
use Auth;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Session;
use Modules\Kepegawaian\Entities\MesinFinger;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InsertAbsensi extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'insert:absensi';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Insert All Data in File Absensi to Database';

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
        $this->store();
    }

    public function syncabsensi()
    {
        $date_now = date("Y-m-d");
        $date_yesterday = date('Y-m-d', strtotime('-1 days', strtotime( $date_now )));
        $file_name = "Absensi.txt";
        $path = File::get(storage_path('app/' . $file_name));

        if(!$file_name){
            echo "not exists";
        }

        $karyawan = Absensi::AllKaryawan();
        $a_karyawan = [];
        foreach($karyawan as $key => $value){
            $a_karyawan[$value->cloud_id."/".$value->id_finger] = $value;
        }

        $a_json = json_decode($path, true);
        $d_data = [];
        foreach($a_json as $key => $value){
            $id = explode("/", $key);
            $tgl_absen = date("Y-m-d", strtotime($id[0]));
            $id_finger = $id[1];
            $id_mesin = $id[2];
            // $key_id = $id_mesin."/".$id_finger."/".$tgl_absen;

            $d_data[$tgl_absen."/".$value["pin"]."/".$id_mesin][$key] = date("H:i:s", strtotime($value["scan_date"]));
        }

        $a_absen = [];
        foreach($d_data as $key => $value){
            $id = explode("/", $key);
            $id_mesin = $id[2];
            $tgl_absen = date("Y-m-d", strtotime($id[0]));
            $id_finger = $id[1];
            $key_id = $id_mesin."/".$id_finger;
            $day = strtolower(date("D", strtotime($tgl_absen)));
            if(isset($a_karyawan[$key_id])){

                $karyawan = $a_karyawan[$key_id];
                $id_absen = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));

                // $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();

                $s_jam_masuk = $karyawan->jam_masuk;
                $s_jam_terlambat = $karyawan->jam_terlambat;
                $s_jam_pulang = $karyawan->jam_pulang;
                $s_jam_toleransi = $karyawan->jam_toleransi;

                $s_jam_istirahat = $karyawan->jam_istirahat;
                $s_jam_istirahat_masuk = $karyawan->jam_istirahat_masuk;
                $s_jam_sabtu = $karyawan->jam_sabtu;

                $jam_datang = "";
                $jam_pulang = "";

                $status_datang = 0;
                $status_pulang = 0;
                $status_istirahat = 0;
                $status_istirahat_masuk = 0;

                $jam_istirahat = 0;
                $jam_istirahat_masuk = 0;

                $jam = date("H:i", strtotime('+45 minutes', strtotime($s_jam_istirahat)));
                $jam2 = date("H:i", strtotime('+5 minutes', strtotime($s_jam_istirahat_masuk)));

                $status = 0;
                foreach($value as $key2 => $value2){
                    if($value2>$s_jam_masuk and  $value2 < $s_jam_toleransi){
                        $jam_datang = $value2;
                    }
                    if($karyawan->is_sopir == 1){
                        if($value2>$s_jam_istirahat and $value2<=$jam){
                            $jam_istirahat = $value2;
                        }else{
                            $jam_istirahat = $jam;
                        }

                        if($value2>$jam and $value2<=$jam2){
                            $jam_istirahat_masuk = $value2;
                        }else{
                            $jam_istirahat_masuk = $jam2;
                        }

                    }else{
                        if($value2>=$s_jam_istirahat and $value2<$jam){
                            $jam_istirahat = $value2;
                        }
                        if($value2>=$jam and $value2<$jam2){
                            $jam_istirahat_masuk = $value2;
                        }
                    }

                    $day = strtolower(date("D", strtotime($tgl_absen)));
                    if($day=="sat" and $value2 >= $s_jam_sabtu){
                        $jam_pulang = $value2;
                    }elseif($day!="sat" and $value2 >= $s_jam_sabtu){
                        $jam_pulang = $value2;
                    }
                }

                    $jam2 = date("H:i", strtotime('+15 minutes', strtotime($s_jam_istirahat_masuk)));

                    if($jam_istirahat==""){
                        $status_istirahat = 2;
                    }

                    if($jam_istirahat_masuk==""){
                        $status_istirahat_masuk = 2;
                    }elseif($jam_istirahat_masuk != "" and $jam_istirahat_masuk > $jam2 and $jam_istirahat_masuk < $s_jam_pulang){
                        $status_istirahat_masuk = 1;
                    }

                if($jam_datang < $s_jam_masuk){
                    $status_datang = 1;
                }

                if($jam_datang>$s_jam_masuk and $jam_datang<$s_jam_terlambat){
                    $status_datang = 0;
                }

                if($jam_datang > $s_jam_terlambat and $jam_datang < $s_jam_toleransi){
                    $status_datang = 2;
                }

                if($jam_pulang < $s_jam_pulang){
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

                if($jam_datang > $s_jam_toleransi){
                    $status = 0;
                }

                $day = strtolower(date("D", strtotime($tgl_absen)));
                if($day=="sat" and $jam_pulang >= $s_jam_sabtu){
                    $status_pulang = 0;
                }
                if($jam_datang==""){
                    $jam_datang = "00:00:00";
                }

                if($jam_pulang==""){
                    $jam_pulang = "00:00:00";
                }

                if($jam_istirahat==""){
                    $jam_istirahat = "00:00:00";
                }

                if($jam_istirahat_masuk==""){
                    $jam_istirahat_masuk = "00:00:00";
                }

                if($jam_datang < $s_jam_toleransi){
                    $a_absen[$id_absen]["id_absen"] = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));
                    $a_absen[$id_absen]["id_karyawan"] = $karyawan->id_karyawan;
                    $a_absen[$id_absen]["id_perush"] = $karyawan->id_perush;
                    $a_absen[$id_absen]["status_datang"] = $status_datang;
                    $a_absen[$id_absen]["status_pulang"] = $status_pulang;
                    $a_absen[$id_absen]["status"] = $status;
                    $a_absen[$id_absen]["id_finger"] = $id_finger;

                    $a_absen[$id_absen]["tgl_absen"] = date("Y-m-d", strtotime($tgl_absen));
                    $a_absen[$id_absen]["jam_datang"] = date("H:i:s", strtotime($jam_datang));
                    $a_absen[$id_absen]["jam_pulang"] = date("H:i:s", strtotime($jam_pulang));

                    $a_absen[$id_absen]["jam_istirahat"] = date("H:i:s", strtotime($jam_istirahat));
                    $a_absen[$id_absen]["jam_istirahat_masuk"] = date("H:i:s", strtotime($jam_istirahat_masuk));

                    $a_absen[$id_absen]["status_istirahat"] = $status_istirahat;
                    $a_absen[$id_absen]["status_istirahat_masuk"] = $status_istirahat_masuk;

                    $a_absen[$id_absen]["created_at"] = date("Y-m-d H:i:s");
                    $a_absen[$id_absen]["updated_at"] = date("Y-m-d H:i:s");
                }
            }
        }
        // dd($a_absen);
        return $a_absen;
    }

    public function store()
    {
        $a_data = $this->syncabsensi();
        // dd($a_data);
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
                $absen["jam_istirahat"] = $value["jam_istirahat"];
                $absen["jam_istirahat_masuk"] = $value["jam_istirahat_masuk"];
                $absen["status_istirahat"] = $value["status_istirahat"];
                $absen["status_istirahat_masuk"] = $value["status_istirahat_masuk"];
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
            Log::info('Insert Data to Database');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Absen Gagal Ditarik '.$e->getMessage());
        }
    }
}
