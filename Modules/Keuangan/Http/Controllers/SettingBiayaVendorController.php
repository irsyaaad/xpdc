<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\SettingBiayaVendor;
use App\Models\Vendor;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\GroupBiaya;
use Auth;
use Session;
use Modules\Keuangan\Http\Requests\SettingBiayaVendorRequest;
use DB;

class SettingBiayaVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = SettingBiayaVendor::with("hutang", "biaya", "group")->orderBy("created_at")->get();
        
        return view('keuangan::settingbiayavendor.index', $data);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["hutang"] = ACPerush::getPiutang("hutang");
        $data["biaya"] = ACPerush::getPiutang("biaya");
        $data["group"] = GroupBiaya::getList();

        return view('keuangan::settingbiayavendor.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SettingBiayaVendorRequest $request)
    {
        $cek = SettingBiayaVendor::where("ac_hutang", $request->ac_hutang)
                                ->where("ac_biaya", $request->ac_biaya)
                                ->where("id_biaya_grup", $request->id_biaya_grup)
                                ->get()->first();
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Biaya Vendor Sudah Ada ! ');
        }
        
        DB::beginTransaction();
        try {

            $setting                       = new SettingBiayaVendor();
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_hutang            = $request->ac_hutang;
            $setting->ac_biaya          = $request->ac_biaya;
            $setting->id_biaya_grup          = $request->id_biaya_grup;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Biaya Vendor Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya Vendor Disimpan');
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
        $data["hutang"] = ACPerush::getPiutang("hutang");
        $data["biaya"] = ACPerush::getPiutang("biaya");
        $data["group"] = GroupBiaya::getList();
        $cek = SettingBiayaVendor::findOrFail($id);
        $data["data"] = $cek;

        return view('keuangan::settingbiayavendor.index', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SettingBiayaVendorRequest $request, $id)
    {
        $cek = SettingBiayaVendor::where("ac_hutang", $request->ac_hutang)
                                ->where("ac_biaya", $request->ac_biaya)
                                ->where("id_biaya_grup", $request->id_biaya_grup)
                                ->get()->first();
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Setting Biaya Vendor Sudah Ada ! ');
        }

        DB::beginTransaction();
        try {
            $setting                       = SettingBiayaVendor::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->ac_hutang            = $request->ac_hutang;
            $setting->ac_biaya          = $request->ac_biaya;
            $setting->id_biaya_grup          = $request->id_biaya_grup;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Biaya Vendor Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya Vendor Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // save to user
        DB::beginTransaction();
        try {    
            $setting                       = SettingBiayaVendor::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Setting Biaya Vendor Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya Vendor Disimpan');
    }
}
