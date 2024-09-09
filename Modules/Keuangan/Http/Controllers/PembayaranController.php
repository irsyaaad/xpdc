<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\PembayaranDelete;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Keuangan\Entities\ACPerush;
use DB;
use Auth;
use Modules\Keuangan\Http\Requests\PembayaranRequest;
use Modules\Keuangan\Entities\MasterAC;
use App\Models\Perusahaan;
use App\Models\Pelanggan;
use Modules\Keuangan\Entities\DpModel;
use Exception;
use App\Models\Layanan;
use Modules\Operasional\Entities\StatusStt;
use App\Models\Wilayah;

class PembayaranController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    public function index(Request $request)
    {
        $page = $request->shareselect!=null?$request->shareselect:50;
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date("Y-m-01");
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:date("Y-m-t");
        $f_id_stt = $request->f_id_stt!=null?$request->f_id_stt:null;
        $f_id_pelanggan = $request->f_id_pelanggan!=null?$request->f_id_pelanggan:null;
        $f_cr_byr = $request->f_cr_byr!=null?$request->f_cr_byr:null;
        
        $byr = Pembayaran::with("stt")->where("is_aktif", true)
        ->where("id_perush", $id_perush)
        ->where("tgl",">=", $dr_tgl)
        ->where("tgl","<=", $sp_tgl)
        ->orderBy("created_at", "desc");
        
        if($f_id_stt != null){ $byr->where("id_stt", $f_id_stt); }
        if($f_id_pelanggan != null){ $byr->where("id_plgn", $f_id_pelanggan); }
        if($f_cr_byr != null){ $byr->where("id_cr_byr", $f_cr_byr); }
        
        $data["data"] = $byr->paginate($page);
        $data["page"] = $page;
        $data["pelanggan"] = Pelanggan::where("id_perush", $id_perush)->select("id_pelanggan", "nm_pelanggan")->get();
        $data["stt"] = SttModel::where("id_perush_asal", $id_perush)->select("id_stt", "kode_stt")->get();
        $data["cara"] = CaraBayar::where("kode_cr_byr_o","!=", "inv")->where("kode_cr_byr_o","!=", "dp")->get();;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'f_id_pelanggan' => $f_id_pelanggan,
            'f_id_stt' => $f_id_stt,
            'f_cr_byr' => $f_cr_byr
        ];
        $data["cara"]    = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["akun"]   = ACPerush::where("is_bank",true)
        ->where("id_perush", $id_perush)
        ->orWhere("is_kas",true)
        ->where("id_perush", $id_perush)->get();
        
        return view('keuangan::bayar.index', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $page = 50;
        $newdata = [];
        $data["temp"] = null;
        $newdata = SttModel::getOrder(Session("perusahaan")["id_perush"]);
        if(isset($request) and count($request->request) > 0){
            $id_stt = null;
            $id_pelanggan = null;
            
            if(isset($request->stt)){
                $id_stt = $request->stt;
                $newdata = $newdata->where("id_stt",$id_stt);
            }
            if(isset($request->pelanggan_id)){
                $id_pelanggan = $request->pelanggan_id;
                $newdata = $newdata->where("id_plgn",$id_pelanggan);
            }
            
            $pelanggan = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_pelanggan", $id_pelanggan)->get()->first();
            $stt = SttModel::select("id_stt", "kode_stt")->where("id_stt", $id_stt)->get()->first();
            $data["temp"] = array("pelanggan"=> $pelanggan, "stt"=>$stt);
            
        }else{
            $newdata = $newdata;
        }
        
        $data["stt"]  = $newdata->paginate($page);
        
        return view('keuangan::bayar.index', $data);
    }
    
    public function bayar($id=null)
    {
        $stt = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->findOrFail($id);
        $data["cara"] = CaraBayar::all();
        if($stt->id_cr_byr_o=="cash"){
            $data["akun"] = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])->where("is_kas", true)->get();
        }else{
            $data["akun"] = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])
            ->where("is_bank", true)
            ->orWhere("is_kas", true)
            ->where("id_perush", Session("perusahaan")["id_perush"])
            ->get();
        }
        
        $data["data"] = $stt;
        
        return view('keuangan::bayar.index', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(PembayaranRequest $request, $id)
    {
        try {
            
            DB::beginTransaction();
            $bayar                  = new Pembayaran();
            $bayar->id_cr_byr   = $request->id_cr_byr;
            $bayar->tgl         = $request->tgl_bayar;
            $bayar->info        = $request->info;
            $bayar->id_perush     = Session("perusahaan")["id_perush"];
            $bayar->id_user        = Auth::user()->id_user;
            $bayar->ac4_d        = $request->ac4_d;
            $bayar->no_bayar        = $request->referensi;
            $bayar->info        = $request->info;
            
            $bayar->id_stt          = $id;
            $bayar->n_bayar         = $request->n_bayar;
            $bayar->tgl             = $request->tgl_bayar;
            $bayar->info            = $request->info;
            $bayar->no_bayar        = $request->no_bayar;
            
            $bayar->tgl_bg          = $request->tgl_bg;
            $bayar->id_plgn         = $request->id_plgn;
            $bayar->is_aktif        = true;
            //$bayar->is_konfirmasi   = true;
            
            // for ac kredit
            $stt                    = SttModel::findOrfail($id);
            $bayar->ac4_k           = $stt->c_ac4_piut;
            $bayar->nm_bayar        = $stt->pengirim_nm;
            
            if(isset($request->nm_bayar) && $request->nm_bayar!=null){
                $bayar->nm_bayar     = $request->nm_bayar;
            }
            
            $bayar->ac4_d           = $request->ac4_d;
            $bayar->id_cr_byr       = $request->id_cr_byr;
            
            if(isset($request->id_cr_byr) && $request->id_cr_byr!="1"){
                $bayar->no_bayar = $request->no_bayar;
                $bayar->nm_bayar = $request->nm_bayar;
                $bayar->tgl_bg = $request->tgl_bg;
            }

            $bayar->id_plgn = $stt->id_plgn;
            $bayar->save();
            $id_update = DB::getPdo()->lastInsertId();

            // cek update stt
            $cek = Pembayaran::where("id_stt", $bayar->id_stt)->sum("n_bayar");
            
            if($cek > $stt->c_total){
                DB::rollback();
                return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembayaran gagal, total bayar terlalu besar');
            }
            
            if($stt->c_total == $cek){
                $data["is_lunas"] = true;
                $data["x_n_piut"] = 0;
                $data["x_n_bayar"] = $cek;
            }else{
                $data["is_lunas"] = false;
                $data["x_n_piut"] = $stt->c_total - $cek;
                $data["x_n_bayar"] = $cek;
            }

            SttModel::where("id_stt", $id)->update($data);
            
            $update = [];
            $update["no_kwitansi"] = $id_update."/".$bayar->id_perush."/KW".date("m")."/".date("Y");
            Pembayaran::where("id_order_pay", $id_update)->update($update);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pembayaran sukses');
    }
    
    public function konfirmasi($id)
    {
        try {
            
            DB::beginTransaction();
            
            $bayar = Pembayaran::findOrFail($id);
            $a_data = [];
            $a_data["is_lunas"] = true;
            $a_data["is_bayar"] = true;
            STTModel::where("id_stt", $bayar->id_stt)->update($a_data);
            $bayar->is_konfirmasi = true;
            $bayar->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        
        return redirect("pembayaran")->with('success', 'Pembayaran sukses');
    }
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $pembayaran = Pembayaran::with("user","stt")->where("id_order_pay",$id)->get()->first();
        $id_stt = $pembayaran->id_stt;
        $data["data"] = $pembayaran;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        
        return view('keuangan::bayar.show', $data);
    }
    
    public function print($id)
    {
        $data["perusahaan"] = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
        $data["data"] = Pembayaran::with("cara")->where("id_order_pay",$id)->get()->first();
        
        return view('keuangan::bayar.cetakkuwitansi', $data);
    }
    
    public function cetak(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        
        $newdata = Pembayaran::with("perusahaan","pelanggan")
        ->where("id_perush",$id_perush)
        ->where("tgl",">=", $dr_tgl)
        ->where("tgl","<=", $sp_tgl);
        $filter = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        if (isset($request->id_pelanggan)) {
            $newdata = $newdata->where('id_plgn',$request->id_pelanggan);
            $filter['pelanggan'] = Pelanggan::findOrFail($request->id_pelanggan);
        }
        if (isset($request->id_stt)) {
            $newdata = $newdata->where('id_stt',$request->id_stt);
            $filter['stt'] = SttModel::findOrFail($request->id_stt);
        }
        
        $data["perusahaan"] = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
        $data["data"] = $newdata->get();
        $data["filter"] = $filter;
        
        return view('keuangan::bayar.cetakpembayaran', $data);
    }
    
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $bayar = Pembayaran::findOrFail($id);
        $data["data"] = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->findOrFail($bayar->id_stt);
        $data["cara"] = CaraBayar::where("id_cr_byr_o","!=", "inv")->where("id_cr_byr_o","!=", "dp")->get();
        $data["akun"] = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])
        ->where("is_bank", true)->where("is_bank", true)->get();
        
        $data["bayar"] = $bayar;
        
        return view('keuangan::bayar.index', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(PembayaranRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $bayar = Pembayaran::findOrFail($id);
            $bayar->id_cr_byr   = $request->id_cr_byr;
            $bayar->tgl         = $request->tgl_bayar;
            $bayar->info        = $request->info;
            $bayar->id_perush     = Session("perusahaan")["id_perush"];
            $bayar->id_user        = Auth::user()->id_user;
            $bayar->ac4_d        = $request->ac4_d;
            $bayar->no_bayar        = $request->referensi;
            $bayar->info        = $request->info;
            // set bayar
            $bayar->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        
        return redirect("pembayaran")->with('success', 'Pembayaran sukses');
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
            
            $bayar = Pembayaran::findOrFail($id);
            $stt = STTModel::findOrFail($bayar->id_stt);
            
            $del = new PembayaranDelete();
            $del->id_order_pay    = $id;
            $del->id_stt          = $bayar->id_stt;
            $del->n_bayar         = $bayar->n_bayar;
            $del->tgl             = $bayar->tgl;
            $del->info            = $bayar->info;
            $del->no_bayar        = $bayar->no_bayar;
            $del->tgl_bg          = $bayar->tgl_bg;
            $del->id_plgn         = $bayar->id_plgn;
            $del->id_perush       = $bayar->id_perush;
            $del->is_aktif        = $bayar->is_aktif;
            $del->is_konfirmasi   = $bayar->is_konfirmasi;
            $del->deleted_by         = Auth::user()->id_user;
            $del->id_user         = $bayar->id_user;
            $del->delete_at       = date("Y-m-d H:i:s");
            $del->id_cr_byr   = $bayar->id_cr_byr;
            $del->id_bank   = $bayar->id_bank;
            $del->nm_bayar   = $bayar->nm_bayar;
            $del->no_kwitansi   = $bayar->no_kwitansi;
            $del->nm_bayar   = $bayar->nm_bayar;
            $del->ac4_d   = $bayar->ac4_d;
            $del->ac4_k   = $bayar->ac4_k;
            $del->id_plgn   = $bayar->id_plgn;
            
            $del->save();
            $bayar->delete();
            
            // cek update stt
            $cek = Pembayaran::where("id_stt", $stt->id_stt)->sum("n_bayar");
            
            $a_data = [];
            $a_data["is_lunas"] = false;
            $a_data["is_bayar"] = false;
            $a_data["x_n_piut"] = $stt->c_total - $cek;
            $a_data["x_n_bayar"] = $cek;
            STTModel::where("id_stt", $bayar->id_stt)->update($a_data);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Pembayaran gagal dihapus'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pembayaran sukses dihapus');
    }
    
    public function sttbelumbayar(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = $request->page!=null?$request->page:1;
        $perpage = $request->shareselect!=null?$request->shareselect:50;
        $f_layanan = $request->f_id_layanan!=null?$request->f_id_layanan:null;
        $f_id_stt = $request->f_id_stt!=null?$request->f_id_stt:null;
        $f_id_pelanggan = $request->f_id_pelanggan!=null?$request->f_id_pelanggan:null;
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date("Y-m-01");
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:date("Y-m-t");
        
        $data["data"] = Pembayaran::getSttBelumBayar($page, $perpage, $id_perush, $f_layanan, $f_id_stt, $f_id_pelanggan, $dr_tgl, $sp_tgl);
        $data["stt"] = SttModel::getSttBelumLunas($id_perush);
        $data["pelanggan"] = Pelanggan::select("id_pelanggan", "nm_pelanggan")
        ->where("id_perush", $id_perush)->get();
        $data["filter"] = array("page"=>$perpage, 
        "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl, 
        "f_id_layanan"=>$f_layanan, 
        "f_id_stt"=>$f_id_stt, 
        "f_id_pelanggan"=>$f_id_pelanggan);

        $data["layanan"]    = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["cara"]    = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["akun"]   = ACPerush::where("is_bank",true)
        ->where("id_perush", $id_perush)
        ->orWhere("is_kas",true)
        ->where("id_perush", $id_perush)->get();
        
        return view('keuangan::bayar.sttbelumbayar', $data);
    }
    
    public function cetaksttbelumlunas()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = Pembayaran::getSttBelumBayarNoPage($id_perush);
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        
        return view('keuangan::bayar.cetaksttbelumlunas', $data);
    }
}
