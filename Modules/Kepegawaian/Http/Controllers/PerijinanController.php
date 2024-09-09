<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Kepegawaian\Entities\Perijinan;
use App\Models\Karyawan;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\SettingJam;
use Auth;
use Validator;
use App\Models\Perusahaan;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Modules\Kepegawaian\Entities\SettingHariLibur;
class PerijinanController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {   
        $page = 50;
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-t");
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $id_perush = Session("perusahaan")["id_perush"];
        $id_karyawan = null;
        $id_jenis = $request->f_id_jenis;
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        if(isset($request->f_dr_tgl) and $request->f_dr_tgl != null){
            $dr_tgl = $request->f_dr_tgl;
        }
        if(isset($request->f_sp_tgl) and $request->f_sp_tgl != null){
            $sp_tgl = $request->f_sp_tgl;
        }
        if(isset($request->f_id_perush) and $request->f_id_perush != null){
            $id_perush = $request->f_id_perush;
        }
        if(isset($request->f_id_karyawan) and $request->f_id_karyawan != null){
            $id_karyawan = $request->f_id_karyawan;
        }
        
        $ijin = Perijinan::with("karyawan","ijin","user")
        ->where("dr_tgl", ">=", $dr_tgl . ' 00:00:00')->where("dr_tgl", "<=", $sp_tgl . ' 23:59:59')
        ->where("id_perush", $id_perush)->orderBy("dr_tgl", "desc");
        
        if($id_karyawan != null){
            $ijin = $ijin->where("id_karyawan", $id_karyawan);
        }
        
        if($id_jenis != null){
            $ijin = $ijin->where("id_jenis", $id_jenis);
        }
        
        $data["data"] = $ijin->paginate($page);
        $data["perush"] = Perusahaan::getRoleUser();
        $data["jenis"] = JenisPerijinan::select("nm_jenis", "id_jenis")->get();
        
        /// for pup konfirmasi
        $tanggal = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
        $hari_ini = date("d");
        $total = $tanggal-$hari_ini;
        if ($total == 1) {
            $data["popup"] = ["oke"];
        }
        
        $filter = array("page" => $page, "f_id_karyawan" => $id_karyawan, "f_id_perush" => $id_perush, "f_id_jenis" => $id_jenis, "f_dr_tgl" => $dr_tgl, "f_sp_tgl" => $sp_tgl);
        $data["filter"] = $filter;
        $data["karyawan"] = Karyawan::select("id_karyawan", "nm_karyawan")->where("id_perush", $id_perush)->get();
        
        return view('kepegawaian::perijinan.index',$data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["jenis"] = JenisPerijinan::all();
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["karyawan"] = Karyawan::select("id_karyawan", "nm_karyawan")->get();
        
        return view('kepegawaian::perijinan.create',$data);
    }
    
    public function creategroup(Request $request)
    {
        $data["jenis"] = JenisPerijinan::where("format", "2")->get();
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["data"] = [];
        $data["id_perush"] = null;
        $data["jenis_perijinan"] = null;
        $data["dr_tgl"] = null;
        $data["sp_tgl"] = null;
        $data["keterangan"] = null;
        
        if ($request->method()=="POST") {
            $data["jenis_perijinan"] = $request->jenis_perijinan;
            $data["dr_tgl"] = $request->dr_tgl;
            $data["sp_tgl"] = $request->sp_tgl;
            $data["id_perush"] = $request->id_perush;
            $data["keterangan"] =  $request->keterangan;
            $data["data"] = Karyawan::getData($request->id_perush);
        }
        
        return view('kepegawaian::perijinan.creategroup',$data);
    }
    
    public function savegroup(Request $request)
    {
        $rules = array(
            'keterangan'  => 'bail|nullable|max:100',
            'jenis_perijinan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_jenis_perijinan,id_jenis',
            'dr_tgl' => 'date|bail|nullable|min:10|max:11',
            'sp_tgl' => 'date|bail|nullable|min:10|max:11|after_or_equal:dr_tgl'
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }
        
        DB::beginTransaction();
        try {
            $ijin = [];
            
            if($request->c_pro == null){
                return redirect()->back()->with('error', 'Karyawan Belum Dipilih');
            }
            
            foreach($request->c_pro as $key => $value){
                $ijin[$value]["id_karyawan"] = $value;
                $ijin[$value]["id_perush"] = $request->id_perush;
                $ijin[$value]["id_jenis"] = $request->jenis_perijinan;
                $ijin[$value]["id_user"] = Auth::user()->id_user;;
                $ijin[$value]["dr_tgl"] = $request->dr_tgl;
                $ijin[$value]["sp_tgl"] = $request->sp_tgl;
                $ijin[$value]["keterangan"] = $request->keterangan;
                
                $tgl1 = new DateTime(date($request->dr_tgl));
                $tgl2 = new DateTime(date($request->sp_tgl));
                $junlah = $tgl2->diff($tgl1)->format("%a");
                $junlah = $junlah+1;
                
                $sunday = SettingHariLibur::getSunday($request->dr_tgl, $request->sp_tgl);
                $libur = SettingHariLibur::getSum($request->id_perush, $request->dr_tgl, $request->sp_tgl);
                $junlah = $junlah-($libur+$sunday);
                
                $ijin[$value]["jumlah"] = $junlah;
                $ijin[$value]["created_at"] = date("Y-m-d H:i:s");
                $ijin[$value]["updated_at"] = date("Y-m-d H:i:s");
            }
            
            Perijinan::insert($ijin);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Perijinan Gagal Disimpan' .$e->getMessage());
        }   
        
        return redirect(route_redirect())->with('success', 'Data Perijinan Disimpan');
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $rules = array(
            'dok1'  => 'bail|image|mimes:jpg,png,jpeg,svg,gif|max:1024',
            'keterangan'  => 'bail|nullable|max:100',
            'id_karyawan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
            'jenis_perijinan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_jenis_perijinan,id_jenis',
            'dr_tgl' => 'date|bail|nullable|min:10|max:11',
            'sp_tgl' => 'date|bail|nullable|min:10|max:11|after_or_equal:dr_tgl',
            'dr_jam' => 'bail|nullable|date_format:H:i',
            'sp_jam' => 'bail|nullable|date_format:H:i|after_or_equal:dr_jam',
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }else{
            try {
                
                DB::beginTransaction();
                $jenis = JenisPerijinan::where("id_jenis", $request->jenis_perijinan)->get()->first();
                
                $ijin                 = new Perijinan();
                $karyawan             = Karyawan::findOrFail($request->id_karyawan); 
                
                $ijin->id_karyawan    = $request->id_karyawan;
                $ijin->id_jenis       = $request->jenis_perijinan;
                $ijin->id_user        = Auth::user()->id_user;
                $ijin->id_perush      = $karyawan->id_perush;
                $ijin->keterangan     = $request->keterangan;
                
                $din = 0;
                if($jenis->format=="1"){
                    $ijin->dr_tgl         = $request->dr_tgl;
                    $ijin->sp_tgl         = $request->dr_tgl;
                    $ijin->dr_jam         = $request->dr_jam;
                    $ijin->sp_jam         = $request->sp_jam;
                    $din = 1;
                }else{
                    $ijin->dr_tgl         = $request->dr_tgl;
                    $ijin->sp_tgl         = $request->sp_tgl;
                    $ijin->dr_jam         = date("H:i:s", strtotime("08:00:00"));
                    $ijin->sp_jam         = date("H:i:s", strtotime("16:00:00"));
                    $tgl1 = new DateTime(date($ijin->dr_tgl));
                    $tgl2 = new DateTime(date($ijin->sp_tgl));
                    
                    $din = $tgl2->diff($tgl1)->format("%a");
                    $din = $din+1;
                }
                
                $sunday = SettingHariLibur::getSunday($ijin->dr_tgl, $ijin->sp_tgl);
                $libur = SettingHariLibur::getSum($ijin->id_perush, $ijin->dr_tgl, $ijin->sp_tgl);
                $din = $din-($libur+$sunday);
                
                if(isset($request->dok1) and $request->file('dok1')!=null){
                    $img = $request->file('dok1');
                    
                    $path_img = $img->store('public/uploads/perijinan');
                    $image = explode("/", $path_img);
                    $ijin->gambar = $image[3];
                }
                
                $ijin->jumlah = $din;
                
                $cek = Perijinan::where("dr_tgl", $ijin->dr_tgl)
                ->where("sp_tgl", $ijin->sp_tgl)
                ->where("id_karyawan", $ijin->id_karyawan)
                ->where("id_jenis", $ijin->id_jenis)->get()->first();
                
                if($cek){
                    return redirect()->back()->with('error', 'Ijin untuk karyawan ini sudah ada !');
                }
                $ijin->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data Perijinan Gagal Disimpan' .$e->getMessage());
            }
        }        
        
        return redirect(route_redirect())->with('success', 'Data Perijinan Disimpan');
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
    
    public function getdetail($id)
    {
        $ijin = Perijinan::with("ijin", "karyawan")->findOrFail($id);
        if($ijin->gambar != null and Storage::exists('public/uploads/perijinan/'.$ijin->gambar)){
            $path = 'public/uploads/perijinan/'.$ijin->gambar;
            
            $full_path = Storage::path($path);
            $base64 = base64_encode(Storage::get($path));
            $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
            $ijin->gambar = $image;
        }else{
            $path = asset("assets/no-image.png");
            $ijin->gambar = $path;
        }
        
        $html = '<div class="col-md-4 mt-2">
        <label>Nama Karyawan</label>
        <input type="text" class="form-control" disabled value="'.$ijin->karyawan->nm_karyawan.'" />
        </div>
        <div class="col-md-4 mt-2">
        <label>Jenis Perijinan</label>
        <input type="text" class="form-control" disabled value="'.$ijin->ijin->nm_jenis.'" />
        </div>
        <div class="col-md-4 mt-2">
        <label>Tgl Ijin</label>
        <input type="text" class="form-control" disabled value="'.dateindo($ijin->dr_tgl).' - '.dateindo($ijin->sp_tgl).'" />
        </div>
        <div class="col-md-4 mt-2">
        <label>Jam Ijin </label>
        <input type="text" class="form-control" disabled value="'.$ijin->dr_jam.' - '.$ijin->sp_jam.'" />
        </div>
        <div class="col-md-4 mt-2">
        <label>Jumlah Hari </label>
        <input type="text" class="form-control" disabled value="'.$ijin->jumlah.'" />
        </div>
        <div class="col-md-4 mt-2">
        <label>Tgl Pengajuan</label>
        <input type="text" class="form-control" disabled value="'.$ijin->created_at.'" />
        </div>
        <div class="col-md-12 mt-2">
        <label>Foto Bukti Ijin</label>
        <br>
        <center><img src="'.$ijin->gambar.'" style="width:50%"></center>
        </div>';
        
        return response()->json($html);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $izin = Perijinan::with("karyawan","ijin","user")->findOrFail($id);
        $jenis = JenisPerijinan::all();
        $data["data"] = $izin;
        $data["format"] = JenisPerijinan::findOrfail($izin->id_jenis);
        $data["jenis"] = $jenis;
        $data["karyawan"] = Karyawan::where("id_karyawan", $izin->id_karyawan)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush", $izin->id_perush)->get();
        
        return view('kepegawaian::perijinan.create',$data);
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
            
            DB::beginTransaction();
            $jenis = JenisPerijinan::where("id_jenis", $request->jenis_perijinan)->get()->first();
            
            $ijin                 = Perijinan::findOrfail($id);
            $karyawan             = Karyawan::findOrFail($request->id_karyawan); 
            $ijin->id_karyawan    = $request->id_karyawan;
            $ijin->id_jenis       = $request->jenis_perijinan;
            $ijin->id_user        = Auth::user()->id_user;
            $ijin->id_perush      = $karyawan->id_perush;
            $ijin->keterangan     = $request->keterangan;
            $ijin->dr_tgl         = $request->dr_tgl;
            $ijin->sp_tgl         = $request->sp_tgl;
            
            $din = 0;
            if($jenis->format=="1"){
                $ijin->dr_tgl         = $request->dr_tgl;
                $ijin->sp_tgl         = $request->dr_tgl;
                $ijin->dr_jam         = $request->dr_jam;
                $ijin->sp_jam         = $request->sp_jam;
                $din = 1;
            }else{
                $ijin->dr_tgl         = $request->dr_tgl;
                $ijin->sp_tgl         = $request->sp_tgl;
                $ijin->dr_jam         = date("H:i:s", strtotime("08:00:00"));
                $ijin->sp_jam         = date("H:i:s", strtotime("16:00:00"));
                $tgl1 = new DateTime(date($ijin->dr_tgl));
                $tgl2 = new DateTime(date($ijin->sp_tgl));
                
                $din = $tgl2->diff($tgl1)->format("%a");
                $din = $din+1;
            }
            
            if(isset($request->dok1) and $request->file('dok1')!=null){
                $img = $request->file('dok1');
                
                if(Storage::exists('public/uploads/perijinan/'.$ijin->gambar)){
                    Storage::delete('public/uploads/perijinan/'.$ijin->gambar);
                }
                $path_img = $img->store('public/uploads/perijinan');
                $image = explode("/", $path_img);
                $ijin->gambar = $image[3];
            }
            
            $ijin->jumlah = $din;
            $ijin->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Perijinan Gagal Disimpan' .$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Jenis Perijinan Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        try {
            
            DB::beginTransaction();
            
            $ijin                  = Perijinan::findOrFail($id);
            $ijin->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Perijinan Gagal Dihapus' .$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Perijinan Dihapus');
    }
    
    public function Laporan(Request $request)
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $jenis = null;
        $id_perush = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->f_perush;
        }
        
        if(isset($request->f_perush) and $request->f_perush != null){
            $id_perush = $request->f_perush;
        }
        
        if(isset($request->f_dr_tgl) and $request->f_dr_tgl != null){
            $dr_tgl = $request->f_dr_tgl;
        }
        
        if(isset($request->f_sp_tgl) and $request->f_sp_tgl != null){
            $sp_tgl = $request->f_sp_tgl;
        }
        
        if(isset($request->f_jenis) and $request->f_jenis != null){
            $jenis = $request->f_jenis;
        }
        
        $ijin = Perijinan::getData($dr_tgl, $sp_tgl, $jenis, $id_perush);
        $a_jenis = JenisPerijinan::select("nm_jenis", "id_jenis")->get();
        $tanggal = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
        $hari_ini = date("d");
        $total = $tanggal-$hari_ini;
        
        if ($total == 1) {
            $data["popup"] = ["oke"];
        }
        
        $data["jenis"] = $a_jenis;
        $data["data"] = $ijin;
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["filter"] = array("f_dr_tgl"=> $dr_tgl, "f_sp_tgl" => $sp_tgl, "f_perush"=>$id_perush, "f_jenis" => $jenis);
        
        return view('kepegawaian::perijinan.laporan',$data);
    }
    
    public function allkonfirmasi(Request $request)
    {
        $tgl_awal = date("Y-m-d", strtotime($request->co_dr_tgl));
        $tgl_akhir = date("Y-m-d", strtotime($request->co_sp_tgl));
        $datetime1 = new DateTime($tgl_awal);
        $datetime2 = new DateTime($tgl_akhir);
        
        $diff = $datetime1->diff($datetime2);
        $diff = $diff->format("%a");
        
        if($diff > 31){
            return redirect()->back()->with('error', 'range date tidak boleh lebih 31 hari');
        }
        
        $ijin = Perijinan::where("dr_tgl", ">=", $request->co_dr_tgl)
        ->where("dr_tgl", "<=", $request->co_sp_tgl)
        ->where("id_perush", $request->co_id_perush)->get();
        
        DB::beginTransaction();
        try {
            foreach ($ijin as $key => $value) {
                $perijinan = Perijinan::findOrFail($value->id_perijinan);
                
                // jenis ijin
                $jenis = JenisPerijinan::where("id_jenis", $perijinan->id_jenis)->get()->first();
                $bln = date("Y-m", strtotime($perijinan->dr_tgl));
                $dr_tgl = date("d", strtotime($perijinan->dr_tgl));
                $sp_tgl = date("d", strtotime($perijinan->sp_tgl));
                
                // karyawan
                $karyawan = Karyawan::findOrFail($perijinan->id_karyawan);
                $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();
                
                // jika izin dinas
                if(strtolower($jenis->id_jenis) == "dk" or strtolower($jenis->id_jenis)=="id"  or strtolower($jenis->id_jenis)=="it" or strtolower($jenis->id_jenis)=="ip"){
                    
                    // cek absensi
                    for($i = $dr_tgl; $i<= $sp_tgl; $i++){
                        $tgl = $bln."-".$i;
                        $dd = date("dmY", strtotime($tgl));
                        $tgl_dinas = date("Y-m-d", strtotime($tgl));
                        
                        $cek = Absensi::where("id_karyawan", $karyawan->id_karyawan)
                        ->where("tgl_absen", $tgl_dinas)->get()->first();
                        
                        $a_absen = [];
                        $a_absen["id_karyawan"] = $karyawan->id_karyawan;
                        $a_absen["id_perush"] = $karyawan->id_perush;
                        $a_absen["status_datang"] = 0;
                        $a_absen["status_pulang"] = 0;
                        $a_absen["status"] = 0;
                        $a_absen["id_finger"] = $karyawan->id_finger;
                        $a_absen["id_admin"] = Auth::user()->id_user;
                        $a_absen["tgl_absen"] = date("Y-m-d", strtotime($tgl_dinas));
                        $a_absen["jam_datang"] = date("H:i:s", strtotime($setting->jam_masuk));
                        $a_absen["jam_pulang"] = date("H:i:s", strtotime($setting->jam_pulang));
                        $a_absen["created_at"] = date("Y-m-d H:i:s");
                        $a_absen["updated_at"] = date("Y-m-d H:i:s");
                        
                        // jika sudah absen akan di update
                        if($cek!=null){
                            Absensi::where("id_absen", $cek->id_absen)->update($a_absen);
                        }
                    }
                }
                
                $a_ijin = [];
                $a_ijin["is_konfirmasi"] = "1";
                Perijinan::where("id_perijinan", $value->id_perijinan)->update($a_ijin);
            }            
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Konfirmasi gagal'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Konfirmasi sukses');
    }

    public function tolak($id)
    {
        // if(Auth::user()->is_kacab !=1){
        //     return redirect()->back()->with('error', 'Akses Gagal, Anda Bukan Kepala Cabang');
        // }
        DB::beginTransaction();
        try {
            
            $perijinan = Perijinan::findOrFail($id);
            $perijinan->is_konfirmasi = null;
            $perijinan->approval = "0";
            $perijinan->user_approval = Auth::user()->id_user;
            $perijinan->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menolak perijinan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Berhasil Menolak perijinan');
    }
    
    public function terima($id)
    {
        // if(Auth::user()->is_kacab !=1){
        //     return redirect()->back()->with('error', 'Akses Gagal, Anda Bukan Kepala Cabang');
        // }
        DB::beginTransaction();
        try {
            
            $perijinan = Perijinan::findOrFail($id);
            $perijinan->is_konfirmasi = "1";
            $perijinan->approval = "1";
            $perijinan->user_approval = Auth::user()->id_user;
            
            // jenis ijin
            $jenis = JenisPerijinan::where("id_jenis", $perijinan->id_jenis)->get()->first();
            $bln = date("Y-m", strtotime($perijinan->dr_tgl));
            $dr_tgl = date("d", strtotime($perijinan->dr_tgl));
            $sp_tgl = date("d", strtotime($perijinan->sp_tgl));
            
            // karyawan
            $karyawan = Karyawan::findOrFail($perijinan->id_karyawan);
            $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();
            
            // jika izin dinas
            if(strtolower($jenis->id_jenis) == "dk" or strtolower($jenis->id_jenis)=="id"  or strtolower($jenis->id_jenis)=="it" or strtolower($jenis->id_jenis)=="ip"){
                
                // cek absensi
                for($i = $dr_tgl; $i<= $sp_tgl; $i++){
                    $tgl = $bln."-".$i;
                    $dd = date("dmY", strtotime($tgl));
                    $tgl_dinas = date("Y-m-d", strtotime($tgl));
                    
                    $cek = Absensi::where("id_karyawan", $karyawan->id_karyawan)
                    ->where("tgl_absen", $tgl_dinas)->get()->first();
                    
                    $a_absen = [];
                    $a_absen["id_karyawan"] = $karyawan->id_karyawan;
                    $a_absen["id_perush"] = $karyawan->id_perush;
                    $a_absen["status_datang"] = 0;
                    $a_absen["status_pulang"] = 0;
                    $a_absen["status"] = 0;
                    $a_absen["id_finger"] = $karyawan->id_finger;
                    $a_absen["id_admin"] = Auth::user()->id_user;
                    $a_absen["tgl_absen"] = date("Y-m-d", strtotime($tgl_dinas));
                    $a_absen["jam_datang"] = date("H:i:s", strtotime($setting->jam_masuk));
                    $a_absen["jam_pulang"] = date("H:i:s", strtotime($setting->jam_pulang));
                    $a_absen["status_istirahat_masuk"] = 0;
                    $a_absen["status_istirahat"] = 0;
                    $a_absen["created_at"] = date("Y-m-d H:i:s");
                    $a_absen["updated_at"] = date("Y-m-d H:i:s");

                    // jika sudah absen akan di update
                    if($cek!=null){
                        Absensi::where("id_absen", $cek->id_absen)->update($a_absen);
                    }
                }
            }
            
            $perijinan->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menerima perijinan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Berhasil Menerima perijinan');
    }
    
    public function konfirmasi($id)
    {
        DB::beginTransaction();
        try {
            
            $perijinan = Perijinan::findOrFail($id);
            $perijinan->is_konfirmasi = "1";
            
            // jenis ijin
            $jenis = JenisPerijinan::where("id_jenis", $perijinan->id_jenis)->get()->first();
            $bln = date("Y-m", strtotime($perijinan->dr_tgl));
            $dr_tgl = date("d", strtotime($perijinan->dr_tgl));
            $sp_tgl = date("d", strtotime($perijinan->sp_tgl));
            
            // karyawan
            $karyawan = Karyawan::findOrFail($perijinan->id_karyawan);
            $setting = SettingJam::where("id_setting", $karyawan->id_jam_kerja)->get()->first();
            
            // jika izin dinas
            if(strtolower($jenis->id_jenis) == "dk" or strtolower($jenis->id_jenis)=="id"  or strtolower($jenis->id_jenis)=="it" or strtolower($jenis->id_jenis)=="ip"){
                
                // cek absensi
                for($i = $dr_tgl; $i<= $sp_tgl; $i++){
                    $tgl = $bln."-".$i;
                    $dd = date("dmY", strtotime($tgl));
                    $tgl_dinas = date("Y-m-d", strtotime($tgl));
                    
                    $cek = Absensi::where("id_karyawan", $karyawan->id_karyawan)
                    ->where("tgl_absen", $tgl_dinas)->get()->first();
                    
                    $a_absen = [];
                    $a_absen["id_karyawan"] = $karyawan->id_karyawan;
                    $a_absen["id_perush"] = $karyawan->id_perush;
                    $a_absen["status_datang"] = 0;
                    $a_absen["status_pulang"] = 0;
                    $a_absen["status"] = 0;
                    $a_absen["id_finger"] = $karyawan->id_finger;
                    $a_absen["id_admin"] = Auth::user()->id_user;
                    $a_absen["tgl_absen"] = date("Y-m-d", strtotime($tgl_dinas));
                    $a_absen["jam_datang"] = date("H:i:s", strtotime($setting->jam_masuk));
                    $a_absen["jam_pulang"] = date("H:i:s", strtotime($setting->jam_pulang));
                    $a_absen["jam_istirahat"] = date("H:i:s", strtotime($setting->jam_istirahat));
                    $a_absen["jam_istirahat_masuk"] = date("H:i:s", strtotime($setting->jam_istirahat_masuk));
                    $a_absen["status_istirahat_masuk"] = 0;
                    $a_absen["status_istirahat"] = 0;
                    $a_absen["created_at"] = date("Y-m-d H:i:s");
                    $a_absen["updated_at"] = date("Y-m-d H:i:s");
                    
                    // jika sudah absen akan di update
                    if($cek!=null){
                        Absensi::where("id_absen", $cek->id_absen)->update($a_absen);
                    }
                }
            }
            
            $perijinan->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Konfirmasi Izin gagal'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Konfirmasi Izin sukses');
    }
    
    public function detail($id)
    {
        $data["data"] = Perijinan::with("karyawan","ijin","user")->findOrFail($id);
        
        return view('kepegawaian::perijinan.laporan',$data);
        
    }
    
    public function cetaklaporan(Request $request)
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $jenis = null;
        $id_perush = Session("perusahaan")["id_perush"];
        $status = null;
        
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->f_perush;
        }
        
        if(isset($request->f_perush) and $request->f_perush != null){
            $id_perush = $request->f_perush;
        }
        
        if(isset($request->f_dr_tgl) and $request->f_dr_tgl != null){
            $dr_tgl = $request->f_dr_tgl;
        }
        
        if(isset($request->f_sp_tgl) and $request->f_sp_tgl != null){
            $sp_tgl = $request->f_sp_tgl;
        }
        
        if(isset($request->f_status) and $request->f_status != null){
            $status = $request->f_status;
        }
        
        if(isset($request->f_jenis) and $request->f_jenis != null){
            $jenis = $request->f_jenis;
        }
        
        $ijin = Perijinan::getData($dr_tgl, $sp_tgl, $jenis, $id_perush, $status);
        $a_jenis = JenisPerijinan::select("nm_jenis", "id_jenis")->get();
        $data["jenis"] = $a_jenis;
        $data["data"] = $ijin;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["perusahaan"] = Perusahaan::findorfail($id_perush);
        
        return view('kepegawaian::perijinan.cetaklaporan',$data);
    }
    
    public function excellaporan()
    {
        $dt = date("Y-m-")."01";
        $sp = date("Y-m-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));
        $jenis = null;
        $id_perush = Session("perusahaan")["id_perush"];
        $status = null;
        
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->f_perush;
        }
        
        if(isset($request->f_perush) and $request->f_perush != null){
            $id_perush = $request->f_perush;
        }
        
        if(isset($request->f_dr_tgl) and $request->f_dr_tgl != null){
            $dr_tgl = $request->f_dr_tgl;
        }
        
        if(isset($request->f_sp_tgl) and $request->f_sp_tgl != null){
            $sp_tgl = $request->f_sp_tgl;
        }
        
        if(isset($request->f_status) and $request->f_status != null){
            $status = $request->f_status;
        }
        
        if(isset($request->f_jenis) and $request->f_jenis != null){
            $jenis = $request->f_jenis;
        }
        
        $ijin = Perijinan::getData($dr_tgl, $sp_tgl, $jenis, $id_perush, $status);
        $a_jenis = JenisPerijinan::select("nm_jenis", "id_jenis")->get();
        $data["jenis"] = $a_jenis;
        $data["data"] = $ijin;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["perusahaan"] = Perusahaan::findorfail($id_perush);
        
        return view('kepegawaian::perijinan.excellaporan',$data);
    }
}
