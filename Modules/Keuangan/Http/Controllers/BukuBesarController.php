<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Neraca;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\MasterAC;

class BukuBesarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");

        $ac   = ACPerush::where("id_perush",$id_perush)->orderBy("id_ac")->get();
        $lev3 = MasterAC::where("level",3)->get();
        $lev2 = MasterAC::where("level",2)->get();
        $lev1 = MasterAC::where("level",1)->get();

        $newdata3 = [];
        $newdata2 = [];
        $newdata1 = [];
        foreach ($lev3 as $key => $value) {
            $newdata3[$value->id_ac] = $value;
        }
        foreach ($lev2 as $key => $value) {
            $newdata2[$value->id_ac] = $value;
        }
        foreach ($lev1 as $key => $value) {
            $newdata1[$value->id_ac] = $value;
        }

        $data["ac"]     = $ac;
        $data["data1"]  = $newdata1;
        $data["data2"]  = $newdata2;
        $data["data3"]  = $newdata3;

        $dr_tgl   =  date('Y-m-01');
        $sp_tgl   =  date('Y-m-t');

        $awal = (isset($request->dr_tgl)) ? $request->dr_tgl : $dr_tgl ;
        $sampai = (isset($request->sp_tgl)) ? $request->sp_tgl : $sp_tgl ;

        $data["filter"]         = [
            'dr_tgl'    => $awal,
            'sp_tgl'    => $sampai,
        ];

        return view('keuangan::laporan.bukubesar',$data);
    }
    
    public function getData($bulan,$tahun)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tes = Neraca::RugiLaba($id_perush,$bulan,$tahun);
        $lev1 = []; $lev2=[]; $lev3=[]; $lev4 = [];
        $nilai1 =[];

        foreach ($tes as $key => $value) {
            if ($value->level == 1) {
                $lev1[$value->id_ac] = $value;
            }
            elseif ($value->level == 2) {
                $lev2[$value->id_parent][$value->id_ac] = $value;
            }
            elseif ($value->level == 3) {
                $lev3[$value->id_parent][$value->id_ac] = $value;
                if ($value->parent != null) {
                    $lev4[$value->parent][$value->ac_perush] = $value;
                }
            }
        }
        foreach ($lev1 as $key => $value) {
                if(isset($lev2[$value->id_ac])){
                    foreach($lev2[$value->id_ac] as $key2 => $value2){
                        if (isset($lev3[$value2->id_ac])) {
                            foreach ($lev3[$value2->id_ac] as $key3 => $value3) {
                                if (isset($lev4[$value3->id_ac])) {
                                    foreach ($lev4[$value3->id_ac] as $key4 => $value4) {
                                        //echo $value4->ac_perush. $value4->nama_ac_perush. "<br>";
                                    }
                                }
                            }
                        }
                    }
                }
        }
        $data["lev1"] = $lev1;
        $data["lev2"] = $lev2;
        $data["lev3"] = $lev3;
        $data["lev4"] = $lev4;
        $data["nilai"] = $nilai1;
        //dd($data);
        return $data;
    }

    public function Saldo_Awal($bulan,$tahun)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tes = Neraca::getData($id_perush,$bulan,$tahun);
        $lev1 = []; $lev2=[]; $lev3=[]; $lev4 = [];
        $nilai1 =[]; $debit = []; $kredit=[]; $nilai2=[]; $temp=[];
        $tot_deb=[]; $tot_kre=[];

        foreach ($tes as $key => $value) {
            if ($value->level == 1) {
                $lev1[$value->id_ac] = $value;
            }
            elseif ($value->level == 2) {
                $lev2[$value->id_parent][$value->id_ac] = $value;
            }
            elseif ($value->level == 3) {
                $lev3[$value->id_parent][$value->id_ac] = $value;
                if ($value->parent != null) {
                    $lev4[$value->parent][$value->ac_perush] = $value;
                }
            }
        }
        foreach ($lev1 as $key => $value) {
                if(isset($lev2[$value->id_ac])){
                    foreach($lev2[$value->id_ac] as $key2 => $value2){
                        $subtotal2 = 0;$deb = 0; $kre=0; $sa=0;
                        if (isset($lev3[$value2->id_ac])) {
                            foreach ($lev3[$value2->id_ac] as $key3 => $value3) {
                                if (isset($lev4[$value3->id_ac])) {
                                    $total = 0; $total_debit = 0; $total_kredit = 0;
                                    foreach ($lev4[$value3->id_ac] as $key4 => $value4) {
                                        $total+=$value4->total;
                                        $subtotal2+=$value4->total;
                                        $total_debit+=$value4->debit;
                                        $total_kredit+=$value4->kredit;
                                        $deb+=$value4->debit;
                                        $kre+=$value4->kredit;
                                        $temp[$value4->ac_perush] = $value4->total;
                                    }
                                    $debit[$value3->id_ac] = $total_debit;
                                    $kredit[$value3->id_ac] = $total_kredit;
                                    $nilai2[$value3->id_ac] = $total;
                                }
                            }
                        }
                    $nilai1[$value2->id_ac] = $subtotal2;
                    $tot_deb[$value2->id_ac] = $deb;
                    $tot_kre[$value2->id_ac] = $kre;
                    }
                }
        }
        $data["lev1"]  = $lev1;
        $data["lev2"]  = $lev2;
        $data["lev3"]  = $lev3;
        $data["lev4"]  = $lev4;
        $data["nilai"] = $nilai1;
        $data["sa"]    = $temp;
        $data["debit"] = $debit;
        $data["kredit"] = $kredit;
        $data["total_deb"] = $tot_deb;
        $data["total_kre"] = $tot_kre;
        $data["total"] = $nilai2;
        return $data;
    }
}
