<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Neraca;
use Modules\Keuangan\Entities\Proyeksi;
use Modules\Keuangan\Http\Controllers\NeracaController;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;

class RugiLabaController extends Controller
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

        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        
        $cetak = url("rugilaba")."/cetak/pdf?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $excel = url("rugilaba")."/cetak/excel?=_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $show = url("rugilaba")."/show?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        
        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'tanggal'   => $dr_tgl,
            'cetak' => $cetak,
            'excel'=> $excel,
            'show'=> $show,
            'sp_tgl'    => $sp_tgl,
        ];
        
        return view('keuangan::laporan.rugilaba', $data);
    }

    public function cetak(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl     =  $request->sp_tgl!=null?$request->sp_tgl:date('Y-m-t');
        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        $back = url("rugilaba")."?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        
        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'tanggal'   => $dr_tgl,
            'back'=> $back,
            'sp_tgl'    => $sp_tgl,
        ];

        // return view('keuangan::laporan.cetak-rugilaba',$data);

        $pdf = \PDF::loadview("keuangan::laporan.cetak.rugilaba",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function excel(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl     =  $request->sp_tgl!=null?$request->sp_tgl:date('Y-m-t');
        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);

        return view('keuangan::laporan.excel-rugilaba',$data);
    }

    private function get_data($id_perush,$dr_tgl,$sp_tgl) {
        $tes        = MasterAC::all();
        $level3     = MasterAC::where("level",3)->get();
        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $lev1       = [];
        $lev2       = [];
        $lev3       = [];
        $lev4       = [];

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

        $nilai = [];
        foreach ($level3 as $key => $value) {
            $total = 0;
            $pengurang = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->parent_d == $value->id_ac) {
                    if ($value2->pos_d == "D") {
                        $total+=$value2->total_debet;
                    }else{
                        $total-=$value2->total_debet;
                    }

                }
                if ($value2->parent_k == $value->id_ac) {
                    if ($value2->pos_k == "K") {
                        $total+=$value2->total_kredit;
                    }else {
                        $total-=$value2->total_kredit;
                    }
                }
            }
            $nilai[$value->id_ac]=$total;
        }
        $lababerjalan = 0;
        foreach ($nilai as $key => $value) {
            if ($key > 400) {
                if ($key < 410 || $key == 531) {
                    $lababerjalan+=$value;
                } else {
                    $lababerjalan-=$value;
                }
            }

        }
        // dd($nilai, $lababerjalan);
        $total_pendapatan = 0;
        foreach ($nilai as $key => $value) {
            if ($key > 400 and $key < 500) {
                if (!in_array($key, [405, 406])) {
                    if ($key == 411 or $key == 412) {
                        $total_pendapatan -= $value;
                    } else {
                        $total_pendapatan += $value;
                    }
                }
            }
        }
// dd($total_pendapatan);
        $data["nilai"]              = $nilai;
        $data["total_omset"]        = $total_pendapatan;
        $data["data1"]              = $lev1;
        $data["data2"]              = $lev2;
        $data["data3"]              = $lev3;
        $data["filter"]             = [
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl,
        ];
        $data["perusahaan"]         = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return $data;
    }

    public function show(Request $request)
    {
        // dd($request->request);
        $id = $request->id_ac;
        $id_perush  = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl     = date('Y-01-01', strtotime($getTanggal));
        $sp_tgl     = date('Y-m-t', strtotime($getTanggal));
        $ac         = ACPerush::where("parent",$id)
                        ->where("id_perush",$id_perush)->get();

        if (isset($request->dr_tgl)) {
            $dr_tgl  = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = date($request->sp_tgl);
        }

        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $debit      = [];
        $kredit     = [];

        foreach ($ac as $key => $value) {
            $total_deb = 0;
            $total_kre = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_debet == $value->id_ac) {
                    $total_deb+=$value2->total_debet;
                }elseif ($value2->id_kredit == $value->id_ac) {
                    $total_kre+=$value2->total_kredit;
                }
            }
            $debit[$value->id_ac]   = $total_deb;
            $kredit[$value->id_ac]  = $total_kre;
        }
        $data["nm_akun"]    = MasterAC::findOrFail($id);
        $data["ac"]         = $ac;
        $data["debit"]      = $debit;
        $data["kredit"]     = $kredit;
        $data["id"]         = $id;

        $cetak = url("rugilaba")."/cetak/pdf?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $excel = url("rugilaba")."/excel/excel?=_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $show = url("rugilaba")."/showdetail?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        $back = url("rugilaba")."?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;

        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'tanggal'   => $dr_tgl,
            'cetak' => $cetak,
            'excel'=> $excel,
            'show'=> $show,
            'back'=> $back,
            'sp_tgl'    => $sp_tgl,
        ];

        return view('keuangan::laporan.showneraca',$data);
    }

    public function showdetail(Request $request)
    {
        $id         = $request->id_ac;
        $id_perush  = Session('perusahaan')['id_perush'];
        $dr_tgl     = $request->dr_tgl!=null?$request->dr_tgl:date('Y-01-01');
        $sp_tgl     =  $request->sp_tgl!=null?$request->sp_tgl:date('Y-m-t');

        $tahun      = date("Y", strtotime($dr_tgl));
        $ha         = date("m-d", strtotime($dr_tgl));
        $awal       = $tahun."-01-01";
        $tes        = date('Y-m-t', strtotime(date("Y-m-d")));
        $sampai     = date('Y-m-d', strtotime('-1 days', strtotime($dr_tgl)));
        $ac         = ACPerush::where("parent",$id)->where("id_perush",$id_perush)->get();

        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl,$ha);

        // dd($dr_tgl,$sp_tgl,$awal,$sampai,$saldo_awal);
        $temp       = [];
        $saldo      = 0;

        foreach ($newdata as $key => $value) {
            if($value->id_debet == $id or $value->id_kredit == $id){
                $temp[$key] = $value;
            }
        }

        if ($ha == "01-01") {
            $saldo_awal = Neraca::SaldoAwal($id_perush,$tahun,$id);
            if (isset($saldo_awal) and count($saldo_awal)>0) {
                $saldo = $saldo_awal[0]->total;
            }


        }else {
            $saldo_awal = Neraca::Master($id_perush,$awal,$sampai);
            foreach ($saldo_awal as $key => $value) {
                if($value->id_debet == $id){
                    if($value->pos_d == "D"){
                        $saldo+=$value->total_debet;
                    }else{
                        $saldo-=$value->total_kredit;
                    }
                }elseif($value->id_kredit == $id){
                    if($value->pos_k == "K"){
                        $saldo+=$value->total_kredit;
                    }else{
                        $saldo-=$value->total_debet;
                    }
                }
            }
        }
        $data["data"]           = $temp;
        $data["saldo_awal"]     = $saldo;
        $data["id"]             = $id;
        $data["akun"]           = ACPerush::select("parent","nama")->where("id_ac",$id)->get()->first();
        $back = url("rugilaba")."/show?_token=".$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl;
        
        $data["filter"]         = [
            'dr_tgl'    => $dr_tgl,
            'tanggal'   => $dr_tgl,
            'back'=> $back,
            'sp_tgl'    => $sp_tgl,
        ];
        return view('keuangan::laporan.detailneraca',$data);
    }

    public function Tester()
    {
        $lev1 = []; $lev2=[]; $lev3=[]; $lev4 = [];
        $tes = MasterAC::all();
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

        $level3 = MasterAC::where("level",3)->get();
        $newdata = Neraca::Master();
        $nilai = [];
        foreach ($level3 as $key => $value) {
            $total = 0;
            $pengurang = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->parent_d == $value->id_ac) {
                    if ($value2->pos_d == "D") {
                        $total+=$value2->total_debet;
                    }else{
                        $total-=$value2->total_debet;
                    }

                }
                if ($value2->parent_k == $value->id_ac) {
                    if ($value2->pos_k == "K") {
                        $total+=$value2->total_kredit;
                    }else {
                        $total-=$value2->total_kredit;
                    }
                }
            }
            $nilai[$value->id_ac]=$total;
        }

        $data["nilai"] = $nilai;
        $data["data1"] = $lev1;
        $data["data2"] = $lev2;
        $data["data3"] = $lev3;
        return view('keuangan::laporan.rugilaba',$data);
    }

}
