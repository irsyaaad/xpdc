<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\TarifPacking;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Entities\Packing;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DetailStt;
use App\Models\Pelanggan;
use App\Models\Perusahaan;
use DB;
use Auth;
use Exception;
use Session;
use Modules\Operasional\Entities\PackingBarang;
use Modules\Operasional\Entities\DetailPackingBarang;
use Modules\Keuangan\Entities\SettingPackingPerush;
use Validator;

class PackingBarangController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        
        $data["data"] = PackingBarang::getListPacking($page, $id_perush);
        $data["perusahaan"] = Perusahaan::getRoleUser();
        
        return view('operasional::packing.packingbarang', $data);
    }
    
    public function packing($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $d_packing = PackingBarang::where("id_stt", $id)->get()->first();
        $stt = SttModel::with("pelanggan")->findOrFail($id);
        $setting  = SettingPackingPerush::where("id_perush", $id_perush)->get()->first();
        
        if($setting == null){
            return redirect()->back()->with('error', 'Setting Akun Packing belum ada');
        }
        
        if($d_packing == null){
            return redirect()->back()->with('error', 'Data packing belum di proses');
        }
        
        $cek = PackingBarang::where("id_stt", $id)->where("id_perush", $id_perush)->get()->first();
        $id_packing = null;
        
        if($cek == null){
            DB::beginTransaction();
            
            try {
                $packing                               = new PackingBarang();
                $packing->id_perush                    = $id_perush;
                $packing->id_user                      = Auth::user()->id_user;
                $packing->id_pelanggan                 = $stt->id_plgn;
                $packing->no_awb                       = $stt->no_awb;
                $packing->n_total                      = $d_packing->n_total;
                $packing->n_bayar                      = 0;
                $packing->is_lunas                     = false;
                $packing->id_stt                       = $id;
                $packing->kode_stt                     = $d_packing->kode_stt;
                $packing->nm_pengirim                 = $stt->pengirim_nm;
                $packing->nm_pelanggan                = $stt->pelanggan->nm_pelanggan;
                $time                                 = time();
                $packing->kode_packing                = "PKG".$id_perush.substr($time, 3,10);
                $packing->ac4_d                       = $setting->ac_piutang;
                $packing->ac4_k                       = $setting->ac_pendapatan;
                $packing->save();
                
                $id_packing = $packing->id_packing;
                
                $detail =DetailPackingBarang::where("id_packing", $d_packing->id_packing)->get();
                $a_detail = [];
                foreach($detail as $key => $value){
                    $a_detail[$key]["id_packing"] = $id_packing;
                    $a_detail[$key]["id_jenis_packing"] = $value->id_jenis_packing;
                    $a_detail[$key]["id_tipe_kirim"] = $value->id_tipe_kirim;
                    $a_detail[$key]["koli"] = $value->koli;
                    $a_detail[$key]["panjang"] = $value->panjang;
                    $a_detail[$key]["lebar"] = $value->lebar;
                    $a_detail[$key]["tinggi"] = $value->tinggi;
                    $a_detail[$key]["volume"] = $value->volume;
                    $a_detail[$key]["tarif"] = $value->tarif;
                    $a_detail[$key]["is_borongan"] = $value->is_borongan;
                    $a_detail[$key]["n_borongan"] = $value->n_borongan;
                    $a_detail[$key]["n_total"] = $value->n_total;
                    $a_detail[$key]["keterangan"] = $value->keterangan;
                    $a_detail[$key]["id_user"] = Auth::user()->id_user;
                    $a_detail[$key]["id_stt"] = $value->id_stt;
                    $a_detail[$key]["created_at"] = date("Y-m-d h:i:s");
                    $a_detail[$key]["updated_at"] = date("Y-m-d h:i:s");
                    $a_detail[$key]["id_perush"] = $id_perush;
                }
                
                DetailPackingBarang::insert($a_detail);
                
                DB::commit();
                
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data packing barang Gagal Disimpan'.$e->getMessage());
            }
        }else{
            $id_packing = $cek->id_packing;
        }
        
        $data["packing"] = Packing::getList();
        $data["tipe"] = TipeKirim::select("id_tipe_kirim", "nm_tipe_kirim")->get();
        $data["detail"] = DetailPackingBarang::getListDetail($id_packing);
        $data["barang"] = PackingBarang::findOrFail($id_packing);
        $data["data"] = $stt;
        
        return view('operasional::detail-packing', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["packing"] = Packing::getList();
        $data["jenis"] = TipeKirim::getList();
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["pelanggan"] = Pelanggan::select("id_pelanggan", "nm_pelanggan")->get();
        
        return view('operasional::packing.packingbarang', $data);
    }
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    
    public function showimport()
    {
        $page = 50;
        
        if(isset($request)){
            $id = $request->id_stt;
        }
        
        $data["data"]= SttModel::getSttPacking($page);
        
        return view('operasional::packing.showimport-packingbarang', $data);
    }
    
    public function import($id)
    {
        $stt = SttModel::with("perush_asal")->findOrFail($id);
        $pelanggan = Pelanggan::where("id_perush_cabang", $stt->id_perush_asal)->get()->first();
        $id_perush = Session("perusahaan")["id_perush"];
        
        if($pelanggan == null){
            return redirect()->back()->with('error', 'Data Pelanggan '.$stt->perush_asal->nm_perush.' belum terdaftar');
        }
        
        $data["packing"] = Packing::getList();
        $data["tipe"] = TipeKirim::select("id_tipe_kirim", "nm_tipe_kirim")->get();
        $barang = PackingBarang::where("id_stt", $id)->where("id_perush", $id_perush)->get()->first();
        $detail = [];
        if($barang != null){
            $detail = DetailPackingBarang::getListDetail($barang->id_packing);
        }
        $data["barang"] = $barang;
        $data["data"] = $stt;
        $data["detail"] = $detail;
        
        return view('operasional::packing.import-packingbarang', $data);
    }
    
    public function doimport($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_jenis_packing' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_packing,id_packing',
            'id_tipe_kirim' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_tipe_kirim,id_tipe_kirim',
            'n_koli' => 'bail|numeric|required',
            'panjang' => 'bail|numeric|required',
            'lebar' => 'bail|numeric|required',
            'tinggi' => 'bail|numeric|required',
            'n_volume' => 'bail|numeric|required',
            'tarif' => 'bail|numeric|nullable',
            'is_borongan' => 'bail|numeric|nullable',
            'n_borongan' => 'bail|numeric|nullable',
            'nominal' => 'bail|numeric|required',
            'keterangan' => 'bail|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }  

        $id_perush = Session("perusahaan")["id_perush"];
        
        try {
            
            DB::beginTransaction();
            $stt = SttModel::with("pelanggan")->findOrFail($id);
            $cek = PackingBarang::where("id_stt", $id)->get()->first();
            $pelanggan = Pelanggan::where("id_perush_cabang", $stt->id_perush_asal)->get()->first();
            $setting                              = SettingPackingPerush::where("id_perush", $id_perush)->get()->first();
            
            if($setting == null){
                return redirect()->back()->with('error', 'Setting Akun Packing belum ada');
            }
            
            // save to packing 
            $id_packing = null;
            if(!$cek){
                $packing                               = new PackingBarang();
                $packing->id_perush                    = $id_perush;
                $packing->id_perush_kirim              = $stt->id_perush_asal;
                $packing->id_user                      = Auth::user()->id_user;
                $packing->id_pelanggan                 = $pelanggan->id_pelanggan;
                $packing->no_awb                       = $stt->no_awb;
                $packing->id_stt                       = $id;
                $packing->kode_stt                     = $stt->kode_stt;
                $packing->nm_pengirim                 = $stt->pengirim_nm;
                $packing->nm_pelanggan                = $stt->pelanggan->nm_pelanggan;
                $time                                 = time();
                $packing->kode_packing                = "PKG".$packing->id_perush.substr($time, 3,10);
                $packing->ac4_d                       = $setting->ac_piutang;
                $packing->ac4_k                       = $setting->ac_pendapatan;
                $packing->save();
                
                $id_packing = $packing->id_packing;
            }else{
                
                $id_packing = $cek->id_packing;
            }
            
            // save to packing detail
            $detail = new DetailPackingBarang();
            $detail->id_packing = $id_packing;
            $detail->id_jenis_packing = $request->id_jenis_packing;
            $detail->id_tipe_kirim = $request->id_tipe_kirim;
            $detail->koli = $request->n_koli;
            $detail->panjang = $request->panjang;
            $detail->lebar = $request->lebar;
            $detail->tinggi = $request->tinggi;
            
            // count as detail
            $volume = $detail->panjang  * $detail->tinggi * $detail->lebar;
            
            $detail->volume = $volume;
            $detail->tarif = $request->tarif;
            $detail->is_borongan = $request->is_borongan;
            $detail->n_borongan = $request->n_borongan;
            
            $total = ($detail->tarif * $detail->koli);
            if($detail->is_borongan == 1){
                $total = $detail->n_borongan;
            }
            
            $detail->n_total = $total;
            $detail->keterangan = $request->keterangan;
            $detail->id_stt = $stt->id_stt;
            $detail->id_perush = Session("perusahaan")["id_perush"];
            $detail->save();
            
            // update total packing stt
            $a_total = DetailPackingBarang::where("id_packing", $id_packing)->sum("n_total");
            $sum["n_total"] = $a_total;
            
            PackingBarang::where("id_packing", $id_packing)->update($sum);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data packing barang Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data packing barang Disimpan');
    }
    
    public function store(Request $request)
    {
        try {
            
            DB::beginTransaction();
            $stt = SttModel::findOrFail($request->id_stt);
            
            $packing                      = new PackingBarang();
            $packing->id_perush                    = Session("perusahaan")["id_perush"];
            $packing->id_perush_kirim              = $stt->id_perush_asal;
            $packing->id_user                      = Auth::user()->id_user;
            $packing->id_pelanggan                 = $stt->id_pelanggan;
            $packing->no_awb                       = $stt->no_awb;
            $packing->n_total                       = 0;
            $packing->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data tarif packing Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect("tarifpacking")->with('success', 'Data packing Disimpan');
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
        $id_perush = Session("perusahaan")["id_perush"];
        $data["packing"] = Packing::getList();
        $data["tipe"] = TipeKirim::select("id_tipe_kirim", "nm_tipe_kirim")->get();
        $data["data"] = PackingBarang::findOrFail($id);
        $data["pelanggan"] = Pelanggan::select("nm_pelanggan", "id_pelanggan")->where("id_perush", $id_perush)->get();
        
        return view('operasional::packing.create', $data);
    }
    
    public function editdetail($id)
    {
        $data = DetailPackingBarang::findOrFail($id);
        
        return Response()->json($data);
    }
    
    public function updatedetail($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_jenis_packing' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_packing,id_packing',
            'id_tipe_kirim' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_tipe_kirim,id_tipe_kirim',
            'n_koli' => 'bail|numeric|required',
            'panjang' => 'bail|numeric|required',
            'lebar' => 'bail|numeric|required',
            'tinggi' => 'bail|numeric|required',
            'n_volume' => 'bail|numeric|required',
            'tarif' => 'bail|numeric|nullable',
            'is_borongan' => 'bail|numeric|nullable',
            'n_borongan' => 'bail|numeric|nullable',
            'nominal' => 'bail|numeric|required',
            'keterangan' => 'bail|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }  

        DB::beginTransaction();
        try {
            
            $detail = DetailPackingBarang::findOrFail($id);
            $id_packing = $detail->id_packing;
            $detail->id_jenis_packing = $request->id_jenis_packing;
            $detail->id_tipe_kirim = $request->id_tipe_kirim;
            $detail->koli = $request->n_koli;
            $detail->panjang = $request->panjang;
            $detail->lebar = $request->lebar;
            $detail->tinggi = $request->tinggi;
            
            // count as detail
            $volume = $detail->panjang  * $detail->tinggi * $detail->lebar;
            
            $detail->volume = $volume;
            $detail->tarif = $request->tarif;
            $detail->is_borongan = $request->is_borongan;
            $detail->n_borongan = $request->n_borongan;
            $total = ($detail->tarif * $detail->koli);
            
            if($detail->is_borongan == 1){
                $total = $detail->n_borongan;
            }
            
            $detail->n_total = $total;
            $detail->save();
            
            // update total packing stt
            $a_total = DetailPackingBarang::where("id_packing", $id_packing)->sum("n_total");
            $sum["n_total"] = $a_total;
            
            PackingBarang::where("id_packing", $id_packing)->update($sum);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data packing Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data packing Disimpan');
        
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'id_pelanggan' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_plgn,id_pelanggan',
            'no_awb' => 'bail|nullable|max:32',
            'keterangan' => 'bail|nullable|max:128',
            'nm_pengirim' => 'bail|required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }  

        $id_perush = Session("perusahaan")["id_perush"];
        
        DB::beginTransaction();
        try {
            $setting                              = SettingPackingPerush::where("id_perush", $id_perush)->get()->first();
            $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);
            if($setting == null){
                return redirect()->back()->with('error', 'Setting Akun Packing belum ada');
            }

            $packing                               = PackingBarang::findOrfail($id);
            $packing->id_perush                    = $id_perush;
            $packing->id_user                      = Auth::user()->id_user;
            $packing->id_pelanggan                 = $request->id_pelanggan;
            $packing->no_awb                       = $request->no_awb;
            $packing->nm_pengirim                 = $request->nm_pengirim;
            $packing->nm_pelanggan                = $pelanggan->nm_pelanggan;
            $packing->ac4_d                       = $setting->ac_piutang;
            $packing->ac4_k                       = $setting->ac_pendapatan;
            $packing->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data packing Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data packing Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $packing = PackingBarang::findOrFail($id);
            
            if($packing->n_bayar != "0"){
                return redirect()->back()->with('success', 'Data packing Tidak Bisa Dihapus, Karena Telah Dibayar');
            }
            
            DetailPackingBarang::where("id_packing", $packing->id_packing)->delete();
            $packing->delete();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data packing Barang Gagal Dihapus'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data packing Barang Dihapus');
    }
    
    public function deletedetail($id)
    {
        DB::beginTransaction();
        try {
            $detail = DetailPackingBarang::findOrFail($id); 
            $id_packing = $detail->id_packing;
            $detail->delete();
            
            $a_total = DetailPackingBarang::where("id_packing", $id_packing)->sum("n_total");
            $sum["n_total"] = $a_total;
            
            PackingBarang::where("id_packing", $id_packing)->update($sum);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data packing Barang Gagal Dihapus'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data packing Barang Dihapus');
    }
}
