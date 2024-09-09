<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DetailStt;
use Auth;
use Session;
use Exception;
use DB;
use App\Models\Layanan;
use App\Models\Wilayah;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\StatusStt;

class SttDiterimaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;
        $id_perush  = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect) and $request->shareselect != null){
            $perpage = $request->shareselect;
        }

        if(isset($request->page) and $request->page != null){
            $perpage = $request->page;
        }
        
        $id_stt = $request->filterstt;
        $id_perush_asal = $request->filterperush;
        $id_asal = $request->filterasal;
        $id_tujuan = $request->filtertujuan;
        $id_status = $request->filterstatusstt;
        $tgl_berangkat = $request->tgl_berangkat;
        $tgl_tiba = $request->tgl_tiba;
        $id_layanan = $request->filterlayanan;

        $data["data"] = SttModel::getSttterima($page, $perpage, $id_perush, $id_perush_asal, $id_asal, $id_tujuan, $id_status, $id_layanan, $tgl_berangkat, $tgl_tiba, $id_stt);
        $id_asal = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_asal)->get()->first();
        $id_tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_tujuan)->get()->first();
        $id_stt = SttModel::select("id_stt", "kode_stt")->where("id_stt", $id_stt)->get()->first();
        $id_perush_asal = Perusahaan::select("id_perush", "nm_perush")->where("id_perush", $id_perush_asal)->get()->first();

        $filter = array("id_stt"=> $id_stt, "id_perush_asal"=> $id_perush_asal, "id_asal"=>$id_asal, "id_tujuan"=> $id_tujuan, "id_status"=>$id_status, "tgl_berangkat"=>$tgl_berangkat,
                        "tgl_tiba" => $tgl_tiba, "id_layanan"=>$id_layanan, "page" => $perpage);

        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["status"]    = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "asc")->get(); 
        $data["filter"] = $filter;
        
        return view('operasional::sttterima', $data);
    }

    public function cetak()
    {
        $dataf = SttModel::getSttterima(null, Session("perusahaan")["id_perush"]);

        if (Session('perushtj') !== null) {
            $dataf = $dataf->where("d.id_perush_tj",Session('perushtj'));    
        }
        
        if (Session('statusstt') !== null) {
            $dataf = $dataf->where("o.id_status",Session('statusstt'));     
        }

        if (Session('dr_tgl') !== null) {
            if (Session('sp_tgl') !== null) {
                $dataf = $dataf->whereBetween("tgl_masuk",[Session('dr_tgl'),Session('sp_tgl')]);
            }else{
                $sp = date('Y-m-d');
                $dataf = $dataf->whereBetween("tgl_masuk",[Session('dr_tgl'),$sp]);
            }
        }

        if (Session('sp_tgl') !== null and Session('dr_tgl') == null) {
            $dataf = $dataf->where("tgl_masuk",'<=',Session('sp_tgl'));
        }

        $data["data"] = $dataf->get();
        $data["layanan"] = Layanan::all();
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('operasional::sttkembali.cetak', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->findOrfail($id);
        $data["detail"] = DetailStt::where("id_stt", $id)->get();
        
        if($data["data"]==null){
            return redirect()->back()->with('error', 'Data STT tidak ada');
        }
        
        return view('operasional::detail-stt', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
