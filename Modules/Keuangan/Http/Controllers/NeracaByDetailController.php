<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Neraca;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\ACPerush;

class NeracaByDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl     =  $request->sp_tgl!=null?$request->sp_tgl:date('Y-m-t');
        
        $ac                 = ACPerush::where("id_perush",$id_perush)->orderBy("id_ac")->get();
        $newdata            = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $temp               = [];
        $debit              = [];
        $kredit             = [];
        foreach ($ac as $key => $value) {
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_debet == $value->id_ac) {
                    $temp[$value->id_ac][$key2] = $value2;
                }elseif ($value2->id_kredit == $value->id_ac) {
                    $temp[$value->id_ac][$key2] = $value2;
                }
            }
        }

        $data["data"]       = $temp;
        $data["ac"]         = $ac;
        $pdf = url("neracadetail")."/cetak/pdf?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $excel = url("neracadetail")."/cetak/excel?=_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        
        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl,
            'pdf' => $pdf,
            'excel' => $excel
        ];

        return view('keuangan::laporan.neracadetail',$data);
    }

    public function cetak(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl     =  $request->sp_tgl!=null?$request->sp_tgl:date('Y-m-t');
        $ac                 = ACPerush::where("id_perush",$id_perush)->orderBy("id_ac")->get();
        $newdata            = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $temp               = [];
        $debit              = [];
        $kredit             = [];

        foreach ($ac as $key => $value) {
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_debet == $value->id_ac) {
                    $temp[$value->id_ac][$key2] = $value2;
                }elseif ($value2->id_kredit == $value->id_ac) {
                    $temp[$value->id_ac][$key2] = $value2;
                }
            }
        }

        $data["data"]       = $temp;
        $data["ac"]         = $ac;
        $data["perusahaan"]     = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $back = url("neracadetail")."?=_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;

        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl,
            'back' => $back
        ];

        // return view('keuangan::laporan.cetak-neracadetail',$data);

        $pdf = \PDF::loadview("keuangan::laporan.cetak.neraca-detail",$data)
        ->setOptions(['defaultFont' => 'Tahoma', 'isPhpEnabled' => false])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function excel(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        
        $id_perush = Session('perusahaan')['id_perush'];
        $data["data1"] = $this->getData($bulan,$tahun)["lev1"];
        $data["data2"] = $this->getData($bulan,$tahun)["lev2"];
        $data["data3"] = $this->getData($bulan,$tahun)["lev3"];
        $data["data4"] = $this->getData($bulan,$tahun)["lev4"];
        $data["data5"] = $this->byDetail($bulan,$tahun)["data"];
        $data["batas"] = $this->byDetail($bulan,$tahun)["batas"];
        $data["saldo_awal"] = $this->Saldo_Awal($bulan,$tahun)["sa"];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('keuangan::laporan.excellaporan',$data);
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

    public function getData($bulan,$tahun)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tes = Neraca::getData($id_perush,$bulan,$tahun);
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
                        $subtotal2 = 0;
                        if (isset($lev3[$value2->id_ac])) {
                            foreach ($lev3[$value2->id_ac] as $key3 => $value3) {
                                if (isset($lev4[$value3->id_ac])) {
                                    $total = 0;
                                    foreach ($lev4[$value3->id_ac] as $key4 => $value4) {
                                        $total+=$value4->total;
                                        $subtotal2+=$value4->total;
                                    }
                                }
                            }
                        }
                    $nilai1[$value2->id_ac] = $subtotal2;
                    }
                }
        }
        $data["lev1"] = $lev1;
        $data["lev2"] = $lev2;
        $data["lev3"] = $lev3;
        $data["lev4"] = $lev4;
        $data["nilai"] = $nilai1;

        return $data;
    }

    public function getDetail($id,$bulan,$tahun)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $debit = Neraca::GetDetailNeraca_debit($id,$id_perush,$bulan,$tahun);
        $kredit = Neraca::GetDetailNeraca_kredit($id,$id_perush,$bulan,$tahun);
        $gab = [];
        for ($i=0; $i < count($debit) ; $i++) {
            $gab[] = $debit[$i];
        }
        for ($i=0; $i < count($kredit); $i++) {
            $gab[] = $kredit[$i];
        }
        $data["array"] = collect($gab)->sortBy('created_at');
        $data["batas"] = count($debit);

        return $data;
    }

    public function byDetail($bulan,$tahun)
    {
        $data1 = $this->getData($bulan,$tahun)["lev1"];
        $data2 = $this->getData($bulan,$tahun)["lev2"];
        $data3 = $this->getData($bulan,$tahun)["lev3"];
        $data4 = $this->getData($bulan,$tahun)["lev4"];
        $data5 = [];
        $batasnya = [];

        foreach ($data1 as $key => $value) {
            if ($value->id_ac < 3) {
                if(isset($data2[$value->id_ac])){
                    foreach ($data2[$value->id_ac] as $key2 => $value2) {
                        if (isset($data3[$value2->id_ac])) {
                            foreach ($data3[$value2->id_ac] as $key3 => $value3) {
                                //echo $value3->id_ac." => ".$value3->nama."<br>";
                                if(isset($data4[$value3->id_ac])){
                                    foreach ($data4[$value3->id_ac] as $key4 => $value4) {
                                        //echo " => ".$value4->ac_perush." => ".$value4->nama_ac_perush."<br>";
                                        $temp = $this->getDetail($value4->ac_perush,$bulan,$tahun)["array"];
                                        $batas = $this->getDetail($value4->ac_perush,$bulan,$tahun)["batas"];
                                        $batasnya[$value4->ac_perush]= $batas;
                                        foreach ($temp as $key5 => $value5) {
                                            $data5[$value4->ac_perush][$key5] = $value5;
                                            if(isset($value5->n_materai)){
                                                $ha = $value5->n_materai;
                                            }else{
                                                if(isset($value5->total)){
                                                $ha = $value5->total;
                                                }
                                            }
                                            //echo " => => ".$ha."<br>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //dd();
        $data["batas"] = $batasnya;
        $data["data"] = $data5;
        return $data;
    }

    public function Saldo_Awal($bulan,$tahun)
    {
        if((int)($bulan) != 0){
            $temp = (int)($bulan)-1;
            $bulan = "0".$temp;
        }else{
            $bulan ="12";
            $tahun = (int)($tahun)-1;
        }
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
