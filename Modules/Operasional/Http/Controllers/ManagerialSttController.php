<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\ManagerialStt;
use App\Models\Perusahaan;

class ManagerialSttController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $this->get_data($request);

        return view('operasional::managerial.managerial-stt', $data);
    }

    public function cetak(Request $request)
    {
        $data = $this->get_data($request);
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();

        $pdf = \PDF::loadview("operasional::managerial.cetak-managerial-stt", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function get_data($request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = date('Y-m-01');
        $sp_tgl = date('Y-m-t');
        $mode = 'SEMUA-STT';

        if (isset($request->dr_tgl)) {
            $dr_tgl = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = date($request->sp_tgl);
        }
        if (isset($request->mode)) {
            $mode = $request->mode;
        }

        $id_stt = !empty($request->id_stt) ? $request->id_stt : null;
        $id_dm = !empty($request->id_dm) ? $request->id_dm : null;
        $id_pelanggan = !empty($request->id_pelanggan) ? $request->id_pelanggan : null;

        $newdata = ManagerialStt::getDataManagerialStt($id_perush, $dr_tgl, $sp_tgl, $id_stt, $id_dm, $id_pelanggan, $mode);
        $datax = [];
        foreach ($newdata as $key => $value) {
            $datax[$value->tgl_masuk][] = $value;
        }
        // dd($datax);
        $data['data'] = $datax;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'mode' => $mode,
            'id_stt' => $id_stt,
            'id_dm' => $id_dm,
            'id_pelanggan' => $id_pelanggan
        ];

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::create');
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
        return view('operasional::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('operasional::edit');
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
