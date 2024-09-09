<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\ReportModel;
use Modules\Operasional\Entities\SttModel;
use App\Models\Perusahaan;

class ReportController extends Controller
{
    public function bycrabayar(Request $request)
    {
        $data = [];
        if(isset($request->tgl_awal) && isset($request->tgl_akhir)){
            $data["data"] = ReportModel::getSttCaraBayar($request->tgl_awal, $request->tgl_akhir);
        }
        
        return view('operasional::reportstt.bycarabayar', $data);
    }

    public function bycash(Request $request)
    {
        $dr_tgl = date("Y-m-d");
        $sp_tgl = date("Y-m-d");

        
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")
                        ->where("id_perush_asal", $id_perush)
                        ->where("id_cr_byr_o",1)
                        ->whereBetween('tgl_masuk', [$dr_tgl, $sp_tgl])
                        ->get();
        
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        return view('operasional::reportstt.bycash', $data);        
        
    }

    public function filtercash(Request $request)
    {
        $dr_tgl = date("Y-m-d");
        $sp_tgl = date("Y-m-d");

        if ($request->method() == "POST") {
            if(isset($request->tgl_awal)){
                $dr_tgl = $request->tgl_awal;
            }
            if(isset($request->tgl_akhir)){
                $sp_tgl = $request->tgl_akhir;
            }
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")
                        ->where("id_perush_asal", $id_perush)
                        ->where("id_cr_byr_o",1)
                        ->whereBetween('tgl_masuk', [$dr_tgl, $sp_tgl])
                        ->get();
        
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;

        return view('operasional::reportstt.bycash', $data); 
    }

    public function SttNoDM()
    {

        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SttModel::SttNoDM($id_perush);
        
        return view('operasional::reportstt.sttnodm', $data); 
    }

    public function cetakSttNoDM()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $datax = SttModel::SttNoDM($id_perush);
        $stt = [];
        foreach ($datax as $key => $value) {
            $stt[$key] = $value->id_stt;
        }

        $newdata = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->whereIn('id_stt',$stt)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["stt"] = $newdata;
        // dd($newdata);

        return view('operasional::reportstt.cetak-sttnodm', $data);
    }
}
