<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\SettingLimitPiutang;
use App\Models\Pelanggan;
use DB;
use Auth;
use Exception;
use Session;

class SettingLimitPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = SettingLimitPiutang::orderBy("nominal", "asc")->get();
        
        return view('keuangan::settinglimit.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["pelanggan"] = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_perush", Session("perusahaan")["id_perush"])->get();
        
        return view('keuangan::settinglimit.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nominal'  => 'required|numeric|digits_between:4,100'
        ]);
            
        $cek = SettingLimitPiutang::where("nominal", $request->nominal)
            ->where("is_default", $request->is_default)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Limit Piutang Sudah Ada ! ');
        }

        DB::beginTransaction();
        try {

            $setting                       = new SettingLimitPiutang();
            $setting->id_user              = Auth::user()->id_user;
            $setting->nominal              = $request->nominal;
            $setting->is_default              = $request->is_default;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Limit Piutang Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Limit Piutang  Disimpan');
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
        $data["data"] = SettingLimitPiutang::findOrFail($id);

        return view('keuangan::settinglimit.index', $data);
    }

    public function ceklimit($id)
    {
        $cek = SettingLimitPiutang::ceklimit($id);

        return Response()->json($cek);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nominal'  => 'required|numeric|digits_between:4,100'
        ]);
            
        $cek = SettingLimitPiutang::where("nominal", $request->nominal)
            ->where("is_default", $request->is_default)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Data Limit Piutang Sudah Ada ! ');
        }

        DB::beginTransaction();
        try {

            $setting                       = SettingLimitPiutang::findOrFail($id);
            $setting->id_user              = Auth::user()->id_user;
            $setting->nominal              = $request->nominal;
            $setting->is_default              = $request->is_default;
            $setting->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Limit Piutang Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Limit Piutang  Disimpan');
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

            $setting                       =   SettingLimitPiutang::findOrFail($id);
            $setting->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Limit Piutang Gagal dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Limit Piutang  dihapus');
    }
}
