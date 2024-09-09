<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerushArmada;
use App\Models\Wilayah;
use DB;
use Auth;
use Exception;
use App\Http\Requests\PerushArmadaReq;
use Illuminate\Support\Facades\Storage;
use Session;

class PerushArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 50;
        $f_id_perush = $request->f_id_perush;
        $f_id_wil = $request->f_id_wil;
        
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }   
        
        $perush = PerushArmada::with("wil")->where("id_perush", $id_perush);
        if($f_id_perush != null){
            $perush = $perush->where("id_perush_armd", $f_id_perush);
            $f_id_perush = PerushArmada::select("id_perush_armd", "nm_perush")->where("id_perush_armd", $f_id_perush)->get()->first();
        }

        if($f_id_wil != null){
            $perush = $perush->where("id_wil", $f_id_wil);
            $f_id_wil = Wilayah::select("nama_wil", "id_wil")->where("id_wil", $f_id_wil)->get()->first();
        }

        $data["data"]= $perush->paginate($page);
        $data["filter"] = array("page"=>$page, "f_id_perush" => $f_id_perush, "f_id_wil" => $f_id_wil);
        return view("perusharmada", $data);
    }
    
    public function filter(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 50;
        $f_id_perush = $request->f_id_perush;
        $f_id_wil = $request->f_id_wil;

        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }   
        
        $perush = PerushArmada::with("wil")->where("id_perush", $id_perush);
        if($f_id_perush != null){
            $perush = $perush->where("id_perush_armd", $f_id_perush);
            $f_id_perush = PerushArmada::select("id_perush_armd", "nm_perush")->where("id_perush_armd", $f_id_perush)->get()->first();
        }
        
        if($f_id_wil != null){
            $perush = $perush->where("id_wil", $f_id_wil);
            $f_id_wil = Wilayah::select("nama_wil", "id_wil")->where("id_wil", $f_id_wil)->get()->first();
        }

        $data["data"]= $perush->paginate($page);
        $data["filter"] = array("page"=>$page, "f_id_perush" => $f_id_perush, "f_id_wil" => $f_id_wil);
        return view("perusharmada", $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view("perusharmada");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PerushArmadaReq $request)
    {
        try {
            // save to group
            DB::beginTransaction();

            $perush                 = new PerushArmada();
            $perush->nm_perush = $request->nm_perush;
            $perush->nm_pemilik = $request->nm_pemilik;
            $perush->alamat = $request->alamat;
            $perush->id_wil = $request->id_wil;
            $perush->telp = $request->telp;
            $perush->no_hp = $request->no_hp;
            $perush->npwp = $request->npwp;
            $perush->id_user = Auth::user()->id_user;   
            if(isset($request->foto) and $request->file('foto')!=null){

                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/perusahaan');
                $image = explode("/", $path_img);
                $perush->foto = $image[3];
            }

            $perush->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Perusahaan Armada Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Perusahaan Armada Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["data"] = PerushArmada::with("wil")->findOrFail($id);
        
        return view("perusharmada", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PerushArmadaReq $request, $id)
    {
        try {
            // save to group
            DB::beginTransaction();
            $perush                 = PerushArmada::findOrFail($id);
            $perush->nm_perush = $request->nm_perush;
            $perush->nm_pemilik = $request->nm_pemilik;
            $perush->alamat = $request->alamat;
            $perush->id_wil = $request->id_wil;
            $perush->telp = $request->telp;
            $perush->no_hp = $request->no_hp;
            $perush->npwp = $request->npwp;
            $perush->id_user = Auth::user()->id_user;   
            if(isset($request->foto) and $request->file('foto')!=null){
                if(Storage::exists('public/uploads/perusahaan/'.$perush->foto)){
                    Storage::delete('public/uploads/perusahaan/'.$perush->foto);
                }

                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/perusahaan');
                $image = explode("/", $path_img);
                $perush->foto = $image[3];
            }

            $perush->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pemilik Perusahaan Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Pemilik Perusahaan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $foto = null;
        try {

            DB::beginTransaction();
            $perush                 = PerushArmada::findOrFail($id);
            $foto = $perush->foto;
            $perush->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Perusahaan Armada Gagal Dihapus'.$e->getMessage());
        }

        if(Storage::exists('public/uploads/perusahaan/'.$foto)){
            Storage::delete('public/uploads/perusahaan/'.$foto);
        }

        return redirect(route_redirect())->with('success', 'Data Perusahaan Armada Dihapus');
    }
}
