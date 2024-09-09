<?php

namespace Modules\Keuangan\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\Neraca;
use Modules\Keuangan\Entities\Proyeksi;

class RugiLabaProyeksiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $getTanggal = date("Y-m-d");
        $dr_tgl = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl = date('Y-m-t', strtotime($getTanggal));

        $data = $this->get_data($dr_tgl, $sp_tgl);

        return view('keuangan::rugilaba.rugilabaproyeksi', $data);
    }

    public function filter(Request $request)
    {
        // dd($request->request);
        $dr_tgl = date($request->dr_tgl);
        $sp_tgl = date($request->sp_tgl);

        $data = $this->get_data($dr_tgl, $sp_tgl);
        // dd($data);
        return view('keuangan::rugilaba.rugilabaproyeksi', $data);
    }

    public function cetak(Request $request)
    {

        $dr_tgl = date($request->dr_tgl);
        $sp_tgl = date($request->sp_tgl);

        $data = $this->get_data($dr_tgl, $sp_tgl);

        // return view('keuangan::rugilaba.cetak-rugilabaproyeksi',$data);

        $pdf = \PDF::loadview("keuangan::laporan.cetak.rugilaba-proyeksi", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function excel(Request $request)
    {

        $dr_tgl = date($request->dr_tgl);
        $sp_tgl = date($request->sp_tgl);

        $data = $this->get_data($dr_tgl, $sp_tgl);

        // return view('keuangan::rugilaba.cetak-rugilabaproyeksi',$data);

        return view('keuangan::laporan.cetak.rugilaba-proyeksi-excel', $data);
    }

    public function RugiLaba($dr_tgl, $sp_tgl)
    {
        $id_perush = Session("perusahaan")["id_perush"];

        $tes = MasterAC::all();
        $level3 = MasterAC::where("level", 3)->get();
        $newdata = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
        $lev1 = [];
        $lev2 = [];
        $lev3 = [];
        $lev4 = [];

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

        $data["nilai"] = $nilai;
        $data["data1"] = $lev1;
        $data["data2"] = $lev2;
        $data["data3"] = $lev3;
        return $data;
    }

    public function Proyeksi($dr_tgl, $sp_tgl)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $level3 = MasterAC::where("level", 3)->get();
        $level4 = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])->get();

        $proyeksi = Proyeksi::getProyeksi($id_perush, date('Y-m-01', strtotime($dr_tgl)), date('Y-m-t', strtotime($sp_tgl)));
        $temp = [];
        $nilai = [];

        foreach ($level4 as $key => $value) {
            foreach ($proyeksi as $key2 => $value2) {
                if ($value->id_ac == $value2->ac4) {
                    $temp[$value->parent][$value->id_ac] = $value2->proyeksi;
                }
            }

        }

        foreach ($level3 as $key => $value) {
            $total = 0;
            if (isset($temp[$value->id_ac])) {
                foreach ($temp[$value->id_ac] as $key2 => $value2) {
                    $total += $value2;
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

        $data["data"] = $nilai;
        $data["pendapatan_proyeksi"] = $total_pendapatan;
        return $data;
    }

    public function get_data($dr_tgl, $sp_tgl)
    {
        $dr_tgl_1 = date('Y-m-01', strtotime('-1 year', strtotime($dr_tgl)));
        $sp_tgl_1 = date('Y-m-t', strtotime('-1 year', strtotime(date('Y-m-01', strtotime($sp_tgl)))));

        $rugilaba = $this->RugiLaba($dr_tgl, $sp_tgl);
        $proyeksi = $this->Proyeksi($dr_tgl, $sp_tgl);
        $sebelum = $this->RugiLaba($dr_tgl_1, $sp_tgl_1);

        $total_pendapatan = 0;
        foreach ($rugilaba["nilai"] as $key => $value) {
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

        $total_pendapatan_sebelum = 0;
        foreach ($sebelum["nilai"] as $key => $value) {
            if ($key > 400 and $key < 500) {
                if (!in_array($key, [405, 406])) {
                    if ($key == 411 or $key == 412) {
                        $total_pendapatan_sebelum -= $value;
                    } else {
                        $total_pendapatan_sebelum += $value;
                    }
                }
            }
        }

        $data["data1"] = $rugilaba["data1"];
        $data["data2"] = $rugilaba["data2"];
        $data["data3"] = $rugilaba["data3"];

        $data["nilai"] = $rugilaba["nilai"];
        $data["proyeksi"] = $proyeksi["data"];
        $data["pendapatan_proyeksi"] = $proyeksi["pendapatan_proyeksi"];
        $data["sebelum"] = $sebelum["nilai"];

        $data["total_omset"] = $total_pendapatan;
        $data["total_omset_sebelum"] = $total_pendapatan_sebelum;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];

        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return $data;
    }
}
