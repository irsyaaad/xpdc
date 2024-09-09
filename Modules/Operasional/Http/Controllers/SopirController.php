<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Http\Requests\SopirRequest;
use DB;
use Auth;
use Exception;
use File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Hash;
use App\Models\Perusahaan;

class SopirController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {   
        $page = 50;
        $f_id_sopir = $request->f_id_sopir;
        $f_def_armada = $request->f_def_armada;
        $id_perush = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $sopir = Sopir::getFilter($id_perush, $f_id_sopir, $f_def_armada);
        $data["data"] = $sopir->paginate($page);

        if($f_id_sopir != null){
            $f_id_sopir = Sopir::select("id_sopir", "nm_sopir")->where("id_sopir", $f_id_sopir)->get()->first();
        }

        if($f_def_armada != null){
            $f_def_armada = Armada::select("id_armada", "nm_armada", "no_plat")
                            ->where("id_armada", $f_def_armada)->get()->first();
        }

        $data["filter"] = array("page"=>$page, "f_id_sopir" => $f_id_sopir, "f_def_armada"=>$f_def_armada);
        
        return view('operasional::sopir', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $data["armada"] = Armada::where("id_perush",Session("perusahaan")["id_perush"])->get();

        return view('operasional::sopir', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SopirRequest $request)
    {
        try {

            DB::beginTransaction();
            
            $sopir                      = new Sopir();
            
            $sopir->nm_sopir            = $request->nm_sopir;
            $sopir->alamat              = $request->alamat;
            $sopir->alamat_domisili              = $request->alamat_domisili;
            $sopir->telp                = $request->telp;
            $sopir->no_ktp              = $request->no_ktp;
            $sopir->no_sim              = $request->no_sim;
            $sopir->exp_ktp             = $request->exp_ktp;
            $sopir->exp_sim             = $request->exp_sim;
            $sopir->id_perush           = Session("perusahaan")["id_perush"];
            
            if(isset($request->foto_ktp) and $request->file('foto_ktp')!=null){
                $img = $request->file('foto_ktp');
                
                $path_img = $img->store('public/uploads/ktp');
                $image = explode("/", $path_img);
                $sopir->foto_ktp = $image[3];
            }

            if(isset($request->foto_sim) and $request->file('foto_sim')!=null){
                $img = $request->file('foto_sim');
                
                $path_img = $img->store('public/uploads/sim');
                $image = explode("/", $path_img);
                $sopir->foto_sim = $image[3];
            }

            if(isset($request->foto_kk) and $request->file('foto_kk')!=null){
                $img = $request->file('foto_kk');
                
                $path_img = $img->store('public/uploads/kk');
                $image = explode("/", $path_img);
                $sopir->foto_kk = $image[3];
            }
            
            if(isset($request->foto) and $request->file('foto')!=null){
                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/sopir');
                $image = explode("/", $path_img);
                $sopir->foto_ktp = $image[3];
            }

            $sopir->is_aktif            = $request->is_aktif;
            $sopir->def_armada          = $request->def_armada;
            $sopir->id_user             = Auth::user()->id_user;
            $sopir->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Sopir Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Sopir Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {   
        $sopir = Sopir::with("armada", "perusahaan")->findOrFail($id);
        $data["data"] = $sopir;
        
        return view('operasional::sopir', $data);
    }

    public function setAkses($id)
    {
        $cek = User::where("id_sopir", $id)->get()->first();
        
        if($cek != null){
            
            return redirect()->back()->with('error', 'Akses Sopir Telah Didaftarkan');
        }else{
            // save to user
            DB::beginTransaction();
            try {
                $sopir = Sopir::findOrFail($id);

                // create user
                $user                       = new User();
                $user->id_perush      = $sopir->id_perush;
                $user->nm_user = $sopir->nm_sopir;
                $user->username = $sopir->nm_sopir;
                $user->password = Hash::make("driver".$sopir->nm_sopir);
                $user->telp  = $sopir->telp;
                $user->id_sopir = $sopir->id_sopir;
                $user->save();
                
                $user1 = User::findOrFail($user->id_user);
                $user1->username = "driver".$user1->id_user;
                $user1->password = Hash::make("driver".$user1->id_user);
                $user1->save();
                // update sopir is user
                $sopir->is_user = true;
                $sopir->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data User Sopir Gagal Disimpan'.$e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Data User Sopir Disimpan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = Sopir::with("armada")->findOrFail($id);
        $data["armada"] = Armada::all();
        
        return view('operasional::sopir', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SopirRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $sopir                      = Sopir::findOrFail($id);
            
            $sopir->nm_sopir            = $request->nm_sopir;
            $sopir->alamat              = $request->alamat;
            $sopir->alamat_domisili              = $request->alamat_domisili;
            $sopir->telp                = $request->telp;
            $sopir->no_ktp              = $request->no_ktp;
            $sopir->no_sim              = $request->no_sim;
            $sopir->exp_ktp             = $request->exp_ktp;
            $sopir->exp_sim             = $request->exp_sim;
            $sopir->id_perush           = Session("perusahaan")["id_perush"];
            
            if(isset($request->foto) and $request->file('foto')!=null){
                if(Storage::exists('public/uploads/sopir/'.$sopir->foto)){
                    Storage::delete('public/uploads/sopir/'.$sopir->foto);
                }

                $img = $request->file('foto');
                $path_img = $img->store('public/uploads/sopir');
                $image = explode("/", $path_img);
                $sopir->foto = $image[3];
            }

            if(isset($request->foto_ktp) and $request->file('foto_ktp')!=null){
                if(Storage::exists('public/uploads/ktp/'.$sopir->foto_ktp)){
                    Storage::delete('public/uploads/ktp/'.$sopir->foto_ktp);
                }
                $img = $request->file('foto_ktp');
                $path_img = $img->store('public/uploads/ktp');
                $image = explode("/", $path_img);
                $sopir->foto_ktp = $image[3];
            }
            
            if(isset($request->foto_sim) and $request->file('foto_sim')!=null){
                if(Storage::exists('public/uploads/sim/'.$sopir->foto_sim)){
                    Storage::delete('public/uploads/sim/'.$sopir->foto_sim);
                }

                $img = $request->file('foto_sim');
                $path_img = $img->store('public/uploads/sim');
                $image = explode("/", $path_img);
                $sopir->foto_sim = $image[3];
            }
            
            if(isset($request->foto_kk) and $request->file('foto_kk')!=null){
                if(Storage::exists('public/uploads/kk/'.$sopir->foto_kk)){
                    Storage::delete('public/uploads/kk/'.$sopir->foto_kk);
                }

                $img = $request->file('foto_kk');
                $path_img = $img->store('public/uploads/kk');
                $image = explode("/", $path_img);
                $sopir->foto_kk = $image[3];
            }

            $sopir->is_aktif            = $request->is_aktif;
            $sopir->def_armada          = $request->def_armada;
            $sopir->id_user             = Auth::user()->id_user;
            
            $sopir->save();
            
            DB::commit();
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data Sopir Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Sopir Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $ktp = "";
        $sim = "";
        $kk = "";
        $foto  = "";
        try{
                
            $sopir                = Sopir::findOrFail($id);
            $ktp = $sopir->foto_ktp;
            $sim = $sopir->foto_sim;
            $kk = $sopir->foto_kk;
            $foto = $sopir->foto;
            $sopir->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        if(Storage::exists('public/uploads/sim/'.$sim)){
            Storage::delete('public/uploads/sim/'.$sim);
        }

        if(Storage::exists('public/uploads/ktp/'.$ktp)){
            Storage::delete('public/uploads/ktp/'.$ktp);
        }

        if(Storage::exists('public/uploads/kk/'.$kk)){
            Storage::delete('public/uploads/kk/'.$kk);
        }

        if(Storage::exists('public/uploads/sopir/'.$foto)){
            Storage::delete('public/uploads/sopir/'.$foto);
        }
        
        return redirect()->back()->with('success', 'Data Sopir dihapus');
    }
}
