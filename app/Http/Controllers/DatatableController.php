<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Operasional\Entities\Asuransi;
use App\Models\HargaVendor;
use DataTables;
use Modules\Keuangan\Entities\InvoiceAsuransi;

class DatatableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Asuransi
    public function getDataAsuransi(Request $request)
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $data = Asuransi::where("id_perush",$id_perush)->get();
        $key = 0;
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('tgl_masuk', function ($user) {
            $tgl=dateindo($user->created_at);
            return $tgl;
        })
        ->addColumn('n_pertanggungan', function ($user) {
            $val="Rp. " . number_format($user->harga_pertanggungan,0,',','.');
            return $val;
        })
        ->addColumn('n_nominal', function ($user) {
            $val="Rp. " . number_format($user->nominal,0,',','.');
            return $val;
        })
        ->addColumn('pelanggan', function ($user) {
            if (isset($user->pelanggan->nm_pelanggan)) {
                $plgn=$user->pelanggan->nm_pelanggan;
            }else{
                $plgn = $user->nm_pengirim;
            }
            return $plgn;
        })
        ->addColumn('broker', function ($user) {
            $perush=$user->nm_broker->nm_perush_asuransi;
            return $perush;
        })
        ->make(true);
    }

    public function getDataInvoiceAsuransi(Request $request)
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $data           = InvoiceAsuransi::where("id_perush",$id_perush)->orderBy("id_invoice","DESC")->get();
        $key = 0;
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('tgl_masuk', function ($user) {
            $tgl=dateindo($user->tgl);
            return $tgl;
        })
        ->addColumn('jatuh_tempo', function ($user) {
            $tgl=dateindo($user->inv_j_tempo);
            return $tgl;
        })
        ->addColumn('nm_status', function ($user) {
            $status = $user->status->nm_status;
            return $status;
        })
        ->addColumn('total', function ($user) {
            $total = torupiah($user->total);
            return $total;
        })
        ->make(true);
    }
    

    public function getDataHargaVendor(Request $request)
    {
        $id_ven = $request->id_ven!=null?$request->id_ven:0;
        $id_asal = $request->id_asal!=null?$request->id_asal:0;
        $id_tujuan = $request->id_tujuan!=null?$request->id_tujuan:0;

        $data = HargaVendor::getDataTables($id_asal, $id_tujuan, $id_ven);
        return Datatables::of($data)->make(true);
    }
}
