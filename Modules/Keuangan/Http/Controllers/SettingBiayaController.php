<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Keuangan\Http\Requests\SettingBiayaRequest;
use DB;
use Auth;

class SettingBiayaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $data["data"] = SettingBiaya::with("hutang", "biaya")->get();
        
        return view('keuangan::settingbiaya.index', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["hutang"]             = ACPerush::getPiutang("hutang");
        $data["biaya"]              = ACPerush::getPiutang("biaya");
        $data["ac"] = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["group"] = GroupBiaya::select("id_biaya_grup", "nm_biaya_grup")->get();
        
        return view('keuangan::settingbiaya.index', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(SettingBiayaRequest $request)
    {
        //dd($request->request);
        try {

            // save to user
            DB::beginTransaction();
            
            $setting                       = new SettingBiaya();
            $setting->id_user              = Auth::user()->id_user;
            $setting->id_biaya_grup        = $request->id_biaya_grup;
            $setting->id_ac_hutang         = $request->ac4_hutang;
            $setting->id_ac_biaya          = $request->ac4_biaya;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya  Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        return view('keuangan::show');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["group"]              = GroupBiaya::select("id_biaya_grup", "nm_biaya_grup")->get();
        $data["hutang"]             = ACPerush::getPiutang("hutang");
        $data["biaya"]              = ACPerush::getPiutang("biaya");
        $data["data"] = SettingBiaya::findOrFail($id);
        
        return view('keuangan::settingbiaya.index', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(SettingBiayaRequest $request, $id)
    {
        // save to user
        DB::beginTransaction();
        try {
            
            
            $setting                       = SettingBiaya::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->id_biaya_grup        = $request->id_biaya_grup;
            $setting->id_ac_hutang         = $request->ac4_hutang;
            $setting->id_ac_biaya          = $request->ac4_biaya;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Setting Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya  Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = SettingBiaya::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal Hapus, Data Masih Dipakai Tabel Lain '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Setting Biaya  Dihapus');
    }
}
