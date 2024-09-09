<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\SettingPacking;
use Modules\Keuangan\Entities\SettingPackingPerush;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\MasterAC;
use DB;
use Auth;
use Session;

class SettingPackingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SettingPacking::getData();
        
        return view('keuangan::settingpacking.index', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["pendapatan"]         = ACPerush::select("id_ac", "nama")->get();
        $data["piutang"]            = ACPerush::select("nama", "id_ac")->get();
        
        return view('keuangan::settingpacking.index', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingPacking::where("ac_piutang", $request->ac_piutang)
        ->where("ac_pendapatan", $request->ac_pendapatan)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Packing Sudah Ada ! ');
        }
        
        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = new SettingPacking();
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Packing Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Packing  Disimpan');
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
        $data["pendapatan"]         = MasterAC::select("id_ac", "nama")->get();
        $data["piutang"]            = MasterAC::select("nama", "id_ac")->get();
        $data["data"]               = SettingPacking::findOrFail($id);

        return view('keuangan::settingpacking.index', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $cek = SettingPacking::where("ac_piutang", $request->ac_piutang)
        ->where("ac_pendapatan", $request->ac_pendapatan)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Packing Sudah Ada ! ');
        }
        
        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = SettingPacking::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Packing Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Packing  Disimpan');
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
            
            $setting                       = SettingPacking::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Packing Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Packing Dihapus');
    }
}
