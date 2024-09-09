<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Http\Requests\DmVendorRequest;
use Auth;
use DB;
use Exception;
use App\Models\Layanan;
use App\Models\Vendor;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\DetailProyeksi;
use App\Models\Proyeksi;
use App\Models\Wilayah;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\StatusDM;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\StatusStt;
use Validator;
use App\Models\CronJob;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Http\Requests\DMProyeksiRequest;
use Modules\Operasional\Entities\Notifikasi;

class DMVendorController extends Controller
{
    public function index(Request $request)
    {
        $page = 50;
        $id_dm = $request->id_dm;
        $id_perush_tj = $request->f_perush;
        $id_ven = $request->id_ven;
        $id_asal = $request->f_asal;
        $id_tujuan = $request->f_tujuan;
        $id_layanan = $request->f_layanan;
        $tglberangkat = $request->tglberangkat;
        $tglsampai = $request->tglsampai;
        $tglsampai = $request->tglsampai;
        $id_status = $request->id_status;
        $id_perush = Session("perusahaan")["id_perush"];
        $is_tiba = $request->is_tiba;
        $id_stt = isset($request->filterstt) ? $request->filterstt : null;
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        
        $a_data = DaftarMuat::getDmVendor($id_perush, true, $id_dm, $id_layanan, $id_perush_tj, $id_ven, $id_asal, $id_tujuan, $tglberangkat, $tglsampai, $id_status, $id_stt)->paginate($page);
        $data["data"] = $a_data;
        $data["layanan"] = Layanan::getLayanan();
        $data["status"] = StatusDM::getList(1);
        $data["vendor"] = Vendor::getData(Session("perusahaan")["id_perush"]);
        $data["wilayah"] = Wilayah::getWilayah();
        
        $filter = array(
            "page"=>$page, 
            "id_dm" => $id_dm,  
            "id_status" => $id_status, 
            "id_layanan" => $id_layanan, 
            "id_asal" => $id_asal, 
            "id_tujuan" => $id_tujuan, 
            "id_ven" => $id_ven, 
            "tglberangkat" => $tglberangkat, 
            "tglsampai"=>$tglsampai,
            "id_stt" => isset($id_stt) ? SttModel::findOrFail($id_stt) : null,
        );
        $data["filter"] = $filter;
        
        return view('operasional::daftarmuat.dmvendor', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["wilayah"] = Wilayah::getKecamatan2();
        $data["vendor"] = Vendor::getData(Session("perusahaan")["id_perush"]);
        $data["cabang"] = Perusahaan::getDataExept();
        
        return view('operasional::daftarmuat.createdmvendor', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    
    public function penerusan(Request $request)
    {
        $rules = array(
            't_id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
        );
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }else{
            DB::beginTransaction();
            try {
                // cek stt
                $stt                       = SttModel::findOrFail($request->t_id_stt);
                $stt->is_penerusan          = 1;
                $stt->save();
                
                // cek history
                $history = HistoryStt::where("id_stt", $request->t_id_stt)->orderBy("no_status", "desc")->get()->first();
                $history->is_penerusan = 1;
                $history->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal Meneruskan Stt '.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Stt Diterusakan Ke Vendor Penerusan');
        }
    }
    
    public function sampai(Request $request)
    {
        $rules = array(
            'dok1'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif',
            'dok2'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif',
            'keterangan'  => 'bail|nullable|max:100',
            'nm_penerima'  => 'bail|nullable|max:100',
            'id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
            'jenis_status'  => 'bail|required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            
        }else{
            DB::beginTransaction();
            try {
                $stt                       = SttModel::findOrFail($request->id_stt);
                $hs                         = new HistoryStt();
                $id_status                 = $request->jenis_status;
                
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
                
                $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat", "kode_status")->where("id_ord_stt_stat", $id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
                $a_data = [];
                $a_data["id_status"] = $id_status;
                $a_data["tgl_update"] = $request->tgl_update;
                $a_data["status_kembali"] = "0";
                $cron_hs = [];
                
                // update stt
                SttModel::where("id_stt", $stt->id_stt)->update($a_data);
                // update cron job
                $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
                
                // add history stt
                $d_stt = HistoryStt::where("id_stt", $stt->id_stt)->orderBy("no_status", "desc")->get()->first();
                $no = 1;
                if($d_stt != null){
                    $no = $d_stt->no_status+1;
                }
                $hs->id_stt = $stt->id_stt;
                $hs->id_status = $a_data["id_status"];
                $hs->kode_status = $statusstt->kode_status;
                $hs->id_user    = Auth::user()->id_user;
                $hs->nm_user    = Auth::user()->nm_user;
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->gambar1 = $hs->gambar1;
                $hs->gambar2 = $hs->gambar2;
                $hs->no_status = $no;
                $hs->id_perush = $perush->id_perush;
                $hs->keterangan = $statusstt->nm_ord_stt_stat." Diterima Oleh : ".$request->jenis_penerima." ";
                $hs->nm_penerima = $request->nm_penerima;
                $hs->tgl_update = $request->tgl_update;
                $hs->jenis_penerima = $request->jenis_penerima;
                $hs->save();
                

                $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

                $pesan = "Hi {$stt->pengirim_nm}, \n";
                $pesan .= "STT : {$stt->kode_stt}, untuk {$stt->penerima_nm} telah *{$hs->keterangan}* pada tanggal : " . dateindo($request->tgl_update) . " diterima oleh *" . strtoupper($request->nm_penerima) . "*";
                $pesan .= "\n\n - " . Session("perusahaan")["nm_perush"] . " -";
                $pesan .= "\n\n_Pesan ini dikirim otomatis oleh sistem_";
                $pesan .= "\n_Informasi detail klik";
                if (!empty($perusahaan->website)) {
                    $pesan .= " {$perusahaan->website}";
                }
                $pesan .= "_\n_Customer Support {$perusahaan->telp_cs}_";

                $notifikasi                      = new Notifikasi();
                $notifikasi->pesan               = $pesan;
                $notifikasi->id_user             = Auth::user()->id_user;
                $notifikasi->id_perush           = Session("perusahaan")["id_perush"];
                $notifikasi->device_id           = $perusahaan->device_id;
                $notifikasi->no_hp_customer      = detect_chat_id($stt->pengirim_telp);

                $notifikasi->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Barang Sudah Terima');
        }
    }
    
    public function store(DmVendorRequest $request)
    {
        $id_dm = null;
        if($request->id_lsj_ven==null and $request->id_ven == null){
            return redirect()->back()->withInput($request->all())->with('error', 'vendor cabang / luar harus di pilih');
        }
        
        try {
            $perush         = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $gen                   = $this->generate($request->id_layanan);
            
            DB::beginTransaction();
            $daftar                     = new DaftarMuat();
            $daftar->id_perush_dr   = Session("perusahaan")["id_perush"];
            $daftar->id_ven         = $request->id_ven;
            $daftar->id_perush_tj = null;
            $daftar->kode_dm        = $gen["kode_dm"];
            $daftar->id_layanan         = $request->id_layanan;
            $daftar->tgl_berangkat      = $request->tgl_berangkat;
            $daftar->tgl_sampai         = $request->tgl_sampai;
            $daftar->nm_dari            = $request->nm_dari;
            $daftar->nm_tuju            = $request->nm_tuju;
            $daftar->nm_pj_dr           = $request->nm_pj_dr;
            $daftar->nm_pj_tuju         = $request->nm_pj_tuju;
            $daftar->id_user            = Auth::user()->id_user;
            $daftar->id_status          = 1;
            $daftar->is_vendor          = true;
            $daftar->id_wil_asal        = $perush->id_region;
            $daftar->id_wil_tujuan      = $request->id_wil;
            $daftar->status_dm_ven      = 1;
            $daftar->cara = $request->cara;
            $daftar->n_harga = $request->n_harga;
            $daftar->id_wil_asal = $request->id_wil_asal;
            $daftar->no_seal         = $request->no_seal;
            $daftar->no_container         = $request->no_container;
            $daftar->keterangan         = $request->keterangan;
            $daftar->save();
            $id_dm = $daftar->id_dm;
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', 'Data Daftar Muat Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect()."/".$id_dm."/show")->with('success', 'Data Daftar Muat Disimpan');
    }
    
    public function generate($id_layanan)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        
        $time = substr(time(), 3,10);
        $data = [];
        $data["kode_dm"] = "DMV".$id_perush.$id_layanan.$time;
        $data["id_dm"] = $id_perush.$id_layanan.$time;
        
        return $data;
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    
    public function show($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrfail($id);
        $data["data"] = $dm;
        $data["status"] = StatusDM::getList(1);
        $data["detail"] = SttModel::getSttDM($id);
        $data["sttstat"] = StatusStt::getList();
        $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $bvendor = ProyeksiDm::getProyeksi($id, "2");
        $data["bumum"] = $bumum;
        $data["bvendor"] = $bvendor;
        $data["stt"] = SttDm::getStt($id);
        
        return view('operasional::daftarmuat.showdm-vendor', $data);
    }
    
    public function detailstt($id, $id_stt)
    {
        $data["data"] = SttModel::findOrFail($id_stt);
        $data["koli"] = DmKoli::where("id_dm", $id)->where("id_stt", $id_stt)->get();
        
        return view('operasional::daftarmuat.showkoli', $data);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["vendor"] = Vendor::getData(Session("perusahaan")["id_perush"]);
        $data["cabang"] = Perusahaan::getDataExept();
        $data["wilayah"] = Wilayah::getKecamatan2();
        
        $dm = DaftarMuat::with("vendor", "wilayah")->findOrFail($id);
        $data["data"] = $dm;
        
        return view('operasional::daftarmuat.createdmvendor', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(DmVendorRequest $request, $id)
    {
        if($request->id_lsj_ven==null and $request->id_ven == null){
            return redirect()->back()->withInput($request->all())->with('error', 'vendor cabang / luar harus di pilih');
        }
        
        try {
            
            DB::beginTransaction();
            
            $perush         = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $daftar                = DaftarMuat::findOrFail($id);
            
            if($request->jenis1==1){
                $daftar->id_perush_dr   = Session("perusahaan")["id_perush"];
                $daftar->id_perush_tj   = $request->id_lsj_ven;
                $daftar->id_ven = null;
            }else{
                $daftar->id_perush_dr   = Session("perusahaan")["id_perush"];
                $daftar->id_ven         = $request->id_ven;
                $daftar->id_perush_tj = null;
            }
            
            $daftar->tgl_berangkat       = $request->tgl_berangkat;
            $daftar->tgl_sampai       = $request->tgl_sampai;
            $daftar->nm_dari       = $request->nm_dari;
            $daftar->nm_tuju       = $request->nm_tuju;
            $daftar->nm_pj_dr       = $request->nm_pj_dr;
            $daftar->nm_pj_tuju       = $request->nm_pj_tuju;
            $daftar->id_user        = Auth::user()->id_user;
            $daftar->id_layanan       = $request->id_layanan;
            $daftar->is_vendor       = true;
            $daftar->id_wil_asal = $perush->id_region;
            $daftar->id_wil_tujuan   = $request->id_wil;
            $daftar->cara = $request->cara;
            $daftar->n_harga = $request->n_harga;
            $daftar->id_wil_asal = $request->id_wil_asal;
            $daftar->keterangan         = $request->keterangan;
            
            if(isset($request->no_seal)){
                $daftar->no_seal         = $request->no_seal;
            }
            
            if(isset($request->no_container)){
                $daftar->no_container         = $request->no_container;
            }
            
            $daftar->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', 'Data Daftar Muat Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect()."/".$id."/show")->with('success', 'Data Daftar Muat Disimpan');
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
    
    public function detail($kode = null, Request $request)
    {
        $dm = DaftarMuat::findorFail($kode);
        $id = $dm->id_dm;
        if($dm->id_status!="1"){
            return redirect()->back()->with('error', 'Access Terbatas');
        }
        
        $data["stt"] = SttModel::getSttKoli($id, $dm->id_perush_dr, $dm->id_perush_tj, $dm->id_layanan,1);
        
        if(isset($request->id_stt)){
            $data["data"] = SttModel::getIdSttKoli($request->id_stt);
            $data["koli"] = OpOrderKoli::getKoliStt($request->id_stt, 1);
            
            if(count($data["koli"])<1){
                return redirect()->back()->with('error', 'Data Stt Tidak Ditemukan');
            }
        }
        
        return view('operasional::daftarmuat.detaildm', $data);
    }
    public function  proyeksi($id)
    {
        abort(404);
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada", "vendor")->findOrFail($id);
        if($dm->is_approve==true){
            return redirect()->back()->with('error', 'Biaya Sudah Di Approve');
        }
        
        // if($dm->id_status!="1"){
            //     return redirect()->back()->with('error', 'Access Terbatas');
            // }
            
            $proyeksi = [];
            if($dm->id_ven!=null){
                $proyeksi = Proyeksi::where("id_perush", $dm->id_perush_dr)->where("id_ven", $dm->id_ven)->where("id_layanan", $dm->id_layanan)->get()->first();
            }else{
                $proyeksi = Proyeksi::where("id_perush", $dm->id_perush_dr)->where("id_perush_tj", $dm->id_perush_tj)->where("id_layanan", $dm->id_layanan)->get()->first();
            }
            
            $detail = [];
            if($proyeksi!=null){
                $detail = DetailProyeksi::with("grup")->where("id_proyeksi", $proyeksi->id_proyeksi)->get();
            }
            
            $total = ProyeksiDm::with("dm", "proyeksi", "group")->where("id_dm", $id)->get();
            
            // jika sudah ada proyeksi dibuat
            if(count($total)==0 and $detail != null){
                try{
                    
                    DB::beginTransaction();
                    // clear all biaya
                    ProyeksiDm::where("id_dm", $id)->delete();
                    
                    // save all biaya
                    $total = 0;
                    foreach($detail as $key => $value) {
                        $proyeksi_dm = new ProyeksiDm();
                        $proyeksi_dm->id_dm = $id;
                        $proyeksi_dm->nominal = $value->nominal;
                        $proyeksi_dm->id_biaya_grup = $value->id_biaya_grup;
                        $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_biaya_grup", $value->id_biaya_grup)->where("id_perush", $dm->id_perush_dr)->get()->first();
                        
                        if($ac == null){
                            return redirect()->back()->with('error', 'Setting biaya Belum Di buat');
                        }
                        
                        $proyeksi_dm->tgl_posting = date("Y-m-d");
                        $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
                        $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
                        $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
                        $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
                        $total += $value->nominal;
                        $proyeksi_dm->id_user = Auth::user()->id_user;
                        $proyeksi_dm->save();
                    }
                    
                    $dm->c_pro = $total;
                    $dm->save();
                    
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator'.$e->getMessage());
                }
                
            }
            
            
            $data["data"] = $dm;
            $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);
            $bstt = ProyeksiDm::getProyeksi($id, "0");
            $bumum = ProyeksiDm::getProyeksi($id, "1");
            $bvendor = ProyeksiDm::getProyeksi($id, "2");
            $data["bstt"] = $bstt;
            $data["bumum"] = $bumum;
            $data["bvendor"] = $bvendor;
            $data["stt"] = SttDm::getStt($id);
            
            return view("operasional::daftarmuat.biayavendor", $data);
        }
        
        public function saveproyeksi(DMProyeksiRequest $request, $id){
            $id_perush = Session("perusahaan")["id_perush"];
            try{
                
                if($request->id_jenis == null){
                    return redirect()->back()->with('error', 'Jenis Biaya Harus Dipilih');
                }
                
                if($request->id_jenis != 0 and $request->nominal == null){
                    return redirect()->back()->with('error', 'Nominal Harus di isi');
                }
                
                DB::beginTransaction();
                $dm = DaftarMuat::findOrFail($id);
                $proyeksi_dm = new ProyeksiDm();
                $proyeksi_dm->id_stt = null;
                $proyeksi_dm->kode_stt = null;
                $proyeksi_dm->nominal = $request->nominal!=null?$request->nominal:0;
                
                if(isset($request->id_stt) and $request->id_stt != null){
                    $stt = SttModel::findOrFail($request->id_stt);
                    $proyeksi_dm->id_stt = $request->id_stt;
                    $proyeksi_dm->kode_stt = $stt->kode_stt;
                    if($request->id_jenis == 0 and $dm->cara ==1){
                        $proyeksi_dm->nominal = $stt->n_berat * $dm->n_harga;
                    }elseif($request->id_jenis == 0 and $dm->cara ==2){
                        $proyeksi_dm->nominal = $stt->n_volume * $dm->n_harga;
                    }elseif($request->id_jenis == 0 and $dm->cara ==3){
                        $proyeksi_dm->nominal = $stt->n_kubik * $dm->n_harga;
                    }elseif($request->id_jenis == 0 and $dm->cara ==4){
                        $stt_dm = SttDm::where("id_dm", $id)->count("id_stt");
                        $proyeksi_dm->nominal = $dm->n_harga / $stt_dm;
                    }
                }
                
                $proyeksi_dm->id_dm = $id;
                $proyeksi_dm->kode_dm = $dm->kode_dm;
                $proyeksi_dm->keterangan = $request->keterangan;
                $proyeksi_dm->id_user = Auth::user()->id_user;
                $proyeksi_dm->id_biaya_grup = $request->id_biaya_grup;
                $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_perush", $id_perush)->where("id_biaya_grup", $request->id_biaya_grup)->get()->first();
                if($ac == null){
                    return redirect()->back()->with('error', 'Setting Biaya Perusahaan Belum Dibuat');
                }
                $proyeksi_dm->tgl_posting = $request->tgl_posting;
                $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
                $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
                $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
                $proyeksi_dm->id_jenis = $request->id_jenis;
                
                if($request->id_jenis!=1){
                    $proyeksi_dm->id_ven = $dm->id_ven;
                    $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
                }
                
                $proyeksi_dm->save();
                
                $sum = ProyeksiDm::where("id_dm", $id)->sum("nominal");
                $dm->c_pro =$sum;
                $dm->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator'.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Proyeksi Biaya Sukses Dibuat');
        }
        
        public function updateproyeksi(DMProyeksiRequest $request, $id){
            $id_perush = Session("perusahaan")["id_perush"];
            DB::beginTransaction();
            try{
                
                if($request->id_jenis != 0 and $request->nominal == null){
                    return redirect()->back()->with('error', 'Nominal Harus di isi');
                }
                
                $proyeksi_dm = ProyeksiDm::findOrFail($id);
                $dm = DaftarMuat::findOrFail($proyeksi_dm->id_dm);
                $proyeksi_dm->id_stt = null;
                $proyeksi_dm->kode_stt = null;
                $proyeksi_dm->nominal = $request->nominal!=null?$request->nominal:0;
                
                if(isset($request->id_stt) and $request->id_stt != null){

                    $stt = SttModel::findOrFail($request->id_stt);
                    $proyeksi_dm->id_stt = $request->id_stt;
                    $proyeksi_dm->kode_stt = $stt->kode_stt;
                    
                    if($proyeksi_dm->id_jenis == 0 and $dm->cara ==1){
                        $proyeksi_dm->nominal = $stt->n_berat * $dm->n_harga;
                    }elseif($proyeksi_dm->id_jenis == 0 and $dm->cara ==2){
                        $proyeksi_dm->nominal = $stt->n_volume * $dm->n_harga;
                    }elseif($proyeksi_dm->id_jenis == 0 and $dm->cara ==3){
                        $proyeksi_dm->nominal = $stt->n_kubik * $dm->n_harga;
                    }elseif($proyeksi_dm->id_jenis == 0 and $dm->cara ==4){
                        $stt_dm = SttDm::where("id_dm", $dm->id_dm)->count("id_stt");
                        $proyeksi_dm->nominal = $dm->n_harga / $stt_dm;
                    }

                }
                
                $proyeksi_dm->keterangan = $request->keterangan;
                $proyeksi_dm->id_user = Auth::user()->id_user;
                $proyeksi_dm->id_biaya_grup = $request->id_biaya_grup;
                $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_perush", $id_perush)->where("id_biaya_grup", $request->id_biaya_grup)->get()->first();
                if($ac == null){
                    return redirect()->back()->with('error', 'Setting Biaya Perusahaan Belum Dibuat');
                }
                $proyeksi_dm->tgl_posting = $request->tgl_posting;
                $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
                $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
                $proyeksi_dm->save();
                
                $sum = ProyeksiDm::where("id_dm", $dm->id_dm)->sum("nominal");
                $dm->c_pro =$sum;
                $dm->save();
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator'.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Proyeksi Biaya Sukses Dibuat');
        }
        
        public function updateStatus($id, Request $request)
        {
            try {
                DB::beginTransaction();
                $data["status_dm_ven"] = "2";
                DaftarMuat::where("id_dm", $id)->update($data);
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                
                return redirect()->back()->with('error', 'Muatan Koli di gagal di tambahkan'.$e->getMessage());
            }
            return redirect()->back()->with('success', 'Muatan Koli di berhasil di tambahkan');
        }
        
        public function cetakDM($id)
        {
            $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
            $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
            $data["stt"]            = SttModel::getDM($id)->get();
            $stt                    = SttModel::getDM($id)->get();
            $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
            $data["id"]             = $id;
            $temp                   = [];
            
            foreach ($stt as $key => $value) {
                $temp[$value->id_cr_byr_o][$key] = $value;
            }
            
            $data["data"]           = $temp;
            $data["carabayar"]      = $cara_bayar;
            return view('operasional::daftarmuat.cetakdm', $data);
        }
        
        public function cetakDMNoTarif($id)
        {
            $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
            $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
            $data["stt"]            = SttModel::getDM($id)->get();
            $stt                    = SttModel::getDM($id)->get();
            $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
            $data["id"]             = $id;
            $temp                   = [];
            
            foreach ($stt as $key => $value) {
                $temp[$value->id_cr_byr_o][$key] = $value;
            }
            
            $data["data"]           = $temp;
            $data["carabayar"]      = $cara_bayar;
            
            return view('operasional::daftarmuat.cetakdm', $data);
        }
        
        public function cetakDMBarcode($id)
        {
            $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
            $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
            $data["stt"]            = SttModel::getDM($id)->get();
            $stt                    = SttModel::getDM($id)->get();
            $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
            $data["id"]             = $id;
            $temp                   = [];
            
            foreach ($stt as $key => $value) {
                $temp[$value->id_cr_byr_o][$key] = $value;
            }
            
            $data["data"]           = $temp;
            $data["carabayar"]      = $cara_bayar;
            
            return view('operasional::daftarmuat.cetakdmbarcode', $data);
        }
        
        public function detaildm($id)
        {
            $data["data"] = DaftarMuat::findOrFail($id);
            return view('operasional::daftarmuat.popupdm', $data);
        }
        
        public function editupdatestatusajax(Request $request)
        {
            $ids = $request->id_stt;
            $status = [];
            $status_stt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "asc")->get();
            foreach ($status_stt as $key => $value) {
                $status[$value->id_ord_stt_stat] = $value->nm_ord_stt_stat;
            }
            $kota = Wilayah::select("nama_wil")->where("id_wil",$request->id_kota)->get()->first();
            $nama_kota = $kota->nama_wil;
            $nama_status = $status[$request->id_status];
            $keterangan = $nama_status." ( ".$nama_kota." ) ";
            // dd($keterangan);
            
            try {
                DB::beginTransaction();
                SttModel::where("id_stt", $request->id_stt)
                ->update([
                    'id_status' => $request->id_status
                ]);
                
                HistoryStt::where("id_history",$request->id_history)
                ->update([
                    'id_status' => $request->id_status,
                    'nm_status' => $nama_status,
                    'keterangan' => $keterangan,
                    'place' => $nama_kota,
                    'id_wil' => $request->id_kota,
                    'tgl_update' => $request->tgl_update,
                    'id_user' => Auth::user()->id_user,
                    'nm_user' => Auth::user()->nm_user,
                ]);
                
                DB::commit();    
                
                return response()->json($request->all());
                
            } catch (Exception $e) {
                return response()->json($e);
            }
            return response()->json($request->all());
        }
    }
    