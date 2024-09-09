<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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
use Modules\Operasional\Entities\Handling;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\BiayaHandling;
use Modules\Operasional\Entities\ProyeksiHandling;
use Modules\Operasional\Entities\HandlingDetailPro;
use Modules\Operasional\Http\Requests\BiayaProyeksiRequest;
use Modules\Keuangan\Entities\GroupBiaya;
use Validator;
use App\Models\Vendor;
use App\Models\CronJob;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Entities\TarifHandling;
use Modules\Keuangan\Entities\InvoiceHandling;
use Modules\Keuangan\Entities\InvoiceHandlingPendapatan;
use Modules\Keuangan\Entities\HandlingBiaya;
use Modules\Keuangan\Http\Controllers\InvoiceController;
use App\Models\User;
use Illuminate\Support\Str;
use Storage;

class HandlingKirimanController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
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
        $id_ven = $request->id_ven;
        
        if(isset($request->shareselect) and $request->shareselect != null){
            $perpage = $request->shareselect;
        }
        
        if(isset($request->page) and $request->page != null){
            $page = $request->page;
        }
        
        $data["data"]= SttModel::get_stt_handling(Auth::user()->id_user)->paginate($perpage);
        
        return view('operasional::handling.handlingvendor', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::where("id_perush", $id_perush)->get();
        
        if(get_admin()){
            $sopir = Sopir::all();
        }
        
        $perush = Perusahaan::findOrFail($id_perush);
        
        $data["sopir"] = $sopir;
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::select("id_armada", "nm_armada", "no_plat")->where("id_perush", $id_perush)->get();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", $id_perush)->get();
        
        return view('operasional::handling.handlingvendor', $data);
    }
    
    public function getstttiba(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = SttModel::select("id_stt", "kode_stt", "id_status")
        ->where("kode_stt", 'ILIKE', '%' . $term . '%')
        ->where("id_perush_asal", $id_perush)->get();
        
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
            $status = StatusStt::select("id_ord_stt_stat")->where("id_ord_stt_stat", ">=", "4")->orderBy("id_ord_stt_stat", "asc")->get()->first();
            $stt = Handling::getSttKiriman($id_perush, $status->id_ord_stt_stat, $request->id_dm, $request->id_stt);
            
            $data["id_dm"] = $request->id_dm;
            $data["id_stt"] = SttModel::select("id_stt", "kode_stt")->where("id_stt", $request->id_stt)->get()->first();
        }else{
            $status = StatusStt::select("id_ord_stt_stat")->where("id_ord_stt_stat", ">=", "4")->orderBy("id_ord_stt_stat", "asc")->get()->first();
            $stt = Handling::getSttKiriman($id_perush, $status->id_ord_stt_stat);
        }

        $data["dm"] =  DaftarMuat::select("id_dm", "kode_dm")->where("id_perush_dr", $id_perush)->where("id_status", "4")->get();
        $data["handling"] = $handling;
        $data["id_handling"] = $id;
        $data["data"] = $stt;
        $data["perusahaan"] = Perusahaan::where("id_perush", $id_perush)->get();
        
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
    
    public function getdm()
    {
        $id= Session("perusahaan")["id_perush"];
        $data = DaftarMuat::select("id_dm", "kode_dm")
        ->where("id_perush_dr", $id)->where("id_status", "4")
        ->get();
        
        return response()->json($data); 
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sopir'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'id_ven'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
            'region_tuju' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'keterangan'  => 'bail|nullable|min:4|max:150',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        
        $id_perush = Session("perusahaan")["id_perush"];
        $perush = Perusahaan::FindOrFail($id_perush);
        $id_handling =null;
        
        DB::beginTransaction();
        try {
            
            $gen = $this->generateId($id_perush);
            $status                        = StatusDM::select("id_status")->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
            $handling                      = new Handling();
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr            = $perush->id_region;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_ven              = $request->id_ven;
            $handling->id_user             = Auth::user()->id_user;
            $handling->kode_handling       = strtoupper($gen["kode_handling"]);
            $handling->id_handling          = strtoupper($gen["id_handling"]);
            $handling->id_sopir            = $request->id_sopir;
            $handling->id_status           = $status->id_status;
            $handling->keterangan          = $request->keterangan;
            $handling->is_kirim            = 1;
            $handling->save();
            
            $id_handling = $handling->id_handling;
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("handlingkirim/".$id_handling."/show"))->with('success', 'Data Handling Disimpan');
    }
    
    public function generateId($id_perush)
    {
        $time = substr(time(), 3,10);
        $data["kode_handling"] = "HK".$id_perush.$time;
        $data["id_handling"] = $id_perush.$time;
        
        return $data;
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
        $handling = Handling::findOrFail($id);
        if($handling->id_ven == null){
            return redirect()->back()->with('error', 'Data Handling Bukan Handling Vendor ');
        }
        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::where("id_perush", $id_perush)->get();
        
        if(get_admin()){
            $sopir = Sopir::all();
        }
        
        $perush = Perusahaan::findOrFail($id_perush);
        $data["data"] = $handling;
        $data["sopir"] = $sopir;
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::select("id_armada", "nm_armada", "no_plat")->where("id_perush", $id_perush)->get();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", $id_perush)->get();
        $data["tujuan"] = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $handling->region_tuju)->get()->first();
        
        return view('operasional::handling.handlingvendor', $data);
    }
    
    public function gethandling(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = Handling::select("id_handling", "kode_handling")->where("id_perush", $id_perush)->whereNotNull("id_ven")->where("kode_handling", 'LIKE', '%' . strtoupper($term) . '%')->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_handling, 'value' => strtoupper($value->kode_handling)];
        }  
        
        return response()->json($results); 
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_sopir'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'id_ven'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
            'region_tuju' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'keterangan'  => 'bail|nullable|min:4|max:150',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        
        $id_perush = Session("perusahaan")["id_perush"];
        $perush = Perusahaan::FindOrFail($id_perush);
        
        DB::beginTransaction();
        try {
            
            $handling                      = Handling::findOrFail($id);
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr            = $perush->id_region;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_ven              = $request->id_ven;
            $handling->id_user             = Auth::user()->id_user;
            $handling->id_sopir            = $request->id_sopir;
            $handling->keterangan          = $request->keterangan;
            $handling->is_kirim            = 1;
            $handling->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Handling Disimpan');
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

    public function sampai(Request $request, $id)
    {
        $rules = array(
            'dok1'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif',
            'dok2'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif',
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
                
                $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "desc")->get()->first();
                
                $a_data = [];
                $a_data["id_status"] = $statusstt->id_ord_stt_stat;
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
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->place    = $wilayah->nama_wil;
                $hs->id_wil    = $wilayah->id_wil;
                $hs->gambar1 = $handling->gambar1;
                $hs->gambar2 = $handling->gambar2;
                $hs->no_status = $no;
                $hs->id_perush = $perush->id_perush;
                $hs->keterangan = $statusstt->nm_ord_stt_stat." ( ".$wilayah->nama_wil." )";
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
}
