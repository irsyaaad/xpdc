<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\LaporanOmset;

class OmsetVsCashInController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $dr_tgl             = Date('Y-01-01');
        $sp_tgl             = Date('Y-12-t');
        $id_perush          = Session("perusahaan")["id_perush"];

        if (isset($request->dr_tgl) && $request->dr_tgl != '') {
            $dr_tgl = $request->dr_tgl;
        }

        if (isset($request->sp_tgl) && $request->sp_tgl != '') {
            $sp_tgl = $request->sp_tgl;
        }

        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        // dd($data);
        return view('keuangan::omset.omset_vs_cashin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('keuangan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request)
    {
        $sp_tgl             = date('Y-m-t', strtotime($request->sp_tgl));
        $id_perush          = Session("perusahaan")["id_perush"];
        $data["data"]       = LaporanOmset::DetailSaldoAwal2($id_perush, $sp_tgl);
        // dd($data);
        return view('keuangan::omset.omset_vs_cashin.show', $data);
    }

    public function showTotalOmset(Request $request)
    {

        $dr_tgl             = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl             = isset($request->sp_tgl) ? date('Y-m-t', strtotime($request->sp_tgl)) : date('Y-m-t');
        $id_perush          = Session("perusahaan")["id_perush"];
        $data["data"]       = LaporanOmset::DetailTotalOmset($id_perush, $dr_tgl, $sp_tgl);
        $data["filter"]     = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl
        ];
        // dd($data);
        return view('keuangan::omset.omset_vs_cashin.show-total-omset', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('keuangan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function get_data($id_perush, $dr_tgl, $sp_tgl) {
        $newdata            = LaporanOmset::OmsetVsCashIn($id_perush, $dr_tgl, $sp_tgl);
        $saldo_awal         = LaporanOmset::SaldoAwalCashIn($id_perush, $dr_tgl);
        $data['data']       = $newdata;
        $data['saldo_awal'] = $saldo_awal;
        $data['filter']     = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];

        return $data;
    }
}
