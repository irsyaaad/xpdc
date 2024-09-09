<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Asuransi\Entities\JurnalAsuransi;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\MasterAC;

class JurnalAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Jurnal",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $sp_tgl = date("Y-m-d");
        $dr_tgl = date('Y-m-d', strtotime('-30 days', strtotime($sp_tgl)));

        $data["data"] = JurnalAsuransi::Master(Session("perusahaan")["id_perush"], $dr_tgl, $sp_tgl);
        return view('asuransi::jurnal.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('asuransi::create');
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
        return view('asuransi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('asuransi::edit');
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

    public function neraca(Request $request)
    {
        $data = [
            'page_title' => "Neraca",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/invoice-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $id_perush = 17;
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date('Y-01-01');
        $start = date('Y', strtotime($dr_tgl)) . "-01-01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date('Y-m-t');
        $data = $this->get_data($id_perush, $start, $sp_tgl);
        $cetak = url("neraca-asuransi") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("neraca-asuransi") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("neraca-asuransi") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'page_title' => 'Neraca',
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'show' => $show,
            'sp_tgl' => $sp_tgl,
        ];

        return view('asuransi::neraca.index', $data);
    }

    public function neraca_show(Request $request)
    {
        $data = [
            'page_title' => "Show Neraca",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/invoice-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $id_perush = 17;
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date('Y-01-01');
        $start = date('Y', strtotime($dr_tgl)) . "-01-01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date('Y-m-t');
        $id = $request->id_ac;

        $ac = ACPerush::where("parent", $id)->where("id_perush", $id_perush)->get();
        $newdata = JurnalAsuransi::Master($id_perush, $start, $sp_tgl);
        $debit = [];
        $kredit = [];

        foreach ($ac as $key => $value) {
            $total_deb = 0;
            $total_kre = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_debet == $value->id_ac) {
                    $total_deb += $value2->total_debet;
                } elseif ($value2->id_kredit == $value->id_ac) {
                    $total_kre += $value2->total_kredit;
                }
            }
            $debit[$value->id_ac] = $total_deb;
            $kredit[$value->id_ac] = $total_kre;
        }

        $data["nm_akun"] = MasterAC::findOrFail($id);
        $data["ac"] = $ac;
        $data["debit"] = $debit;
        $data["kredit"] = $kredit;

        $cetak = url("neraca-asuransi") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("neraca-asuransi") . "/excel/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("neraca-asuransi") . "/showdetail?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $back = url("neraca-asuransi") . "?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'show' => $show,
            'back' => $back,
            'sp_tgl' => $sp_tgl,
        ];

        return view('asuransi::neraca.show', $data);
    }

    public function neraca_show_detail(Request $request)
    {
        $data = [
            'page_title' => "Detail Neraca",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/invoice-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $id_perush = 17;
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date('Y-01-01');
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date('Y-m-t');
        $id = $request->id_ac;

        if (isset($request->dr_tgl)) {
            $tanggal = date('Y', strtotime($request->dr_tgl));
            $dr_tgl = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = date($request->sp_tgl);
        }

        $tahun = date("Y", strtotime($dr_tgl));
        $ha = date("m-d", strtotime($dr_tgl));
        $awal = $tahun . "-01-01";
        $tes = date('Y-m-t', strtotime(date("Y-m-d")));
        $sampai = date('Y-m-d', strtotime('-1 days', strtotime($dr_tgl)));
        $ac = ACPerush::where("parent", $id)->where("id_perush", $id_perush)->get();

        $newdata = JurnalAsuransi::Master($id_perush, $dr_tgl, $sp_tgl, $ha, $id);
        $temp = [];
        $saldo = 0;

        foreach ($newdata as $key => $value) {
            if ($value->id_debet == $id or $value->id_kredit == $id) {
                $temp[$key] = $value;
            }
        }

        if ($ha == "01-01") {
            $saldo_awal = JurnalAsuransi::SaldoAwal($id_perush, $tahun, $id);
            if (isset($saldo_awal) and count($saldo_awal) > 0) {
                $saldo = $saldo_awal[0]->total;
            }
        } else {
            $saldo_awal = JurnalAsuransi::Master($id_perush, $awal, $sampai, null, $id);
            foreach ($saldo_awal as $key => $value) {
                if ($value->id_debet == $id) {
                    if ($value->pos_d == "D") {
                        $saldo += $value->total_debet;
                    } else {
                        $saldo -= $value->total_kredit;
                    }
                } elseif ($value->id_kredit == $id) {
                    if ($value->pos_k == "K") {
                        $saldo += $value->total_kredit;
                    } else {
                        $saldo -= $value->total_debet;
                    }
                }
            }
        }

        $data["data"] = $temp;
        $data["saldo_awal"] = $saldo;
        $data["id"] = $id;
        $data["akun"] = ACPerush::select("parent", "nama")->where('id_perush', $id_perush)
            ->where("id_ac", $id)
            ->firstOrFail();

        $cetak = url("neraca") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("neraca") . "/excel/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $back = url("neraca") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'back' => $back,
            'sp_tgl' => $sp_tgl,
        ];

        return view('asuransi::neraca.showdetail', $data);
    }

    public function rugilaba(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date('Y-01-01');
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date('Y-m-t');

        $data = $this->get_data_rugilaba($id_perush, $dr_tgl, $sp_tgl);

        $cetak = url("rugilaba-asuransi") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("rugilaba-asuransi") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("rugilaba-asuransi") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'show' => $show,
            'sp_tgl' => $sp_tgl,
        ];

        return view('asuransi::rugilaba.index', $data);
    }

    private function get_data($id_perush, $r_dr_tgl, $r_sp_tgl)
    {

        $getTanggal = date("Y-m-d");
        $dr_tgl = date('Y-01-01', strtotime($getTanggal));
        $sp_tgl = date('Y-m-t', strtotime($getTanggal));

        if (isset($r_dr_tgl)) {
            $tanggal = date('Y', strtotime($r_dr_tgl));
            $dr_tgl = date($tanggal . '-01-01');
        }
        if (isset($r_sp_tgl)) {
            $sp_tgl = date($r_sp_tgl);
        }

        $tes = MasterAC::all();
        $level3 = MasterAC::where("level", 3)->get();
        $newdata = JurnalAsuransi::Master($id_perush, $dr_tgl, $sp_tgl);

        $lev1 = [];
        $lev2 = [];
        $lev3 = [];
        $lev4 = [];
        $nilai = [];

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

        $lababerjalan = 0;
        foreach ($nilai as $key => $value) {
            if ($key > 400) {
                if ($key < 410 || $key == 531) {
                    $lababerjalan += $value;
                } else {
                    $lababerjalan -= $value;
                }
            }
        }
        $data["nilai"] = $nilai;
        $data["data1"] = $lev1;
        $data["data2"] = $lev2;
        $data["data3"] = $lev3;
        $data["lababerjalan"] = $lababerjalan;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return $data;
    }

    private function get_data_rugilaba($id_perush, $dr_tgl, $sp_tgl)
    {
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
        $lababerjalan = 0;
        foreach ($nilai as $key => $value) {
            if ($key > 400) {
                if ($key < 410 || $key == 531) {
                    $lababerjalan += $value;
                } else {
                    $lababerjalan -= $value;
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
        $data["nilai"] = $nilai;
        $data["total_omset"] = $total_pendapatan;
        $data["data1"] = $lev1;
        $data["data2"] = $lev2;
        $data["data3"] = $lev3;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return $data;
    }
}
