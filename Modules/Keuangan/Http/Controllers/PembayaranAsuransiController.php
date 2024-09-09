<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Http\Requests\PembayaranRequest;
use Modules\Keuangan\Entities\PembayaranAsuransi;
use Modules\Operasional\Entities\Asuransi;
use DB;
use Auth;

class PembayaranAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('keuangan::index');
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
    public function store(PembayaranRequest $request, $id)
    {
        // dd($request->all(), $id);
        $asuransi           = Asuransi::findOrFail($id);
        
        if ($request->n_bayar > $asuransi->nominal) {
            return redirect()->back()->withInput($request->all())->with('error', 'Pembayaran gagal, Nominal Melebihi Tagihan');
        }

        try {
            DB::beginTransaction();
            $bayar              = new PembayaranAsuransi();
            $bayar->id_asuransi = $id;
            $bayar->id_cr_byr   = $request->id_cr_byr;
            $bayar->tgl         = $request->tgl_bayar;
            $bayar->info        = $request->info;
            $bayar->id_perush   = Session("perusahaan")["id_perush"];
            $bayar->created_by  = Auth::user()->id_user;
            $bayar->ac4_d       = $request->ac4_d;
            $bayar->ac4_k       = $asuransi->ac4_d;
            $bayar->no_bayar    = $request->referensi;
            $bayar->n_bayar     = $request->n_bayar;
            $bayar->info        = $request->info;
            $bayar->id_plgn     = $asuransi->id_pelanggan;
            $bayar->no_kwitansi = Session("perusahaan")["id_perush"] ."/KW/". date("m"). "/". date("Y") . "/" .substr(crc32(uniqid()),-4);
            $bayar->is_aktif    = true;
            // dd($bayar);
            $bayar->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        return redirect("invoiceasuransi")->with('success', 'Pembayaran sukses');
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
