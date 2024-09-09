<?php

namespace Modules\Operasional\Http\Controllers;

use App\Booking;
use App\Libraries\GoogleAuthenticator;
use App\Models\Authenticator;
use App\Models\Grouppelanggan;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Perusahaan;
use App\Models\RoleUser;
use App\Models\Wilayah;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Marketing;
use Modules\Keuangan\Entities\DraftSttInvoice;
use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\SettingLayananPerush;
use Modules\Keuangan\Entities\SettingLimitPiutang;
use Modules\Keuangan\Http\Controllers\PembayaranController;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\DetailStt;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\HistoryDokumenStt;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\Packing;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\TandaTangan;
use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Http\Requests\SttRequest;
use QrCode;
use Session;
use App\Traits\SendNotification;

class SttController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    use SendNotification;
    protected $pembayaran;
    public function __construct(PembayaranController $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function index(Request $request)
    {
        $perpage = $perpage = $request->shareselect != null ? $perpage = $request->shareselect : 50;
        $page = $request->page != null ? $request->page : 1;
        $id_perush = $request->filterperush != null ? $request->filterperush : Session("perusahaan")["id_perush"];
        $id_stt = $request->filterstt;
        $asal = $request->filterasal;
        $tujuan = $request->filtertujuan;
        $status = $request->filterstatusstt;
        $layanan = $request->filterlayanan;
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : null;
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : null;
        $cara = $request->filtercarabayar;
        $f_awb = $request->f_awb != null ? $request->f_awb : null;
        $f_pelanggan = $request->f_pelanggan != null ? $request->f_pelanggan : null;
        $f_penerima = $request->f_penerima != null ? $request->f_penerima : null;

        $data["data"] = SttModel::getDataStt($perpage, $page, $id_perush, $id_stt, $asal, $tujuan, $status, $layanan, $dr_tgl, $sp_tgl, $cara, $f_awb, $f_pelanggan, $f_penerima);
        $asal = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $asal)->get()->first();
        $tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $tujuan)->get()->first();
        $id_stt = SttModel::select("id_stt", "kode_stt")->where("id_stt", $id_stt)->get()->first();
        $pelanggan = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_pelanggan", $f_pelanggan)->get()->first();
        $filter = array("page" => $perpage, "f_pelanggan" => $pelanggan, "id_perush" => $id_perush, "id_stt" => $id_stt, "asal" => $asal, "tujuan" => $tujuan, "status" => $status, "id_layanan" => $layanan, "dr_tgl" => $dr_tgl, "sp_tgl" => $sp_tgl, "cara" => $cara, "f_awb" => $f_awb);

        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["cara"] = CaraBayar::getList();
        $data["status"] = StatusStt::getStatusKosong();

        /**
         * Persiapan Jika Ganti Model Status.
         * 12-01-2024
         * @return Response
         */
        // $data["status"]    = MasterStatusStt::all();

        $data["filter"] = $filter;

        return view('operasional::stt', $data);
    }

    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $perusahaan = Perusahaan::findOrFail($id_perush);
        $data["cara"] = CaraBayar::getList();
        $data["marketing"] = Marketing::getMarketing($id_perush);
        $data["packing"] = Packing::getList();
        $data["group"] = Grouppelanggan::select("id_plgn_group as kode", "nm_group", "is_umum")->get();
        $data["layanan"] = Layanan::getLayanan();
        $data["tarif_asuransi"] = TarifAsuransi::get()->first();
        $data["tarif_ppn"] = $perusahaan->n_ppn;
        $data["limit"] = SettingLimitPiutang::select("nominal", "is_default")->get();
        $data["tipe"] = TipeKirim::getList();

        return view('operasional::stt', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SttRequest $request)
    {
        DB::beginTransaction();
        try {

            $cek = SettingLimitPiutang::ceklimit($request->id_pelanggan);
            $bpiutang = true;
            if ($cek["piutang"] == 0) {
                $bpiutang = true;
            } elseif ($cek["piutang"] > 0 and $cek["sisa"] <= 0) {
                $bpiutang = false;
            }

            if ($bpiutang == false) {
                $text = 'Stt Gagal Dibuat, Limit Piutang Rp. ' . toNumber($cek["limit"]) . ' |
                Jumlah Piutang Rp. ' . toNumber($cek["piutang"]) . ' |
                Sisa Limit Piutang Sebesar Rp. ' . toNumber($cek["sisa"]);

                return redirect()->back()->withInput($request->input())
                    ->with('error', $text);
            }

            $id = $this->generate3($request->id_layanan);
            $stt = new SttModel();
            // for creator
            $stt->id_user = Auth::user()->id_user;
            $stt->id_perush_asal = $id["id_perush"];
            $stt->kode_stt = strtoupper($id["kode_stt"]);
            $stt->tgl_masuk = date("Y-m-d h:i:s");

            if (isset($request->tgl_masuk)) {
                $stt->tgl_masuk = $request->tgl_masuk;
            }

            $stt->tgl_keluar = $request->tgl_keluar;

            // for pengirim
            $stt->pengirim_perush = $request->pengirim_perush;
            $stt->id_plgn = $request->id_pelanggan;
            $stt->pengirim_nm = $request->pengirim_nm;
            $stt->pengirim_alm = $request->pengirim_alm;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->pengirim_kodepos = $request->pengirim_kodepos;
            $stt->pengirim_id_region = $request->pengirim_id_region;
            $stt->no_awb = $request->no_awb;

            // for penerima
            $stt->penerima_perush = $request->penerima_perush;
            $stt->penerima_nm = $request->penerima_nm;
            $stt->penerima_alm = $request->penerima_alm;
            $stt->penerima_telp = $request->penerima_telp;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->penerima_kodepos = $request->penerima_kodepos;
            $stt->penerima_id_region = $request->penerima_id_region;

            // for detail kirim
            $stt->id_layanan = $request->id_layanan;
            $stt->id_tarif = $request->id_tarif;
            $stt->id_cr_byr_o = $request->id_cr_byr_o;
            $stt->id_tipe_kirim = $request->id_tipe_kirim;
            $stt->id_marketing = $request->id_marketing;
            $stt->info_kirim = $request->info_kirim;
            //$stt->instruksi_kirim           = $request->instruksi_kirim;

            // for count stt
            $stt->n_berat = (Double) $request->n_berat;
            $stt->n_volume = (Double) $request->n_volume;
            $stt->n_kubik = (Double) $request->n_kubik;
            $stt->n_koli = $request->n_koli;
            $stt->n_tarif_brt = (Double) $request->n_tarif_brt;
            $stt->n_tarif_vol = (Double) $request->n_tarif_vol;
            $stt->n_tarif_kubik = (Double) $request->n_tarif_kubik;
            $stt->n_tarif_borongan = (Double) $request->n_tarif_borongan;
            $stt->n_hrg_bruto = (Double) $request->n_hrg_bruto;
            $stt->n_terusan = (Double) $request->n_terusan;
            $stt->n_hrg_terusan = (Double) $request->n_hrg_terusan;
            $stt->n_diskon = (Double) $request->n_diskon;
            $stt->n_materai = (Double) $request->n_materai;
            $stt->is_ppn = $request->is_ppn;
            $stt->n_ppn = (Double) $request->n_ppn;
            $stt->n_packing = (Double) $request->n_packing;
            $stt->is_bayar = false;
            $stt->cara_kemas = $request->cara_kemas;

            //Asuransi
            $stt->is_asuransi = $request->is_asuransi;
            $stt->id_asuransi = $request->id_asuransi;
            $stt->n_asuransi = (Double) $request->n_asuransi;
            $stt->n_harga_pertanggungan = (Double) $request->n_pertanggungan;

            //Packing
            $stt->is_packing = $request->is_packing;

            // for id wil
            $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $stt->id_wil = $perusahaan->id_region;
            // for mathematic sum all
            $min_brt = (Double) $request->cm_brt;
            $min_vol = (Double) $request->cm_vol;
            $min_vol = (Double) $request->cm_kubik;

            if ($stt->n_berat < $min_brt) {
                $stt->n_berat = $min_brt;
            }
            if ($stt->n_volume < $min_vol) {
                $stt->n_volume = $min_vol;
            }
            // for cek to bruto
            $c_cek = $request->c_hitung;
            $stt->cara_hitung = $c_cek;
            if ($c_cek == 1) {

                $stt->n_hrg_bruto = (double) $stt->n_berat * $stt->n_tarif_brt;
                $stt->c_tarif = 1;
            } elseif ($c_cek == 2) {

                $stt->n_hrg_bruto = (double) $stt->n_volume * $stt->n_tarif_vol;
                $stt->c_tarif = 2;

            } elseif ($c_cek == 4) {

                $stt->n_hrg_bruto = (double) $stt->n_kubik * $stt->n_tarif_kubik;
                $stt->c_tarif = 4;

            } elseif ($c_cek == 3) {

                $stt->n_hrg_bruto = (Double) $stt->n_tarif_borongan;
                $stt->c_tarif = 3;
            } else {

                return redirect()->back()->withInput($request->input())
                    ->with('error', 'Anda memasukan karakter tidak dikenali');
            }

            // for tarif netto
            $stt->n_ppn = 0;
            if (isset($request->n_ppn) and $request->n_ppn != "0") {
                $stt->n_ppn = ($stt->n_hrg_bruto - $stt->n_diskon) * 1 / 100;
            }
            $stt->c_total = (double) ($stt->n_hrg_bruto + $stt->n_ppn + $stt->n_materai + $stt->n_asuransi + $stt->n_packing) - $stt->n_diskon;

            // tarif koli
            $n_koli = $request->n_koli;
            if ($request->n_koli == null or $request->n_koli == "0") {
                $n_koli = 1;
            }

            $stt->n_tarif_koli = (Double) ($stt->n_hrg_bruto - $stt->n_diskon) / $stt->n_koli;

            //dd($stt);
            $stt->id_status = 1;
            $stt->is_aktif = true;

            // for keuangan
            $group = SettingLayananPerush::where("id_layanan", $stt->id_layanan)->where("id_perush", Session("perusahaan")["id_perush"])
                ->get()->first();
            if ($group == null) {
                DB::rollback();
                return redirect()->back()->withInput($request->input())
                    ->with('error', "Setting Group Layanan Keuangan Belum Ada");
            }

            // cek piutang
            if ($cek["piutang"] > 0 and $stt->c_total > $cek["sisa"]) {
                $bpiutang = false;
            }

            if ($bpiutang == false) {

                $text = 'Stt Gagal Dibuat, Limit Piutang Rp. ' . toNumber($cek["limit"]) . ' |
                Jumlah Piutang Rp. ' . toNumber($cek["piutang"]) . ' |
                Sisa Limit Piutang Sebesar Rp. ' . toNumber($cek["sisa"]);
                DB::rollback();

                return redirect()->back()->withInput($request->input())
                    ->with('error', $text);
            }

            $stt->c_ac4_pend = $group->ac_pendapatan;
            $stt->c_ac4_disc = $group->ac_diskon;
            $stt->c_ac4_ppn = $group->ac_ppn;
            $stt->c_ac4_mat = $group->ac_materai;
            $stt->c_ac4_piut = $group->ac_piutang;
            $stt->c_ac4_asur = $group->ac_asuransi;
            $stt->c_ac4_packing = $group->ac_packing;
            $stt->save();

            for ($i = 1; $i <= $stt->n_koli; $i++) {
                $koli = new OpOrderKoli();
                $koli->id_stt = $stt->id_stt;
                $koli->no_koli = $i;
                $koli->dr_koli = $stt->n_koli;
                $koli->info = " Koli " . $i . "/" . $stt->n_koli . " RESI " . $stt->kode_stt;
                $koli->id_user = $stt->id_user;
                $koli->status = 1;
                $koli->status_dm_ven = "0";
                $koli->save();
            }

            //add history stt
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);

            $stat = StatusStt::where("id_ord_stt_stat", "1")->get()->first();
            $hs = new HistoryStt();
            $hs->id_stt = $stt->id_stt;
            $hs->id_status = $stat->id_ord_stt_stat;
            $hs->id_user = Auth::user()->id_user;
            $hs->keterangan = "Barang diterima dari " . $stt->pengirim_nm;
            $hs->nm_user = Auth::user()->nm_user;
            $hs->place = $wilayah->nama_wil;
            $hs->tgl_update = $stt->tgl_masuk;
            $hs->nm_pengirim = $stt->pengirim_nm;
            $hs->nm_status = $stat->nm_ord_stt_stat;
            $hs->id_wil = $wilayah->id_wil;
            $hs->id_perush = Session("perusahaan")["id_perush"];
            $hs->save();

            if (isset($request->is_import)) {
                $hasil["is_import"] = true;
                SttModel::where("id_stt", $request->no_awb)->update(
                    $hasil
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())
                ->with('error', 'Data RESI Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url("stt/" . $stt->id_stt . "/show"))->with('success', 'Data RESI  Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $stt = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $data["detail"] = DetailStt::where("id_stt", $id)->get();

        if ($stt == null) {
            return redirect()->back()->with('error', 'Data RESI tidak ada');
        }

        $awb = SttModel::where("kode_stt", $stt->no_awb)->get()->first();
        $data["data"] = $stt;

        return view('operasional::detail-stt', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $stt = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "tarif")->findOrFail($id);
        $today = date("Y-m-d");
        $futureDate = date("Y-m-d", strtotime($stt->created_at . ' + 3 days'));
        $difference = strtotime($futureDate) - strtotime($today);
        $days = abs($difference / (60 * 60) / 24);

        if ($days > 3 && !in_array(strtolower(Session("role")["nm_role"]), ['keuangan', 'admin'])) {
            return redirect()->back()->with('error', 'RESI sudah lebih dari 3 hari tidak bisa di edit');
        }

        if (is_numeric($stt->no_awb)) {
            $awb = SttModel::where("id_stt", $stt->no_awb)->get()->first();

            if ($awb != null) {
                $data["awb"] = $awb;
            } else {
                $data["awb"] = $stt->no_awb;
            }
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $data["marketing"] = Marketing::getMarketing($id_perush);
        $data["data"] = $stt;
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["cara"] = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["packing"] = Packing::select("id_packing", "nm_packing")->get();
        $data["detail"] = DetailStt::where("id_stt", $id)->get();
        $data["tarif_asuransi"] = TarifAsuransi::get()->first();
        $data["tarif_ppn"] = $perusahaan->n_ppn;
        $data["group"] = Grouppelanggan::select("id_plgn_group as kode", "nm_group", "is_umum")->get();
        $data["limit"] = SettingLimitPiutang::select("nominal", "is_default")->get();
        $data["tipe"] = TipeKirim::getList();

        return view('operasional::stt', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SttRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $stt = SttModel::findOrFail($id);

            $role = RoleUser::where("id_user", Auth::user()->id_user)->get()->first();
            $perush = Perusahaan::find($role->id_perush);

            // for creator
            $stt->updated_by = Auth::user()->id_user;
            $stt->tgl_masuk = date("Y-m-d");

            if (isset($request->tgl_masuk)) {
                $stt->tgl_masuk = $request->tgl_masuk;
            }

            $stt->tgl_keluar = $request->tgl_keluar;
            $stt->cara_kemas = $request->cara_kemas;
            // for pengirim
            $stt->pengirim_perush = $request->pengirim_perush;
            $stt->id_plgn = $request->id_pelanggan;
            $stt->pengirim_nm = $request->pengirim_nm;
            $stt->pengirim_alm = $request->pengirim_alm;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->pengirim_kodepos = $request->pengirim_kodepos;
            $stt->pengirim_id_region = $request->pengirim_id_region;
            if (isset($request->no_awb)) {
                $stt->no_awb = $request->no_awb;
            }

            // for penerima
            $stt->penerima_perush = $request->penerima_perush;
            $stt->penerima_nm = $request->penerima_nm;
            $stt->penerima_alm = $request->penerima_alm;
            $stt->penerima_telp = $request->penerima_telp;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->penerima_kodepos = $request->penerima_kodepos;
            $stt->penerima_id_region = $request->penerima_id_region;

            // for detail kirim
            $stt->id_layanan = $request->id_layanan;
            $stt->id_tarif = $request->id_tarif;
            $stt->id_cr_byr_o = $request->id_cr_byr_o;
            $stt->id_tipe_kirim = $request->id_tipe_kirim;
            $stt->id_marketing = $request->id_marketing;
            $stt->info_kirim = $request->info_kirim;
            //$stt->instruksi_kirim           = $request->instruksi_kirim;

            // for count stt
            $stt->n_berat = (Double) $request->n_berat;
            $stt->n_volume = (Double) $request->n_volume;
            $stt->n_kubik = (Double) $request->n_kubik;
            $stt->n_tarif_brt = (Double) $request->n_tarif_brt;
            $stt->n_tarif_vol = (Double) $request->n_tarif_vol;
            $stt->n_tarif_kubik = (Double) $request->n_tarif_kubik;
            $stt->n_tarif_borongan = (Double) $request->n_tarif_borongan;
            $stt->n_terusan = (Double) $request->n_terusan;
            $stt->n_hrg_terusan = (Double) $request->n_hrg_terusan;
            $stt->n_diskon = (Double) $request->n_diskon;
            $stt->n_materai = (Double) $request->n_materai;
            $stt->is_ppn = $request->is_ppn;
            $stt->n_ppn = (Double) $request->n_ppn;
            $stt->id_asuransi = $request->id_asuransi;
            $stt->n_asuransi = (Double) $request->n_asuransi;
            $stt->is_bayar = false;
            $stt->is_asuransi = $request->is_asuransi;
            $stt->is_packing = $request->is_packing;
            $stt->n_packing = (Double) $request->n_packing;

            // for id wil
            $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $stt->id_wil = $perusahaan->id_region;
            // for mathematic sum all
            $min_brt = (Double) $request->cm_brt;
            $min_vol = (Double) $request->cm_vol;
            $min_vol = (Double) $request->cm_kubik;

            if ($stt->n_berat < $min_brt) {
                $stt->n_berat = $min_brt;
            }
            if ($stt->n_volume < $min_vol) {
                $stt->n_volume = $min_vol;
            }
            // for cek to bruto
            $c_cek = $request->c_hitung;
            $stt->cara_hitung = $c_cek;
            $bruto = 0;
            if ($c_cek == 1) {

                $bruto = (double) $stt->n_berat * $stt->n_tarif_brt;
                $stt->c_tarif = 1;
            } elseif ($c_cek == 2) {

                $bruto = (double) $stt->n_volume * $stt->n_tarif_vol;
                $stt->c_tarif = 2;

            } elseif ($c_cek == 4) {

                $bruto = (double) $stt->n_kubik * $stt->n_tarif_kubik;
                $stt->c_tarif = 4;

            } elseif ($c_cek == 3) {

                $bruto = (Double) $stt->n_tarif_borongan;
                $stt->c_tarif = 3;
            } else {

                return redirect()->back()->withInput($request->input())->with('error', 'Anda memasukan karakter tidak dikenali');
            }

            // for tarif netto
            $stt->n_ppn = 0;
            if (isset($request->n_ppn) and $request->n_ppn != "0") {
                $stt->n_ppn = ($bruto - $stt->n_diskon) * 1 / 100;
            }
            $totals = (double) ($bruto + $stt->n_ppn + $stt->n_materai + $stt->n_asuransi + $stt->n_packing) - $stt->n_diskon;

            if (!in_array(strtolower(Session("role")["nm_role"]), ['keuangan', 'admin']) && $stt->n_hrg_bruto > $bruto) {
                DB::rollback();
                return redirect()->back()->withInput($request->input())->with('error', "Nominal Harga Bruto tidak boleh lebih kecil dari nominal awal");
            }

            $stt->n_hrg_bruto = $bruto;
            $stt->c_total = $totals;

            $n_koli = $request->n_koli;
            if ($request->n_koli == null or $request->n_koli == "0") {
                $n_koli = 1;
            }

            $stt->n_tarif_koli = (Double) ($stt->n_hrg_bruto - $stt->n_diskon) / $n_koli;

            //dd($stt);
            // $stt->id_status = 1;
            $stt->is_aktif = true;

            // for keuangan
            $group = SettingLayananPerush::where("id_layanan", $stt->id_layanan)->where("id_perush", Session("perusahaan")["id_perush"])
                ->get()->first();
            if ($group == null) {
                DB::rollback();
                return redirect()->back()->withInput($request->input())->with('error', "Setting Group Layanan Keuangan Belum Ada");
            }

            $stt->c_ac4_pend = $group->ac_pendapatan;
            $stt->c_ac4_disc = $group->ac_diskon;
            $stt->c_ac4_ppn = $group->ac_ppn;
            $stt->c_ac4_mat = $group->ac_materai;
            $stt->c_ac4_piut = $group->ac_piutang;
            $stt->c_ac4_asur = $group->ac_asuransi;
            $stt->c_ac4_packing = $group->ac_packing;
            //dd($request->request,$stt);

            $cekkoli = DmKoli::where("id_stt", $stt->id_stt)->get()->first();
            if ($cekkoli == null) {
                OpOrderKoli::where("id_stt", $stt->id_stt)->delete();
                for ($i = 1; $i <= $n_koli; $i++) {
                    $koli = new OpOrderKoli();
                    $koli->id_stt = $stt->id_stt;
                    $koli->no_koli = $i;
                    $koli->dr_koli = $stt->n_koli;
                    $koli->info = " Koli " . $i . "/" . $stt->n_koli . " RESI " . $stt->kode_stt;
                    $koli->id_user = $stt->id_user;
                    $koli->status = 1;
                    $koli->status_dm_ven = "0";
                    $koli->save();
                }
            } elseif ($cekkoli != null and $stt->n_koli != $request->n_koli) {
                return redirect()->back()->withInput($request->input())->with('error', "Koli sudah masuk ke DM tidak bisa diubah, hapus dahulu !");
            }

            $stt->n_koli = $request->n_koli;

            $cek = SettingLimitPiutang::ceklimitUpdate($request->id_pelanggan, $stt->c_total);

            $bpiutang = true;
            if ($cek["piutang"] == 0) {
                $bpiutang = true;
            } elseif ($cek["piutang"] > 0 and $cek["sisa"] < 0) {
                $bpiutang = false;
            }

            if ($bpiutang == false) {
                DB::rollback();
                $text = 'Stt Gagal Dibuat, Limit Piutang Rp. ' . toNumber($cek["limit"]) . ' |
                Jumlah Piutang Rp. ' . toNumber($cek["piutang"]) . ' |
                Sisa Limit Piutang Sebesar Rp. ' . toNumber($cek["sisa"]);

                return redirect()->back()->withInput($request->input())
                    ->with('error', $text);
            }

            $stt->save();

            $stat = StatusStt::where("id_ord_stt_stat", "1")->get()->first();
            $history = [];

            $history["id_stt"] = $stt->id_stt;
            $history["id_status"] = $stat->id_ord_stt_stat;
            $history["id_user"] = Auth::user()->id_user;
            $history["keterangan"] = "Barang diterima dari " . $stt->pengirim_nm;
            $history["nm_user"] = Auth::user()->nm_user;
            $history["tgl_update"] = $stt->tgl_masuk;
            $history["nm_pengirim"] = $stt->pengirim_nm;
            $history["nm_status"] = $stat->nm_ord_stt_stat;

            HistoryStt::where('id_stt', $stt->id_stt)->where('id_status', 1)->update($history);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())->with('error', 'Data RESI Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data RESI Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Session("role")["id_role"] != 3) {
            return redirect()->back()->with('error', 'Yang Bisa menghapus Stt Hanya Admin');
        }

        DB::beginTransaction();

        try {

            $stt = SttModel::findOrFail($id);
            $stt_dm = DB::table('t_order_dm')->where('id_stt', $id)->get();
            DB::table('t_order_dm')->where('id_stt', $id)->delete();
            DraftSttInvoice::where("id_stt", $id)->delete();
            HistoryStt::where("id_stt", $id)->delete();
            Pembayaran::where("id_stt", $id)->delete();
            OpOrderKoli::where("id_stt", $id)->delete();

            $stt->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Stt Gagal Dihapus ' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Stt berhasil hapus');
    }

    public function generate3($id_layanan)
    {
        $perush = Perusahaan::findOrFail(Session('perusahaan')['id_perush']);
        $layanan = Layanan::find($id_layanan);
        $date = date("ym");

        $b = substr(crc32(uniqid()), -4);
        $id = strtoupper($layanan->id_layanan . $perush->id_perush . $date . $b);
        $kode = strtoupper($perush->kode_perush . $date . $b);

        $data = [];
        $data["id_perush"] = $perush->id_perush;
        $data["kode_stt"] = $kode;

        return $data;
    }

    public function goAuthBorongan(Request $request)
    {
        $data = [];
        if (!is_numeric($request->code) or strlen($request->code) != 6) {
            $data["code"] = 0;
            $data["message"] = "Request Code Tidak Valid";
        } else {

            $cek = $this->CheckCode($request->code);

            if ($cek) {
                $data["code"] = 1;
                $data["secret"] = $request->code;
                $data["message"] = "Request Code Valid";
            } else {
                $data["code"] = 0;
                $data["message"] = "Request Code Invalid";
            }
        }

        return response()->json($data);
    }

    public function CheckCode($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $auth = Authenticator::where("id_perush", $id_perush)->get()->first();
        $kode = $auth->auth_kode;
        $ga = new GoogleAuthenticator();
        $ga->setSecret($kode);
        $checkResult = $ga->verifyCode($ga->secret_code, $id, 6);

        return $checkResult;
    }

    public function savedetail(Request $request)
    {
        DB::beginTransaction();
        try {

            $detail = new DetailStt();
            $detail->id_stt = $request->id_stt;
            $detail->ket_koli = $request->ket_koli;
            $detail->keterangan = $request->keterangan;
            $detail->id_user = Auth::user()->id_user;

            $detail->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())->with('error', 'Data Detail RESI Gagal Disimpan' . $e->getMessage());
        }

        return redirect(url("stt") . "/" . $detail->id_stt . "/show")->with('success', 'Data Detail RESI Disimpan');
    }

    public function updatestt(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $detail = DetailStt::findOrFail($id);
            $detail->id_stt = $request->id_stt;
            $detail->ket_koli = $request->ket_koli;
            $detail->keterangan = $request->keterangan;
            $detail->id_user = Auth::user()->id_user;
            //dd($detail);
            $detail->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())->with('error', 'Data Detail RESI Gagal Disimpan');
        }

        return redirect(url("stt") . "/" . $detail->id_stt . "/show")->with('success', 'Data Detail RESI Disimpan');
    }

    public function deletestt($id)
    {
        DB::beginTransaction();
        try {

            $detail = DetailStt::findOrFail($id);
            $detail->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Detail RESI Gagal dihapus');
        }

        return redirect(url("stt") . "/" . $detail->id_stt . "/show")->with('success', 'Data Detail RESI dihapus');
    }

    public function getSttKoli($id = null)
    {
        $data = OpOrderKoli::where("id_stt", $id)->get();

        $a_data = [];
        foreach ($data as $key => $value) {
            if ($value->status == 1) {

                $a_data[$key] = '<tr><td>' . ($key + 1) . '</td><td>' . $value->id_stt . $value->no_koli . '</td><td>' . $value->no_koli . '</td><td class="text-center"><input type="checkbox" name="c_koli" id="c_koli" value="1" class="form-control"></td></tr>';
            } else {

                $a_data[$key] = '<tr><td>' . ($key + 1) . '</td><td>' . $value->id_stt . $value->no_koli . '</td><td>' . $value->no_koli . '</td><td class="text-center"><input type="checkbox" name="c_koli" id="c_koli" value="0" class="form-control"></td></tr>';
            }
        }

        $d_data = [];
        for ($i = 0; $i < 100; $i++) {
            $d_data[$i] = $a_data[1];
        }
        // //dd($d_data);

        return response()->json($d_data);
    }

    public function tracking($id)
    {
        $stt = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $data["detail"] = HistoryStt::getHistory($id);
        $data["detail_dokumen"] = HistoryDokumenStt::where('id_stt', $id)->get();
        $data["status"] = StatusStt::getStatusKosong();
        $data["mapping"] = StatusStt::getMapping();

        if ($stt == null) {
            return redirect()->back()->with('error', 'Data RESI tidak ada');
        }

        if ($stt->id_status < 2) {
            return redirect()->back()->with('error', 'Belum ada update status RESI');
        }

        $gambar = HistoryStt::with("status")->select("gambar1", "gambar2")->where("id_stt", $id)->where("id_status", "7")->get()->first();

        $data["gambar"] = $gambar;

        $awb = SttModel::where("kode_stt", $stt->no_awb)->get()->first();

        if ($awb != null) {
            $stt->no_awb = $awb->kode_stt;
        }

        $data["data"] = $stt;

        return view('operasional::track', $data);
    }

    public function cetak_pdf($id)
    {
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();

        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $data["detail"] = DetailStt::where("id_stt", $id)->get();
        $data["id"] = $id;

        $data["qrcode"] = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($data["data"]->kode_stt));
        $pdf = \PDF::loadview("operasional::cetak-stt-new", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function cetak_kosong($id)
    {
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();

        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $ttd = TandaTangan::where("id_ref", $id)->get();
        if (isset($ttd) and count($ttd) > 0) {
            foreach ($ttd as $key => $value) {
                if ($value->level == 1) {
                    $data["admin"] = $value->ttd;
                }
                if ($value->level == 2) {
                    $data["pengirim"] = $value->ttd;
                }
                if ($value->level == 3) {
                    $data["penerima"] = $value->ttd;
                }
            }
        }
        $data["detail"] = DetailStt::where("id_stt", $id)->get();
        $data["id"] = $id;

        $data["qrcode"] = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($data["data"]->kode_stt));
        return view('operasional::cetak-stt-kosong', $data);
    }

    public function cetak_pdf1($id)
    {
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        //dd($data);
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $ttd = TandaTangan::where("id_ref", $id)->get();
        if (isset($ttd) and count($ttd) > 0) {
            foreach ($ttd as $key => $value) {
                if ($value->level == 1) {
                    $data["admin"] = $value->id;
                }
                if ($value->level == 2) {
                    $data["pengirim"] = $value->id;
                }
                if ($value->level == 3) {
                    $data["penerima"] = $value->id;
                }
            }
        }
        $data["detail"] = DetailStt::where("id_stt", $id)->get();
        $data["id"] = $id;
        return view('operasional::cetak-stt', $data);
        // return view('operasional::reportstt.new-cetakstt', $data);
    }

    public function Labeling($id)
    {
        $dataf = SttModel::getKoli($id)->get();
        //dd($dataf);
        $data["data"] = $dataf;
        $data["id_stt"] = $id;
        $data["kode_stt"] = SttModel::with("layanan", "asal", "tujuan")->where("id_stt", $id)->get()->first();
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["perush_tj"] = SttDm::getPerushTj($id);
        // $data["barcode"] = base64_encode(DNS1D::getBarcodePNG('4', 'C39+'));
        // dd($data);

        $customPaper = array(0, 0, 301.36220472, 187.97637795);
        $pdf = \PDF::loadview("operasional::label.new-label81", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])
            ->setPaper($customPaper, 'potrait');

        return $pdf->stream();
    }

    public function NewLabeling($id)
    {
        $dataf = SttModel::getKoli($id)->get();
        //dd($dataf);
        $data["data"] = $dataf;
        $data["id_stt"] = $id;
        $data["kode_stt"] = SttModel::with("layanan", "asal", "tujuan")->where("id_stt", $id)->get()->first();
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["perush_tj"] = SttDm::getPerushTj($id);
        // $data["barcode"] = base64_encode(DNS1D::getBarcodePNG('4', 'C39+'));
        // dd($data);

        $customPaper = array(0, 0, 301.36220472, 187.97637795);
        $pdf = \PDF::loadview("operasional::label.new-label150", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])
            ->setPaper($customPaper, 'potrait');

        return $pdf->stream();
        // return view('operasional::label.new-label150', $data);
    }

    public function Labeling1($id)
    {
        $dataf = SttModel::getKoli($id)->get();
        //dd($dataf);
        $data["data"] = $dataf;
        $data["id_stt"] = $id;
        $data["kode_stt"] = SttModel::with("layanan", "asal", "tujuan")->where("id_stt", $id)->get()->first();
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        return view('operasional::label', $data);
    }

    public function getSttPerush($id, $id_dm)
    {
        $dm = DaftarMuat::findOrFail($id_dm);

        $data = SttModel::with(["koli" => function ($query) {
            $query->where("status", "1");
        }, "koli2" => function ($query) {
            $query->where("status", "2");
        }, "sttdm"])->where("id_perush_asal", $id)->where("id_layanan", $dm->id_layanan)->get();

        $a_data = [];
        foreach ($data as $key => $value) {
            foreach ($value->koli as $key2 => $value2) {
                $a_data[$key]["kode"] = $value2->id_stt;
                $a_data[$key]["value"] = $value2->id_stt;
            }
        }

        return response()->json($a_data);
    }

    public function import(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai ? $request->tgl_mulai : date("Y-m-d", strtotime("-2 day"));
        $tgl_selesai = $request->tgl_selesai ? $request->tgl_selesai : date("Y-m-d", strtotime("+2 day"));
        $res = Booking::getBooking($tgl_mulai, $tgl_selesai) != null ? Booking::getBooking($tgl_mulai, $tgl_selesai) : [];

        $data["data"] = $res != [] ? $res->Respon_data : [];
        $data["layanan"] = Layanan::getOrderId();

        return view("operasional::stt.import", $data);
    }

    public function showimport($id)
    {
        $res = $id != null ? Booking::getDetail($id) : abort(404);

        if ($res->Respon_status != 0) {
            abort(404);
        }

        $rest = $res->Respon_data;
        $data["data"] = $rest;
        $data["asal"] = Wilayah::where("id_wil", $rest->id_region_pengirim)->first();
        $data["tujuan"] = Wilayah::where("id_wil", $rest->id_region_penerima)->first();
        $id_perush = Session("perusahaan")["id_perush"];
        $perusahaan = Perusahaan::find($id_perush);
        $data["cara"] = CaraBayar::getList();
        $data["marketing"] = Marketing::getMarketing($id_perush);
        $data["packing"] = Packing::getList();
        $data["group"] = Grouppelanggan::select("id_plgn_group as kode", "nm_group", "is_umum")->get();
        $data["layanan"] = Layanan::getLayanan();
        $data["tarif_asuransi"] = TarifAsuransi::where("id_perush", $id_perush)->get()->first();
        $data["tarif_ppn"] = $perusahaan->n_ppn;
        $data["limit"] = SettingLimitPiutang::select("nominal", "is_default")->get();
        $data["tipe"] = TipeKirim::getList();
        $data["pelanggan"] = Pelanggan::where("id_perush", $id_perush)->get();
        $data["id_pelanggan"] = $rest->id_pelanggan;

        return view("operasional::stt.showimport", $data);
    }

    public function saveimport($id, SttRequest $request)
    {
        DB::beginTransaction();
        try {

            $cek = SettingLimitPiutang::ceklimit($request->id_pelanggan);
            $bpiutang = true;
            if ($cek["piutang"] == 0) {
                $bpiutang = true;
            } elseif ($cek["piutang"] > 0 and $cek["sisa"] <= 0) {
                $bpiutang = false;
            }

            if ($bpiutang == false) {
                $text = 'Stt Gagal Dibuat, Limit Piutang Rp. ' . toNumber($cek["limit"]) . ' |
                Jumlah Piutang Rp. ' . toNumber($cek["piutang"]) . ' |
                Sisa Limit Piutang Sebesar Rp. ' . toNumber($cek["sisa"]);

                return redirect()->back()->withInput($request->input())
                    ->with('error', $text);
            }

            $ids = $this->generate3($request->id_layanan);
            $stt = new SttModel();
            $stt->kode_stt = $id;
            // for creator
            $stt->id_user = Auth::user()->id_user;
            $stt->id_perush_asal = Session("perusahaan")["id_perush"];
            $stt->tgl_masuk = date("Y-m-d h:i:s");
            $stt->tgl_masuk = $request->tgl_masuk;
            $stt->tgl_keluar = $request->tgl_keluar;

            // for pengirim
            $stt->pengirim_perush = $request->pengirim_perush;
            $stt->id_plgn = $request->id_pelanggan;
            $stt->pengirim_nm = $request->pengirim_nm;
            $stt->pengirim_alm = $request->pengirim_alm;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->pengirim_kodepos = $request->pengirim_kodepos;
            $stt->pengirim_id_region = $request->pengirim_id_region;
            $stt->no_awb = $request->no_awb;

            // for penerima
            $stt->penerima_perush = $request->penerima_perush;
            $stt->penerima_nm = $request->penerima_nm;
            $stt->penerima_alm = $request->penerima_alm;
            $stt->penerima_telp = $request->penerima_telp;
            $stt->pengirim_telp = $request->pengirim_telp;
            $stt->penerima_kodepos = $request->penerima_kodepos;
            $stt->penerima_id_region = $request->penerima_id_region;

            // for detail kirim
            $stt->id_layanan = $request->id_layanan;
            $stt->id_tarif = $request->id_tarif;
            $stt->id_cr_byr_o = $request->id_cr_byr_o;
            $stt->id_tipe_kirim = $request->id_tipe_kirim;
            $stt->id_marketing = $request->id_marketing;
            $stt->info_kirim = $request->info_kirim;

            // for count stt
            $stt->n_berat = (Double) $request->n_berat;
            $stt->n_volume = (Double) $request->n_volume;
            $stt->n_kubik = (Double) $request->n_kubik;
            $stt->n_koli = $request->n_koli;
            $stt->n_tarif_brt = (Double) $request->n_tarif_brt;
            $stt->n_tarif_vol = (Double) $request->n_tarif_vol;
            $stt->n_tarif_kubik = (Double) $request->n_tarif_kubik;
            $stt->n_tarif_borongan = (Double) $request->n_tarif_borongan;
            $stt->n_hrg_bruto = (Double) $request->n_hrg_bruto;
            $stt->n_terusan = (Double) $request->n_terusan;
            $stt->n_hrg_terusan = (Double) $request->n_hrg_terusan;
            $stt->n_diskon = (Double) $request->n_diskon;
            $stt->n_materai = (Double) $request->n_materai;
            $stt->is_ppn = $request->is_ppn;
            $stt->n_ppn = (Double) $request->n_ppn;
            $stt->is_bayar = false;
            $stt->cara_kemas = $request->cara_kemas;

            //Asuransi
            $stt->is_asuransi = $request->is_asuransi;
            $stt->id_asuransi = $request->id_asuransi;
            $stt->n_asuransi = (Double) $request->n_asuransi;
            $stt->n_harga_pertanggungan = (Double) $request->n_pertanggungan;

            //Packing
            $stt->is_packing = $request->is_packing;

            // for id wil
            $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $stt->id_wil = $perusahaan->id_region;
            // for mathematic sum all
            $min_brt = (Double) $request->cm_brt;
            $min_vol = (Double) $request->cm_vol;
            $min_vol = (Double) $request->cm_kubik;

            if ($stt->n_berat < $min_brt) {
                $stt->n_berat = $min_brt;
            }
            if ($stt->n_volume < $min_vol) {
                $stt->n_volume = $min_vol;
            }
            // for cek to bruto
            $c_cek = $request->c_hitung;
            $stt->cara_hitung = $c_cek;
            if ($c_cek == 1) {

                $stt->n_hrg_bruto = (double) $stt->n_berat * $stt->n_tarif_brt;
                $stt->c_tarif = 1;
            } elseif ($c_cek == 2) {

                $stt->n_hrg_bruto = (double) $stt->n_volume * $stt->n_tarif_vol;
                $stt->c_tarif = 2;

            } elseif ($c_cek == 4) {

                $stt->n_hrg_bruto = (double) $stt->n_kubik * $stt->n_tarif_kubik;
                $stt->c_tarif = 4;

            } elseif ($c_cek == 3) {

                $stt->n_hrg_bruto = (Double) $stt->n_tarif_borongan;
                $stt->c_tarif = 3;
            } else {

                return redirect()->back()->withInput($request->input())
                    ->with('error', 'Anda memasukan karakter tidak dikenali');
            }

            // for tarif netto
            $stt->n_ppn = 0;
            if (isset($request->n_ppn) and $request->n_ppn != "0") {
                $stt->n_ppn = ($stt->n_hrg_bruto - $stt->n_diskon) * 1 / 100;
            }
            $stt->c_total = (double) ($stt->n_hrg_bruto + $stt->n_ppn + $stt->n_materai + $stt->n_asuransi) - $stt->n_diskon;

            // tarif koli
            $n_koli = $request->n_koli;
            if ($request->n_koli == null or $request->n_koli == "0") {
                $n_koli = 1;
            }

            $stt->n_tarif_koli = (Double) ($stt->n_hrg_bruto - $stt->n_diskon) / $stt->n_koli;
            $stt->id_status = 1;
            $stt->is_aktif = true;

            // for keuangan
            $group = SettingLayananPerush::where("id_layanan", $stt->id_layanan)->where("id_perush", Session("perusahaan")["id_perush"])
                ->get()->first();
            if ($group == null) {
                DB::rollback();
                return redirect()->back()->withInput($request->input())
                    ->with('error', "Setting Group Layanan Keuangan Belum Ada");
            }

            // cek piutang
            if ($cek["piutang"] > 0 and $stt->c_total > $cek["sisa"]) {
                $bpiutang = false;
            }

            if ($bpiutang == false) {

                $text = 'Stt Gagal Dibuat, Limit Piutang Rp. ' . toNumber($cek["limit"]) . ' |
                Jumlah Piutang Rp. ' . toNumber($cek["piutang"]) . ' |
                Sisa Limit Piutang Sebesar Rp. ' . toNumber($cek["sisa"]);
                DB::rollback();

                return redirect()->back()->withInput($request->input())
                    ->with('error', $text);
            }

            $stt->c_ac4_pend = $group->ac_pendapatan;
            $stt->c_ac4_disc = $group->ac_diskon;
            $stt->c_ac4_ppn = $group->ac_ppn;
            $stt->c_ac4_mat = $group->ac_materai;
            $stt->c_ac4_piut = $group->ac_piutang;
            $stt->c_ac4_asur = $group->ac_asuransi;
            $is_booking = true;
            $stt->save();

            $koli = [];
            for ($i = 1; $i <= $stt->n_koli; $i++) {
                $koli[$i]["id_stt"] = $stt->id_stt;
                $koli[$i]["no_koli"] = $i;
                $koli[$i]["dr_koli"] = $stt->n_koli;
                $koli[$i]["info"] = " Koli " . $i . "/" . $stt->n_koli . " RESI " . $stt->kode_stt;
                $koli[$i]["id_user"] = $stt->id_user;
                $koli[$i]["status"] = 1;
                $koli[$i]["status_dm_ven"] = 0;
                $koli[$i]["created_at"] = date("Y-m-d");
                $koli[$i]["updated_at"] = date("Y-m-d");
            }
            // insert koli
            OpOrderKoli::insert($koli);

            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);

            $stat = StatusStt::where("id_ord_stt_stat", "1")->get()->first();
            $hs = new HistoryStt();
            $hs->id_stt = $stt->id_stt;
            $hs->id_status = "1";
            $hs->id_user = Auth::user()->id_user;
            $hs->keterangan = "Barang diterima dari " . $stt->pengirim_nm;
            $hs->nm_user = Auth::user()->nm_user;
            $hs->place = $wilayah->nama_wil;
            $hs->nm_pengirim = $stt->pengirim_nm;
            $hs->nm_status = $stat->nm_ord_stt_stat;
            $hs->id_wil = $wilayah->id_wil;
            $hs->id_perush = Session("perusahaan")["id_perush"];
            $hs->save();

            if (isset($request->is_import)) {
                $hasil["is_import"] = true;
                SttModel::where("id_stt", $request->no_awb)->update(
                    $hasil
                );
            }

            // update ke booking
            Booking::donebooking($id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->input())
                ->with('error', 'Data RESI Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url("stt/" . $stt->id_stt . "/show"))->with('success', 'Data RESI  Disimpan');
    }

    public function getStt(Request $request)
    {
        $term = $request->term;
        $data = SttModel::select("id_stt")->where("id_stt", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->id_stt)];
        }

        return response()->json($results);
    }

    public function getppn($id)
    {
        $sql = "select coalesce(n_ppn, 0) from s_perusahaan where id_perush='" . Session("perusahaan")["id_perush"] . "' ";
        $data = DB::select(DB::raw($sql))[0];

        return $data;
    }
}
