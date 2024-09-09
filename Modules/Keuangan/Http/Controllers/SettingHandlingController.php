<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\SettingHandling;
use Modules\Keuangan\Entities\ACPerush;
use App\Models\Perusahaan;
use Modules\Keuangan\Http\Requests\SettingHandlingRequest;
use DB;
use Auth;
use Exception;

class SettingHandlingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $data["data"] = SettingHandling::getData();

        return view('keuangan::settinghandling.index', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["pendapatan"]         = ACPerush::getPiutang("pendapatan");
        $data["piutang"]            = ACPerush::getPiutang("piutang");
        $data["hutang"]             = ACPerush::getPiutang("hutang");
        $data["biaya"]              = ACPerush::getPiutang("biaya");
        $data["perush"]             = Perusahaan::select("id_perush", "nm_perush")->where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();
        
        return view('keuangan::settinghandling.index', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(SettingHandlingRequest $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingHandling::where("ac4_pend_penerima", $request->ac4_pend_penerima)
        ->where("ac4_piutang_penerima", $request->ac4_piutang_penerima)
        ->where("ac4_hutang", $request->ac4_hutang)
        ->where("ac4_biaya", $request->ac4_biaya)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Handling Sudah Ada ! ');
        }
        
        DB::beginTransaction();
        try {
            
            $setting                                = new SettingHandling();
            $setting->id_user                       = Auth::user()->id_user;
            $setting->ac4_pend_penerima             = $request->ac4_pend_penerima;
            $setting->ac4_piutang_penerima          = $request->ac4_piutang_penerima;
            $setting->ac4_hutang                    = $request->ac4_hutang;
            $setting->ac4_biaya                     = $request->ac4_biaya;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Handling  Disimpan');
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
        $data["pendapatan"]          = ACPerush::getChild();
        $data["piutang"]             = ACPerush::getChild();
        $data["hutang"]              = ACPerush::getChild();
        $data["biaya"]               = ACPerush::getChild();
        $data["data"]                = SettingHandling::findOrFail($id);
        
        return view('keuangan::settinghandling.index', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    
    public function update(SettingHandlingRequest $request, $id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingHandling::where("ac4_pend_penerima", $request->ac4_pend_penerima)
        ->where("ac4_piutang_penerima", $request->ac4_piutang_penerima)
        ->where("ac4_hutang", $request->ac4_hutang)
        ->where("ac4_biaya", $request->ac4_biaya)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Handling Sudah Ada ! ');
        }
        
        DB::beginTransaction();
        try {
            
            $setting                            = SettingHandling::findOrFail($id);
            $setting->id_user                   = Auth::user()->id_user;
            $setting->ac4_pend_penerima         = $request->ac4_pend_penerima;
            $setting->ac4_piutang_penerima      = $request->ac4_piutang_penerima;
            $setting->ac4_hutang                = $request->ac4_hutang;
            $setting->ac4_biaya                 = $request->ac4_biaya;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Handling  Disimpan');
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
            
            $setting                       = SettingHandling::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Handling Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Handling  Dihapus');
    }
}
