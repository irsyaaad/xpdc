<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use Exception;
use Illuminate\Routing\Controller;
use App\Models\Proyeksi;
use Modules\Operasional\Http\Requests\TarifProyeksiReq;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\DetailProyeksi;
use Auth;
use Session;
use App\Models\Tarif;
use App\Models\Layanan;
use App\Models\Vendor;
use DB;
use Modules\Keuangan\Entities\SettingBiayaVendor;

class ProyeksiVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {   
        $page = 50;
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }

        $id_perush =Session("perusahaan")["id_perush"];
        $f_id_ven = $request->f_id_ven;
        $f_id_layanan = $request->f_id_layanan; 
        
        $proyeksi = Proyeksi::getByVendor($id_perush, $f_id_ven, $f_id_layanan);

        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        $data["data"] = $proyeksi->paginate($page);
        
        if($f_id_ven != null){
            $f_id_ven = Vendor::select("id_ven", "nm_ven")->where("id_ven", $f_id_ven)->get()->first();
        }

        $data["filter"] = array("page"=>$page, "f_id_layanan"=>$f_id_layanan, "f_id_ven" => $f_id_ven);
        
        return view('operasional::proyeksivendor', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["layanan"] = Layanan::all();

        return view('operasional::proyeksivendor', $data);
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
            $tarif->id_ven = $request->id_ven;
            $tarif->id_layanan = $request->id_layanan;
            $tarif->id_perush = Session("perusahaan")["id_perush"];
            $tarif->id_user = Auth::user()->id_user;
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Proyeksi Vendor Gagal Disimpan');
        }
        
        return redirect(url("proyeksivendor/".$tarif->id_proyeksi."/show"))->with('success', 'Data Proyeksi Vendor Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data["data"] = Proyeksi::with("perusahaan","perusahaantj", "tarif", "vendor", "user", "layanan")->findOrFail($id);
        $data["group"] = SettingBiayaVendor::getBiaya();
        $data["detail"] = DetailProyeksi::getDetail($id);
        
        return view('operasional::proyeksivendor', $data);
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
        $data["data"] = Proyeksi::findOrFail($id);
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["layanan"] = Layanan::all();

        return view('operasional::proyeksivendor', $data);
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

            $d_tarif = Tarif::where("id_layanan", $request->id_layanan)
                        ->where("id_ven", $request->id_ven)
                        ->where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
            
            if($d_tarif==null){
                return redirect()->back()->with('error', 'Tarif Belum Di Buat');
            }
            
            $tarif = Proyeksi::findOrFail($id);
            $tarif->id_tarif  = $d_tarif->id_tarif;
            $tarif->id_ven = $request->id_ven;
            $tarif->id_perush = Session("perusahaan")["id_perush"];
            $tarif->id_user = Auth::user()->id_user;
            
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Proyeksi Vendor Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Proyeksi Vendor Disimpan');
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

        return redirect()->back()->with('success', 'Data Proyeksi Vendor Disimpan');
    }
}
