<?php

namespace Modules\Keuangan\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\CashFlowDetail;
use Modules\Keuangan\Entities\MasterCashflow;
use Modules\Keuangan\Entities\MasterCashFlowPerush;
use Modules\Keuangan\Entities\Neraca;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        $cetak = url("cashflow") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("cashflow") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("cashflow") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'show' => $show,
            'sp_tgl' => $sp_tgl,
        ];

        // return view('keuangan::laporan.cashflow',$data);

        return view('keuangan::laporan.cashflow.index2', $data);
    }

    public function getSaldoAwalCashflow($cf_perush, $id_perush, $dr_tgl, $sp_tgl)
    {
        $newdata = Neraca::Cashflow($id_perush, $dr_tgl, $sp_tgl);
        $cashin = [];
        $cashout = [];

        $total_plus = 0;
        $total_minus = 0;
        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $total_plus += $value->nominal;
                $cashin[$value->id_kredit][$key] = $value;
            }
            if ($value->parent_k == 101) {
                $total_minus += $value->nominal;
                $cashout[$value->id_debet][$key] = $value;
            }
        }

        return $total_plus - $total_minus;
    }

    public function cetak(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        $cetak = url("cashflow") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("cashflow") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("cashflow") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'show' => $show,
            'sp_tgl' => $sp_tgl,
        ];

        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $pdf = \PDF::loadview("keuangan::laporan.cetak.cashflow", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function cetak_old(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");
        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);
        $back = url("cashflow") . "?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $show = url("cashflow") . "/show?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'show' => $show,
            'back' => $back,
            'sp_tgl' => $sp_tgl,
        ];

        return view('keuangan::laporan.cetak-cashflow', $data);
    }

    public function excel(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");
        $dr_tgl = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl = date('Y-m-t', strtotime($getTanggal));

        if (isset($request->dr_tgl)) {
            $dr_tgl = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl = date($request->sp_tgl);
        }

        $data = $this->get_data($id_perush, $dr_tgl, $sp_tgl);

        return view('keuangan::laporan.excel-cashflow', $data);
    }

    public function show(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id = $request->id_ac;
        $ha = date("m-d", strtotime($dr_tgl));
        $tahun = date('Y', strtotime($dr_tgl));
        $newdata = Neraca::Master($id_perush, $dr_tgl, $sp_tgl, $ha);
        if ($id == 15) {
            $ac = array_column(MasterCashflowPerush::where("id_perush", $id_perush)->where('tipe', 1)->get()->toArray(), 'id_ac');
            $cf_perush = (ACPerush::where('id_perush', $id_perush)->whereNotIn('id_ac', $ac)->get());
            $temp = [];
            foreach ($cf_perush as $it => $item) {
                foreach ($newdata as $key => $value) {
                    if ($value->parent_d == 101) {
                        if ($item->id_ac == $value->id_kredit) {
                            $temp[] = $value;
                        }
                    }
                }
            }
            // dd($cf_perush, $temp);
        } else if ($id == 33) {
            $ac = array_column(MasterCashflowPerush::where("id_perush", $id_perush)->where('tipe', 2)->get()->toArray(), 'id_ac');
            $cf_perush = (ACPerush::where('id_perush', $id_perush)->whereNotIn('id_ac', $ac)->get());
            $temp = [];
            foreach ($cf_perush as $it => $item) {
                foreach ($newdata as $key => $value) {
                    if ($value->parent_k == 101) {
                        if ($item->id_ac == $value->id_debet) {
                            $temp[] = $value;
                        }
                    }
                }
            }
            // dd($cf_perush, $temp);
        } else {
            $cf_perush = MasterCashflowPerush::where("id_perush", $id_perush)->where("id_cf", $id)->get();
            // dd($cf_perush);
            $temp = [];
            foreach ($cf_perush as $it => $item) {
                foreach ($newdata as $key => $value) {
                    if ($item->tipe == 1) {
                        if ($value->parent_d == 101) {
                            if ($item->id_ac == $value->id_kredit) {
                                $temp[] = $value;
                            }
                        }
                    }
                    if ($item->tipe == 2) {
                        if ($value->parent_k == 101) {
                            if ($item->id_ac == $value->id_debet) {
                                $temp[] = $value;
                            }
                        }
                    }
                }
            }
        }

        // dd($cf_perush, $temp);
        $data["data"] = $temp;
        $data["cashflow"] = MasterCashflow::findOrFail($id);
        $back = url("cashflow") . "?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'back' => $back,
            'sp_tgl' => $sp_tgl,
        ];

        return view('keuangan::laporan.detailcashflow', $data);
    }

    public function showdetail($id)
    {
        dd();
        $id_perush = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl = date('Y-m-01', strtotime($getTanggal));
        $sp_tgl = date('Y-m-t', strtotime($getTanggal));

        if (Session('dr_tgl')) {
            $dr_tgl = date(Session('dr_tgl'));
        }
        if (Session('sp_tgl')) {
            $sp_tgl = date(Session('sp_tgl'));
        }

        $awal = date("Y-") . "01-" . "01";
        $tes = date('Y-m-t', strtotime(date("Y-m-d")));
        $sampai = date('Y-m-d', strtotime('-1 month', strtotime($tes)));

        $ac = ACPerush::where("parent", $id)->where("id_perush", $id_perush)->get();

        $newdata = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
        $saldo_awal = Neraca::Master($id_perush, $awal, $sampai);
        $temp = [];
        $saldo = 0;

        foreach ($newdata as $key => $value) {
            if ($value->id_debet == $id or $value->id_kredit == $id) {
                $temp[$key] = $value;
            }
        }
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

        $data["data"] = $temp;
        $data["saldo_awal"] = $saldo;
        $data["id"] = $id;
        $data["akun"] = ACPerush::select("parent", "nama")->where("id_ac", $id)->get()->first();
        return view('keuangan::laporan.detailneraca', $data);
    }

    public function CashFlowDetail(Request $request)
    {

        $id_perush = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");
        $id_ac = $request->id_ac != null ? $request->id_ac : null;
        $id_user = $request->id_user != null ? $request->id_user : null;

        $newdata = Neraca::Cashflow($id_perush, $dr_tgl, $sp_tgl, $id_ac, $id_user);
        $ac = ACPerush::where("id_perush", $id_perush);
        $user = User::select('users.id_user', 'users.nm_user')->join('m_karyawan', 'users.id_karyawan', '=', 'm_karyawan.id_karyawan')->where('m_karyawan.id_perush', $id_perush)->get();

        $cashin = [];
        $cashout = [];

        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_debet][$value->id_kredit][] = $value;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_kredit][$value->id_debet][] = $value;
            }
        }

        $data["filter_ac"] = $ac->orderBy("id_ac")->get();
        $data["acperush"] = $ac->where("parent", 101)->orderBy("id_ac")->get();
        $data["users"] = $user;
        $data["cashin"] = $cashin;
        $data["cashout"] = $cashout;
        $cetak = url("cashflowdetail") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;
        $excel = url("cashflowdetail") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'sp_tgl' => $sp_tgl,
            'id_ac' => $id_ac,
            'id_user' => $id_user,
        ];
// dd($data);
        return view('keuangan::laporan.cashflowdetail', $data);
    }

    public function cetakcashflowdetail(Request $request)
    {
        $id_perush = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-") . "01";
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $newdata = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
        $ac = ACPerush::where("id_perush", $id_perush)->where("parent", 101)->orderBy("id_ac")->get();

        $cashin = [];
        $cashout = [];
        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_debet][$key] = $value;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_kredit][$key] = $value;
            }
        }

        $data["acperush"] = $ac;
        $data["cashin"] = $cashin;
        $data["cashout"] = $cashout;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();

        $pdf = \PDF::loadview("keuangan::laporan.cetak-cashflowdetail", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function excelcashflowdetail()
    {
        abort(404);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('keuangan::laporan.excellaporan', $data);
    }

    public function CashflowHarian(Request $request)
    {
        $id_perush = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-d");
        $id_ac = $request->id_ac != null ? $request->id_ac : null;
        $id_user = $request->id_user != null ? $request->id_user : null;

        $newdata = Neraca::Cashflow($id_perush, $dr_tgl, $sp_tgl, $id_ac, $id_user);
        $ac = ACPerush::where("id_perush", $id_perush);
        $user = User::select('users.id_user', 'users.nm_user')->join('m_karyawan', 'users.id_karyawan', '=', 'm_karyawan.id_karyawan')->where('m_karyawan.id_perush', $id_perush)->get();

        $cashin = [];
        $cashout = [];

        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_debet][$key] = $value;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_kredit][$key] = $value;
            }
        }

        $data["filter_ac"] = $ac->orderBy("id_ac")->get();
        $data["acperush"] = $ac->where("parent", 101)->orderBy("id_ac")->get();
        $data["users"] = $user;
        $data["cashin"] = $cashin;
        $data["cashout"] = $cashout;
        $cetak = url("cashflowharian") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl . "&id_ac=" . $id_ac;
        $excel = url("cashflowharian") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl . "&id_ac=" . $id_ac;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'sp_tgl' => $sp_tgl,
            'id_ac' => $id_ac,
            'id_user' => $id_user,
        ];
// dd($data);
        return view('keuangan::laporan.cashflowharian', $data);

    }

    public function cetakcashflowharian(Request $request)
    {
        $id_perush = Session('perusahaan')['id_perush'];
        $getTanggal = date("Y-m-d");
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-d");
        $id_ac = $request->id_ac != null ? $request->id_ac : null;
        $id_user = $request->id_user != null ? $request->id_user : null;

        $newdata = Neraca::Cashflow($id_perush, $dr_tgl, $sp_tgl, $id_ac, $id_user);
        $ac = ACPerush::where("id_perush", $id_perush);
        $user = User::select('users.id_user', 'users.nm_user')->join('m_karyawan', 'users.id_karyawan', '=', 'm_karyawan.id_karyawan')->where('m_karyawan.id_perush', $id_perush)->get();

        $cashin = [];
        $cashout = [];

        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_debet][$key] = $value;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_kredit][$key] = $value;
            }
        }

        $data["filter_ac"] = $ac->orderBy("id_ac")->get();
        $data["acperush"] = $ac->where("parent", 101)->orderBy("id_ac")->get();
        $data["users"] = $user;
        $data["cashin"] = $cashin;
        $data["cashout"] = $cashout;
        $cetak = url("cashflowharian") . "/cetak/pdf?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl . "&id_ac=" . $id_ac;
        $excel = url("cashflowharian") . "/cetak/excel?=_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl . "&id_ac=" . $id_ac;

        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'tanggal' => $dr_tgl,
            'cetak' => $cetak,
            'excel' => $excel,
            'sp_tgl' => $sp_tgl,
            'id_ac' => $id_ac,
            'id_user' => $id_user,
        ];
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $pdf = \PDF::loadview("keuangan::laporan.cetak.cashflow-harian", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    /**
     * Processing Data
     */
    private function get_data_old($id_perush, $dr_tgl, $sp_tgl)
    {
        $newdata = Neraca::Cashflow($id_perush, $dr_tgl, $sp_tgl);
        $dr_tgl_tahun_lalu = date('Y-m-d', strtotime('-1 year', strtotime($dr_tgl)));
        $sp_tgl_tahun_lalu = date('Y-m-d', strtotime('-1 year', strtotime($sp_tgl)));
        $ac_perush = ACPerush::where("id_perush", $id_perush);
        $cashin = [];
        $cashout = [];

        $total_cashIn = 0;
        $total_cashOut = 0;
        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_kredit][] = $value;
                $total_cashIn += $value->nominal;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_debet][] = $value;
                $total_cashOut += $value->nominal;
            }
        }
        // dd($cashin,$cashout);

        $cashflow = MasterCashflow::all();
        $child = $cashflow->where("head", "!=", 0);
        $cf_child = [];

        foreach ($child as $key => $value) {
            $cf_child[$value->head][$key] = $value;
        }

        $cf_perush = MasterCashFlowPerush::where("id_perush", $id_perush)->get();
        $total = [];
        $not_cashin = [];
        $not_cashout = [];
        $i = 0;
        foreach ($cf_perush as $key => $value) {
            if ($value->tipe == "1") {
                if (isset($cashin[$value->id_ac])) {
                    $sum = 0;
                    foreach ($cashin[$value->id_ac] as $key2 => $value2) {
                        $sum += $value2->nominal;
                    }
                    $total[$value->id_cf] = $sum;
                    $not_cashin[$i] = $value->id_ac;
                    $i += 1;
                }
            }
            if ($value->tipe == "2") {
                if (isset($cashout[$value->id_ac])) {
                    $sum = 0;
                    foreach ($cashout[$value->id_ac] as $key2 => $value2) {
                        $sum += $value2->nominal;
                    }
                    $total[$value->id_cf] = $sum;
                    $not_cashout[$i] = $value->id_ac;
                    $i += 1;
                }
            }
        }
        $inl = $ac_perush->whereNotIn('id_ac', $not_cashin)->get();
        $outl = $ac_perush->whereNotIn('id_ac', $not_cashout)->get();

        $cashin_lain = 0;
        $ac_in_belum_mapping = [];
        foreach ($inl as $key => $value) {
            if (isset($cashin[$value->id_ac])) {
                $ac_in_belum_mapping[$value->id_ac] = $value->nama;
                foreach ($cashin[$value->id_ac] as $key2 => $value2) {
                    $cashin_lain += $value2->nominal;
                }
            }
        }
        $total["inlain"] = $cashin_lain;

        $cashout_lain = 0;
        $ac_out_belum_mapping = [];
        foreach ($outl as $key => $value) {
            if (isset($cashout[$value->id_ac])) {
                $ac_out_belum_mapping[$value->id_ac] = $value->nama;
                foreach ($cashout[$value->id_ac] as $key2 => $value2) {
                    $cashout_lain += $value2->nominal;
                }
            }
        }
        $total["outlain"] = $cashout_lain;
        // dd($total);
        // dd($cashin,$cashout,$total,$not_cashin,$not_cashout);

        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["head"] = $cashflow->where("head", "=", 0);
        $data["child"] = $cf_child;
        $data["total"] = $total;
        $data["total_cashIn"] = $total_cashIn;
        $data["total_cashOut"] = $total_cashOut;
        $data["ac_in_belum_mapping"] = $ac_in_belum_mapping;
        $data["ac_out_belum_mapping"] = $ac_out_belum_mapping;
        $data["saldo_awal"] = $this->getSaldoAwalCashflow($cf_perush, $id_perush, $dr_tgl_tahun_lalu, $sp_tgl_tahun_lalu);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return $data;
    }

    public function get_data($id_perush, $dr_tgl, $sp_tgl)
    {
        $cashflow = MasterCashflow::all();
        $cashflow_perush = MasterCashFlowPerush::where("id_perush", $id_perush)->get();
        $child = $cashflow->where("head", "!=", 0);
        $ac_perush = ACPerush::where("id_perush", $id_perush);
        $newdata = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
        $ha = date("m-d", strtotime($dr_tgl));
        $saldo_awal = Neraca::Master($id_perush, date(date('Y', strtotime($dr_tgl)) . '-01-01'), date('Y-m-d', strtotime('-1 day', strtotime($dr_tgl))), $ha);

        $cf_child = [];
        foreach ($child as $key => $value) {
            $cf_child[$value->head][] = $value;
        }
        // get data in range
        $cashin = [];
        $cashout = [];
        foreach ($newdata as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin[$value->id_kredit][] = $value->total_debet;
            }
            if ($value->parent_k == 101) {
                $cashout[$value->id_debet][] = $value->total_debet;
            }
        }

        $temp_in = [];
        $temp_out = [];
        $saldo_awal_cash_in = 0;
        $saldo_awal_cash_out = 0;
        foreach ($cashin as $key => $value) {
            $temp_in[$key] = array_sum($cashin[$key]);
        }
        foreach ($cashout as $key => $value) {
            $temp_out[$key] = array_sum($cashout[$key]);
        }

        $list_cashflow = [];
        $list = [];
        foreach ($cashflow_perush as $value) {
            $list_cashflow[$value->id_cf][] = $value->id_ac;
            $list[] = $value->id_ac;
        }

        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["head"] = $cashflow->where("head", "=", 0);
        $data["cashflow"] = $list_cashflow;
        $data["child"] = $cf_child;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];

        $total_cash_in = [];
        foreach ($data['head']->where('tipe', 1) as $key => $value) {
            foreach ($cf_child[$value->id_cf] as $key2 => $value2) {
                if (isset($list_cashflow[$value2->id_cf])) {
                    $total_cash_in[$value2->id_cf] = 0;
                    $total_cash_in[99] = 0;
                    foreach ($list_cashflow[$value2->id_cf] as $index) {
                        if (isset($temp_in[$index])) {
                            $total_cash_in[$value2->id_cf] += $temp_in[$index];
                            unset($temp_in[$index]);
                        }
                    }
                }
            }
        }
        if ($ha == '01-01') {
            $saldo_awal_cash_in = $temp_in[''];
            unset($temp_in['']);
        } else {
            $saldo_awal_cash_in = 0;
        }

        $total_cash_in[99] = array_sum($temp_in);
        
        $total_cash_out = [];
        foreach ($data['head']->where('tipe', 2) as $key => $value) {
            foreach ($cf_child[$value->id_cf] as $key2 => $value2) {
                if (isset($list_cashflow[$value2->id_cf])) {
                    $total_cash_out[$value2->id_cf] = 0;
                    $total_cash_out[99] = 0;
                    foreach ($list_cashflow[$value2->id_cf] as $index) {
                        if (isset($temp_out[$index])) {
                            $total_cash_out[$value2->id_cf] += $temp_out[$index];
                            unset($temp_out[$index]);
                        }
                    }
                }
            }
        }
        $total_cash_out[99] = array_sum($temp_out);

        $cashin_sa = [];
        $cashout_sa = [];
        foreach ($saldo_awal as $key => $value) {
            if ($value->parent_d == 101) {
                $cashin_sa[$value->id_kredit][] = $value->total_debet;
            }
            if ($value->parent_k == 101) {
                $cashout_sa[$value->id_debet][] = $value->total_debet;
            }
        }

        $temp_in_sa = [];
        $temp_out_sa = [];
        foreach ($cashin_sa as $key => $value) {
            $temp_in_sa[$key] = array_sum($cashin_sa[$key]);
        }
        foreach ($cashout_sa as $key => $value) {
            $temp_out_sa[$key] = array_sum($cashout_sa[$key]);
        }

        if ($ha == '01-01') {
            $saldo_awal = $saldo_awal_cash_in - $saldo_awal_cash_out;
        } else {
            $saldo_awal = array_sum($temp_in_sa) - array_sum($temp_out_sa);
        }        

        $data["total_cash_in"] = $total_cash_in;
        $data["saldo_awal"] = $saldo_awal;
        $data["cash_in"] = $total_cash_in;
        $data["cash_out"] = $total_cash_out;
        $data["total_cash_in"] = array_sum($total_cash_in);
        $data["total_cash_out"] = array_sum($total_cash_out);
        // dd($data);
        return $data;
    }
}
