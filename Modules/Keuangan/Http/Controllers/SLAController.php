<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\SLA;
use App\Models\Vendor;

class SLAController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata        = SLA::SLADMTrucking($id_perush, $dr_tgl, $sp_tgl);
        $data['data']   = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($newdata);
        return view('keuangan::sla.sla-stt', $data);
    }

    public function detail(Request $request)
    {
        // dd($request->all());

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');
        $wilayah    = 'PONTINAK';

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }
        if (isset($request->wilayah)) {
            $wilayah             = $request->wilayah;
        }

        $newdata        = SLA::detailSLADMTrucking($id_perush, $dr_tgl, $sp_tgl, $wilayah);
        $data['data']   = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($newdata);
        return view('keuangan::sla.detail-sla-stt', $data);
    }

    public function DmVendor(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata        = SLA::SLADMVendor($id_perush, $dr_tgl, $sp_tgl);
        $datax          = [];
        foreach ($newdata as $key => $value) {
            $datax[$value->nm_ven][] = $value;
        }
        
        $data['data']   = $datax;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($newdata);
        return view('keuangan::sla.sla-dm-vendor', $data);
    }

    public function DmVendorDetail(Request $request)
    {
        // dd($request->all());

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');
        $wilayah    = 'PONTINAK';
        $id_ven     = 389;

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }
        if (isset($request->vendor)) {
            $id_ven             = $request->vendor;
        }
        if (isset($request->wilayah)) {
            $wilayah             = $request->wilayah;
        }

        $newdata        = SLA::detailSLADMVendor($id_perush, $dr_tgl, $sp_tgl, $id_ven, $wilayah);

        $data['vendor'] = Vendor::findOrFail($id_ven);
        $data['data']   = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($data);
        return view('keuangan::sla.detail-sla-dm-vendor', $data);
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
