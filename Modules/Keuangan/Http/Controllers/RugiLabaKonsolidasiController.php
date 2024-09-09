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

class RugiLabaKonsolidasiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
    
        $id_perush    = Session("perusahaan")["id_perush"];
        $perush       = [26, 32, 29];        
        $getTanggal   = date("Y-m-d");
        $dr_tgl       = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl       = date('Y-m-t', strtotime($getTanggal));
        $data         = $this->get_data($perush, $dr_tgl, $sp_tgl);
        return view('keuangan::rugilaba.rugilabakonsolidasi', $data);
    }

    public function filter(Request $request) {
        // dd($request->all());
        $id_perush    = Session("perusahaan")["id_perush"];
        $perush       = [26, 32, 29];        
        $getTanggal   = date("Y-m-d");
        $dr_tgl       = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl       = date('Y-m-t', strtotime($getTanggal));

        if (isset($request->id_perush) and $request->id_perush != '') {
            $perush   = $request->id_perush;
        }

        if (isset($request->dr_tgl)) {
            $dr_tgl   = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl   = date($request->sp_tgl);
        }

        $data         = $this->get_data($perush, $dr_tgl, $sp_tgl);
        return view('keuangan::rugilaba.rugilabakonsolidasi', $data);
    }
    
    public function cetak(Request $request){
        $id_perush    = Session("perusahaan")["id_perush"];
        $perush       = [26, 32, 29];        
        $getTanggal   = date("Y-m-d");
        $dr_tgl       = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl       = date('Y-m-t', strtotime($getTanggal));

        if (isset($request->id_perush) and $request->id_perush != '') {
            $perush   = $request->id_perush;
        }

        if (isset($request->dr_tgl)) {
            $dr_tgl   = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl   = date($request->sp_tgl);
        }

        $data         = $this->get_data($perush, $dr_tgl, $sp_tgl);
        $pdf = \PDF::loadview("keuangan::laporan.cetak.rugilaba-konsolidasi",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function excel(Request $request){
        $id_perush    = Session("perusahaan")["id_perush"];
        $perush       = [26, 32, 29];        
        $getTanggal   = date("Y-m-d");
        $dr_tgl       = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl       = date('Y-m-t', strtotime($getTanggal));

        if (isset($request->id_perush) and $request->id_perush != '') {
            $perush   = $request->id_perush;
        }

        if (isset($request->dr_tgl)) {
            $dr_tgl   = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl   = date($request->sp_tgl);
        }

        $data         = $this->get_data($perush, $dr_tgl, $sp_tgl);

        return view('keuangan::laporan.cetak.rugilaba-konsolidasi-excel', $data);
    }

    public function RugiLaba($id_perush, $dr_tgl, $sp_tgl)
    {
        $tes        = MasterAC::all();
        $level3     = MasterAC::where("level",3)->get();
        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $lev1       = [];
        $lev2       = [];
        $lev3       = [];
        $lev4       = [];

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

        $data["nilai"]  = $nilai;
        $data["data1"]  = $lev1;
        $data["data2"]  = $lev2;
        $data["data3"]  = $lev3;
        return $data;
    }

    public function Daftar()
    {
        $id_perush  = Session("perusahaan")["id_perush"];

        $tes        = MasterAC::all();
        $level3     = MasterAC::where("level",3)->get();
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

        $data["data1"]  = $lev1;
        $data["data2"]  = $lev2;
        $data["data3"]  = $lev3;
        return $data;
    }

    public function get_data($perush, $dr_tgl, $sp_tgl) {
        $temp                            = [];
        $total_pendapatan                = [];
        $total                           = [];

        foreach ($perush as $key => $value) {
            $temp[$value]                = $this->RugiLaba($value, $dr_tgl, $sp_tgl)["nilai"];
            $total_pendapatan[$value]    = 0;
            $total[$value]               = 0;
        }

        $daftar                          = $this->Daftar();
        $perusahaan                      = Perusahaan::select('id_perush', 'nm_perush')->whereIn('id_perush', $perush)->get();

        $data["data1"]                   = $daftar["data1"];
        $data["data2"]                   = $daftar["data2"];
        $data["data3"]                   = $daftar["data3"];
        $data["data"]                    = $temp;
        $data["perush"]                  = $perusahaan;
        $data["total_pendapatan"]        = $total_pendapatan;
        $data["total"]                   = $total;

        $data["filter"] = [
            'dr_tgl'                     => $dr_tgl,
            'sp_tgl'                     => $sp_tgl,
            'perush'                     => $perush,
            'tahun'                      => date('Y'),
        ];

        $data["perusahaan"]         = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        // dd($data);

        return $data;
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
