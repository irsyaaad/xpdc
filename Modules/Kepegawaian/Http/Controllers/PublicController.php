<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Exception;
use Auth;
use Validator;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\Perijinan;
use Modules\Kepegawaian\Entities\SettingDenda;
use Modules\Kepegawaian\Entities\SettingHariLibur;

class PublicController extends Controller
{
    public function jamkerja(Request $request)
    {   
        $id_perush = $request->f_perush?$request->f_perush:3;
        $dtl = date("Y-m");
        $dr_tgl = $request->f_dr_tgl!=null?$request->f_dr_tgl:date("Y-m-d", strtotime($dtl."-"."01"));
        $sp_tgl = $request->f_sp_tgl!=null?$request->f_sp_tgl:date("Y-m-d", strtotime($dtl."-".date("d")));
        $is_aktif = true;
        $f_id_karyawan = $request->f_id_karyawan!=null?$request->f_id_karyawan:null;

        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);
        
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);
        
        $hijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);
        $data["kehadiran"] = $kehadiran;
        $data["hijin"] = $hijin;
        $data["jizin"] = $jizin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] =  Perusahaan::where("id_perush", $id_perush)->get();
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl, "f_id_karyawan" => $f_id_karyawan);
        $data["data"] = Absensi::getAbsensi($dr_tgl, $sp_tgl, $id_perush, true, $f_id_karyawan)->paginate(100);
        $data["statistik"] = Absensi::getStatistik($dr_tgl, $sp_tgl, $id_perush, $f_id_karyawan, true);
        $hizin = Perijinan::getIzin($dr_tgl, $sp_tgl, $id_perush);
        $a_izin = [];
        foreach($hizin as $key => $value){
            $a_izin[$value->id_karyawan] = $value;
        }
        $data["izin"] = $a_izin;
        $data["sdatang"] = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush, $f_id_karyawan);
        $data["spulang"] = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush, $f_id_karyawan);
        $data["sistirahat"] = Absensi::getStatusIstirahat($dr_tgl, $sp_tgl, $id_perush, $f_id_karyawan);
        
        return view("kepegawaian::jamkerja", $data);
    }
}
