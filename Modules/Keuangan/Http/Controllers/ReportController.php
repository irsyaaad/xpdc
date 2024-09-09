<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\ProyeksiDm;
use Session;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:date('Y-12-31');
        
        $data["data"] = DaftarMuat::getDmOmzet($id_perush, $dr_tgl, $sp_tgl);
        $data["count"] = DaftarMuat::getOmzetCount($id_perush, $dr_tgl, $sp_tgl);
        $data["bstt"] = ProyeksiDm::getGroupProyeksi($id_perush, $dr_tgl, $sp_tgl, 0);
        $data["bumum"] = ProyeksiDm::getGroupProyeksi($id_perush, $dr_tgl, $sp_tgl, 1);
        $data["bvendor"] = ProyeksiDm::getGroupProyeksi($id_perush, $dr_tgl, $sp_tgl, 2);
        $data["satuan"] = DaftarMuat::getSatuanDM($id_perush, $dr_tgl, $sp_tgl);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        
        return view('keuangan::laporanomzetvsbiaya.index', $data);
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
    public function show($id, Request $request)
    {
        $dm = DaftarMuat::where("kode_dm", $id)->firstOrFail();
        $data["data"] = $dm;
        $data["detail"] = ProyeksiDm::getRepProyeksi($dm->id_dm);
        $data["back"] = url("omzetvsbiayadmvendor")."?dr_tgl=".$request->dr_tgl."&sp_tgl=".$request->sp_tgl;
        return view('keuangan::laporanomzetvsbiaya.show', $data);
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
