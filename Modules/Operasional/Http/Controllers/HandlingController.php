<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\Handling;
use Modules\Operasional\Http\Requests\HandlingRequest;
use Modules\Operasional\Http\Requests\SttDmHandlingRequest;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DMTiba;
use App\Models\Perusahaan;
use App\Models\Wilayah;
use App\Models\Layanan;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\SttDm;
use DB;
use Auth;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\BiayaHandling;
use Modules\Operasional\Entities\ProyeksiHandling;
use Modules\Operasional\Entities\HandlingDetailPro;
use Modules\Operasional\Http\Requests\BiayaProyeksiRequest;
use Modules\Keuangan\Entities\GroupBiaya;
use Validator;
use Modules\Keuangan\Http\Controllers\InvoiceController;
use App\Models\User;
use Illuminate\Support\Str;
use Storage;
use App\Models\CronJob;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Entities\TarifHandling;
use Modules\Keuangan\Entities\InvoiceHandling;
use Modules\Keuangan\Entities\InvoiceHandlingPendapatan;
use Modules\Keuangan\Entities\HandlingBiaya;

class HandlingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    protected $InvoiceController;
    public function __construct(InvoiceController $InvoiceController)
    {
        $this->InvoiceController = $InvoiceController;
    }
    
    public function index(Request $request)
    {   
        $page = 1;
        $perpage = 50;

        $id_perush = Session("perusahaan")["id_perush"];
        $id_handling = $request->id_handling;
        $id_wil = $request->id_wil;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $id_status = $request->id_status;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $id_wil = $request->id_wil;
        $id_perush_dr = null;

        if(isset($request->shareselect) and $request->shareselect != null){
            $perpage = $request->shareselect;
        }

        if(isset($request->page) and $request->page != null){
            $page = $request->page;
        }
        
        $data["data"]= Handling::getHandling($page,$perpage, $id_perush, 1, null, $id_handling, $id_perush_dr, $id_wil, $id_sopir, $id_armada, $dr_tgl, $sp_tgl, $id_status);
        $data["sopir"] = Sopir::select("id_sopir", "nm_sopir")->where("id_perush", $id_perush)->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();;
        $data["perusahaan"] = Perusahaan::getDataExept();
        $data["status_handling"] = StatusDM::select("id_status", "nm_status")->where("tipe", "2")->get();

        $id_handling = Handling::select("id_handling", "kode_handling")->where("id_handling", $id_handling)->get()->first();
        $id_wil = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_wil)->get()->first();
        $filter = array("page"=>$perpage, "id_perush_dr"=>$id_perush_dr, "id_wil" => $id_wil, "id_handling"=>$id_handling, "id_status" => $id_status, "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl, "id_sopir"=>$id_sopir, "id_armada" => $id_armada);
        $data["filter"] = $filter;
        
        return view('operasional::handling.dmhandling', $data);
    }
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {   
        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::get_sopir($id_perush);
        
        $perush = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["pengirim"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["sopir"] = $sopir;
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();
        
        return view('operasional::handling.dmhandling', $data);
    }
    
    /**
    * Store a newly created resource in storage.55
    * @param Request $request
    * @return Response
    */
    public function store(HandlingRequest $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $id = null;
        DB::beginTransaction();
        try {
            
            $status                        = StatusDM::select("id_status")->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
            $gen = $this->generateId($id_perush);
            $handling                      = new Handling();
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr           = $request->region_dr;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_user             = Auth::user()->id_user;
            $handling->kode_handling         = strtoupper($gen["kode_handling"]);
            $handling->id_sopir            = $request->id_sopir;
            $handling->id_status           = $status->id_status;
            $handling->keterangan          = $request->keterangan;
            if($request->ambil_gudang == 1){
                $handling->ambil_gudang = $request->ambil_gudang;
            }
            
            $handling->save();
            $id = $handling->id_handling;
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("dmhandling/".$id."/show"))->with('success', 'Data Handling Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $dm = Handling::getById($id);
        $data["km"] = Handling::select("id_handling", "km_akhir")->where("id_handling", "<", $dm->id_handling)->where("id_armada", $dm->id_armada)->whereNotnull("km_akhir")->get()->first();
        $data["data"] = $dm;
        $detail = HandlingStt::getStt($id);
        
        $data["detail"] = $detail;
        $data["status"] = StatusDM::getList();
        $data["end"] = HandlingStt::where("id_status", "<", "7")->where("id_handling", $id)->get();
        
        return view('operasional::handling.showhandling', $data);
    }
    
    public function getdm($id)
    {
        $data = DaftarMuat::select("id_dm", "kode_dm")->where("id_perush_dr", $id)->where("id_ven", null)->where("id_perush_tj", Session("perusahaan")["id_perush"])->where("id_status", "4")->get();
        
        return response()->json($data); 
    }

    public function gethandling(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = Handling::select("id_handling", "kode_handling")->where("id_perush", $id_perush)->whereNull("id_ven")->where("kode_handling", 'LIKE', '%' . strtoupper($term) . '%')->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_handling, 'value' => strtoupper($value->kode_handling)];
        }  
        
        return response()->json($results); 
    }
    
    public function proyeksi($id)
    {   
        $handling = Handling::getById($id);
        
        $proyeksi = ProyeksiHandling::with("group")->where("id_perush", $handling->id_perush)->get();
        
        $biaya = BiayaHandling::where("id_handling", $id)->get()->first();
        
        if($biaya==null){
            DB::beginTransaction();
            try {
                
                $pro_bi = [];
                foreach($proyeksi as $key => $value){
                    $pro_bi[$key]["id_handling"]            = $id;
                    $pro_bi[$key]["kode_handling"]          = $handling->kode_handling;
                    $pro_bi[$key]["id_biaya_grup"]          = $value->id_biaya_grup;
                    $pro_bi[$key]["id_user"]                = Auth::user()->id_user;
                    $pro_bi[$key]["nominal"]                = $value->nominal;
                    $pro_bi[$key]["ac4_debit"]              = $value->id_ac_biaya;
                    $pro_bi[$key]["ac4_kredit"]             = $value->id_ac_hutang;
                    $pro_bi[$key]["id_proyeksi"]            = $value->id_proyeksi;
                    $pro_bi[$key]["tgl_posting"]            = date("Y-m-d");
                    $pro_bi[$key]["n_bayar"]                = 0;
                    $pro_bi[$key]["is_lunas"]               = false;
                    $pro_bi[$key]["id_perush"]              = $handling->id_perush;
                    $pro_bi[$key]["created_at"]             = date("Y-m-d H:i:s");
                    $pro_bi[$key]["updated_at"]             = date("Y-m-d H:i:s");
                    $grup = "";
                    if(isset($value->group->nm_biaya_grup)){
                        $grup = $value->group->nm_biaya_grup;
                    }
                    $pro_bi[$key]["keterangan"] = "Biaya ".$grup." Handling ".$value->kode_handling." ".date("d/m/Y");
                }
                $pro_bi = BiayaHandling::insert($pro_bi);
                // get nominal
                $total = BiayaHandling::where("id_handling", $id)->sum("nominal");
                $a_total["c_biaya"] = $total;
                
                // update sum biaya
                Handling::where("id_handling", $id)->update($a_total);
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data Biaya Gagal Disimpan '.$e->getMessage());
            }
        }
        
        $data["data"] = $handling;
        $data["group"] = SettingBiayaPerush::DataHppPerush($handling->id_perush);
        $data["biaya"] = BiayaHandling::where("id_handling", $id)->orderBy("tgl_posting", "asc")->orderBy("id_biaya", "asc")->get();
        $stt = HandlingStt::select("id_stt", "kode_stt")->where("id_handling", $id)->get();
        $data["detail"] = $stt;
        
        return view('operasional::handling.showhandling', $data);
    }
    
    public function savebiaya(BiayaProyeksiRequest $request, $id)
    {   
        
        $id_perush = Session("perusahaan")["id_perush"];
        $handling = Handling::select("kode_handling")->where("id_handling", $request->id_handling)->get()->first();
        DB::beginTransaction();
        try {
            
            // save biaya
            $biaya                      = new BiayaHandling();
            $biaya->id_handling         = $id;
            $biaya->id_biaya_grup       = $request->id_biaya_grup;
            $biaya->nominal             = $request->nominal;
            $biaya->id_user             = Auth::user()->id_user;
            $biaya->id_perush           = $id_perush;
            $biaya->is_lunas            = false;
            $biaya->n_bayar             = 0;
            
            $grup                       = GroupBiaya::findOrFail($request->id_biaya_grup);
            $ac                         = SettingBiayaPerush::where("id_biaya_grup", $request->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
            
            $stt                        = SttModel::where("id_stt", $request->id_stt)->get()->first();
            
            //for keuangan
            $biaya->tgl_posting         = $request->tgl_posting;
            $biaya->ac4_debit           = $ac->id_ac_biaya;
            $biaya->ac4_kredit          = $ac->id_ac_hutang;
            $biaya->keterangan          = $request->keterangan!=null?$request->keterangan:"Biaya ".$grup->nm_biaya_grup." Handling ".$id." ".date("d/m/Y");
            $biaya->id_stt              = $request->id_stt;
            
            if(isset($request->id_stt) and $request->id_stt!=null){
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $biaya->kode_stt    = $stt->kode_stt;
            }
            
            $biaya->kode_handling    = $handling->kode_handling;
            //dd($biaya);
            $biaya->save();
            
            // sum biaya
            $total = BiayaHandling::where("id_handling", $id)->sum("nominal");
            $a_total["c_biaya"] = $total;
            
            // update sum biaya
            Handling::where("id_handling", $id)->update($a_total);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Biaya Berhasil Disimpan ');
    }
    
    public function showbiaya($id)
    {
        $data = BiayaHandling::findOrFail($id);
        
        return response()->json($data); 
    }
    
    public function updatebiaya(BiayaProyeksiRequest $request, $id)
    {   
        DB::beginTransaction();
        try {
            $handling = Handling::select("kode_handling")->where("id_handling", $request->id_handling)->get()->first();
            $biaya = BiayaHandling::findOrFail($id);
            $biaya->id_biaya_grup = $request->id_biaya_grup;
            $biaya->nominal    = $request->nominal;
            $biaya->keterangan          = $request->keterangan!=null?$request->keterangan:"Biaya ".$grup->nm_biaya_grup." Handling ".$id." ".date("d/m/Y");
            if(isset($request->id_stt) and $request->id_stt!=null){
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $biaya->kode_stt    = $stt->kode_stt;
            }

            $biaya->tgl_posting    = $request->tgl_posting;
            $biaya->kode_handling    = $handling->kode_handling;
            $biaya->id_user    = Auth::user()->id_user;
            $biaya->save();
            
            // sum biaya
            $total = BiayaHandling::where("id_handling", $request->id_handling)->sum("nominal");
            $a_total["c_biaya"] = $total;
            
            // update sum biaya
            Handling::where("id_handling", $request->id_handling)->update($a_total);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Biaya Berhasil Disimpan ');
    }
    
    public function deletebiaya($id)
    {
        DB::beginTransaction();
        
        try {
            
            $delete = BiayaHandling::findOrFail($id);
            $id_handling = $delete->id_handling;
            $delete->delete();
            
            // sum biaya
            $total = BiayaHandling::where("id_handling", $id_handling)->sum("nominal");
            $a_total["c_biaya"] = $total;
            
            // update sum biaya
            Handling::where("id_handling", $id_handling)->update($a_total);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Biaya Dihapus');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $handling = Handling::findOrFail($id);
        if($handling->id_ven != null){
            return redirect()->back()->with('error', 'Data Handling Bukan Handling Cabang ');
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::where("id_perush", $id_perush)->get();
        
        if(get_admin()){
            $sopir = Sopir::all();
        }
        
        $perush = Perusahaan::findOrFail($id_perush);
        $data["sopir"] = $sopir;
        $data["data"] = $handling;
        $data["tujuan"] = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $handling->region_tuju)->get()->first();
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::where("id_perush", $id_perush)->get();
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        
        return view('operasional::handling.dmhandling', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        DB::beginTransaction();
        try {
            
            $status                        = StatusDM::select("id_status")->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
            $handling                      = Handling::findOrFail($id);
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr           = $request->region_dr;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_user             = Auth::user()->id_user;
            $handling->id_sopir            = $request->id_sopir;
            $handling->keterangan          = $request->keterangan;
            $handling->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Handling Disimpan');
    }
    
    public function destroy($id)
    {   
        DB::beginTransaction();
        try {
            
            $handling                        = Handling::FindOrfail($id);
            $cek                            = InvoiceHandlingPendapatan::where("id_handling", $id)->get()->first();
            
            if($cek != null){
                return redirect()->back()->with('error', 'Handling Gagal Dihapus, Karena sudah dibuatkan invoice tagihan ');
            }
            
            $cek2 = HandlingBiaya::where("id_handling", $id)->get()->first();
            if($cek != null){
                return redirect()->back()->with('error', 'Handling Gagal Dihapus, Karena biaya handling sudah dibayar');
            }
            
            // delete handling stt
            HandlingStt::where("id_handling", $id)->delete();
            
            // delete biaya handling
            BiayaHandling::where("id_handling", $id)->delete();
            
            $handling->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'Data Handling Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Handling Dihapus');
    }
    
    public function getstttiba(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = Handling::getSttTiba($id_perush, null, null, null, null,$term);
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->kode_stt)];
        }  
        
        return response()->json($results); 
    }
    
    public function import(Request $request, $id)
    {   
        $data = [];
        $id_perush = Session("perusahaan")["id_perush"];
        $handling = Handling::FindOrfail($id);
        if($handling->id_status > 6){
            return redirect()->back()->with('error', 'Handling Sudah Berangkat');
        }
        $stt = [];
        if($request->method()=="POST"){
            $dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $request->id_dm)->get()->first();
            if($handling->ambil_gudang=="1"){
                $status = StatusStt::select("id_ord_stt_stat")->orderBy("id_ord_stt_stat", "desc")->get()->first();
                $stt = Handling::getSttAmbilGudang($id_perush, $status->id_ord_stt_stat, null, null, null);
            }else{
                $status = StatusStt::select("id_ord_stt_stat")->where("id_ord_stt_stat", ">=", "4")->orderBy("id_ord_stt_stat", "asc")->get()->first();
                $stt = Handling::getSttTiba($id_perush, $status->id_ord_stt_stat,  null,null, null);
            }

            $data["id_perush"] = $request->id_perush;
            $data["id_dm"] = $request->id_dm;
            if(isset($request->id_dm)){
                $data["kode_dm"] = $dm->kode_dm;
            }
            $data["id_stt"] = $request->id_stt;
        }else{
            
            if($handling->ambil_gudang=="1"){
                $status = StatusStt::select("id_ord_stt_stat")->orderBy("id_ord_stt_stat", "asc")->get()->first();
                $stt = Handling::getSttAmbilGudang($id_perush, $status->id_ord_stt_stat, null, null, null);
            }else{
                $status = StatusStt::select("id_ord_stt_stat")->where("id_ord_stt_stat", ">=", "4")->orderBy("id_ord_stt_stat", "asc")->get()->first();
                $stt = Handling::getSttTiba($id_perush, $status->id_ord_stt_stat,  null,null, null);
            }
        }

        $data["handling"] = $handling;
        $data["id_handling"] = $id;
        $data["data"] = $stt;
        $data["perusahaan"] = Perusahaan::where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();
        
        return view('operasional::handling.import', $data);
    }

    public function doimport(SttDmHandlingRequest $request, $id)
    {   
        $list = [];
        $list = $request->c_stt;
        $id_perush = Session("perusahaan")["id_perush"];
        if($list==null){
            return redirect()->back()->with('error', 'Pilih data STT !');
        }
        
        DB::beginTransaction();
        try {
            
            $stt = [];
            $total = 0;
            foreach($list as $key => $val){
                $data = SttModel::where("id_stt", $val)->get()->first();
                $cek = HandlingStt::where("id_stt", $val)->get()->first();
                $sd = SttDm::select("id_dm")->where("id_stt", $val)->get()->first();
                $dm = DaftarMuat::select("kode_dm")->where("id_dm", $sd->id_dm)->get()->first();
                $handling = Handling::select("id_handling", "kode_handling")->where("id_handling", $id)->get()->first();
                $tarif = TarifHandling::where("id_perush", $id_perush)->where("id_tujuan", $data->penerima_id_region)->get()->first();
                
                $wilayah = Wilayah::select("nama_wil")->where("id_wil", $data->penerima_id_region)->get()->first();
                if($tarif == null){
                    return redirect()->back()->with('error', 'Tarif Handling Wilayah '.$wilayah->nama_wil." Belum Dibuat ");
                }
                
                if($cek!=null){
                    return redirect()->back()->with('error', 'Data STT '.strtoupper($cek->id_stt).' Sudah di Handling');
                }
                
                // for data stt
                $stt[$key]["id_handling"] = $id;
                $stt[$key]["id_detail"] = strtoupper($id.$key.date("is"));
                $stt[$key]["id_dm"] = $sd->id_dm;
                $stt[$key]["kode_dm"] = $dm->kode_dm;
                $stt[$key]["id_stt"] = $val;
                $stt[$key]["id_perush"] = $id_perush;
                $stt[$key]["id_perush_asal"] = $data->id_perush_asal;
                $stt[$key]["tgl_masuk"] = $data->tgl_masuk;
                $stt[$key]["id_plgn"] = $data->id_plgn;
                $stt[$key]["no_awb"] = $data->no_awb;
                $stt[$key]["kode_stt"] = $data->kode_stt;
                $stt[$key]["kode_handling"] = $handling->kode_handling;
                
                // for pengirim data
                $stt[$key]["pengirim_perush"] = $data->pengirim_perush;
                $stt[$key]["pengirim_nm"] = $data->pengirim_nm;
                $stt[$key]["pengirim_telp"] = $data->pengirim_telp;
                $stt[$key]["pengirim_alm"] = $data->pengirim_alm;
                $stt[$key]["pengirim_id_region"] = $data->pengirim_id_region;
                $stt[$key]["pengirim_kodepos"] = $data->pengirim_kodepos;
                
                // for penerima data
                $stt[$key]["penerima_perush"] = $data->penerima_perush;
                $stt[$key]["penerima_nm"] = $data->penerima_nm;
                $stt[$key]["penerima_telp"] = $data->penerima_telp;
                $stt[$key]["penerima_alm"] = $data->penerima_alm;
                $stt[$key]["penerima_id_region"] = $data->penerima_id_region;
                $stt[$key]["penerima_kodepos"] = $data->penerima_kodepos;
                
                // for hitung hitungan harga handling
                $bruto = $data->n_berat * $tarif->hrg_brt;
                $stt[$key]["n_hrg_handling_brt"] = $tarif->hrg_brt;
                $stt[$key]["n_hrg_handling_vol"] = $tarif->hrg_volume;
                $stt[$key]["n_hrg_handling_kubik"] = $tarif->hrg_kubik;
                $stt[$key]["n_borongan"] = 0;
                $stt[$key]["n_hrg_bruto"] = $bruto;
                $stt[$key]["id_tarif_handling"] = $tarif->id_tarif;
                
                // for keu stt
                $stt[$key]["n_berat"] = $data->n_berat;
                $stt[$key]["n_volume"] = $data->n_volume;
                $stt[$key]["n_kubik"] = $data->n_kubik;
                $stt[$key]["id_layanan"] = $data->id_layanan;
                $stt[$key]["n_volume"] = $data->n_volume;
                $stt[$key]["n_koli"] = $data->n_koli;
                $stt[$key]["n_asuransi"] = $data->n_asuransi;
                $stt[$key]["n_diskon"] = $data->n_diskon;
                $stt[$key]["n_materai"] = $data->n_materai;
                $stt[$key]["n_ppn"] = $data->n_ppn;
                $stt[$key]["n_handling"] = $data->n_handling;
                $stt[$key]["n_total"] = $bruto;
                
                // for detail stt
                $stt[$key]["id_tipe_kirim"] = $data->id_tipe_kirim;
                $stt[$key]["id_status"] = $data->id_status;
                $stt[$key]["info_stt"] = $data->info_kirim;
                $stt[$key]["id_packing"] = $data->id_packing;
                $stt[$key]["info_stt"] = $data->info_kirim;
                $stt[$key]["id_user"] = Auth::user()->id_user;
                
                $stt[$key]["cara_hitung"] = 1;
                $stt[$key]["created_at"] = date("Y-m-d H:i:s");
                $stt[$key]["updated_at"] = date("Y-m-d H:i:s");
            }
            
            $insert = HandlingStt::insert($stt);
            $ttl = HandlingStt::where("id_handling", $id)->sum("n_total");
            $a_total = [];
            $a_total["c_total"] = $ttl;
            
            Handling::where("id_handling", $id)->update($a_total);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Handling Disimpan');
    }
    
    public function updatestt($id, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'n_berat' => 'bail|required|numeric',
            'n_volume' => 'bail|required|numeric',
            'n_kubik' => 'bail|required|numeric',
            'n_hrg_handling_brt' => 'bail|required|numeric',
            'n_hrg_handling_vol' => 'bail|required|numeric',
            'n_hrg_handling_kubik' => 'bail|required|numeric',
            'n_borongan' => 'bail|nullable|numeric',
            'c_hitung' => 'bail|required|numeric',
            'n_total' => 'bail|required|numeric',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        
        $id_perush = Session("perusahaan")["id_perush"];
        DB::beginTransaction();
        try {
            $stt = HandlingStt::findOrFail($id);
            $tarif = TarifHandling::where("id_tarif", $stt->id_tarif_handling)->get()->first();
            
            $stt->n_berat = $request->n_berat;
            $stt->n_borongan = $request->n_borongan;
            $stt->n_volume = $request->n_volume;
            $stt->n_kubik = $request->n_kubik;
            $stt->cara_hitung = $request->c_hitung;
            
            $total = 0;
            if($request->c_hitung == "1"){
                $total =  $request->n_berat * $tarif->hrg_brt;
            }elseif($request->c_hitung == "2"){
                $total =  $request->n_volume * $tarif->hrg_volume;
            }
            elseif($request->c_hitung == "3"){
                $total =  $request->n_kubik * $tarif->hrg_kubik;
            }
            else{
                $total = $stt->n_borongan;
            }
            
            $stt->n_hrg_bruto = $total;
            $stt->n_total = $total;
            $stt->save();
            
            // sum total stt handling
            $sum = HandlingStt::where("id_handling", $stt->id_handling)->sum("n_total");
            $a_total = [];
            $a_total["c_total"] = $sum;
            Handling::where("id_handling", $stt->id_handling)->update($a_total);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data stt Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Stt Handling Disimpan');
    }
    
    public function deletestt($id)
    {
        DB::beginTransaction();
        try {
            
            $delete = HandlingStt::findOrFail($id);
            $total = $delete->n_total;
            $id_handling = $delete->id_handling;
            $delete->delete();
            
            $handling = Handling::findOrFail($id_handling);
            
            $a_total = [];
            $a_total["c_total"] = ($handling->c_total-$total);
            
            Handling::where("id_handling", $id_handling)->update($a_total);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Stt Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Stt Dihapus');
    }
    
    public function setberangkat(Request $request, $id)
    {
        $handling = Handling::findOrFail($id);
        $status = StatusDM::select("id_status")->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
        $handlingstt = HandlingStt::select("id_stt")->where("id_handling", $id)->get();
        
        if($request->km_awal==null){
            return redirect()->back()->with('error', 'Kilometer Awal Wajib Di isi ');
        }
        
        if($handling->id_status!=$status->id_status){
            return redirect()->back()->with('error', 'Access terbatas, Handling kemungkinan Sudah Berangkat ');
        }
        
        if(count($handlingstt)<1){
            return redirect()->back()->with('error', 'Access terbatas, Handling Stt tidak boleh kosong ');
        }
        
        DB::beginTransaction();
        try {
            
            $hn = HandlingStt::select("id_stt", "id_perush")->where("id_handling", $id)->get()->first();
            $perush = Perusahaan::findOrfail($hn->id_perush);

            $wilayah = Wilayah::findOrfail($perush->id_region);

            $stt = SttModel::select("id_status")->where("id_stt", $hn->id_stt)->get()->first();

            // update handling
            $statusdm = StatusDM::select("id_status")->where("id_status", ">", $status->id_status)->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
            $handling->id_status = $statusdm->id_status;
            $handling->tgl_berangkat = date("Y-m-d");
            $handling->waktu_berangkat = date("H:i:s");
            $handling->km_awal = $request->km_awal;
            $handling->save();
            
            $id_status = $handling->id_status;
            $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->where("id_ord_stt_stat", $id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();

            $a_cron = [];
            // update all stt
            foreach($handlingstt as $key => $value){
                $a_data = [];
                $a_data["id_status"] = $id_status;
                // update stt
                SttModel::where("id_stt", $value->id_stt)->update($a_data);
                // update handling stt
                HandlingStt::where("id_stt", $value->id_stt)->update($a_data);
                
                // add history stt
                $hs = new HistoryStt();
                $hs->id_stt = $value->id_stt;
                $hs->id_status = $a_data["id_status"];
                $d_stt = HistoryStt::where("id_stt", $value->id_stt)->orderBy("no_status", "desc")->get()->first();
                $no = 1;
                if($d_stt != null){
                    $no = $d_stt->no_status+1;
                }
                $hs->id_user    = Auth::user()->id_user;
                $hs->nm_user    = Auth::user()->nm_user;
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->place    = $wilayah->nama_wil;
                $hs->id_wil    = $wilayah->id_wil;
                $hs->no_status = $no;
                $hs->id_perush = $perush->id_perush;
                $hs->keterangan = $statusstt->nm_ord_stt_stat." ( ".$wilayah->nama_wil." )";
                $hs->nm_penerima = null;
                if(isset($handling->sopir->nm_sopir)){
                    $hs->nm_sopir = $handling->sopir->nm_sopir;
                    $hs->id_sopir = $handling->sopir->id_sopir;
                }
                $hs->save();
                
                $a_cron[$key]["tipe"] = "stt";
                $a_cron[$key]["id_wil"] = $perush->id_region; 
                $a_cron[$key]["status"] = $hs->id_status;
                $a_cron[$key]["place"] = $wilayah->nama_wil;
                $a_cron[$key]["info"] = $hs->keterangan;
                $a_cron[$key]["id_user"] = Auth::user()->id_user;
                $a_cron[$key]["id_stt"] = $hs->id_stt;
                $a_cron[$key]["id_handling"] = $id;
                $a_cron[$key]["status"] = "1";
                $a_cron[$key]["created_at"] = date("Y-m-d h:i:s");
                $a_cron[$key]["updated_at"] = date("Y-m-d h:i:s");
            }
            // save cron job
            CronJob::insert($a_cron);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Handling gagal Berangkat '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Handling Berangkat');
    }
    
    public function setselesai(Request $request, $id)
    {   
        if($request->km_akhir==null){
            return redirect()->back()->with('error', 'Kilometer Akhir Wajib Di isi ');
        }
        
        $handling = Handling::findOrFail($id);
        if($request->km_akhir<$handling->km_awal){
            return redirect()->back()->with('error', 'Kilometer Akhir Tidak Boleh Lebih Kecil Kilometer Awal');
        }
        
        $status1 = StatusDM::select("id_status")->where("tipe", "2")->where("id_status", ">", $handling->id_status)->orderBy("id_status", "asc")->get()->first();
        $handlingstt = HandlingStt::select("id_stt")->where("id_handling", $id)->where("id_status", "<","7")->get();
        
        if(count($handlingstt)>0){
            return redirect()->back()->with('error', 'Pastikan Semua STT Sudah Terkirim');
        }
        
        DB::beginTransaction();
        try {
            $handling->km_akhir = $request->km_akhir;
            $handling->id_status = $status1->id_status;
            $handling->tgl_selesai = date("Y-m-d");
            $handling->waktu_selesai = date("H:i:s");
            $handling->is_selesai = true;
            $handling->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Handling Selesai');
    }
    
    public function sampai(Request $request, $id)
    {
        $rules = array(
            'dok1'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif',
            'dok2'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif',
            'keterangan'  => 'bail|nullable|max:100',
            'nm_penerima'  => 'bail|nullable|max:100',
            'id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_handling_stt,id_detail',
        );
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }else{
            DB::beginTransaction();
            try {
                $id_status                 = 0;
                $handling                  = HandlingStt::findOrFail($request->id_stt);
                $id_status                 = $handling->id_status;
                
                if(isset($request->dok1) and $request->file('dok1')!=null){
                    $img = $request->file('dok1');
                    
                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $handling->gambar1 = $image[3];
                }
                
                if(isset($request->dok2) and $request->file('dok2')!=null){
                    $img = $request->file('dok2');
                    
                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $handling->gambar2 = $image[3];
                }

                $statusstt = SttModel::findOrfail($handling->id_stt);
                $status = StatusStt::findOrfail($statusstt->id_status);

                $a_data = [];
                $a_data["id_status"] = $status->id_ord_stt_stat+1;
                $a_data["status_kembali"] = "0";
                $cron_hs = [];

                // update stt
                SttModel::where("id_stt", $handling->id_stt)->update($a_data);
                
                // update handling stt
                HandlingStt::where("id_detail", $request->id_stt)->update($a_data);
                
                // update cron job
                $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
                
                $wilayah = Wilayah::findOrfail($perush->id_region);
                if($request->id_kota_handling){
                    $wilayah = Wilayah::findOrfail($request->id_kota_handling); 
                }
                // add history stt
                $hs = new HistoryStt();
                $d_stt = HistoryStt::where("id_stt", $handling->id_stt)->orderBy("no_status", "desc")->get()->first();
                $no = 1;
                if($d_stt != null){
                    $no = $d_stt->no_status+1;
                }
                $hs->id_stt = $handling->id_stt;
                $hs->id_status = $a_data["id_status"];
                $hs->id_user    = Auth::user()->id_user;
                $hs->nm_user    = Auth::user()->nm_user;
                $hs->nm_status  = $status->nm_ord_stt_stat;
                $hs->place    = $wilayah->nama_wil;
                $hs->id_wil    = $wilayah->id_wil;
                $hs->gambar1 = $handling->gambar1;
                $hs->gambar2 = $handling->gambar2;
                $hs->no_status = $no;
                $hs->id_perush = $perush->id_perush;
                $hs->keterangan = $status->nm_ord_stt_stat." ( ".$wilayah->nama_wil." )";
                $hs->nm_penerima = $request->nm_penerima;
                $hs->save();
                
                //array cron
                $a_cron = [];
                $a_cron["tipe"] = "stt";
                $a_cron["id_wil"] = $perush->id_region; 
                $a_cron["status"] = $hs->id_status;
                $a_cron["place"] = $wilayah->nama_wil;
                $a_cron["info"] = $hs->keterangan;
                $a_cron["id_user"] = Auth::user()->id_user;
                $a_cron["id_stt"] = $hs->id_stt;
                $a_cron["id_handling"] = $id;
                $a_cron["status"] = "1";
                $a_cron["created_at"] = date("Y-m-d h:i:s");
                $a_cron["updated_at"] = date("Y-m-d h:i:s");
                
                // save cron job
                CronJob::insert($a_cron);
                
                // update handling
                $handling->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Barang Sudah Terima');
        }
    }
    
    public function generateId($id_perush)
    {
        $time = substr(time(), 3,10);
        $data["kode_handling"] = "H".$id_perush.$time;
        
        return $data;
    }
    
    public function apisampai(Request $request, $id)
    {
        $result = [];
        
        $rules = array(
            'dok1'  => 'bail|required',
            'dok2'  => 'bail|required',
            'keterangan'  => 'bail|nullable|max:100',
            'id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_handling_stt,id_detail',
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            $result = [
                "message" => "Access terbatas, pastikan inputan benar",
                "status"    => 0,
                "error"  => $validator->errors()
            ];
            
            return response()->json($result);
            
        }else{
            
            DB::beginTransaction();
            
            try {
                
                $id_status                 = 0;
                $handling                  = HandlingStt::findOrFail($request->id_stt);
                $id_status                 = $handling->id_status;
                
                if(isset($request->dok1) and $request->dok1!=null){
                    $image_64 = $request->dok1;
                    $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];  
                    $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
                    $image = str_replace($replace, '', $image_64); 
                    
                    $image = str_replace(' ', '+', $image); 
                    
                    $imageName = Str::random(10).'.'.$extension;
                    
                    Storage::disk('handling')->put($imageName, base64_decode($image));
                    
                    $handling->gambar1 = $imageName;
                }
                
                if(isset($request->dok2) and $request->dok2!=null){
                    $image_64 = $request->dok2;
                    $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];  
                    $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
                    $image = str_replace($replace, '', $image_64); 
                    
                    $image = str_replace(' ', '+', $image); 
                    
                    $imageName = Str::random(10).'.'.$extension;
                    
                    Storage::disk('handling')->put($imageName, base64_decode($image));
                    
                    $handling->gambar2 = $imageName;
                }
                
                $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->where("id_ord_stt_stat", ">", $handling->id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
                
                $a_data = [];
                $a_data["id_status"] = $statusstt->id_ord_stt_stat;
                
                // update handling stt
                HandlingStt::where("id_detail", $request->id_stt)->update($a_data);
                
                // select user
                $user = User::where("id_sopir", $handling->id_sopir)->get()->first();
                
                // add history stt
                $hs = new HistoryStt();
                $hs->id_stt = $handling->id_stt;
                $hs->id_status = $a_data["id_status"];
                $hs->id_history = $handling->id_stt.$a_data["id_status"];
                $hs->id_user    = $user->id_user;
                $hs->nm_user    = $user->nm_user;
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->keterangan  = $request->keterangan;
                $hs->save();
                
                // update stt
                $a_data["longitude"] = $request->longitude;
                $a_data["latitude"] = $request->latitude;
                $a_data["lokasi"] = $request->lokasi;
                $a_data["status_kembali"] = "0";
                
                // update stt
                SttModel::where("id_stt", $handling->id_stt)->update($a_data);
                
                // handling
                $handling->save();
                
                $a_handling = [];
                $a_handling["lokasi"] = $request->lokasi;
                $a_handling["longitude"] = $request->longitude;
                $a_handling["latitude"] = $request->latitude;
                
                Handling::where("id_handling", $handling->id_handling)->update($a_handling);
                
                DB::commit();
            } catch (Exception $e) {
                
                DB::rollback();
                
                $result = [
                    "message" => "Access terbatas, ".$e->getMessage(),
                    "status"    => 0,
                    "error"  => $e->getMessage()
                ];
                
                return response()->json($result);
            }
            
            $result = [
                "message" => "Update berhasil ",
                "status"    => 1,
                "data"  => []
            ];
            
            return response()->json($result);
        }
    }
    
    public function cetak($id)
    {
        $dm = Handling::getById($id);
        $data["km"] = Handling::select("id_handling", "km_akhir")->where("id_handling", "<", $dm->id_handling)->where("id_armada", $dm->id_armada)->whereNotnull("km_akhir")->get()->first();
        $data["data"] = $dm;
        $detail = HandlingStt::getStt($id);
        
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["detail"] = $detail;
        $data["status"] = StatusDM::where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
        $data["end"] = HandlingStt::where("id_status", "<", "7")->where("id_handling", $id)->get();
        
        return view('operasional::handling.cetakhandling', $data);
    }
    
}
