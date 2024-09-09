<?php

namespace Modules\Kepegawaian\Http\Controllers;

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
use Modules\Kepegawaian\Entities\SettingHariLibur;

class AbsensiController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = $request->shareselect!=null?$request->shareselect:50;
        $dt = date("Y-m-")."01";
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date("Y-m-d", strtotime($dt));
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:date("Y-m-t");
        $id_perush = $request->f_id_perush!=null?$request->f_id_perush:Session("perusahaan")["id_perush"];
        $id_karyawan = $request->f_id_karyawan!=null?$request->f_id_karyawan:null;
        
        $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, $id_perush, true, $id_karyawan);
        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        }else{
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $data["data"] = $absen->paginate($page);
        $data["page"] = $page;
        $data["mesin"] = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["filter"] = array("dr_tgl" => $dr_tgl, "sp_tgl" => $sp_tgl, "id_perush" => $id_perush, "f_id_karyawan" => $id_karyawan, "page" => $page);
        $data["karyawan"] = Karyawan::getKaryawanShift($id_perush);
        $data["pkaryawan"] = Karyawan::where("is_aktif", true)->OrderBy("nm_karyawan", "asc")->get();
        $data['list_mesin'] = [
            'C26740C20B1D0E35' => 'KANTOR REWWIN',
            'C2696422DF1F312C' => 'KANTOR BREBEK',
            'C2696422DF133037' => 'KANTOR PERAK',
            'C2622D141F293B24' => 'KANTOR MARGOMULYO'
        ];

        return view('kepegawaian::absensi', $data);
    }
    
    public function pindah($id, Request $request) {
        try {
            
            DB::beginTransaction();
            $a_absen =[];
            $a_absen["id_perush"] = $request->p_id_perush;
            Absensi::where("id_karyawan", $request->p_id_karyawan)->update($a_absen);
            
            Perijinan::where("id_karyawan", $request->p_id_karyawan)->update($a_absen);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput($request->input());
        }

        return redirect()->back()->with('success', 'Data Karyawan Berhasil di Pindah');
    }

    public function log(Request $request)
    {
        $id_karyawan = $request->id_karyawan;
        $tgl_absen = $request->tgl_absen;

        $karyawan = Karyawan::join('s_mesin_finger', 's_mesin_finger.id_mesin', '=', 'm_karyawan.id_mesin')
            ->join('s_perusahaan', 's_perusahaan.id_perush', '=', 'm_karyawan.id_perush')
            ->select('m_karyawan.*', 's_mesin_finger.id_mesin', 's_mesin_finger.authorization', 's_mesin_finger.cloud_id', 's_mesin_finger.nm_mesin' ,'s_perusahaan.nm_perush')
            ->where('m_karyawan.id_karyawan', $id_karyawan)
            ->first();

        $url = 'http://developer.fingerspot.io/api/get_attlog';
        $send = '{"trans_id":"'. 1 . '", "cloud_id":"' . $karyawan->cloud_id . '", "start_date":"'.$tgl_absen.'", "end_date":"'.$tgl_absen.'"}';
        $authorization = "Authorization: Bearer ".$karyawan->authorization;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $data_mesin = json_decode($result, true);
        $finger = [];
        foreach ($data_mesin['data'] as $key => $value) {
            if ($value['pin'] == $karyawan->id_finger) {
                $finger[] = $value;
            }
        }

        $data['finger'] = $finger;
        $data['karyawan'] = $karyawan;
        // dd($data);
        return view('kepegawaian::absensi.log-finger', $data);
    }

    
    public function testing()
    {
        $mesin = Absensi::AllMesinFinger();
        $date_now = date("Y-m-d");
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
            $key_id = $id_mesin."/".$id_finger."/".$tgl_absen;

            $d_data[$key_id][$key] = $value["scan_date"];
        }

        $a_absen = [];
        foreach($d_data as $key => $value){
            $id = explode("/", $key);
            $id_mesin = $id[0];
            $tgl_absen = date("Y-m-d", strtotime($id[2]));
            $id_finger = $id[1];
            $key_id = $id_mesin."/".$id_finger;

            if(isset($a_karyawan[$key_id])){

                $karyawan = $a_karyawan[$key_id];
                $id_absen = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));

                $s_jam_masuk = $karyawan->jam_masuk;
                $s_jam_terlambat = $karyawan->jam_terlambat;
                $s_jam_pulang = $karyawan->jam_pulang;
                $s_jam_istirahat = $karyawan->jam_istirahat;
                $s_jam_toleransi = $karyawan->jam_toleransi;

                $jam_datang = "";
                $jam_pulang = "";
                $status_datang = 0;
                $status_pulang = 0;
                $status = 0;

                foreach($value as $key1 => $value1){

                    $jam = date("H:i:s", strtotime($value1));

                    // mapping jam kerja
                    if($jam<$s_jam_masuk){
                        $jam_datang = $jam;
                    }

                    if($jam>$s_jam_masuk and $jam<$s_jam_terlambat){
                        $jam_datang = $jam;
                    }

                    if($jam > $s_jam_terlambat and $jam < $s_jam_toleransi){
                        $jam_datang = $jam;
                    }

                    if($jam>$s_jam_istirahat and $jam<$s_jam_pulang){
                        $jam_pulang = $jam;
                    }

                    if($jam>$s_jam_pulang){
                        $jam_pulang = $jam;
                    }
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

                if($day=="sat" and $jam_pulang> "14.00"){
                    $status_pulang = 0;
                }

                if($jam_datang==""){
                    $jam_datang = "00:00:00";
                }

                if($jam_pulang==""){
                    $jam_pulang = "00:00:00";
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

                    $a_absen[$id_absen]["created_at"] = date("Y-m-d H:i:s");
                    $a_absen[$id_absen]["updated_at"] = date("Y-m-d H:i:s");
                }
            }
        }

        return $a_absen;
    }

    public function getkaryawan($id)
    {
        $data = Karyawan::getList($id);
        return Response()->json($data);
    }

    public function page(Request $request)
    {
        $page =50;
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $id_perush = Session("perusahaan")["id_perush"];
        $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl);

        if($request->method()=="POST"){
            if (isset($request->shareselect)) {
                $page = $request->shareselect;
                $session = [];
                $session['page'] = $request->shareselect;
                Session($session);
            }
        }

        if (Session('dr_tgl') !== null) {
            $dr_tgl = Session('dr_tgl');
            $absen = $absen->where("absensi.tgl_absen",">=", $dr_tgl);
        }

        if (Session('sp_tgl') !== null) {
            $sp_tgl = Session('sp_tgl');
            $absen = $absen->where("absensi.tgl_absen","<=", $sp_tgl);
        }

        if (Session('id_perush') !== null) {
            $id_perush = Session('id_perush');
        }

        if (Session('id_karyawan') !== null) {
            $data["nm_karyawan"] = Karyawan::findOrFail(Session('id_karyawan'));
            $absen = $absen->where("k.id_karyawan", Session('id_karyawan'));
        }

        if (Session('page') !== null) {
            $page = Session('page');
        }

        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        }else{
            $data["perusahaan"] = Perusahaan::getRoleUser();
            if(strtolower(Session("role")["nm_role"])=="staff"){
                $absen = $absen->where("k.id_karyawan", Auth::user()->id_karyawan);
            }else{
                $absen = $absen->where("k.id_perush", $id_perush);
            }
        }

        $data["data"] = $absen->paginate($page);
        $data["page"] = $page;
        $data["filter"] = [];

        return view('kepegawaian::absensi', $data);
    }

    public function filter(Request $request)
    {
        $page = 50;
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $id_perush = Session("perusahaan")["id_perush"];

        $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl);

        if ($request->method()=="POST") {
            if (isset($request->filterperush)) {
                $absen = $absen->where("k.id_perush", $request->filterperush);
                $id_perush = $request->filterperush;
                $session = [];
                $session['id_perush'] = $request->filterperush;
                Session($session);
            }

            if (isset($request->dr_tgl)) {
                $dr = date($request->dr_tgl);
                $session['dr_tgl'] = $request->dr_tgl;
                Session($session);
                $absen = $absen->where("absensi.tgl_absen",">=", $request->dr_tgl);
            }

            if (isset($request->sp_tgl)) {
                $session['sp_tgl'] = $request->sp_tgl;
                Session($session);
                $absen = $absen->where("absensi.tgl_absen","<=", $request->sp_tgl);
            }

            if (isset($request->id_karyawan)) {
                $absen = $absen->where("k.id_karyawan", $request->id_karyawan);
                $session = [];
                $session['id_karyawan'] = $request->id_karyawan;
                Session($session);
                $data["nm_karyawan"] = Karyawan::findOrFail($request->id_karyawan);
            }
        }

        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        }else{
            $data["perusahaan"] = Perusahaan::getRoleUser();

        }

        if (Session('page') !== null) {
            $page = Session('page');
        }

        if (Session('sp_tgl') !== null) {
            $sp_tgl = Session('sp_tgl');
            $absen = $absen->where("absensi.tgl_absen","<=", $sp_tgl);
        }

        if (Session('id_perush') !== null) {
            $id_perush = Session('id_perush');
            $absen = $absen->where("k.id_perush", $id_perush);
        }

        if (Session('id_karyawan') !== null) {
            $data["nm_karyawan"] = Karyawan::findOrFail(Session('id_karyawan'));
            $absen = $absen->where("k.id_karyawan", Session('id_karyawan'));
        }

        $data["data"] = $absen->paginate($page);
        $data["page"] = $page;
        $data["filter"] = [];

        return view('kepegawaian::absensi', $data);
    }

    public function laporan(Request $request)
    {
        $id_perush = $request->f_id_perush?$request->f_id_perush:Session("perusahaan")["id_perush"];
        $bulan = $request->f_bulan?$request->f_bulan:date('m');
        $tahun = $request->tahun?$request->tahun:date('Y');
        $is_aktif = true;
        
        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("t");

        $getTanggal = date($tahun."-".$bulan."-d");

        $dr_tgl = date($tahun."-".$bulan."-01", strtotime($getTanggal));
        $sp_tgl = date($tahun."-".$bulan."-t", strtotime($getTanggal));

        if(get_admin()){
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl);
        }else{
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl, $id_perush);
        }

        $a_absen = [];
        foreach($absen as $key => $value){
            $tgl = date("d", strtotime($value->tgl_absen));
            $tgl = (Int) $tgl;
            $a_absen[$value->id_karyawan][$tgl] = 1;
        }
        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl);
        $status = Absensi::getStatus();

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }

        $a_status = [];
        foreach($status as $key => $value){
            if($value->id_karyawan!=null){
                $a_status[$value->id_karyawan][$value->status] = $value;
            }
        }

        $data["day"] = $a_absen;

        if(get_admin()){
            $data["perusahaan"] = Perusahaan::getPerusahaan();
        }else{
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan")->orderBy("nm_karyawan", "asc");
        if(!get_admin()){
            $karyawan = $karyawan->where("id_perush", $id_perush);
        }

        if($is_aktif != null ){
            $karyawan = $karyawan->where("is_aktif", $is_aktif);
        }

        $data["role_perush"] = Perusahaan::getRoleUser();
        $data["karyawan"] = $karyawan->get();
        $data["status"] = $a_status;
        $data["izin"] = $a_ijin;
        $data["tgl"] = date("Y-m", strtotime($dr_tgl));
        $data["filter"] = array("f_id_perush" => $id_perush, "f_tahun" => $tahun, "f_bulan" => $bulan);
        return view("kepegawaian::laporankehadiran", $data);
    }

    public function cetaklaporankehadiran(Request $request)
    {
        $id_perush = $request->f_id_perush?$request->f_id_perush:Session("perusahaan")["id_perush"];
        $bulan = $request->f_bulan?$request->f_bulan:date('m');
        $tahun = $request->tahun?$request->tahun:date('Y');
        $is_aktif = true;

        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("t");

        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));

        if(get_admin()){
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl);
        }else{
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl, $id_perush);
        }

        $a_absen = [];
        foreach($absen as $key => $value){
            $tgl = date("d", strtotime($value->tgl_absen));
            $tgl = (Int) $tgl;
            $a_absen[$value->id_karyawan][$tgl] = 1;
        }

        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl);
        $status = Absensi::getStatus();

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }
        $a_status = [];
        foreach($status as $key => $value){
            if($value->id_karyawan!=null){
                $a_status[$value->id_karyawan][$value->status] = $value;
            }
        }

        $data["day"] = $a_absen;
        $data["perusahaan"] = Perusahaan::findOrfail($id_perush);
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan")->orderBy("nm_karyawan", "asc");

        if(!get_admin()){
            $karyawan = $karyawan->where("id_perush", $id_perush);
        }

        if($is_aktif != null ){
            $karyawan = $karyawan->where("is_aktif", $is_aktif);
        }

        $data["karyawan"] = $karyawan->get();
        $data["status"] = $a_status;
        $data["izin"] = $a_ijin;
        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;

        return view("kepegawaian::cetak.laporankehadiran", $data);
    }

    public function excellaporankehadiran(Request $request)
    {
        $id_perush = $request->f_id_perush?$request->f_id_perush:Session("perusahaan")["id_perush"];
        $bulan = $request->f_bulan?$request->f_bulan:date('m');
        $tahun = $request->tahun?$request->tahun:date('Y');
        $is_aktif = true;

        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("t");

        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));

        if(get_admin()){
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl);
        }else{
            $absen = Absensi::getLaporanBulan($dr_tgl, $sp_tgl, $id_perush);
        }

        $a_absen = [];
        foreach($absen as $key => $value){
            $tgl = date("d", strtotime($value->tgl_absen));
            $tgl = (Int) $tgl;
            $a_absen[$value->id_karyawan][$tgl] = 1;
        }

        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl);
        $status = Absensi::getStatus();

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }
        $a_status = [];
        foreach($status as $key => $value){
            if($value->id_karyawan!=null){
                $a_status[$value->id_karyawan][$value->status] = $value;
            }
        }

        $data["day"] = $a_absen;
        $data["perusahaan"] = Perusahaan::findOrfail($id_perush);
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan")->orderBy("nm_karyawan", "asc");

        if(!get_admin()){
            $karyawan = $karyawan->where("id_perush", $id_perush);
        }

        if($is_aktif != null ){
            $karyawan = $karyawan->where("is_aktif", $is_aktif);
        }

        $data["karyawan"] = $karyawan->get();
        $data["status"] = $a_status;
        $data["izin"] = $a_ijin;
        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;
        $data["days"] = date("d", strtotime($sp_tgl));

        return view("kepegawaian::cetak.excellaporankehadiran", $data);
    }

    public function statistik(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = date('Y');
        $bulan = date('m');
        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $is_aktif = true;

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);

        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl, $id_perush);
        $s_datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $s_pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);

        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, null, null,$is_aktif);
        }else{
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, $id_perush,null, $is_aktif);
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }

        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $data["status_datang"] = $s_datang;
        $data["status_pulang"] = $s_pulang;
        $data["izin"] = $a_ijin;
        $data["jml"] = $red+$sun;
        $data["jmla"] = $total_hari;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view("kepegawaian::statistikkehadiran", $data);
    }

    public function cetakstatistik(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = date('Y');
        $bulan = date('m');
        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $is_aktif = true;

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        if (isset($request->f_status) and $request->f_status != null) {
            $is_aktif = $request->f_status;
        }

        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);

        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl, $id_perush);
        $s_datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $s_pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);

        if(get_admin()){
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, null, null, $is_aktif);
        }else{
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, $id_perush, null, $is_aktif);
        }

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }

        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $data["status_datang"] = $s_datang;
        $data["status_pulang"] = $s_pulang;
        $data["izin"] = $a_ijin;
        $data["jml"] = $red+$sun;
        $data["jmla"] = $total_hari;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        return view("kepegawaian::pdfstatistik", $data);
    }


    public function excelstatistik(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = date('Y');
        $bulan = date('m');
        $dtl = $tahun."-".$bulan;
        $dt = $dtl."-"."01";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $is_aktif = true;

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        if (isset($request->f_status) and $request->f_status != null) {
            $is_aktif = $request->f_status;
        }

        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);

        $ijin = Perijinan::getIzin($dr_tgl, $sp_tgl, $id_perush);
        $s_datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $s_pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);

        if(get_admin()){
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, null, null, $is_aktif);
        }else{
            $data["data"] = Absensi::getStatistik($dr_tgl, $sp_tgl, $id_perush, null, $is_aktif);
        }

        $a_ijin = [];
        foreach($ijin as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }

        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->orderBy("nm_jenis", "asc")->get();
        $data["status_datang"] = $s_datang;
        $data["status_pulang"] = $s_pulang;
        $data["izin"] = $a_ijin;
        $data["jml"] = $red+$sun;
        $data["jmla"] = $total_hari;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        return view("kepegawaian::excelstatistik", $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {

        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        }else{
            abort(404);
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $data["karyawan"] = Karyawan::select("id_karyawan", "nm_karyawan")->where("id_perush", Session("perusahaan")["id_perush"])->orderBy("nm_karyawan", "asc")->get();
        $data["mesin"] = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", Session("perusahaan")["id_perush"])->get();

        return view("kepegawaian::createabsensi", $data);
    }

    public function download()
    {
        $path = "public/template.xlsx";

        return Storage::download($path);
    }

    public function inject(Request $request)
    {
        $rules = array(
            'id_karyawan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
            'tgl_absen' => 'date|bail|nullable|min:10|max:11',
            'jam_datang' => 'bail|nullable|date_format:H:i',
            'jam_istirahat' => 'bail|nullable|date_format:H:i|after_or_equal:jam_datang',
            'jam_istirahat_masuk' => 'bail|nullable|date_format:H:i|after_or_equal:jam_istirahat',
            'jam_pulang' => 'bail|nullable|date_format:H:i|after_or_equal:jam_istirahat_masuk',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $id_perush = $request->id_perush;
        $karyawan = Karyawan::select("nm_karyawan","id_jam_kerja", "id_mesin")->where("id_karyawan", $request->id_karyawan)->get()->first();
        $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->where("id_perush", $id_perush)->get()->first();
        // cek absen
        $id_absen = $id_perush.$request->id_karyawan.date("dmY", strtotime($request->tgl_absen));
        $cek = Absensi::where("tgl_absen", date("Y-m-d", strtotime($request->tgl_absen)))->where("id_karyawan", $request->id_karyawan)
        ->get()->first();

        try {

            DB::beginTransaction();
            $data = [];
            $status_datang = 0;
            $status = 0;
            $status_pulang = 0;
            $status_istirahat = 0;
            $status_istirahat_masuk = 0;
            $jam_istirahat = $request->jam_istirahat;
            $jam_istirahat_masuk = $request->jam_istirahat_masuk;
            $jam_datang = $request->jam_datang;
            $jam_pulang = $request->jam_pulang;

            $data["tgl_absen"] = $request->tgl_absen;
            $data["id_perush"] = $id_perush;
            $data["id_karyawan"] = $request->id_karyawan;

            if($jam_datang < $setting->jam_masuk){
                $status_datang = 1;
            }

            if($jam_datang>$setting->jam_masuk and $jam_datang<$setting->jam_terlambat){
                $status_datang = 0;
            }

            if($jam_datang > $setting->jam_terlambat and $jam_datang < $setting->jam_toleransi){
                $status_datang = 2;
            }
            
            $jam2 = date("H:i", strtotime('+15 minutes', strtotime($setting->jam_istirahat_masuk)));

            if($jam_istirahat_masuk>$setting->jam_istirahat_masuk and $jam_istirahat_masuk<$jam2){
                $status_istirahat_masuk = 0;
            }else{
                $status_istirahat_masuk = 1;
            }

            if($jam_istirahat>$setting->jam_toleransi and $jam_istirahat<$setting->jam_istirahat_masuk){
                $status_istirahat = 0;
            }else{
                $status_istirahat = 1;
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

            $day = strtolower(date("D", strtotime($request->tgl_absen)));
            if($day=="sat" and $jam_pulang> "14.00"){
                $status_pulang = 0;
            }

            $data["jam_datang"] = $jam_datang;
            $data["jam_pulang"] = $jam_pulang;
            $data["jam_istirahat"] = $jam_istirahat;
            $data["jam_istirahat_masuk"] = $jam_istirahat_masuk;
            $data["status_datang"] = $status_datang;
            $data["status_pulang"] = $status_pulang;
            $data["status_istirahat"] = $status_istirahat;
            $data["status_istirahat_masuk"] = $status_istirahat_masuk;
            $data["status"] = $status;
            $data["id_finger"] = $karyawan->id_finger;
            $data["id_admin"] = Auth::user()->id_user;

            if($cek){
                Absensi::where("id_absen", $id_absen)->update($data);
            }

            if(!$cek){
                $data["id_absen"] = $id_absen;
                Absensi::insert($data);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Absensi Gagal Dibuat' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Absensi Dibuat');
    }

    public function store(Request $request)
    {
        $date1  =   date_create($request->start_date);
        $date2  =   date_create($request->end_date);
        $diff   =   date_diff($date1, $date2);
        $diff = $diff->format("%a");
        $date_now = date("Y-m-d");

        if($date1==$date_now or $date2==$date_now){
            return redirect()->back()->with('error', 'pilih Tanggal awal dan akhir yang sudah dilewati');
        }

        if($diff>2){
            return redirect()->back()->with('error', 'Range Tanggal terlalu panjang, maksimal 2 hari');
        }

        $mesin = Mesinfinger::select("id_mesin", "id_perush")->where("id_perush", $request->id_perush)->get();
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $a_data = [];
        foreach($mesin as $key => $value){
            $data = $this->getCurl($value->id_mesin, $start_date,$end_date);
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
                $cek = Absensi::where("tgl_absen", $value["tgl_absen"])->where("id_karyawan", $value["id_karyawan"])->get()->first();
                
                $absen["id_absen"] = $value["id_absen"];
                $absen["id_karyawan"] = $value["id_karyawan"];
                $absen["id_perush"] = $value["id_perush"];
                $absen["status_datang"] = $value["status_datang"];
                $absen["status_pulang"] = $value["status_pulang"];
                $absen["status"] = $value["status"];
                $absen["status_istirahat"] = $value["status_istirahat"];
                $absen["status_istirahat_masuk"] = $value["status_istirahat_masuk"];
                $absen["id_finger"] = $value["id_finger"];
                $absen["id_admin"] = $value["id_admin"];
                $absen["tgl_absen"] = $value["tgl_absen"];
                $absen["jam_datang"] = $value["jam_datang"];
                $absen["jam_pulang"] = $value["jam_pulang"];
                $absen["jam_istirahat"] = $value["jam_istirahat"];
                $absen["jam_istirahat_masuk"] = $value["jam_istirahat_masuk"];
                $absen["created_at"] = $value["created_at"];
                $absen["updated_at"] = $value["updated_at"];
                
                if($cek==null){
                    Absensi::insert($absen);
                }

                if($cek!=null){
                    Absensi::where("id_absen", $absen["id_absen"])->update($absen);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Absen Gagal Ditarik '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Absen Ditarik');
    }

    public function arrGet($data, $id_perush, $id_mesin)
    {
        $a_data = [];
        foreach($data as $key => $value){
            $tgl = date("Y-m-d", strtotime($value["scan_date"]));
            $a_data[$tgl."/".$value["pin"]."/".$id_mesin][$key] = date("H:i:s", strtotime($value["scan_date"]));
        }

        $d_data = [];
        foreach($a_data as $key => $value){
            $id = explode("/", $key);
            $karyawan = Karyawan::where("id_finger", $id[1])->where("id_mesin", $id_mesin)->where("id_perush", $id_perush)->get()->first();
            $jam_datang = "";
            $jam_istirahat = "";
            $jam_istirahat_masuk = "";
            $jam_pulang = "";
            $status_datang = 0;
            $status_pulang = 0;
            $status_istirahat = 0;
            $status_istirahat_masuk = 0;
            $status = 0;
            
            if($karyawan!=null){
                $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();
                if($setting!=null){
                    $id_finger = $id[1];
                    $tgl_absen = $id[0];
                    $day = strtolower(date("D", strtotime($tgl_absen)));
                    foreach($value as $key2 => $value2){
                        if($value2 >= $setting->jam_masuk and $value2 <= $setting->jam_toleransi){
                            $jam_datang = $value2;
                        }
                        
                        $jam = date("H:i", strtotime('+45 minutes', strtotime($setting->jam_istirahat)));
                        $jam2 = date("H:i", strtotime('+15 minutes', strtotime($setting->jam_istirahat_masuk)));

                        if($karyawan->is_sopir == 1){
                            if($value2>$setting->jam_istirahat and $value2<=$jam){
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
                            if($value2>=$setting->jam_istirahat and $value2<$jam){
                                $jam_istirahat = $value2;
                            }
                            
                            if($value2>=$setting->jam_istirahat_masuk and $value2<$jam2){
                                $jam_istirahat_masuk = $value2;
                            }
                        }

                        if($day=="sat" and $value2 >= $setting->jam_sabtu){
                            $jam_pulang = $value2;
                        }elseif($day!="sat" and $value2 >= $setting->jam_sabtu){
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

                    if($jam_istirahat==""){
                        $status_istirahat = 2;
                    }

                    if($jam_istirahat_masuk==""){
                        $status_istirahat_masuk = 2;
                    }elseif($jam_istirahat_masuk != "" and $jam_istirahat_masuk > $jam2 and $jam_istirahat_masuk < $setting->jam_pulang){
                        $status_istirahat_masuk = 1;
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

                    if($day=="sat" and $jam_pulang >= $setting->jam_sabtu){
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

                    $d_data[$key]["id_absen"] = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));
                    $d_data[$key]["id_karyawan"] = $karyawan->id_karyawan;
                    $d_data[$key]["id_perush"] = $karyawan->id_perush;
                    $d_data[$key]["status_datang"] = $status_datang;
                    $d_data[$key]["status_pulang"] = $status_pulang;
                    $d_data[$key]["status"] = $status;
                    $d_data[$key]["status_istirahat"] = $status_istirahat;
                    $d_data[$key]["status_istirahat_masuk"] = $status_istirahat_masuk;
                    $d_data[$key]["id_finger"] = $id_finger;
                    $d_data[$key]["id_admin"] = Auth::user()->id_user;
                    $d_data[$key]["tgl_absen"] = date("Y-m-d", strtotime($tgl_absen));
                    $d_data[$key]["jam_datang"] = date("H:i:s", strtotime($jam_datang));
                    $d_data[$key]["jam_pulang"] = date("H:i:s", strtotime($jam_pulang));
                    $d_data[$key]["jam_istirahat"] = date("H:i:s", strtotime($jam_istirahat));
                    $d_data[$key]["jam_istirahat_masuk"] = date("H:i:s", strtotime($jam_istirahat_masuk));
                    $d_data[$key]["created_at"] = date("Y-m-d H:i:s");
                    $d_data[$key]["updated_at"] = date("Y-m-d H:i:s");
                }
            }
        }
        //dd($d_data);
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

    public function laporanabsensi(Request $request)
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-d");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $is_aktif = true;
        $page = 50;
        $id_perush   = Session("perusahaan")["id_perush"];

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        if (isset($request->shareselect) and $request->shareselect != null) {
            $page = $request->shareselect;
        }

        if(!get_admin()){
            $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, $id_perush, $is_aktif);
        }else{
            $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, null, $is_aktif);
        }

        $data["data"] = $absen->paginate($page);
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["mesin"] = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", $id_perush)->get();
        $data["filter"] = array("page" => $page, "f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view('kepegawaian::laporanabsensi', $data);
    }

    public function cetaklaporanabsensi(Request $request)
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $is_aktif = true;
        $id_perush   = Session("perusahaan")["id_perush"];

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        if (isset($request->f_status) and $request->f_status != null) {
            $is_aktif = $request->f_status;
        }

        $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, null, $is_aktif);
        if(!get_admin()){
            $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, $id_perush, $is_aktif);
        }elseif(strtolower(Session("role")["nm_role"])=="staff"){
            $absen = $absen->where("k.id_karyawan", Auth::user()->id_karyawan);
        }
        $absen = $absen->orderBy("id_absen", "asc");
        $data["data"] = $absen->get();
        $data["mesin"] = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", $id_perush)->get();
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);

        return view('kepegawaian::cetaklaporanabsensi', $data);
    }

    public function excellaporanabsensi(Request $request)
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $is_aktif = true;
        $id_perush   = Session("perusahaan")["id_perush"];

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_dr_tgl) and $request->f_dr_tgl != null) {
            $dr_tgl = $request->f_dr_tgl;
        }

        if (isset($request->f_sp_tgl) and $request->f_sp_tgl != null) {
            $sp_tgl = $request->f_sp_tgl;
        }

        if (isset($request->f_status) and $request->f_status != null) {
            $is_aktif = $request->f_status;
        }

        $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, null, $is_aktif);
        if(!get_admin()){
            $absen = Absensi::getAbsensi($dr_tgl, $sp_tgl, $id_perush, $is_aktif);
        }elseif(strtolower(Session("role")["nm_role"])=="staff"){
            $absen = $absen->where("k.id_karyawan", Auth::user()->id_karyawan);
        }
        $absen = $absen->orderBy("id_absen", "asc");
        $data["data"] = $absen->get();
        $data["mesin"] = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", $id_perush)->get();
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);

        return view('kepegawaian::cetaklaporanabsensi', $data);
    }

    public function download_by_mesin(Request $request)
    {
        $url = 'http://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"' . $request->id_mesin . '", "start_date":"'.$request->start_date.'", "end_date":"'.$request->end_date.'"}';
        $authorization = "Authorization: Bearer HLT79UO4FK3AHF2C";

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
        $finger = [];
        foreach ($data['data'] as $key => $value) {
            $finger[$value['pin']][] = date("H:i:s", strtotime($value["scan_date"]));
        }
        $absensi = [];
        foreach ($finger as $key => $value) {
            $karyawan           = Karyawan::getJamKerja($key, $request->id_mesin);
            $jam_datang         = "";
            $jam_pulang         = "";
            $status_datang      = 0;
            $status_pulang      = 0;
            $status             = 0;

            foreach ($value as $jam) {
                if ($jam_datang != "" && ($jam >= $karyawan->jam_masuk && $jam <= $karyawan->jam_toleransi )) {
                    $jam_datang = $jam;
                }

                $day = strtolower(date("D", strtotime($request->start_date)));
                if ($day == "sat" && ($jam_pulang != "" && $jam >= $karyawan->jam_sabtu)){
                    $jam_pulang = $jam;
                } else if ($status_pulang != "" && ($jam >= $karyawan->jam_pulang)) {
                    $jam_pulang = $jam;
                }

                if ($jam_datang > $karyawan->jam_masuk) {
                    $status_datang = 2;
                }

                if ($jam_datang < $karyawan->jam_masuk) {
                    $status_datang = 1;
                }

                if ($jam_pulang < $karyawan->jam_pulang) {
                    $status_pulang = 5;
                }

                if ($jam_datang == "") {
                    $status_datang = 4;
                }

                if ($jam_pulang == "") {
                    $status_pulang = 4;
                }
            }
            
            dd($karyawan, $value);
        }
        $d_data[$key]["id_absen"] = $karyawan->id_perush.$karyawan->id_karyawan.date("dmY", strtotime($tgl_absen));
        $d_data[$key]["id_karyawan"] = $karyawan->id_karyawan;
        $d_data[$key]["id_perush"] = $karyawan->id_perush;
        $d_data[$key]["status_datang"] = $status_datang;
        $d_data[$key]["status_pulang"] = $status_pulang;
        $d_data[$key]["status"] = $status;
        $d_data[$key]["status_istirahat"] = $status_istirahat;
        $d_data[$key]["status_istirahat_masuk"] = $status_istirahat_masuk;
        $d_data[$key]["id_finger"] = $id_finger;
        $d_data[$key]["id_admin"] = Auth::user()->id_user;
        $d_data[$key]["tgl_absen"] = date("Y-m-d", strtotime($tgl_absen));
        $d_data[$key]["jam_datang"] = date("H:i:s", strtotime($jam_datang));
        $d_data[$key]["jam_pulang"] = date("H:i:s", strtotime($jam_pulang));
        $d_data[$key]["jam_istirahat"] = date("H:i:s", strtotime($jam_istirahat));
        $d_data[$key]["jam_istirahat_masuk"] = date("H:i:s", strtotime($jam_istirahat_masuk));
        $d_data[$key]["created_at"] = date("Y-m-d H:i:s");
        $d_data[$key]["updated_at"] = date("Y-m-d H:i:s");
        dd($finger, $request->id_mesin);
    }
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        abort(404);
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        abort(404);
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        abort(404);
    }
}
