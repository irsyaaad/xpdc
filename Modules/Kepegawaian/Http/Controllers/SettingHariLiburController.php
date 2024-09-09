<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\SettingHariLibur;
Use Auth;
use Session;
use App\Models\Perusahaan;
use DB;
use Exception;

class SettingHariLiburController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 50;
        $dt = date("Y-01-")."01";
        $sp = date("Y-12-")."31";
        $dr_tgl = date("Y-m-d", strtotime($dt));
        $sp_tgl = date("Y-m-d", strtotime($sp));

        if(isset($request->f_dr_tgl) and $request->f_dr_tgl != null){
            $dr_tgl = $request->f_dr_tgl;
        }
        
        if(isset($request->f_sp_tgl) and $request->f_sp_tgl != null){
            $sp_tgl = $request->f_sp_tgl;
        }
        
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }

        if(isset($request->f_id_perush) and $request->f_id_perush!= null){
            $id_perush = $request->f_id_perush;
        }

        $data["role_perush"] = Perusahaan::getRoleUser();
        $libur = SettingHariLibur::with("user", "perush")
                        ->where("id_perush", $id_perush)
                        ->where("dr_tgl" ,">=", $dr_tgl)->where("dr_tgl", "<=", $sp_tgl)
                        ->where("sp_tgl" ,">=", $dr_tgl)->where("sp_tgl" ,"<=", $sp_tgl)
                        ->orderBy("dr_tgl", "desc");

        $data["data"] = $libur->paginate($page);
        $data["filter"] = array("page" => $page, "f_id_perush"=> $id_perush, "f_dr_tgl"=> $dr_tgl, "f_sp_tgl" => $sp_tgl);

        return view('kepegawaian::settingharilibur', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {   
        $data["role_perush"] = Perusahaan::getRoleUser();
        
        return view('kepegawaian::settingharilibur', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingHariLibur::where("id_perush", $id_perush)
        ->where("dr_tgl", $request->dr_tgl)
        ->where("sp_tgl", $request->sp_tgl)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Setting Hari Libur Sudah Ada ');
        }
        
        DB::beginTransaction();
        try {
            
            if($request->c_all and $request->c_all==1){
                $perush = Perusahaan::select("id_perush")->get();

                $data = [];
                foreach($perush as $key => $value){
                    $data[$key]["id_perush"] = $value->id_perush;
                    $data[$key]["dr_tgl"] = $request->dr_tgl;
                    $data[$key]["sp_tgl"] = $request->sp_tgl;
                    $data[$key]["keterangan"] = $request->keterangan;
                    $data[$key]["id_user"] = Auth::user()->id_user;
                }
                
                SettingHariLibur::insert($data);
            }else{
                $denda = new SettingHariLibur();
                $denda->id_perush = $id_perush;
                $denda->id_user = Auth::user()->id_user;
                $denda->dr_tgl = $request->dr_tgl;
                $denda->sp_tgl = $request->sp_tgl;
                $denda->keterangan = $request->keterangan;
                $denda->save();
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Hari Libur Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Hari Libur  Disimpan');
    }
    
    public function copy(Request $request)
    {
        $cek = SettingHariLibur::where("id_perush", $request->perush_tujuan)->get()->first();
        DB::beginTransaction();
        if($cek!=null){
            SettingHariLibur::where("id_perush", $request->perush_tujuan)->delete();
        }
        
        try {
            $setting = SettingHariLibur::where("id_perush", $request->perush_asal)->get();
            $data = [];
            foreach($setting as $key => $value){
                $data[$key]["id_perush"] = $request->perush_tujuan;
                $data[$key]["dr_tgl"] = $value->dr_tgl;
                $data[$key]["sp_tgl"] = $value->sp_tgl;
                $data[$key]["keterangan"] = $value->keterangan;
                $data[$key]["id_user"] = Auth::user()->id_user;
            }
            
            SettingHariLibur::insert($data);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Hari Libur Gagal Dicopy '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Hari Libur Dicopy');
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
        $data["role_perush"] = Perusahaan::getRoleUser();
        $data["data"] = SettingHariLibur::findOrFail($id);
        
        return view('kepegawaian::settingharilibur', $data);
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
        $cek = SettingHariLibur::where("id_perush", $id_perush)
        ->where("dr_tgl", $request->dr_tgl)
        ->where("sp_tgl", $request->sp_tgl)->get()->first();
        
        if($cek!=null){
            return redirect()->back()->with('error', 'Setting Hari Libur Sudah Ada ');
        }
        
        DB::beginTransaction();
        try {
            $denda = SettingHariLibur::findOrFail($id);
            $denda->id_perush = $id_perush;
            $denda->id_user = Auth::user()->id_user;
            $denda->dr_tgl = $request->dr_tgl;
            $denda->sp_tgl = $request->sp_tgl;
            $denda->keterangan = $request->keterangan;
            $denda->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Hari Libur Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Setting Hari Libur  Disimpan');
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
            $denda = SettingHariLibur::findOrFail($id);
            $denda->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Setting Hari Libur Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Setting Hari Libur  Dihapus');
    }
}
