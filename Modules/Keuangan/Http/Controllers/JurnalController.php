<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Neraca;
use App\Models\Perusahaan;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        session()->forget('dr_tgl');
        session()->forget('sp_tgl');

        $id_perush          = Session("perusahaan")["id_perush"];
        $dr_tgl             = date("Y-m-")."01";
        $sp_tgl             = date("Y-m-t");

        $data["data"]       = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl
        ];

        return view('keuangan::laporan.jurnal',$data);
    }

    public function filter(Request $request)
    {

        $id_perush  = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");
        $dr_tgl     = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl     = date('Y-m-t', strtotime($getTanggal));

        if ($request->method()=="POST") {
            if (isset($request->dr_tgl)) {
                $dr_tgl             = date($request->dr_tgl);
            }

            if (isset($request->sp_tgl)) {
                $sp_tgl             = date($request->sp_tgl);
            }
        }

        $data["data"]       = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $data["filter"]     = [
            'dr_tgl'    => $request->dr_tgl,
            'sp_tgl'    => $request->sp_tgl
        ];
        return view('keuangan::laporan.jurnal',$data);

    }

    public function cetak(Request $request)
    {

        $id_perush  = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");
        $dr_tgl     = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl     = date('Y-m-t', strtotime($getTanggal));

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $data["data"]       = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $data["filter"]     = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl,
        ];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::laporan.cetak-jurnal',$data);
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
}
