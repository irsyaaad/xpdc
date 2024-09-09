<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Proyeksi;
use App\Models\Layanan;
use Modules\Operasional\Http\Requests\TarifProyeksiReq;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\DetailProyeksi;
use Auth;
use Session;
use App\Models\Tarif;
use DB;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\SettingBiayaPerush;

class TarifProyeksiController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {   
        $page = 50;
        $f_id_perush_tj = $request->f_id_perush_tj;
        $f_id_layanan = $request->f_id_layanan;
        $id_perush = Session("perusahaan")["id_perush"];

        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }
        
        $data["data"] = Proyeksi::getByTarif($id_perush, $f_id_perush_tj, $f_id_layanan)->paginate($page);

        $data["perush"] = Perusahaan::getDataExept();
        $data["layanan"] = Layanan::getLayanan();

        $data["filter"] = array("page" => $page, "f_id_perush_tj" => $f_id_perush_tj, "f_id_layanan"=> $f_id_layanan);

        return view('operasional::tarifproyeksi', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["layanan"] = Layanan::all();
        
        return view('operasional::tarifproyeksi', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(TarifProyeksiReq $request)
    {
        try {
            
            DB::beginTransaction();
            
            $tarif = new Proyeksi();
            $tarif->id_perush_tj = $request->id_perush_tj;
            $tarif->id_layanan = $request->id_layanan;
            $tarif->id_perush = Session("perusahaan")["id_perush"];
            $tarif->id_user = Auth::user()->id_user;
            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Tarif Proyeksi Gagal Disimpan'. $e->getMessage());
        }
        
        return redirect(url("tarifproyeksi/".$tarif->id_proyeksi."/show"))->with('success', 'Data Tarif Proyeksi  Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    
    public function show($id)
    {   
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = Proyeksi::with("perusahaan","tarif", "perusahaantj", "user", "layanan")->findOrFail($id);
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["detail"] = DetailProyeksi::getDetail($id);
        
        return view('operasional::tarifproyeksi', $data);
    }
    
    public function savedetail(Request $request)
    {   
        $cek = DetailProyeksi::where("id_proyeksi", $request->id_proyeksi)->where("id_biaya_grup", $request->id_biaya_grup)->get()->first();
        
        if($cek){
            return redirect()->back()->with('error', 'Proyeksi sudah ada');
        }
        
        try {
            
            DB::beginTransaction();
            
            $detail = new DetailProyeksi();
            $detail->id_proyeksi = $request->id_proyeksi;
            $detail->nominal = $request->nominal;
            $detail->id_biaya_grup = $request->id_biaya_grup;
            $detail->id_user = Auth::user()->id_user;
            $detail->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data detail proyeksi gagal disimpan');
        }
        
        return redirect()->back()->with('success', 'Data detail proyeksi disimpan');
    }
    
    public function showdetail($id)
    {
        $detail = DetailProyeksi::findOrFail($id);
        
        return response()->json($detail); 
    }
    
    public function editdetail(Request $request, $id)
    {
        try {
            
            DB::beginTransaction();
            
            $detail = DetailProyeksi::findOrFail($id);
            $detail->id_proyeksi = $request->id_proyeksi;
            $detail->nominal = $request->nominal;
            $detail->id_biaya_grup = $request->id_biaya_grup;
            $detail->id_user = Auth::user()->id_user;
            $detail->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data detail proyeksi gagal disimpan');
        }
        
        return redirect()->back()->with('success', 'Data detail proyeksi disimpan');
    }
    
    public function deletedetail($id)
    {
        try {
            
            DB::beginTransaction();
            
            $detail = DetailProyeksi::findOrFail($id);
            $detail->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data masih dipakai tabel lain');
        }
        
        return redirect()->back()->with('success', 'Data detail Proyeksi  dihapus');
    }
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["data"] = Proyeksi::with("perusahaan", "tarif", "perusahaantj", "user", "layanan")->findOrFail($id);
        $data["layanan"] = Layanan::all();
        $data["detail"] = [];
        
        return view('operasional::tarifproyeksi', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(TarifProyeksiReq $request, $id)
    {
        try {
            
            DB::beginTransaction();
            
            $tarif = Proyeksi::findOrFail($id);
            $tarif->id_perush_tj = $request->id_perush_tj;
            $tarif->id_layanan = $request->id_layanan;
            $tarif->id_perush = Session("perusahaan")["id_perush"];
            $tarif->id_user = Auth::user()->id_user;
            
            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Tarif Proyeksi Gagal Disimpan');
        }
        
        return redirect("tarifproyeksi")->with('success', 'Data Tarif Proyeksi  Disimpan');
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
            
            $tarif = Proyeksi::findOrFail($id);
            $tarif->delete();
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data masih dipakai tabel lain');
        }
        
        return redirect()->back()->with('success', 'Data Tarif Proyeksi  Disimpan');
    }
}
