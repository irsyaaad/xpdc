<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\ProyeksiHandling;
use Modules\Operasional\Http\Requests\ProyeksiHandlingRequest;
use App\Models\Layanan;
use App\Models\Perusahaan;
use DB;
use Session;
use Auth;
use Modules\Keuangan\Entities\GroupBiaya;
use Exception;
use Modules\Keuangan\Entities\SettingHandling;
use Modules\Keuangan\Entities\SettingBiayaPerush;

class ProyeksiHandlingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $biaya = SettingBiayaPerush::DataHppPerush($id_perush);
        $data["data"]   = ProyeksiHandling::getData()->get();
        $data["group"] = $biaya;
        
        return view('operasional::handling.proyeksi', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {   
        abort(404);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(ProyeksiHandlingRequest $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = ProyeksiHandling::where("id_biaya_grup", $request->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Proyeksi Biaya Sudah ada ');
        }
        
        try {
            
            DB::beginTransaction();
            
            $handling                       = new ProyeksiHandling();
            $ac                             = SettingBiayaPerush::where("id_biaya_grup", $request->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
            if($ac == null){
                return redirect()->back()->with('error', 'Setting Akun Handling Belum Ada');
            }
            $handling->id_perush            = $id_perush;
            $handling->id_biaya_grup        = $request->id_biaya_grup;
            $handling->nominal              = $request->nominal;
            $handling->id_ac_biaya          = $ac->id_ac_biaya;
            $handling->id_ac_hutang         = $ac->id_ac_hutang;
            $handling->id_user              = Auth::user()->id_user;

            
            $handling->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Proyeksi Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Proyeksi Biaya Disimpan');
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
        $data["data"]   = ProyeksiHandling::findOrFail($id);
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        
        return view('operasional::handling.proyeksi', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(ProyeksiHandlingRequest $request, $id)
    {   
        $id_perush = Session("perusahaan")["id_perush"];
        try {
            
            DB::beginTransaction();
            
            $handling                       = ProyeksiHandling::findOrFail($id);
            $ac                             = SettingBiayaPerush::where("id_biaya_grup", $request->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
            if($ac == null){
                return redirect()->back()->with('error', 'Setting Akun Handling Belum Ada');
            }
            $handling->id_perush            = $id_perush;
            $handling->id_biaya_grup        = $request->id_biaya_grup;
            $handling->nominal              = $request->nominal;
            $handling->id_ac_biaya                = $ac->id_ac_biaya;
            $handling->id_ac_hutang                = $ac->id_ac_hutang;
            $handling->id_user              = Auth::user()->id_user;
            $handling->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Proyeksi Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Proyeksi Biaya Disimpan');
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
            
            $handling                       = ProyeksiHandling::findOrFail($id);
            $handling->delete();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Proyeksi Masih dipakai tabel lain ');
        }
        
        return redirect()->back()->with('success', 'Data Proyeksi Biaya dihapus');
    }
}
