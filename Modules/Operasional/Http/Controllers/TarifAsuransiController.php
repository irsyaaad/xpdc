<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Operasional\Entities\PerusahaanAsuransi;
use App\Models\Perusahaan;
use DB;
use Auth;

class TarifAsuransiController extends Controller
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

        $tarif = TarifAsuransi::where("id_perush",Session("perusahaan")["id_perush"])->paginate($page);

        $data["data"] = $tarif;
        //$data["filter"] = [];

        return view('operasional::tarif_index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["perusahaan_asuransi"] = PerusahaanAsuransi::all();
        return view('operasional::tarif_index',$data);
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

            $tarif                          = new TarifAsuransi();
            $tarif->id_perush_asuransi      = $request->id_perush_asuransi;
            $tarif->jenis_asuransi          = $request->jenis_asuransi;
            $tarif->harga_beli              = $request->harga_beli;
            $tarif->harga_jual              = $request->harga_jual;
            $tarif->harga_pertanggungan     = $request->harga_pertanggungan;
            $tarif->min_harga_pertanggungan = $request->min_harga_pertanggungan;


            $tarif->id_user                = Auth::user()->id_user;
            $tarif->id_perush              = Session("perusahaan")["id_perush"];

            //dd($tarif);
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Tarif Asuransi Gagal Disimpan'.$e->getMessage());
        }



        return redirect("tarifasuransi")->with('success', 'Data Asuransi Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('operasional::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = TarifAsuransi::findOrFail($id);
        $data["perusahaan_asuransi"] = PerusahaanAsuransi::where("id_perush",Session("perusahaan")["id_perush"])->get();
        return view('operasional::tarif_index',$data);
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

            $tarif                          = TarifAsuransi::findORfail($id);
            $tarif->id_perush_asuransi      = $request->id_perush_asuransi;
            $tarif->jenis_asuransi          = $request->jenis_asuransi;
            $tarif->harga_beli              = $request->harga_beli;
            $tarif->harga_jual              = $request->harga_jual;
            $tarif->harga_pertanggungan     = $request->harga_pertanggungan;
            $tarif->min_harga_pertanggungan = $request->min_harga_pertanggungan;


            $tarif->id_user                = Auth::user()->id_user;
            $tarif->id_perush              = Session("perusahaan")["id_perush"];

            //dd($tarif);
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Tarif Asuransi Gagal Disimpan'.$e->getMessage());
        }

        return redirect("tarifasuransi")->with('success', 'Data Asuransi Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{

            $tarif = TarifAsuransi::findORfail($id);
            $tarif->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect("tarifasuransi")->with('success', 'Tarif Berhasil Dihapus');
    }
}
