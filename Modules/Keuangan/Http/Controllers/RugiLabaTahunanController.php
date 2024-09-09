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

class RugiLabaTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $tahun  = date('Y');
        $data   = $this->get_data($tahun);
        return view('keuangan::rugilaba.rugilabatahunan', $data);
    }

    public function filter(Request $request)
    {
        $tahun = date('Y');
        if (isset($request->tahun) and $request->tahun != 0) {
            $tahun = $request->tahun;
        }

        $data   = $this->get_data($tahun);
        // dd($data);
        return view('keuangan::rugilaba.rugilabatahunan', $data);
    }

    public function cetak(Request $request)
    {
        $tahun = date('Y');
        if (isset($request->tahun) and $request->tahun != 0) {
            $tahun = $request->tahun;
        }

        $data   = $this->get_data($tahun);
        // return view('keuangan::laporan.cetak-rugilabapertahun',$data);

        $pdf = \PDF::loadview("keuangan::laporan.cetak.rugilaba-pertahun", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function excel(Request $request)
    {
        $tahun = date('Y');
        if (isset($request->tahun) and $request->tahun != 0) {
            $tahun = $request->tahun;
        }

        $data   = $this->get_data($tahun);
        return view('keuangan::laporan.cetak.rugilaba-pertahun-excel', $data);
    }

    public function RugiLaba($dr_tgl, $sp_tgl)
    {
        $id_perush  = Session("perusahaan")["id_perush"];

        $tes        = MasterAC::all();
        $level3     = MasterAC::where("level", 3)->get();
        $newdata    = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
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
                        $total += $value2->total_debet;
                    } else {
                        $total -= $value2->total_debet;
                    }
                }
                if ($value2->parent_k == $value->id_ac) {
                    if ($value2->pos_k == "K") {
                        $total += $value2->total_kredit;
                    } else {
                        $total -= $value2->total_kredit;
                    }
                }
            }
            $nilai[$value->id_ac] = $total;
        }

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

        $data["nilai"] = $nilai;
        $data["total_omset"] = $total_pendapatan;
        $data["data1"] = $lev1;
        $data["data2"] = $lev2;
        $data["data3"] = $lev3;
        return $data;
    }

    public function Daftar()
    {
        $id_perush  = Session("perusahaan")["id_perush"];

        $tes        = MasterAC::all();
        $level3     = MasterAC::where("level", 3)->get();
        $lev1       = [];
        $lev2       = [];
        $lev3       = [];
        $lev4       = [];

        foreach ($tes as $key => $value) {
            if ($value->level == 1) {
                $lev1[$value->id_ac] = $value;
            } elseif ($value->level == 2) {
                $lev2[$value->id_parent][$value->id_ac] = $value;
            } elseif ($value->level == 3) {
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

    public function get_data($tahun)
    {
        $temp       = [];
        $arr        = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

        foreach ($arr as $key => $value) {
            $getTanggal     = date($tahun . "-" . $value . "-01");
            $dr_tgl         = date('Y-m-01', strtotime($getTanggal));
            $sp_tgl         = date('Y-m-t', strtotime($getTanggal));
            $temp[$key + 1]   = $this->RugiLaba($dr_tgl, $sp_tgl)["nilai"];
        }

        // get total omset
        $total_omset = $this->RugiLaba($tahun . "-01-01", $tahun . "-12-31")["total_omset"];

        $daftar = $this->Daftar();

        $data["data1"]      = $daftar["data1"];
        $data["data2"]      = $daftar["data2"];
        $data["data3"]      = $daftar["data3"];
        $data["data"]       = $temp;
        $data["total_omset"] = $total_omset;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"] = [
            'tahun' => $tahun,
        ];

        return $data;
    }
}
