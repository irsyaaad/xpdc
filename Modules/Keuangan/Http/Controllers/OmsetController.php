<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Omset;
use Modules\Operasional\Entities\CaraBayar;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Grouppelanggan;
use App\Models\Wilayah;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\SttModel;
use App\Models\Perusahaan;
use App\Models\Vendor;
use Auth;

class OmsetController extends Controller
{

    public function HutangVendor(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::HutangVendor($id_perush, $dr_tgl, $sp_tgl);
        // dd($newdata);
        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($data);
        return view('keuangan::omset.hutang_vendor.index', $data);
    }

    public function DetailHutangVendor(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $id_ven     = 1;
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }
        if (isset($request->id_ven)) {
            $id_ven             = date($request->id_ven);
        }
        $newdata            = Omset::detailHutangVendor($id_perush, $id_ven, $dr_tgl, $sp_tgl);
        $data["saldo_awal"] = Omset::saldoAwalHutangVendor($id_perush, $id_ven, $dr_tgl);
        $data["data"]       = $newdata;
        $data["vendor"]     = Vendor::findOrFail($id_ven);
        $data["back"]       = url("hutangvendor") . "?_token=" . $request->_token . "&dr_tgl=" . $request->dr_tgl . "&sp_tgl=" . $request->sp_tgl;
        // dd($data);
        return view('keuangan::omset.hutang_vendor.detail', $data);
    }

    public function SttCaraBayar(Request $request)
    {
        // dd($request->all());
        $id_perush      = Session("perusahaan")["id_perush"];
        $dr_tgl         = date('Y-m-01');
        $sp_tgl         = date('Y-m-t');
        $cara_bayar     = 0;
        $status_lunas   = 0;

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }
        if (isset($request->cara_bayar)) {
            $cara_bayar             = $request->cara_bayar;
        }
        if (isset($request->status_lunas)) {
            $status_lunas           = $request->status_lunas;
        }

        $newdata = Omset::byCaraBayar($id_perush, $dr_tgl, $sp_tgl, $cara_bayar, $status_lunas);
        $cara = CaraBayar::all();
        $array = [];
        foreach ($newdata as $key => $value) {
            $array[$value->id_cr_byr_o][$value->id_stt] = $value;
        }

        $data["carabayar"]  = $cara;
        $data["data"]       = $array;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'cara_bayar' => $cara_bayar,
            'status_lunas' => $status_lunas
        ];
        return view('keuangan::omset.omsetstt', $data);
    }

    public function byUsers(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $user = User::all();
        $newdata = Omset::byUsers($id_perush, $dr_tgl, $sp_tgl);
        $array = [];
        foreach ($newdata as $key => $value) {
            $array[$value->id_user][$value->id_stt] = $value;
        }
        $data["user"] = $user;
        $data["data"] = $array;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        return view('keuangan::omset.by_user.index', $data);
    }

    public function byDM(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::byDM($id_perush, $dr_tgl, $sp_tgl);

        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        return view('keuangan::omset.by_dm.index', $data);
    }

    public function byTarif(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');
        $id_tarif   = 0;
        $mode       = "DETAIL";

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        if (isset($request->id_tarif)) {
            $id_tarif           = date($request->id_tarif);
        }

        $newdata = Omset::byTarif($id_perush, $dr_tgl, $sp_tgl, $id_tarif);

        $datanya = [];
        foreach ($newdata as $key => $value) {
            $datanya[$value->c_tarif][] = $value;
        }

        if (isset($request->mode) && $request->mode == "REKAPITULASI") {
            $mode           = $request->mode;
            $datanya        = Omset::rekapByTarif($id_perush, $dr_tgl, $sp_tgl, $id_tarif);
        }

        $data["data"] = $datanya;
        $data["tarif"] = [
            [
                'id' => 1,
                'nama' => 'BERAT'
            ],
            [
                'id' => 2,
                'nama' => 'VOLUME'
            ],
            [
                'id' => 3,
                'nama' => 'KUBIK'
            ],
            [
                'id' => 4,
                'nama' => 'BORONGAN'
            ]
        ];
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'id_tarif' => $id_tarif,
            'mode' => $mode,
        ];
        // dd($data);
        return view('keuangan::omset.by_tarif.index', $data);
    }

    public function byPelanggan(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-01-01');
        $sp_tgl     = date('Y-12-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::byPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $group   = Grouppelanggan::all();

        $pelanggan = [];
        $omset     = [];

        foreach ($newdata as $key => $value) {
            $pelanggan[$value->id_plgn_group][$value->id_pelanggan] = $value->nm_pelanggan;
            $omset[$value->id_pelanggan][$value->bulan] = $value->omset;
        }

        $data["pelanggan"] = $pelanggan;
        $data["omset"]     = $omset;
        $data["group"]     = $group;
        $data["filter"]    = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('keuangan::omset.by_pelanggan.index', $data);
    }

    public function cetakbyPelanggan(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-01-01');
        $sp_tgl     = date('Y-12-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::byPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $group   = Grouppelanggan::all();

        $pelanggan = [];
        $omset     = [];

        foreach ($newdata as $key => $value) {
            $pelanggan[$value->id_plgn_group][$value->id_pelanggan] = $value->nm_pelanggan;
            $omset[$value->id_pelanggan][$value->bulan] = $value->omset;
        }

        $data["pelanggan"] = $pelanggan;
        $data["omset"]     = $omset;
        $data["group"]     = $group;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"]    = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // return view('keuangan::omset.by_pelanggan.index', $data);

        $pdf = \PDF::loadview("keuangan::omset.by_pelanggan.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function byGroupPelanggan(Request $request)
    {

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::byGroupPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["filter"]    = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        return view('keuangan::omset.by_group_pelanggan.index', $data);
    }

    public function cetakOmsetByGroupPelanggan(Request $request)
    {

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::byGroupPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"]    = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];

        // return view('keuangan::omset.by_group_pelanggan.cetak', $data);

        $pdf = \PDF::loadview("keuangan::omset.by_group_pelanggan.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function OmsetbyCaraBayar(Request $request)
    {

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-01-01');
        $sp_tgl     = date('Y-12-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::OmsetbyCaraBayar($id_perush, $dr_tgl, $sp_tgl);

        $data["data"] = $newdata;
        $data["filter"]    = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        return view('keuangan::omset.by_cara_bayar.index', $data);
    }

    public function filterOmsetbyCaraBayar(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = date('Y');

        if (isset($request->tahun) and $request->tahun != "0") {
            $tahun = $request->tahun;
            $session = [];
            $session['tahun'] = $request->tahun;
            Session($session);
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::OmsetbyCaraBayar($tahun, $id_perush);

        $array = [];
        $omset = [];
        $tot_bayar = [];
        $tot_omset = [];
        $tot_piutang = [];

        foreach ($newdata as $key => $value) {
            $array[$value->month][$value->id_cr_byr_o] = $value;
        }
        for ($i = 1; $i <= 12; $i++) {
            $bayar = 0;
            $omset = 0;
            $piutang = 0;
            if (isset($array[$i])) {
                foreach ($array[$i] as $key => $value) {
                    $bayar += $value->bayar;
                    $omset += $value->omset;
                    $piutang += ($value->omset - $value->bayar);
                }
            }
            $tot_bayar[$i] = $bayar;
            $tot_omset[$i] = $omset;
            $tot_piutang[$i] = $piutang;
        }
        //dd($array,$newdata,$tot_bayar);
        $data["data"] = $newdata;
        $data["kolom"] = $array;
        $data["carabayar"] = CaraBayar::all();
        $data["bayar"] = $tot_bayar;
        $data["omset"] = $tot_omset;
        $data["piutang"] = $tot_piutang;
        $data["filter"] = [];
        return view('keuangan::omset.omsetstt', $data);
    }

    public function BiayaByDM(Request $request)
    {

        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = date('Y-m-01');
        $sp_tgl = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::BiayaByDMTrucking($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        return view('keuangan::laporanbiaya.biaya', $data);
    }

    public function BiayaByDMshow(Request $request)
    {
        $data["biaya"]  = Omset::showBiayaByDmTrucking($request->id);
        $data["data"]   = DaftarMuat::findOrFail($request->id);
        $data["back"]   = url("biayabydm") . "?_token=" . $request->_token . "&dr_tgl=" . $request->dr_tgl . "&sp_tgl=" . $request->sp_tgl;
        // dd($data);
        return view('keuangan::laporanbiaya.detail-biaya-by-dm-trucking', $data);
    }

    public function filterBiayaByDM(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        if (isset($request->bulan) and $request->bulan != "0") {
            $bulan = $request->bulan;
            $session = [];
            $session['bulan'] = $request->bulan;
            Session($session);
        }
        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (isset($request->tahun) and $request->tahun != "0") {
            $tahun = $request->tahun;
            $session = [];
            $session['tahun'] = $request->tahun;
            Session($session);
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        $newdata = Omset::BiayaByDM($id_perush, $bulan, $tahun);
        $data["data"] = $newdata;
        $data["filter"] = [];
        //dd($data);
        return view('keuangan::laporanbiaya.biaya', $data);
    }

    public function cetakBiayaByDM()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');
        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        $newdata = Omset::BiayaByDM($id_perush, $bulan, $tahun);
        $data["data"] = $newdata;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::laporanbiaya.cetakbiayapdf', $data);
    }

    public function cetakBiayaByDMexcel()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');
        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        $newdata = Omset::BiayaByDM($id_perush, $bulan, $tahun);
        $data["data"] = $newdata;
        $data["filename"] = "BiayaByDM";
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::laporanbiaya.cetakbiayaexcel', $data);
    }

    public function ProyeksiBiayaVsOmset(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $newdata = Omset::ProyeksiBiayaVsOmset($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];

        return view('keuangan::laporanbiaya.biaya', $data);
    }

    public function cetakOmsetVsBiaya(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $newdata = Omset::ProyeksiBiayaVsOmset($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::laporanbiaya.cetakbiayapdf', $data);
    }

    public function cetakOmsetVsBiayaexcel()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');
        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        $newdata = Omset::ProyeksiBiayaVsOmset($id_perush, $bulan, $tahun);
        $data["data"] = $newdata;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::laporanbiaya.cetakbiayaexcel', $data);
    }

    public function PrestasiPenagihan(Request $request)
    {
        $tahun = date('Y');

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-01-01');
        $sp_tgl     = date('Y-12-31');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
            $tahun = date('Y', strtotime($dr_tgl));
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $prestasiPenagihan = Omset::PrestasiPenagihan($id_perush, $dr_tgl, $sp_tgl);
        $array = [];

        foreach ($prestasiPenagihan->bayar_data as $key => $value) {
            $array[$value->bulan_stt][$value->bulan_bayar] = $value;
        }
        $data["omset"] = $prestasiPenagihan->omset_data;
        $data["bayar"] = $array;
        $data["tahun"] = $tahun;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('keuangan::indexprestasi.prestasi-penagihan-omset', $data);
    }

    public function cetakPrestasiPenagihan(Request $request)
    {
        $tahun = date('Y');

        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-01-01');
        $sp_tgl     = date('Y-12-31');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
            $tahun = date('Y', strtotime($dr_tgl));
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $prestasiPenagihan = Omset::PrestasiPenagihan($id_perush, $dr_tgl, $sp_tgl);
        $array = [];

        foreach ($prestasiPenagihan->bayar_data as $key => $value) {
            $array[$value->bulan_stt][$value->bulan_bayar] = $value;
        }
        $data["omset"] = $prestasiPenagihan->omset_data;
        $data["bayar"] = $array;
        $data["tahun"] = $tahun;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        // return view('keuangan::indexprestasi.prestasi-penagihan-omset', $data);

        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $pdf = \PDF::loadview("keuangan::indexprestasi.cetak-prestasi-penagihan-omset", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function LamaHariSTT(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
            $tahun = date('Y', strtotime($dr_tgl));
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $newdata = Omset::newLamaHariSTT($id_perush, $dr_tgl, $sp_tgl);
        $stt = [];
        $dm = [];

        foreach ($newdata as $key => $value) {
            $stt[$value->id_dm][$value->id_stt] = $value;
            $dm[$value->id_dm] = $value;
        }

        $data["data"] = $dm;
        $data["stt"] = $stt;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($stt);
        return view('keuangan::omset.lama_hari_stt.index', $data);
    }

    public function cetakLamaHariStt(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date('Y-m-01');
        $sp_tgl     = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
            $tahun = date('Y', strtotime($dr_tgl));
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $newdata = Omset::newLamaHariSTT($id_perush, $dr_tgl, $sp_tgl);
        $stt = [];
        $dm = [];

        foreach ($newdata as $key => $value) {
            $stt[$value->id_dm][$value->id_stt] = $value;
            $dm[$value->id_dm] = $value;
        }

        $data["data"] = $dm;
        $data["stt"] = $stt;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($stt);

        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $pdf = \PDF::loadview("keuangan::omset.lama_hari_stt.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function cetakLamaHariSttexcel()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }

        $newdata = Omset::LamaHariSTT($id_perush, $tahun, $bulan);
        $stt = [];
        $dm = [];

        foreach ($newdata as $key => $value) {
            $stt[$value->id_dm][$value->id_stt] = $value;
        }
        foreach ($newdata as $key => $value) {
            $dm[$value->id_dm] = $value;
        }
        $data["data"] = $dm;
        $data["stt"] = $stt;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::indexprestasi.prestasipenagihanexcel', $data);
    }

    public function LamaHariSTTbyGroup()
    {
        session()->forget('bulan');
        session()->forget('tahun');

        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');
        $newdata = Omset::LamaHariSTTbyGroup($id_perush, $tahun);

        $data["data"] = $newdata;
        $data["filter"] = [];
        //dd($data);
        return view('keuangan::indexprestasi.prestasipenagihan', $data);
    }

    public function filterlamaharisttbygroup(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        if (isset($request->bulan) and $request->bulan != "0") {
            $bulan = $request->bulan;
            $session = [];
            $session['bulan'] = $request->bulan;
            Session($session);
        }
        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (isset($request->tahun) and $request->tahun != "0") {
            $tahun = $request->tahun;
            $session = [];
            $session['tahun'] = $request->tahun;
            Session($session);
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        $newdata = Omset::LamaHariSTTbyGroup($id_perush, $tahun);

        $data["data"] = $newdata;
        $data["filter"] = [];
        //dd($data);
        return view('keuangan::indexprestasi.prestasipenagihan', $data);
    }

    public function cetakLamaHariSttbyGroup()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }

        $newdata = Omset::LamaHariSTTbyGroup($id_perush, $tahun);
        $data["data"] = $newdata;
        $data["filter"] = [];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::indexprestasi.cetakprestasipenagihan', $data);
    }

    public function cetakLamaHariSttbyGroupexcel()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }

        $newdata = Omset::LamaHariSTTbyGroup($id_perush, $tahun);
        $data["data"] = $newdata;
        $data["filter"] = [];
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        return view('keuangan::indexprestasi.prestasipenagihanexcel', $data);
    }

    public function OmsetByTipeKirim()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::OmsetByTipeKirim($id_perush);

        $data["data"] = $newdata;
        //$data["filter"] = [];
        return view('keuangan::omset.omsetbytipekirim', $data);
    }

    public function showOmsetByTipeKirim($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::showOmsetByTipeKirim($id, $id_perush);
        $data["data"] = $newdata;
        return view('keuangan::omset.omsetbytipekirim', $data);
    }

    public function OmsetByLayanan(Request $request)
    {
        $dr_tgl     = $request->dr_tgl != null ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl     = $request->sp_tgl != null ? $request->sp_tgl : date('Y-m-t');
        $f_layanan = $request->f_layanan;

        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::OmsetByLayanan3($id_perush, $dr_tgl, $sp_tgl, $f_layanan);

        $data["layanan"] = Layanan::getLayanan();
        $data["data"] = $newdata;
        $data["filter"] = array("f_layanan" => $f_layanan, "dr_tgl" => $dr_tgl, "sp_tgl" => $sp_tgl);

        return view('keuangan::omset.omsetbylayanan', $data);
    }

    public function showOmsetByLayanan($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::showOmsetByLayanan($id, $id_perush);
        $data["data"] = $newdata;

        return view('keuangan::omset.omsetbytipekirim', $data);
    }

    public function SttByRegion(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::SttByOrigin($id_perush, $request->f_start, $request->f_end);
        $data["data"] = $newdata;
        $urls = "printbyregion?_token=" . $request->_token . "&f_start=" . $request->f_start . "&f_end=" . $request->f_end;
        $data["filter"] = array("f_start" => $request->f_start, "f_end" => $request->f_end, "urls" => $urls);
        $data["user"] = User::with("karyawan")->findOrFail(Auth::user()->id_user);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('keuangan::omset.sttbyregion', $data);
    }

    public function printbyregion(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::SttByOrigin($id_perush, $request->f_start, $request->f_end);
        $data["data"] = $newdata;
        $urls = "printbyregion?_token=" . $request->_token . "&f_start=" . $request->f_start . "&f_end=" . $request->f_end;
        $backs = "sttbyregion?_token=" . $request->_token . "&f_start=" . $request->f_start . "&f_end=" . $request->f_end;
        $data["filter"] = array("f_start" => $request->f_start, "f_end" => $request->f_end, "urls" => $urls, "backs" => $backs);
        $data["user"] = User::with("karyawan")->findOrFail(Auth::user()->id_user);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('keuangan::omset.cetakbyregion', $data);
    }

    public function showSttByRegion($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Omset::SttByOrigin($id_perush, $id);
        $data["data"] = $newdata;
        return view('keuangan::omset.sttbyregion', $data);
    }

    public function OmsetByRegion(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tgl_start = isset($request->f_start) ? $request->f_start : date('Y-m-01');
        $tgl_end = isset($request->f_end) ? $request->f_end : date('Y-m-t');

        $newdata = Omset::OmsetByRegion($id_perush, $request->f_region, $tgl_start, $tgl_end);
        $data["data"] = $newdata;
        $urls = "printbyregion?_token=" . $request->_token . "&f_start=" . $tgl_start . "&f_end=" . $tgl_end;
        $data['request'] = $request;
        $data["filter"] = array("f_start" => $tgl_start, "f_end" => $tgl_end, "f_region" => $request->f_region, "urls" => $urls);
        $data["user"] = User::with("karyawan")->findOrFail(Auth::user()->id_user);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('keuangan::omset.by_region.index2', $data);
    }

    public function detailOmsetByRegion(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $id_wil = isset($request->id_wil) ? $request->id_wil : '6471';
        $tipe = isset($request->tipe) ? $request->tipe : 'tujuan';

        $newdata = Omset::detailOmsetByRegion($id_perush, $dr_tgl, $sp_tgl, $id_wil, $tipe);
        $datanya = [];
        foreach ($newdata as $key => $value) {
            $datanya[$value->id_layanan][] = $value;
        }
        $data["data"] = $datanya;
        $data["layanan"] = Layanan::all();        
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["wilayah"] = Wilayah::findOrfail($id_wil);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'tipe' => $tipe,
        ];
        return view('keuangan::omset.by_region.detail', $data);
    }

    public function SttAdaAWB()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = SttModel::whereNotNull('no_awb')->get();

        $data["data"] = $newdata;
        return view('keuangan::omset.sttbyregion', $data);
    }

    public function OmsetByTarif()
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $newdata        = SttModel::where('id_perush_asal', $id_perush)->get();
        $data["data"]   = $newdata;
        return view('keuangan::omset.sttbytarif', $data);
    }

    public function BiayaByDMVendor(Request $request)
    {

        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = date('Y-m-01');
        $sp_tgl = date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $newdata = Omset::BiayaByDMVendor($id_perush, $dr_tgl, $sp_tgl);
        $data["data"] = $newdata;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];

        return view('keuangan::laporanbiayadmvendor.biaya', $data);
    }

    public function BiayaByDMshowVendor(Request $request)
    {
        $data["biaya"]  = Omset::showBiayaByDmTrucking($request->id);
        $data["data"]   = DaftarMuat::findOrFail($request->id);
        $data["back"]   = url("biayabydmvendor") . "?_token=" . $request->_token . "&dr_tgl=" . $request->dr_tgl . "&sp_tgl=" . $request->sp_tgl;

        return view('keuangan::laporanbiayadmvendor.detail', $data);
    }
}
