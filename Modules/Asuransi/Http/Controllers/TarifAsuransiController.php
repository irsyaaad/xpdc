<?php

namespace Modules\Asuransi\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\PerusahaanAsuransi;
use Modules\Operasional\Entities\TarifAsuransi;

class TarifAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Tarif Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/tarif-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = TarifAsuransi::with("perusahaan_asuransi")->paginate(10);
        $data["perusahaan_asuransi"] = PerusahaanAsuransi::all();
        // dd($data);
        return view('asuransi::tarif-index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('asuransi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {

            // save to user
            DB::beginTransaction();

            $tarif = new TarifAsuransi();
            $tarif->id_perush_asuransi = $request->id_perush_asuransi;
            $tarif->harga_jual = $request->harga_jual;
            $tarif->harga_beli = $request->harga_beli;
            $tarif->min_harga_pertanggungan = $request->min_harga_pertanggungan;
            $tarif->charge_min_jual = $request->charge_min_jual;
            $tarif->charge_min_beli = $request->charge_min_beli;
            $tarif->id_user = Auth::user()->id_user;
            // dd($tarif);
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Tarif Gagal Disimpan' . $e->getMessage());
        }

        return redirect("tarif-asuransi")->with('success', 'Data Tarif Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('asuransi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('asuransi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {

            // save to user
            DB::beginTransaction();

            $tarif = TarifAsuransi::findOrFail($id);
            $tarif->id_perush_asuransi = $request->id_perush_asuransi;
            $tarif->harga_jual = $request->harga_jual;
            $tarif->harga_beli = $request->harga_beli;
            $tarif->min_harga_pertanggungan = $request->min_harga_pertanggungan;
            $tarif->charge_min_jual = $request->charge_min_jual;
            $tarif->charge_min_beli = $request->charge_min_beli;
            $tarif->id_user = Auth::user()->id_user;
            // dd($tarif);
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Tarif Gagal Disimpan' . $e->getMessage());
        }

        return redirect("tarif-asuransi")->with('success', 'Data Tarif Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $tarif = TarifAsuransi::findOrFail($id);
            $tarif->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data dihapus');
    }

    public function get_tarif($id)
    {
        $data = TarifAsuransi::where('id_perush_asuransi', $id)->first();
        return response()->json($data);
    }
}
