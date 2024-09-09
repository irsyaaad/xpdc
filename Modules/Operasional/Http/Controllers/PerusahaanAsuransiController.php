<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\PerusahaanAsuransi;
use App\Models\Perusahaan;
use DB;
use Auth;
use App\Models\Wilayah;

class PerusahaanAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $page = 10;
        session()->forget('id_perush');

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $perusahaan = PerusahaanAsuransi::with("wilayah")->paginate($page);        

        $data["data"] = $perusahaan;
        //$data["filter"] = [];

        return view('operasional::perusahaan_index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::perusahaan_index');
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

            $perusahaan                         = new PerusahaanAsuransi();
            $perusahaan->id_perush_asuransi     = Session("perusahaan")["id_perush"]."-".$request->id_perush_asuransi;
            $perusahaan->nm_perush_asuransi     = $request->nm_perush_asuransi;
            $perusahaan->id_region              = $request->id_region;
            
            if(isset($request->id_region)){
                $kota       = Wilayah::select("prov_id", "nama_wil")->where("id_wil", $request->id_region)->first();
                $provinsi   = Wilayah::select("nama_wil")->where("id_wil", $kota->prov_id)->first();

                $perusahaan->kotakab            =  $kota->nama_wil;
                $perusahaan->provinsi           =  $provinsi->nama_wil;
            }
            
            $perusahaan->alamat                 = $request->alamat;
            $perusahaan->fax                    = $request->fax;
            $perusahaan->email                  = $request->email;
            $perusahaan->npwp                   = $request->npwp;
            $perusahaan->id_creator             = Auth::user()->id_user;
            $perusahaan->id_perush              = Session("perusahaan")["id_perush"];
            $perusahaan->cp                     = $request->cp;
            $perusahaan->no_cp                  = $request->no_cp;
            $perusahaan->jenis_asuransi         = $request->jenis_asuransi;    
            $perusahaan->jenis_resiko           = $request->jenis_resiko;              

            //dd($perusahaan);
            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("perusahaanasuransi")->with('success', 'Data Perusahaan Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        dd($id);
        return view('operasional::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = PerusahaanAsuransi::findOrFail($id);
        return view('operasional::perusahaan_index',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {

            // save to user
            DB::beginTransaction();

            $perusahaan                         = PerusahaanAsuransi::findOrFail($id);
            $perusahaan->id_perush_asuransi     = Session("perusahaan")["id_perush"]."-".$request->id_perush_asuransi;
            $perusahaan->nm_perush_asuransi     = $request->nm_perush_asuransi;
            
            if(isset($request->id_region)){
                $kota       = Wilayah::select("prov_id", "nama_wil")->where("id_wil", $request->id_region)->first();
                $provinsi   = Wilayah::select("nama_wil")->where("id_wil", $kota->prov_id)->first();

                $perusahaan->kotakab            =  $kota->nama_wil;
                $perusahaan->provinsi           =  $provinsi->nama_wil;
                $perusahaan->id_region              = $request->id_region;
            }
            
            $perusahaan->alamat                 = $request->alamat;
            $perusahaan->fax                    = $request->fax;
            $perusahaan->email                  = $request->email;
            $perusahaan->npwp                   = $request->npwp;
            $perusahaan->id_creator             = Auth::user()->id_user;
            $perusahaan->id_perush              = Session("perusahaan")["id_perush"];
            $perusahaan->cp                     = $request->cp;
            $perusahaan->no_cp                  = $request->no_cp;
            $perusahaan->jenis_asuransi         = $request->jenis_asuransi;    
            $perusahaan->jenis_resiko           = $request->jenis_resiko;      
            
            
            
            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("perusahaanasuransi")->with('success', 'Data Perusahaan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            
            $perush = PerusahaanAsuransi::findOrFail($id);
            $perush->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect("perusahaanasuransi")->with('success', 'Data Perusahaan Disimpan');
    }
}
