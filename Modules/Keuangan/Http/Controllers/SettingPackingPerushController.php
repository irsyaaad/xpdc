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

class SettingPackingPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SettingPackingPerush::getData($id_perush);
        
        return view('keuangan::settingpacking.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["pendapatan"]         = ACPerush::select("id_ac", "nama")->where("id_perush", $id_perush)->get();
        $data["piutang"]            = ACPerush::select("id_ac", "nama")->where("id_perush", $id_perush)->get();
        
        return view('keuangan::settingpacking.index', $data);
    }

    public function generate(Type $var = null)
    {
        DB::beginTransaction();
        try {
            $data = SettingPacking::all();
            $setting = [];
            foreach($data as $key => $value){
                $setting[$key]["id_user"]              = Auth::user()->id_user;
                $setting[$key]["ac_pendapatan"]        = $value->ac_pendapatan;
                $setting[$key]["ac_piutang"]        = $value->ac_piutang;
                $setting[$key]["id_perush"]            = Session("perusahaan")["id_perush"];
                $setting[$key]["created_at"]     = date("Y-m-d h:i:s");
                $setting[$key]["updated_at"]     = date("Y-m-d h:i:s");
            }

            SettingPackingPerush::insert($setting);

            DB::commit();
        } catch (Exception $e) {
            DB::commit();
            return redirect()->back()->with('error', 'Data Setting Packing Perusahaan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Packing Perusahaan Disimpan');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingPackingPerush::where("ac_piutang", $request->ac_piutang)
        ->where("ac_pendapatan", $request->ac_pendapatan)
        ->where("id_perush", $id_perush)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Packing Sudah Ada ! ');
        }
        
        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = new SettingPackingPerush();
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->id_perush           = $id_perush;
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
        $data["pendapatan"]         = ACPerush::getPiutang("piutang", $id_perush);
        $data["piutang"]            = ACPerush::getPiutang("pendapatan", $id_perush);
        $data["data"]               = SettingPackingPerush::findOrFail($id);

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
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingPackingPerush::where("ac_piutang", $request->ac_piutang)
        ->where("ac_pendapatan", $request->ac_pendapatan)
        ->where("id_perush", $id_perush)
        ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Packing Sudah Ada ! ');
        }
        
        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = SettingPackingPerush::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->id_perush           = $id_perush;
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
            
            $setting                       = SettingPackingPerush::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Packing Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Packing Dihapus');
    }
}
