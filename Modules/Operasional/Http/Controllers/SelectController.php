<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Entities\Packing;
use App\Models\Tarif;
use Modules\Operasional\Entities\KapalPerush;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\ArmadaGroup;
use Modules\Operasional\Entities\Sopir;

class SelectController extends Controller
{
    public function getTipeKirim(Request $request)
    {
        $term   = $request->term;
        $data   = TipeKirim::select("id_tipe_kirim", "nm_tipe_kirim")->where("nm_tipe_kirim", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_tipe_kirim, 'value' => strtoupper($value->nm_tipe_kirim)];
        }

        return response()->json($results);
    }

    public function getMinTarif($id){
        $data = Tarif::findOrfail($id);
        return Response()->json($data);
    }
    
    public function getCaraBayar(Request $request)
    {
        $term   = $request->term;
        $data   = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->orderBy("id_cr_byr_o",'ASC')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_cr_byr_o, 'value' => strtoupper($value->nm_cr_byr_o)];
        }

        return response()->json($results);
    }

    public function getPacking(Request $request)
    {
        $term   = $request->term;
        $data   = Packing::select("id_packing", "nm_packing")->where("nm_packing", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_packing, 'value' => strtoupper($value->nm_packing)];
        }

        return response()->json($results);
    }

    public function getTarifPost(Request $request)
    {
        $data = Tarif::getListTarif($request->id_asal, $request->id_tujuan, $request->id_layanan, $request->id_pelanggan);

        $a_data = [];

        foreach ($data as $key => $value) {
            $a_data[$key]["id_tarif"] = $value->id_tarif;
            $ket = "standart";

            if($value->is_standart!=true){
                $ket = $value->info;
            }

            $a_data[$key]["nm_ven"] = $value->nm_ven;

            if($value->nm_ven==null){
                $a_data[$key]["nm_ven"] = "";
            }

            $a_data[$key]["trbrt"] = $value->trbrt;
            $a_data[$key]["trvol"] = $value->trvol;
            $a_data[$key]["trkbk"] = $value->trkbk;
            $a_data[$key]["ket"] = strtoupper($ket);
        }
        // if($data==null){
        //     $data = 0;
        // }else{
        //     $data = $data[0];
        // }

        return response()->json($a_data);
    }

    public function getKapalPerush(Request $request)
    {
        $term   = $request->term;

        $data = KapalPerush::select("id_kapal_perush", "nm_kapal_perush")->where("nm_kapal_perush", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_kapal_perush, 'value' => strtoupper($value->nm_kapal_perush)];
        }

        return response()->json($results);
    }
    public function ChainArmada($id)
    {
        $sopir = Sopir::findOrFail($id);

        $data = Armada::select("id_armada", "nm_armada")->where("id_armada", $sopir->def_armada)->get()->first();

        return response()->json($data);
    }

    public function gettarifvendor(Request $request)
    {
        $data = Tarif::where("id_ven", $request->id_ven)->where("id_asal", $request->id_asal)->where("id_tujuan", $request->id_tujuan)->get()->first();

        return response()->json($data);
    }

    public function getgrouparmada(Request $request)
    {
        $term   = $request->term;
        $data = ArmadaGroup::select("id_armd_grup", "nm_armd_grup")->where("nm_armd_grup", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_armd_grup, 'value' => strtoupper($value->nm_armd_grup)];
        }

        return response()->json($results);
    }
}
