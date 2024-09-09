<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\SettingDenda;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\Perijinan;
use Modules\Kepegawaian\Entities\SettingJam;
use DB;
use Auth;
use Session;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use Modules\Kepegawaian\Entities\SettingHariLibur;

class SettingDendaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        if(isset($request->f_id_perush) and $request->f_id_perush!= null){
            $id_perush = $request->f_id_perush;
        }
        $data["data"] = SettingDenda::where("id_perush", $id_perush)
        ->orderBy("id_perush", "asc")->OrderBy("id_jenis", "asc")->get();
        $data["role_perush"] = Perusahaan::getRoleUser();
        $data["filter"] = array("f_id_perush" => $id_perush);
        
        return view('kepegawaian::settingdenda', $data);
    }
    
    public function copy(Request $request)
    {
        $setting = SettingDenda::where("id_perush", $request->perush_asal)->get();
        
        if(count($setting)==0){
            return redirect()->back()->with('error', 'Setting Denda Belum Ada ');
        }
        
        DB::beginTransaction();
        
        try {
            SettingDenda::where("id_perush", $request->perush_tujuan)->delete();
            foreach($setting as $key => $value){
                $data = [];
                $data["id_perush"] = $request->perush_tujuan;
                $data["id_user"] = Auth::user()->id_user;
                $data["frekuensi"] = $value->frekuensi;
                $data["id_jenis"] = $value->id_jenis;
                $data["nominal"] = $value->nominal;
                $data["created_at"] = date("Y/m/d H:i:s");
                SettingDenda::insert($data);
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Denda Gagal di copy '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Setting Denda berhasil  di copy');
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->get();
        $data["perusahaan"] = Perusahaan::getRoleUser();

        return view('kepegawaian::settingdenda', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $cek = SettingDenda::where("id_perush", $request->id_perush)->where("id_jenis", $request->id_jenis)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Setting Denda Sudah Ada ');
        }
        
        try {
            // save to user
            DB::beginTransaction();
            $denda = new SettingDenda();
            $denda->id_perush = $request->id_perush;
            $denda->id_user = Auth::user()->id_user;
            $denda->frekuensi = $request->frekuensi;
            $denda->id_jenis = $request->id_jenis;
            $denda->nominal = $request->nominal;
            $denda->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Denda Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("settingdenda"))->with('success', 'Data Setting Denda  Disimpan');
    }
    
    public function denda(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $status = true;
        
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
        
        $absen = Absensi::getLaporan($dr_tgl, $sp_tgl, $id_perush);
        $datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);
        $jenis = SettingDenda::getJoinMapping($id_perush);
        $denda = SettingDenda::getSettingDenda($id_perush);
        $perijinan = Perijinan::getDenda($dr_tgl, $sp_tgl, $id_perush, null, $denda);
        $alpha = SettingDenda::getAlpha($id_perush);
        $s_datang = SettingDenda::getDatang($id_perush);
        $s_pulang = SettingDenda::getPulang($id_perush);
        
        if($alpha==null){
            return redirect()->back()->with('error', 'Data Setting Denda Alpha Belum Di Buat');
        }
        
        if($s_datang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Terlambat atau Tidak Absen Masuk Belum Di Buat');
        }
        
        if($s_pulang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Pulang Dulu atau Tidak Absen Belum Di Buat');
        }
        
        $karyawan = [];
        if(get_admin()){
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
            $karyawan = Karyawan::getList(null, null, $status);
        }else{
            $data["perusahaan"] = Perusahaan::getRoleUser();
            $karyawan = Karyawan::getList($id_perush, null, $status);
        }
        
        $data["datang"] = $datang;
        $data["pulang"] = $pulang;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["absen"] = $absen;
        $data["ijin"] = $perijinan;
        $data["jenis"] =$jenis;
        $data["alpha"] = $alpha;
        $data["s_datang"] = $s_datang;
        $data["s_pulang"] = $s_pulang;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["karyawan"] = $karyawan;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);
        $data["id"] = Perijinan::getIjin("id", $dr_tgl, $sp_tgl, $id_perush);
        $data["dk"] = Perijinan::getIjin("dk", $dr_tgl, $sp_tgl, $id_perush);
        // dd($data);
        return view("kepegawaian::laporandenda", $data);
    }
    
    public function exceldenda(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $status = true;
        
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
        
        $absen = Absensi::getLaporan($dr_tgl, $sp_tgl, $id_perush);
        $datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);
        $jenis = SettingDenda::getJoinMapping($id_perush);
        $denda = SettingDenda::getSettingDenda($id_perush);
        $perijinan = Perijinan::getDenda($dr_tgl, $sp_tgl, $id_perush, null, $denda);
        $alpha = SettingDenda::getAlpha($id_perush);
        $s_datang = SettingDenda::getDatang($id_perush);
        $s_pulang = SettingDenda::getPulang($id_perush);
        
        if($alpha==null){
            return redirect()->back()->with('error', 'Data Setting Denda Alpha Belum Di Buat');
        }
        
        if($s_datang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Terlambat atau Tidak Absen Masuk Belum Di Buat');
        }
        
        if($s_pulang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Pulang Dulu atau Tidak Absen Belum Di Buat');
        }
        
        $karyawan = [];
        if(get_admin()){
            $karyawan = Karyawan::getList(null, null, $status);
        }else{
            $karyawan = Karyawan::getList($id_perush, null, $status);
        }
        
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $data["datang"] = $datang;
        $data["pulang"] = $pulang;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["absen"] = $absen;
        $data["ijin"] = $perijinan;
        $data["jenis"] =$jenis;
        $data["alpha"] = $alpha;
        $data["s_datang"] = $s_datang;
        $data["s_pulang"] = $s_pulang;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["karyawan"] = $karyawan;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);
        $data["id"] = Perijinan::getIjin("id", $dr_tgl, $sp_tgl, $id_perush);
        $data["dk"] = Perijinan::getIjin("dk", $dr_tgl, $sp_tgl, $id_perush);

        return view("kepegawaian::excellaporandenda", $data);
    }
    
    public function cetakdenda(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dtl = date("Y-m");
        $dt = $dtl."-"."01";
        $sp = $dtl."-".date("d");
        
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d");
        $status = true;
        
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
        
        $absen = Absensi::getLaporan($dr_tgl, $sp_tgl, $id_perush);
        $datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);
        $jenis = SettingDenda::getJoinMapping($id_perush);
        $denda = SettingDenda::getSettingDenda($id_perush);
        $perijinan = Perijinan::getDenda($dr_tgl, $sp_tgl, $id_perush, null, $denda);
        $alpha = SettingDenda::getAlpha($id_perush);
        $s_datang = SettingDenda::getDatang($id_perush);
        $s_pulang = SettingDenda::getPulang($id_perush);
        
        if($alpha==null){
            return redirect()->back()->with('error', 'Data Setting Denda Alpha Belum Di Buat');
        }
        
        if($s_datang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Terlambat atau Tidak Absen Masuk Belum Di Buat');
        }
        
        if($s_pulang==null){
            return redirect()->back()->with('error', 'Data Setting Denda Pulang Dulu atau Tidak Absen Belum Di Buat');
        }
        
        $karyawan = [];
        if(get_admin()){
            $karyawan = Karyawan::getList(null, null, $status);
        }else{
            $karyawan = Karyawan::getList($id_perush, null, $status);
        }

        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $data["datang"] = $datang;
        $data["pulang"] = $pulang;
        $data["jml"] = $jml;
        $data["jmla"] = $total_hari;
        $data["absen"] = $absen;
        $data["ijin"] = $perijinan;
        $data["jenis"] =$jenis;
        $data["alpha"] = $alpha;
        $data["s_datang"] = $s_datang;
        $data["s_pulang"] = $s_pulang;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["karyawan"] = $karyawan;
        $data["filter"] = array("f_perush" => $id_perush, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);
        $data["id"] = Perijinan::getIjin("id", $dr_tgl, $sp_tgl, $id_perush);
        $data["dk"] = Perijinan::getIjin("dk", $dr_tgl, $sp_tgl, $id_perush);
        
        return view("kepegawaian::cetaklaporandenda", $data);
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        return view('kepegawaian::settingdenda');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["data"] = SettingDenda::findOrFail($id);
        $data["jenis"] = JenisPerijinan::select("id_jenis", "nm_jenis")->get();
        $data["perusahaan"] = Perusahaan::getRoleUser();

        return view('kepegawaian::settingdenda', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $denda = SettingDenda::findOrFail($id);
            $denda->id_perush = $request->id_perush;
            $denda->id_user = Auth::user()->id_user;
            $denda->frekuensi = $request->frekuensi;
            $denda->id_jenis = $request->id_jenis;
            $denda->nominal = $request->nominal;
            $denda->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Denda Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("settingdenda"))->with('success', 'Data Setting Denda  Disimpan');
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
            $denda = SettingDenda::findOrFail($id);
            $denda->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Denda Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("settingdenda"))->with('success', 'Data Setting Denda  Disimpan');
    }
    
}
