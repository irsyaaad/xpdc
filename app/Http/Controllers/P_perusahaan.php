<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Wilayah;
use App\Http\Requests\PerusahaanRequest;
Use Exception;
Use Response;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CabangGroup;

class P_perusahaan extends Controller
{
    public function index(Request $request)
    {
        $f_id_perush = $request->f_id_perush;
        $f_id_wil = $request->f_id_wil;

        $perusahaan = Perusahaan::with("wilayah", "induk")->orderBy("id_perush",'ASC');
        if($f_id_perush != null){
            $perusahaan->where("id_perush", $f_id_perush);
        }
        if($f_id_wil != null){
            $perusahaan->where("id_region", $f_id_wil);
        }

        $data["data"] = $perusahaan->get();
        $data["filter"] = array("f_id_perush" => $f_id_perush, "f_id_wil" => $f_id_wil);
        $data["wilayah"] = Perusahaan::forFilter();
        $data["perusahaan"] = Perusahaan::getData();

        return view("perusahaan", $data);
    }

    public function filter(Request $request)
    {
        $f_id_perush = $request->f_id_perush;
        $f_id_wil = $request->f_id_wil;

        $perusahaan = Perusahaan::with("wilayah", "induk")->orderBy("id_perush",'ASC');
        if($f_id_perush != null){
            $perusahaan->where("id_perush", $f_id_perush);
        }
        if($f_id_wil != null){
            $perusahaan->where("id_region", $f_id_wil);
        }

        $data["data"] = $perusahaan->get();
        $data["filter"] = array("f_id_perush" => $f_id_perush, "f_id_wil" => $f_id_wil);
        $data["wilayah"] = Perusahaan::forFilter();
        $data["perusahaan"] = Perusahaan::getData();

        return view("perusahaan", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["group"] = CabangGroup::all();
        $data["wilayah"] = Wilayah::all();

        return view("perusahaan", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PerusahaanRequest $request)
    {
        // dd($request->all());
        try {

            // save to user
            DB::beginTransaction();

            $perusahaan                         = new Perusahaan();
            if($perusahaan->token==null){
                $perusahaan->token = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);
            }
            
            $perusahaan->nm_perush              = $request->nm_perush;

            if(isset($request->id_region) and $request->id_region!=null){
                $kota       = Wilayah::findOrFail($request->id_region);
                $provinsi   = Wilayah::findOrFail($kota->prov_id);

                $perusahaan->kotakab            =  $kota->nama_wil;
                $perusahaan->provinsi           =  $provinsi->nama_wil;
                $perusahaan->id_region          = $request->id_region;
            }

            $perusahaan->cabang                 = $request->cabang;
            $perusahaan->alamat                 = $request->alamat;
            $perusahaan->telp                   = $request->telp;
            $perusahaan->kode_ref               = $request->id_ref;
            $perusahaan->kode_perush            = strtoupper($request->id_perush);
            $perusahaan->fax                    = $request->fax;
            $perusahaan->email                  = $request->email;
            $perusahaan->npwp                   = $request->npwp;
            $perusahaan->id_creator             = Auth::user()->id_user;
            $perusahaan->is_aktif               = $request->is_aktif;
            $perusahaan->nm_dir                 = $request->nm_dir;
            $perusahaan->nm_keu                 = $request->nm_keu;
            $perusahaan->nm_cs                  = $request->nm_cs;
            $perusahaan->id_cab_group           = $request->id_cab_group;
            $perusahaan->n_ppn                  = $request->n_ppn;
            $perusahaan->telp_cs                = $request->telp_cs;
            $perusahaan->header                 = $request->header;
            $perusahaan->info_invoice           = $request->info_invoice;
            $perusahaan->url_booking            = $request->url_booking;
            $perusahaan->device_id              = $request->device_id;
            $perusahaan->website                = $request->website;

            if(isset($request->logo) and $request->file('logo')!=null){

                $img = $request->file('logo');
                $path_img = $img->store('public/uploads/perusahaan');
                $image = explode("/", $path_img);
                $perusahaan->logo = $image[3];
            }

        //    dd($perusahaan);
            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("perusahaan")->with('success', 'Data Perusahaan Disimpan');
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
        $data["data"] = Perusahaan::with("wilayah")->find($id);
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["group"] = CabangGroup::all();
        $data["wilayah"] = Wilayah::all();

        return view("perusahaan", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PerusahaanRequest $request, $id)
    {
        try {

            // save to user
            DB::beginTransaction();

            $perusahaan                         = Perusahaan::findOrFail($id);
            if($perusahaan->token==null){
                $perusahaan->token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
            }

            $perusahaan->nm_perush              = $request->nm_perush;

            if(isset($request->id_region) and $request->id_region!=null){
                $kota       = Wilayah::findOrFail($request->id_region);
                $provinsi   = Wilayah::findOrFail($kota->prov_id);

                $perusahaan->kotakab            =  $kota->nama_wil;
                $perusahaan->provinsi           =  $provinsi->nama_wil;
                $perusahaan->id_region          = $request->id_region;
            }

            $perusahaan->cabang                 = $request->cabang;
            $perusahaan->alamat                 = $request->alamat;
            $perusahaan->telp                   = $request->telp;
            $perusahaan->kode_ref               = $request->id_ref;
            $perusahaan->kode_perush            = strtoupper($request->id_perush);
            $perusahaan->fax                    = $request->fax;
            $perusahaan->email                  = $request->email;
            $perusahaan->npwp                   = $request->npwp;
            $perusahaan->id_creator             = Auth::user()->id_user;
            $perusahaan->is_aktif               = $request->is_aktif;
            $perusahaan->nm_dir                 = $request->nm_dir;
            $perusahaan->nm_keu                 = $request->nm_keu;
            $perusahaan->nm_cs                  = $request->nm_cs;
            $perusahaan->id_cab_group           = $request->id_cab_group;
            $perusahaan->n_ppn                  = $request->n_ppn;
            $perusahaan->telp_cs                = $request->telp_cs;
            $perusahaan->header                 = $request->header;
            $perusahaan->info_invoice           = $request->info_invoice;
            $perusahaan->url_booking            = $request->url_booking;
            $perusahaan->device_id              = $request->device_id;
            $perusahaan->website                = $request->website;
            
            if(isset($request->logo) and $request->file('logo')!=null){
                if(Storage::exists('public/uploads/perusahaan/'.$perusahaan->foto_sim)){
                    Storage::delete('public/uploads/perusahaan/'.$perusahaan->foto_sim);
                }

                $img = $request->file('logo');
                $path_img = $img->store('public/uploads/perusahaan');
                $image = explode("/", $path_img);
                $perusahaan->logo = $image[3];
            }

            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("perusahaan")->with('success', 'Data Perusahaan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $logo = "";
        try{

            $perush = Perusahaan::findOrFail($id);
            $logo = $perush->logo;
            $perush->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        if(Storage::exists('public/uploads/perusahaan/'.$logo)){
            Storage::delete('public/uploads/perusahaan/'.$logo);
        }

        return redirect()->back()->with('success', 'Data Berhasil Perusahaan Dihapus');
    }
}
