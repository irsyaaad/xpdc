<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\SettingGroupLayanan;
use Modules\Keuangan\Http\Requests\SettingLayananRequest;
use DB;
use Auth;
use App\Models\Layanan;
use Modules\Keuangan\Entities\ACPerush;
use Exception;

class SettingGroupLayananController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    public function index()
    {
        $data["data"] = SettingGroupLayanan::with("layanan", "diskon", "piutang", "pendapatan", "ppn", "materai", "asuransi", "user")->get();
        //dd($data);
        return view('keuangan::settinglayanan', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["layanan"]      = Layanan::all();
        $data["akun"] = ACPerush::select("id_ac", "nama")->get();
        
        return view('keuangan::settinglayanan', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(SettingLayananRequest $request)
    {
        $cek = SettingGroupLayanan::where("id_layanan", $request->id_layanan)
                                ->where("ac_pendapatan", $request->ac_pendapatan)
                                ->where("ac_diskon", $request->ac_diskon)
                                ->where("ac_ppn", $request->ac_ppn)
                                ->where("ac_materai", $request->ac_materai)
                                ->where("ac_piutang", $request->ac_piutang)
                                ->where("ac_asuransi", $request->ac_asuransi)
                                ->where("ac_packing", $request->ac_packing)
                                ->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Group Pelanggan Sudah Ada ! ');
        }

        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = new SettingGroupLayanan();
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_diskon          = $request->ac_diskon;
            $setting->ac_ppn          = $request->ac_ppn;
            $setting->ac_materai          = $request->ac_materai;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->ac_asuransi          = $request->ac_asuransi;
            $setting->id_layanan          = $request->id_layanan;
            $setting->ac_packing          = $request->ac_packing;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Pelanggan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Group Pelanggan  Disimpan');
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
        $data["layanan"]      = Layanan::all();
        $data["akun"] = ACPerush::select("id_ac", "nama")->get();
        $data["data"] = SettingGroupLayanan::findOrFail($id);
        
        return view('keuangan::settinglayanan', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(SettingLayananRequest $request, $id)
    {
        $cek = SettingGroupLayanan::where("id_layanan", $request->id_layanan)
                                ->where("ac_pendapatan", $request->ac_pendapatan)
                                ->where("ac_diskon", $request->ac_diskon)
                                ->where("ac_ppn", $request->ac_ppn)
                                ->where("ac_materai", $request->ac_materai)
                                ->where("ac_piutang", $request->ac_piutang)
                                ->where("ac_asuransi", $request->ac_asuransi)
                                ->where("ac_packing", $request->ac_packing)
                                ->get()->first();

        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Group Pelanggan Sudah Ada ! ');
        }

        try {
            
            // save to user
            DB::beginTransaction();
            
            $setting                       = SettingGroupLayanan::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_pendapatan          = $request->ac_pendapatan;
            $setting->ac_diskon          = $request->ac_diskon;
            $setting->ac_ppn          = $request->ac_ppn;
            $setting->ac_materai          = $request->ac_materai;
            $setting->ac_piutang          = $request->ac_piutang;
            $setting->ac_asuransi          = $request->ac_asuransi;
            $setting->id_layanan          = $request->id_layanan;
            $setting->ac_packing          = $request->ac_packing;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Pelanggan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Group Pelanggan  Disimpan');
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
            
            $setting                       = SettingGroupLayanan::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Layanan Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Group Layanan  Dihapus');
    }
}
