<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Http\Requests\ArmadaRequest;
use DB;
use Auth;
use Exception;
use App\Models\PerushArmada;
use Illuminate\Support\Facades\Storage;
use Modules\Operasional\Entities\ArmadaGroup;

class ArmadaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $f_id_perush_armd =  $request->f_id_perush_armd;
        $f_id_armd_grup =  $request->f_id_armd_grup;
        $f_id_armada =  $request->f_id_armada;
        if(isset($request->shareselect) and $request->shareselect!= null){
            $page = $request->shareselect;
        }

        $armada = Armada::getFilter($id_perush, $f_id_armada, $f_id_perush_armd, $f_id_armd_grup);
        
        $data["data"] = $armada->paginate($page);
        $f_id_perush_armd = PerushArmada::select("id_perush_armd", "nm_perush")->where("id_perush_armd", $f_id_perush_armd)->get()->first();
        $f_id_armada = Armada::select("id_armada", "nm_armada", "no_plat")->where("id_armada", $f_id_armada)->get()->first();
        $f_id_armd_grup = ArmadaGroup::select("id_armd_grup", "nm_armd_grup")->where("id_armd_grup", $f_id_armd_grup)->get()->first();
        
        $data["filter"] = array("page" => $page, "f_id_perush_armd" => $f_id_perush_armd, "f_id_armada" => $f_id_armada, "f_id_armd_grup" => $f_id_armd_grup);
        
        return view('operasional::armada', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["perush"] = PerushArmada::select("nm_perush", "id_perush_armd", "nm_pemilik")->get();
        return view('operasional::armada', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(ArmadaRequest $request)
    {
        try {
            // save to user
            DB::beginTransaction();
            $armada                = new Armada();
            $armada->id_perush_armd   = $request->id_perush_armd;
            $armada->nm_armada   = $request->nm_armada;
            $armada->no_plat   = $request->no_plat;
            $armada->id_armd_grup   = $request->id_armd_grup;
            $armada->is_aktif      = $request->is_aktif;
            $armada->harga      = $request->harga;
            $armada->volume      = $request->volume;
            $armada->id_user       = Auth::user()->id_user;
            $armada->id_perush       = Session("perusahaan")["id_perush"];
            $armada->no_stnk       = $request->no_stnk;
            $armada->no_bpkb       = $request->no_bpkb;

            if(isset($request->gambar_bpkb) and $request->file('gambar_bpkb')!=null){
                
                $img = $request->file('gambar_bpkb');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->gambar_bpkb = $image[3];
            }
            
            if(isset($request->gambar_stnk) and $request->file('gambar_stnk')!=null){
                
                $img = $request->file('gambar_stnk');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->gambar_stnk = $image[3];
            }

            if(isset($request->foto) and $request->file('foto')!=null){
                
                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->foto = $image[3];
            }

            $armada->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Armada Gagal Disimpan');
        }
        
        return redirect(route_redirect())->with('success', 'Data Armada Disimpan');
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
        $data["perush"] = PerushArmada::select("nm_perush", "id_perush_armd", "nm_pemilik")->get();
        $armd = Armada::with("group")->where("id_armada",$id)->get()->first();
        $data["group"] = $armd->group;
        $data["data"] = $armd;

        return view('operasional::armada', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(ArmadaRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $armada                = Armada::findOrFail($id);
            //dd($armada);
            $armada->id_perush_armd   = $request->id_perush_armd;
            $armada->nm_armada     = $request->nm_armada;
            $armada->no_plat       = $request->no_plat;
            $armada->id_armd_grup   = $request->id_armd_grup;
            $armada->is_aktif      = $request->is_aktif;
            $armada->harga      = $request->harga;
            $armada->volume      = $request->volume;
            $armada->id_user       = Auth::user()->id_user;
            $armada->id_perush       = Session("perusahaan")["id_perush"];
            $armada->no_stnk       = $request->no_stnk;
            $armada->no_bpkb       = $request->no_bpkb;

            if(isset($request->gambar_bpkb) and $request->file('gambar_bpkb')!=null){
                
                if(Storage::exists('public/uploads/armada/'.$armada->gambar_bpkb)){
                    Storage::delete('public/uploads/armada/'.$armada->gambar_bpkb);
                }

                $img = $request->file('gambar_bpkb');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->gambar_bpkb = $image[3];
            }

            if(isset($request->gambar_stnk) and $request->file('gambar_stnk')!=null){
                
                if(Storage::exists('public/uploads/armada/'.$armada->gambar_stnk)){
                    Storage::delete('public/uploads/armada/'.$armada->gambar_stnk);
                }

                $img = $request->file('gambar_stnk');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->gambar_stnk = $image[3];
            }

            if(isset($request->foto) and $request->file('foto')!=null){
                
                if(Storage::exists('public/uploads/armada/'.$armada->foto)){
                    Storage::delete('public/uploads/armada/'.$armada->foto);
                }

                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/armada');
                $image = explode("/", $path_img);
                $armada->foto = $image[3];
            }

            $armada->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage().'Data Armada Gagal Disimpan');
        }
        
        return redirect(route_redirect())->with('success', 'Data Armada Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        $stnk = null;
        $bpkb = null;
        $foto = null;

        try{
            
            $armada                = Armada::findOrFail($id);
            $stnk = $armada->gambar_stnk;
            $bpkb = $armada->gambar_bpkb;
            $foto = $armada->foto;
            $armada->delete();
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        if(Storage::exists('public/uploads/armada/'.$stnk)){
            Storage::delete('public/uploads/armada/'.$stnk);
        }

        if(Storage::exists('public/uploads/armada/'.$bpkb)){
            Storage::delete('public/uploads/armada/'.$bpkb);
        }

        if(Storage::exists('public/uploads/armada/'.$armada->foto)){
            Storage::delete('public/uploads/armada/'.$armada->foto);
        }

        return redirect()->back()->with('success', 'Data Armada Di Hapus');
    }
}
