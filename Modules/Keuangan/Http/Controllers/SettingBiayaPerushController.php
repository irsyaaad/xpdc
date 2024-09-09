<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Keuangan\Http\Requests\SettingBiayaRequest;
use DB;
use Auth;

class SettingBiayaPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = SettingBiayaPerush::getData(Session("perusahaan")["id_perush"]);
        return view('keuangan::settingbiaya.index', $data);
    }

    public function generate()
    {
        DB::beginTransaction();
        try {
            $data = SettingBiaya::all();
            $setting = [];
            foreach($data as $key => $value){
                $setting[$key]["id_user"]              = Auth::user()->id_user;
                $setting[$key]["id_biaya_grup"]        = $value->id_biaya_grup;
                $setting[$key]["id_ac_hutang"]         = $value->id_ac_hutang;
                $setting[$key]["id_ac_biaya"]          = $value->id_ac_biaya;
                $setting[$key]["id_perush"]            = Session("perusahaan")["id_perush"];
                $setting[$key]["created_at"]     = date("Y-m-d h:i:s");
                $setting[$key]["updated_at"]     = date("Y-m-d h:i:s");
            }

            SettingBiayaPerush::insert($setting);

            DB::commit();
        } catch (Exception $e) {
            DB::commit();
            return redirect()->back()->with('error', 'Data Setting Biaya Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Biaya  Disimpan');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["hutang"]             = ACPerush::getPiutang("hutang", Session('perusahaan')['id_perush']);
        $data["biaya"]              = ACPerush::getPiutang("biaya", Session('perusahaan')['id_perush']);
        $data["ac"]                 = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["group"]              = GroupBiaya::select("id_biaya_grup", "nm_biaya_grup")->get();
        
        return view('keuangan::settingbiaya.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //dd($request->request);
        try {

            // save to user
            DB::beginTransaction();
            
            $setting                       = new SettingBiayaPerush();
            $setting->id_user              = Auth::user()->id_user;
            $setting->id_biaya_grup        = $request->id_biaya_grup;
            $setting->id_ac_hutang         = $request->ac4_hutang;
            $setting->id_ac_biaya          = $request->ac4_biaya;
            $setting->id_perush            = Session("perusahaan")["id_perush"];
            //dd($setting,$request->request);
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::commit();
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
        $data["data"]               = SettingBiayaPerush::findOrFail($id);

        return view('keuangan::settingbiaya.index', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // save to user
        DB::beginTransaction();
        try {
            
            
            $setting                       = SettingBiayaPerush::findOrFail($id);
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
            
            $setting                       = SettingBiayaPerush::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal Hapus, Data Masih Dipakai Tabel Lain '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Setting Biaya  Dihapus');
    }
}
