<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\SettingJam;
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

class SettingJamController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        if(get_admin()){
            $data["data"] = SettingJam::with("user", "perush")->get();
        }else{
            $data["data"] = SettingJam::with("user", "perush")->where("id_perush", $id_perush)->get();
        }

        return view('kepegawaian::settingjamkerja', $data);
    }

    public function refresh()
    {
        session()->forget('dr_tgl');
        session()->forget('sp_tgl');
        session()->forget('id_perush');
        session()->forget('status');

        return redirect(route_redirect());
    }

    public function getJamKerja($id)
    {
        $data = SettingJam::where("id_perush", $id)->get();

        return Response()->json($data);
    }

    public function jamkerja(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
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

        // set variable parsing
        if(get_admin()){
            // $karyawan = Karyawan::getList();
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::getPerusahaan();
        }else{
            // $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::getRoleUser();
        }

        // $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
        // kurangi menit
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);

        // kurangi hari ijin
        $hijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);
       // dd($kehadiran);
        $data["kehadiran"] = $kehadiran;
        $data["hijin"] = $hijin;
        $data["jizin"] = $jizin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] = $perusahaan;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view('kepegawaian::laporanjambekerja', $data);
    }

    public function jamkehadiran(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
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

        // set variable parsing
        if(get_admin()){
            $karyawan = Karyawan::getList();
            $perusahaan = Perusahaan::getPerusahaan();
        }else{
            $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
            $perusahaan = Perusahaan::getRoleUser();
        }

        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);

        $data["data"] = Absensi::getJamKerja($id_perush, $dr_tgl, $sp_tgl);
        $data["jmla"] = $total_hari;
        $data["jml"] = $jml;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);
        $data["perusahaan"] = $perusahaan;
        $data["jenis"] = SettingDenda::getJoinMapping($id_perush);
        $data["karyawan"] = $karyawan;

        return view('kepegawaian::jamkehadiran', $data);
    }

    public function cetakjamkerja(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
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

        // set variable parsing
        if(get_admin()){
            // $karyawan = Karyawan::getList();
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::findorFail($id_perush);
        }else{
            // $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::findorFail($id_perush);
        }
        // $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
        // $perusahaan = Perusahaan::findorFail($id_perush);
        // $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
        // kurangi menit
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);

        // kurangi hari ijin
        $hijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);
       // dd($kehadiran);
        $data["kehadiran"] = $kehadiran;
        $data["hijin"] = $hijin;
        $data["jizin"] = $jizin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] = $perusahaan;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view('kepegawaian::cetaklaporanjamkerja', $data);
    }

    public function jamkerjacabang(Request $request)
    {
        $id_perush = $request->f_perush != null?$request->f_perush:null;
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = $request->f_perush != null ?$request->f_perush:date("Y-m-d", strtotime($dt));
        $sp_tgl = $request->f_perush != null ?$request->f_perush:date("Y-m-d", strtotime($sp));
        $is_aktif = true;
        // get diff hari
        $red = SettingHariLibur::getSumCabang($id_perush, $dr_tgl, $sp_tgl);

        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);
        $perusahaan = Perusahaan::getRoleUser();

        $id = Perijinan::getIjin("id", $dr_tgl, $sp_tgl, $id_perush);
        $dk = Perijinan::getIjin("dk", $dr_tgl, $sp_tgl, $id_perush);
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);

        // kurangi menit
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);

        // kurangi hari ijin
        $ijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        //dd($ijin);
        $a_ijin = array("c" => "c", "s" => "s", "tm" => "tm");
        // kurangi menit ijin
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);

        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] = $perusahaan;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["kehadiran"] = $kehadiran;
        $data["id"] = $id;
        $data["dk"] = $dk;
        $data["ijin"] = $ijin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["jizin"] = $jizin;
        $data["a_ijin"] = $a_ijin;

        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view('kepegawaian::jamkerjacabang', $data);
    }

    public function exceljamkerja(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
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

        // set variable parsing
        if(get_admin()){
            // $karyawan = Karyawan::getList();
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::findorFail($id_perush);
        }else{
            // $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
            $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
            $perusahaan = Perusahaan::findorFail($id_perush);
        }
        // $karyawan = Karyawan::getList($id_perush, null, $is_aktif);
        // $perusahaan = Perusahaan::findorFail($id_perush);
        // $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "id_perush")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
        // kurangi menit
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);

        // kurangi hari ijin
        $hijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);
       // dd($kehadiran);
        $data["kehadiran"] = $kehadiran;
        $data["hijin"] = $hijin;
        $data["jizin"] = $jizin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] = $perusahaan;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        return view('kepegawaian::excellaporanjamkerja', $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        return view('kepegawaian::settingjamkerja');
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $cek = SettingJam::where("shift", $request->shift)->where("id_perush",Session("perusahaan")["id_perush"])->get()->first();

        if($cek!=null){
            return redirect()->back()->with('error', 'Shift Sudah Ada ');
        }

        try {
            // save to user
            DB::beginTransaction();
            $jam = new SettingJam();
            $jam->id_perush = Session("perusahaan")["id_perush"];
            $jam->shift = $request->shift;
            $jam->jam_masuk = date("H:i:s", strtotime($request->jam_masuk));
            $jam->jam_terlambat = date("H:i:s", strtotime($request->jam_terlambat));
            $jam->jam_toleransi = date("H:i:s", strtotime($request->jam_toleransi));
            $jam->jam_istirahat = date("H:i:s", strtotime($request->jam_istirahat));
            $jam->jam_istirahat_masuk = date("H:i:s", strtotime($request->jam_istirahat_masuk));
            $jam->jam_pulang = date("H:i:s", strtotime($request->jam_pulang));
            $jam->jam_sabtu = date("H:i:s", strtotime($request->jam_sabtu));
            $jam->id_user = Auth::user()->id_user;
            $jam->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Jam Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("jamkerja"))->with('success', 'Data Setting Jam  Disimpan');
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
        $data["data"] = SettingJam::with("user", "perush")->findOrFail($id);

        return view('kepegawaian::settingjamkerja', $data);
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $cek = SettingJam::where("shift", $request->shift)->where("id_perush",Session("perusahaan")["id_perush"])->get()->first();

        if($cek!=null){
            return redirect()->back()->with('error', 'Shift Sudah Ada ');
        }

        try {
            // save to user
            DB::beginTransaction();
            $jam = SettingJam::findOrFail($id);
            $jam->id_perush = Session("perusahaan")["id_perush"];
            $jam->jam_masuk = date("H:i:s", strtotime($request->jam_masuk));
            $jam->jam_terlambat = date("H:i:s", strtotime($request->jam_terlambat));
            $jam->jam_toleransi = date("H:i:s", strtotime($request->jam_toleransi));
            $jam->jam_istirahat = date("H:i:s", strtotime($request->jam_istirahat));
            $jam->jam_istirahat_masuk = date("H:i:s", strtotime($request->jam_istirahat_masuk));
            $jam->jam_pulang = date("H:i:s", strtotime($request->jam_pulang));
            $jam->jam_sabtu = date("H:i:s", strtotime($request->jam_sabtu));
            $jam->id_user = Auth::user()->id_user;
            $jam->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Jam Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("jamkerja"))->with('success', 'Data Setting Jam  Disimpan');
    }

    public function allcabang(Request $request){
        $f_id_perush = null;
        $id_perush = 5;
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        $dr_tgl = $request->f_dr_tgl != null?$request->f_dr_tgl:date("Y-m-d", strtotime($dt));
        $sp_tgl = $request->f_sp_tgl != null?$request->f_sp_tgl:date("Y-m-d", strtotime($sp));
        $is_aktif = isset($request->f_status)?$request->f_status:true;
        
        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red+$sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);
        $perusahaan = Perusahaan::findOrFail($id_perush);
        $perush = Perusahaan::getRoleUser();
        $karyawan = Karyawan::getKaryawanCabang(Session("role")["id_role"], $f_id_perush);
        //dd($karyawan);
        $istirahat = Absensi::getIstirahatCabang($dr_tgl, $sp_tgl, Session("role")["id_role"], $f_id_perush);
        $terlambat = Absensi::getTerlambatCabang($dr_tgl, $sp_tgl, Session("role")["id_role"], $f_id_perush);
        $pulang = Absensi::getPulangCabang($dr_tgl, $sp_tgl, Session("role")["id_role"], $f_id_perush);

        // kurangi hari ijin
        $hijin = Perijinan::getIjinHariCabang($dr_tgl, $sp_tgl,Session("role")["id_role"], $f_id_perush);
        $jizin = Perijinan::getIjinJamCabang($dr_tgl, $sp_tgl, Session("role")["id_role"], $f_id_perush);
        $kehadiran = Absensi::KehadiranCabang($dr_tgl, $sp_tgl, Session("role")["id_role"], $f_id_perush);
        
       // dd($kehadiran);
        $data["kehadiran"] = $kehadiran;
        $data["hijin"] = $hijin;
        $data["jizin"] = $jizin;
        $data["istirahat"] = $istirahat;
        $data["terlambat"] = $terlambat;
        $data["pulang"] = $pulang;
        $data["karyawan"] = $karyawan;
        $data["perush"]= $perush;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["perusahaan"] = $perusahaan;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        
        return view('kepegawaian::jamkerjaallcabang', $data);
    }

    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $jam = SettingJam::findOrFail($id);
            $jam->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Jam Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Setting Jam  Dihapus');
    }
}
