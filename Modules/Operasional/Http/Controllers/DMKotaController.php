<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Http\Requests\DaftarMuatRequest;
use Auth;
use DB;
use Exception;
use App\Models\Layanan;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\DetailProyeksi;
use Modules\Operasional\Entities\ProyeksiDm;
use App\Models\Proyeksi;
use  Modules\Operasional\Http\Requests\DMProyeksiRequest;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\HistoryStt;
use App\Models\CronJob;
use Modules\Keuangan\Entities\BiayaHpp;
use App\Models\Vendor;
use App\Models\Wilayah;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Http\Controllers\DMTruckingController;
use Validator;

class DMKotaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    protected $dmtrucking;
    public function __construct(DMTruckingController $dmtrucking)
    {
        $this->dmtrucking = $dmtrucking;
    }
    
    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get()->first();
        $id_dm = $request->id_dm;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $id_status = $request->id_status;
        $id_wil_tujuan = $request->id_wil_tujuan;
        $is_kota = 1;
        
        if(isset($request->shareselect) and $request->shareselect != null){
            $perpage = $request->shareselect;
        }

        if(isset($request->page) and $request->page != null){
            $page = $request->page;
        }
        
        $data["layanan"] = $layanan;
        $data["data"] = DaftarMuat::getDmKota($page, $perpage, $id_perush, $id_dm, $id_wil_tujuan, $id_sopir, $id_armada, $dr_tgl, $sp_tgl, $id_status);
        $data["status"] = StatusDM::getList(1);
        $data["perusahaan"] = Perusahaan::getData();
        $data["sopir"] = Sopir::getData($id_perush);
        $data["armada"] = Armada::getData($id_perush);

        $id_dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $id_dm)->get()->first();
        $id_wil_tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_wil_tujuan)->get()->first();
        $filter = array("page"=>$perpage, "id_dm" => $id_dm, "id_perush" => $id_perush, "id_sopir"=> $id_sopir, "id_armada" => $id_armada, "id_wil_tujuan" => $id_wil_tujuan,  "id_status" => $id_status, "dr_tgl" => $dr_tgl, "sp_tgl"=>$sp_tgl);
        $data["filter"] = $filter;

        return view('operasional::daftarmuat.dmkota', $data);
    }

    public function getdm(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get()->first();
        $data   = DaftarMuat::select("id_dm", "kode_dm")->where(DB::raw("lower(kode_dm)"),'LIKE','%'.strtolower($term).'%')->where("id_layanan", $layanan->id_layanan)
        ->where("id_perush_tj", null)->where("id_ven", null);
        
        if(!get_admin()){
            $data = $data->where("id_perush_dr", $id_perush);
        }

        $data = $data->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["layanan"] = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();
        $data["sopir"] = Sopir::getSopirInActive($id_perush);
        
        return view('operasional::daftarmuat.dmkota', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_layanan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_layanan,id_layanan',
            'id_sopir' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'tgl_berangkat' => 'bail|required|date',
            'tgl_sampai' => 'bail|required|date|after_or_equal:tgl_berangkat',
            'id_wil_tujuan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
        ])->setAttributeNames(['id_layanan' => 'layanan', 'id_sopir' => 'sopir','id_armada' => 'armada', 
            'tgl_berangkat' => 'tgl berangkat', 
            'tgl_sampai' => 'tgl_sampai',
            'id_wil_tujuan' => 'wilayah tujuan']);
        
        if($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        $id_perush = Session("perusahaan")["id_perush"];
        
        $id_dm = null;
        try {
            
            DB::beginTransaction();
            $perush = Perusahaan::findOrfail($id_perush);
            $gen                   = $this->generate($request->id_layanan);

            $dm                = new DaftarMuat();
            $dm->kode_dm        = $gen["kode_dm"];
            $dm->id_perush_dr = $id_perush;
            $dm->id_layanan = $request->id_layanan;
            $dm->id_sopir = $request->id_sopir;
            $dm->id_armada = $request->id_armada;
            $dm->tgl_berangkat = $request->tgl_berangkat;
            $dm->tgl_sampai = $request->tgl_sampai;
            $dm->id_user = Auth::user()->id_user;
            $dm->id_status = 1;
            $dm->id_wil_asal = $perush->id_region;
            $dm->id_wil_tujuan = $request->id_wil_tujuan;
            $dm->save();
            
            $id_dm = $dm->id_dm;
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'Data Kapal Perusahaan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect("dmkota/".$id_dm."/show")->with('success', 'Data Kapal Perusahaan Disimpan');
    }
    
    public function generate($id_layanan)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        
        $time = substr(time(), 3,10);
        $data = [];
        $data["kode_dm"] = "DMK".$id_perush.$id_layanan.$time;

        return $data;
    }
    
    public function updatestatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            // update status stt
            $dm = DaftarMuat::findOrFail($id);
            $stt = DaftarMuat::getTotalKoli($id);
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            
            $id_stt = [];
            //dd($dm);
            if ($stt==null) {
                
                return redirect()->back()->with('error', 'Stt Tidak Ditemukan ');
            }else{
                
                if (isset($dm->id_kapal)) {
                    $kapal = Kapal::findOrFail($dm->id_kapal);
                }else{
                    $kapal = null;
                } 
                
                $status_dm = StatusDM::where("id_status", ">", $dm->id_status)->orderBy("id_status", "asc")->get()->first();
                
                $a_cron = [];
                $stt_awb = [];
                $cron_hs = [];
                
                if($status_dm->id_status!=6){
                    foreach ($stt as $key => $value) {
                        $status = [];
                        $id_stt[$key] = $value->id_stt; 
                        $stt_stat = StatusStt::where("id_ord_stt_stat", ">", $value->id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
                        $status["id_status"] = $stt_stat->id_ord_stt_stat;
                        
                        if($stt_stat->id_ord_stt_stat=="3"){
                            $status["tgl_keluar"] = date("Y-m-d");
                        }
                        
                        SttModel::where("id_stt", $value->id_stt)->update($status);
                        
                        // add history status
                        $hs = new HistoryStt();
                        $hs->id_stt = $value->id_stt;
                        $hs->id_status = $status["id_status"];
                        $hs->id_history = $value->id_stt.$status["id_status"];
                        $hs->id_user    = Auth::user()->id_user;
                        $hs->keterangan = $request->keterangan;
                        $hs->place    = $wilayah->id_wil." - ".$wilayah->nama_wil;
                        
                        if($status_dm->id_status=="3"){
                            if ($kapal != null) {
                                $hs->keterangan = "Berangkat Dengan "." - KM. ".$kapal->nm_kapal;
                            } else {
                                $hs->keterangan = "Berangkat dari Pelabuhan ";
                            }
                        }
                        
                        $hs->keterangan = $hs->keterangan;
                        if($status_dm->id_status<4){
                            $hs->keterangan = $hs->keterangan." ( ".$wilayah->id_wil." - ".$wilayah->nama_wil." )";
                        }
                        
                        $hs->nm_user    = Auth::user()->nm_user;
                        $hs->nm_pengirim = $value->pengirim_nm;
                        $hs->nm_status  = $stt_stat->nm_ord_stt_stat;
                        
                        // if awb allready in field stt
                        $cek_awb = SttModel::where("kode_stt", $value->no_awb)->get()->first();
                        if($cek_awb){
                            SttModel::where("kode_stt", $value->no_awb)->update($status);
                            $hs_awb = new HistoryStt();
                            $hs_awb->id_stt = $value->no_awb;
                            $hs_awb->id_status = $hs->id_status;
                            $hs_awb->id_history = $value->no_awb.$status["id_status"];
                            $hs_awb->id_user    = $hs->id_user;
                            $hs_awb->place    = $hs->place;
                            $hs_awb->keterangan = $hs->keterangan;
                            $hs_awb->nm_user    = $hs->nm_user;
                            $hs_awb->nm_pengirim = $hs->nm_pengirim;
                            $hs_awb->nm_status  = $hs->nm_status;
                            $hs_awb->save();
                            
                            $cron_hs[$key]["tipe"] = "stt";
                            $cron_hs[$key]["id_wil"] = $perush->id_region; 
                            $cron_hs[$key]["status"] = $hs->id_status;
                            $cron_hs[$key]["place"] = $wilayah->id_wil." - ".$wilayah->nama_wil;
                            $cron_hs[$key]["info"] = $hs->keterangan;
                            $cron_hs[$key]["id_user"] = Auth::user()->id_user;
                            $cron_hs[$key]["id_stt"] = $hs->id_stt;
                            $cron_hs[$key]["id_dm"] = $id;
                            $cron_hs[$key]["status"] = "1";
                        }
                        
                        // update history stt
                        $hs->save();
                        
                        $a_cron[$key]["tipe"] = "stt";
                        $a_cron[$key]["id_wil"] = $perush->id_region; 
                        $a_cron[$key]["status"] = $hs->id_status;
                        $a_cron[$key]["place"] = $wilayah->id_wil." - ".$wilayah->nama_wil;
                        $a_cron[$key]["info"] = $hs->keterangan;
                        $a_cron[$key]["id_user"] = Auth::user()->id_user;
                        $a_cron[$key]["id_stt"] = $hs->id_stt;
                        $a_cron[$key]["id_dm"] = $id;
                        $a_cron[$key]["status"] = "1";
                    }   
                    
                    CronJob::insert($a_cron);
                    CronJob::insert($cron_hs);
                }
                
                // update status dm
                $dm->id_status = $status_dm->id_status;
                if($dm->id_status=="2"){
                    $dm->atd       = date("Y-m-d H:i:s");
                }
                
                $dm->save();
                
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Update Status Berhasil');
    }
    
    public function sampai($id, Request $request)
    {
        $rules = array(
            'dok1'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif|max:1024',
            'dok2'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif|max:1024',
            'keterangan'  => 'bail|nullable|max:100',
            'id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
        );
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }else{
            DB::beginTransaction();
            try {
                $id_status                 = 0;
                $stt                       = SttModel::findOrFail($request->id_stt);
                $hs                         = new HistoryStt();
                $id_status                 = $stt->id_status;
                
                if(isset($request->dok1) and $request->file('dok1')!=null){
                    $img = $request->file('dok1');
                    
                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $hs->gambar1 = $image[3];
                }
                
                if(isset($request->dok2) and $request->file('dok2')!=null){
                    $img = $request->file('dok2');
                    
                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $hs->gambar2 = $image[3];
                }
                
                $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "desc")->get()->first();
                $a_data = [];
                $a_data["id_status"] = $statusstt->id_ord_stt_stat;
                $a_data["status_kembali"] = "0";
                $cron_hs = [];
                
                // update stt
                SttModel::where("id_stt", $stt->id_stt)->update($a_data);
                
                // add history stt
                $hs->id_stt = $stt->id_stt;
                $hs->id_status = $a_data["id_status"];
                $hs->id_history = $stt->id_stt.$a_data["id_status"];
                $hs->id_user    = Auth::user()->id_user;
                $hs->nm_user    = Auth::user()->nm_user;
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->keterangan  = $request->keterangan;
                $hs->place    = "";
                $hs->save();
                
                //array cron
                $a_cron = [];
                $a_cron["id_cron"] = Session("perusahaan")["id_perush"].$hs->id_stt.$hs->id_status;
                $a_cron["tipe"] = "stt";
                $a_cron["id_wil"] = ""; 
                $a_cron["status"] = $hs->id_status;
                $a_cron["place"] = "";
                $a_cron["info"] = $hs->nm_status;
                $a_cron["id_user"] = Auth::user()->id_user;
                $a_cron["id_stt"] = $hs->id_stt;
                $a_cron["id_handling"] = $stt->id_stt;
                $a_cron["status"] = "1";
                
                CronJob::insert($a_cron);
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Barang Sudah Terima');
        }
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;
        $data["status"] = StatusDM::getList();
        $data["detail"] = SttModel::getSttDM($id);
        $data["sttstat"] = StatusStt::getList();
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $data["bumum"]= $bumum;
        $data["stt"] = SttDm::getStt($id);
        $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);
        
        return view('operasional::daftarmuat.showdm', $data);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->get();
        $data["sopir"] = Sopir::getSopirInActive($id_perush);
        $dm = DaftarMuat::findOrFail($id);
        $data["wilayah"] = Wilayah::findOrFail($dm->id_wil_tujuan);
        $data["data"] = $dm;
        
        return view('operasional::daftarmuat.dmkota', $data);
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
            'id_layanan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_layanan,id_layanan',
            'id_sopir' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'tgl_berangkat' => 'bail|required|date',
            'tgl_sampai' => 'bail|required|date|after_or_equal:tgl_berangkat',
            'id_wil_tujuan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
        ])->setAttributeNames(['id_layanan' => 'layanan', 'id_sopir' => 'sopir','id_armada' => 'armada', 
            'tgl_berangkat' => 'tgl berangkat', 
            'tgl_sampai' => 'tgl_sampai',
            'id_wil_tujuan' => 'wilayah tujuan']);
        
        if($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }
        $id_perush = Session("perusahaan")["id_perush"];
        
        try {
            
            DB::beginTransaction();
            $dm                = DaftarMuat::findOrfail($id);
            $dm->id_layanan = $request->id_layanan;
            $dm->id_sopir = $request->id_sopir;
            $dm->id_armada = $request->id_armada;
            $dm->tgl_berangkat = $request->tgl_berangkat;
            $dm->tgl_sampai = $request->tgl_sampai;
            $dm->id_user = Auth::user()->id_user;
            $dm->id_status = 1;
            if(isset($request->id_wil_tujuan) and $request->id_wil_tujuan !=null){
                $dm->id_wil_tujuan = $request->id_wil_tujuan;
            }

            $dm->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'Data Kapal Perusahaan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Kapal Perusahaan Disimpan');
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
            $cek = SttDm::getStt($id);
            $cek2 = ProyeksiDm::getProyeksi($id);
            if($cek != null or $cek2 != null){
                return redirect()->back()->with('error', 'DM sudah ada stt dan proyeksi biaya tidak bisa di hapus');
            }else{
                DaftarMuat::where("id_dm", $id)->delete();
                SttDm::where("id_dm", $id)->delete();
                DmKoli::where("id_dm", $id)->delete();
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Daftar muat gagal di hapus'.$e->getMessage());
        }
        return redirect()->back()->with('success', 'Daftar muat berhasil di hapus');
    }
}
