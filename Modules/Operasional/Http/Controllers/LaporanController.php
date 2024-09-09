<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\CaraBayar;
use App\Models\Perusahaan;
use App\Models\Layanan;
use DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {

        $data["perusahaan"] = Perusahaan::all();
        $data["layanan"] = Layanan::all();
        $data["pengirim_id_region"] = null;

        return view('operasional::repp.rep_stt', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('master::create');
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
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('master::edit');
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

    public function cari(Request $request)
    {
        //dd($request);
        $dataf = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan");
        //dd($dataf);
        if ($request->id_perush_asal != 0) {
            $dataf = $dataf->where('pengirim_perush', $request->id_perush_asal);
        }
        if ($request->id_perush_tujuan != 0) {
            $dataf = $dataf->where('penerima_perush', $request->id_perush_tujuan);
        }
        if ($request->pengirim_id_region != 0) {
            $dataf = $dataf->where('pengirim_id_region', $request->pengirim_id_region);
        }
        if ($request->id_layanan != 0) {
            $dataf = $dataf->where('id_layanan', $request->id_layanan);
        }
        if ($request->tgl_masuk != null) {
            $dataf = $dataf->where('tgl_masuk', $request->tgl_masuk);
        }

        $data["perusahaan"] = Perusahaan::all();
        $data["layanan"] = Layanan::all();
        $data["pengirim_perush"] = $request->id_perush_asal;
        $data["penerima_perush"] = $request->id_perush_tujuan;
        $data["pengirim_id_region"] = $request->pengirim_id_region;
        $data["id_layanan"] = $request->id_layanan;
        $data["tgl_masuk"] = $request->tgl_masuk;
        $data["data"] = $dataf->get();

        return view('operasional::repp.rep_stt', $data);
    }

    public function cetakhtml(Request $request)
    {
        $dataf = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan");
        //dd($dataf);
        if ($request->id_perush_asal != 0) {
            $dataf = $dataf->where('pengirim_perush', $request->id_perush_asal);
        }
        if ($request->id_perush_tujuan != 0) {
            $dataf = $dataf->where('penerima_perush', $request->id_perush_tujuan);
        }
        if ($request->pengirim_id_region != 0) {
            $dataf = $dataf->where('pengirim_id_region', $request->pengirim_id_region);
        }
        if ($request->id_layanan != 0) {
            $dataf = $dataf->where('id_layanan', $request->id_layanan);
        }
        if ($request->tgl_masuk != null) {
            $dataf = $dataf->where('tgl_masuk', $request->tgl_masuk);
        }

        $data["perusahaan"] = Perusahaan::where(DB::raw("lower(id_perush)"), strtolower(Session("perusahaan")['id_perush']))->get()->first();
        $data["data"] = $dataf->get();
        return view('operasional::repp.cetak_html', $data);

    }

    public function cetakexcel(Request $request)
    {
        $dataf = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan");
        //dd($dataf);
        if ($request->id_perush_asal != 0) {
            $dataf = $dataf->where('pengirim_perush', $request->id_perush_asal);
        }
        if ($request->id_perush_tujuan != 0) {
            $dataf = $dataf->where('penerima_perush', $request->id_perush_tujuan);
        }
        if ($request->pengirim_id_region != 0) {
            $dataf = $dataf->where('pengirim_id_region', $request->pengirim_id_region);
        }
        if ($request->id_layanan != 0) {
            $dataf = $dataf->where('id_layanan', $request->id_layanan);
        }
        if ($request->tgl_masuk != null) {
            $dataf = $dataf->where('tgl_masuk', $request->tgl_masuk);
        }

        $data["perusahaan"] = Perusahaan::where(DB::raw("lower(id_perush)"), strtolower(Session("perusahaan")['id_perush']))->get()->first();
        $data["data"] = $dataf->get();
        return view('operasional::repp.excel', $data);
    }

    public function OutstandingStt(Request $request)
    {
        $dr_tgl = $request->dr_tgl ?? date('Y-m-d', strtotime('-1 month'));
        $sp_tgl = $request->sp_tgl ?? date('Y-m-d');

        $stt = DB::table(DB::raw('(
            SELECT DISTINCT id_stt,
                id_status,
                DATE(COALESCE ( tgl_update, created_at )) AS tgl_update,
                nm_status,
                keterangan,
                ( tgl_update - CURRENT_DATE ) AS diff_date
            FROM
                t_history_stt
	        WHERE
		        id_status < 7 
            ORDER BY
                id_stt,
                id_history DESC) AS master'))
        ->select(
            'A.kode_stt',
            'A.no_awb',
            'A.tgl_masuk',
            'A.pengirim_nm','A.pengirim_telp','asal.nama_wil AS asal',
            'A.penerima_nm','A.penerima_telp','tujuan.nama_wil AS tujuan',
            'A.id_stt', 'master.id_status','master.nm_status','master.tgl_update',DB::raw('(master.tgl_update + 3) AS tgl_harus_update'),DB::raw('((master.tgl_update + 3) - CURRENT_DATE) AS diff_date'))
        ->join('t_order AS A','A.id_stt','=','master.id_stt')
        ->join('m_wilayah AS asal','A.pengirim_id_region','=','asal.id_wil')
        ->join('m_wilayah AS tujuan','A.penerima_id_region','=','tujuan.id_wil')
        ->whereBetween('master.tgl_update',[$dr_tgl,$sp_tgl])
        ->where('A.id_status', '<', 7)
        ->where('A.id_perush_asal', Session("perusahaan")["id_perush"]);
        // dd($stt);
        if (isset($request->id_stt)) {
            $stt = $stt->where('A.id_stt', $request->id_stt);
        }
        $data["detail"] = $stt->groupBy('A.id_stt')->get();
        $data["status"] = StatusStt::getStatusKosong();
        $data["filter"] = [
                    "dr_tgl" => $dr_tgl,
                    "sp_tgl" => $sp_tgl,
                    "id_stt" => $request->id_stt ?? null,
                ];
        return view('operasional::stt.outstanding-stt', $data);
    }


    public function rekapentristatus(Request $request)
    {
        if ($request->entri_awal != null && trim($request->entri_awal) != "" && $request->entri_akhir != null && trim($request->entri_akhir) != "") {
            $idp = Session('perusahaan')['id_perush'];
            $data["entrilist"] = DB::select("SELECT QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.nm_sbg_update,
                    COUNT(DISTINCT QX.id_stt) AS n_stt,
                    COUNT(QX.id_history) AS n_stat,
                    SUM(CASE WHEN QX.selisih < 0 THEN 1 ELSE 0 END) AS n_awal,
                    SUM(CASE WHEN QX.selisih = 0 THEN 1 ELSE 0 END) AS n_tepat,
                    SUM(CASE WHEN QX.selisih > 0 THEN 1 ELSE 0 END) AS n_telat

                FROM (
                    SELECT O.kode_stt,
                        O.tgl_masuk,
                        O.id_perush_asal,
                        CASE
                            WHEN O.id_perush_asal = Q1.id_perush_status THEN 'SEBAGAI PENGIRIM'
                            ELSE 'SEBAGAI PENERIMA'
                        END AS nm_sbg_update,
                        Q1.*,
                        H2.tgl_update AS tgl_sbl,
                        (Q1.tgl_update - H2.tgl_update) AS dif_date,
                        ((Q1.tgl_update - H2.tgl_update) - 3) AS selisih
                    FROM (
                    SELECT 
                                H.id_history,
                                H.id_stt,
                                H.id_perush,
                                COALESCE(H.id_perush, O.id_perush_asal) AS id_perush_status,
                                H.kode_status,
                                H.tgl_update,
                                H.nm_status,
                                H.place,
                                H.keterangan,
                                (SELECT MAX(id_history) AS m  FROM t_history_stt WHERE id_history < H.id_history AND id_stt = H.id_stt) id_history_sebelum
                    FROM t_history_stt H
                    LEFT JOIN t_order O ON H.id_stt = O.id_stt
                    WHERE  H.tgl_update BETWEEN '$request->entri_awal' AND '$request->entri_akhir'
                        AND H.id_status > '1'
                        AND COALESCE(H.id_perush, O.id_perush_asal) = $idp

                    ) Q1
                    LEFT JOIN t_history_stt H2 ON Q1.id_history_sebelum = H2.id_history
                    LEFT JOIN t_order O ON Q1.id_stt = O.id_stt
                ) QX

                -- WHERE
                GROUP BY QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.nm_sbg_update");
            // dd($data["entrilist"]);
            $data["entri_awal"] = $request->entri_awal;
            $data["entri_akhir"] = $request->entri_akhir;
            return view('operasional::reportstt.rekapentristatus', $data);
        } else {
            return redirect()->to("outstandingstt")->withErrors(["error" => "Pastikan Anda Memilih Tanggal Rekap Entri Status!"]);
        }
    }

    public function rekapstatusbystt(Request $request)
    
    {
        if ($request->status_awal != null && trim($request->status_awal) != "" && $request->status_akhir != null && trim($request->status_akhir) != "" && $request->nm_sbg != null && trim($request->nm_sbg) != "") {
            $idp = Session('perusahaan')['id_perush'];
            $idp = Session('perusahaan')['id_perush'];
            // defaultnya pengirim
            $data["sttlist"] = Db::select("SELECT QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.id_sbg_update,
                    QX.nm_sbg_update,
                    QX.id_stt,
                    QX.kode_stt,
                    COUNT(QX.id_history) AS n_stat,
                    SUM(CASE WHEN QX.selisih < 0 THEN 1 ELSE 0 END) AS n_awal,
                    SUM(CASE WHEN QX.selisih = 0 THEN 1 ELSE 0 END) AS n_tepat,
                    SUM(CASE WHEN QX.selisih > 0 THEN 1 ELSE 0 END) AS n_telat

                FROM (
                    SELECT O.kode_stt,
                        O.tgl_masuk,
                        O.id_perush_asal,
                        CASE
                            WHEN O.id_perush_asal = Q1.id_perush_status THEN 1
                            ELSE 2
                        END AS id_sbg_update,
                        CASE
                            WHEN O.id_perush_asal = Q1.id_perush_status THEN 'SEBAGAI PENGIRIM'
                            ELSE 'SEBAGAI PENERIMA'
                        END AS nm_sbg_update,
                        Q1.*,
                        H2.tgl_update AS tgl_sbl,
                        (Q1.tgl_update - H2.tgl_update) AS dif_date,
                        ((Q1.tgl_update - H2.tgl_update) - 3) AS selisih
                    FROM (
                    SELECT 
                                H.id_history,
                                H.id_stt,
                                H.id_perush,
                                COALESCE(H.id_perush, O.id_perush_asal) AS id_perush_status,
                                H.kode_status,
                                H.tgl_update,
                                H.nm_status,
                                H.place,
                                H.keterangan,
                                (SELECT MAX(id_history) AS m  FROM t_history_stt WHERE id_history < H.id_history AND id_stt = H.id_stt) id_history_sebelum
                    FROM t_history_stt H
                    LEFT JOIN t_order O ON H.id_stt = O.id_stt
                    WHERE  H.tgl_update BETWEEN '$request->status_awal' AND '$request->status_akhir'
                        AND H.id_status > '1'
                        AND COALESCE(H.id_perush, O.id_perush_asal) = $idp

                    ) Q1
                    LEFT JOIN t_history_stt H2 ON Q1.id_history_sebelum = H2.id_history
                    LEFT JOIN t_order O ON Q1.id_stt = O.id_stt
                ) QX

                WHERE QX.id_perush_asal = $idp
                GROUP BY QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.id_sbg_update,
                    QX.nm_sbg_update,
                    QX.id_stt,
                    QX.kode_stt
                ORDER BY QX.id_stt");
            // apabila sebagai penerima
            if ($request->nm_sbg == "SEBAGAI PENERIMA") {
                $data["sttlist"] = Db::select("SELECT QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.id_sbg_update,
                    QX.nm_sbg_update,
                    QX.id_stt,
                    QX.kode_stt,
                    COUNT(QX.id_history) AS n_stat,
                    SUM(CASE WHEN QX.selisih < 0 THEN 1 ELSE 0 END) AS n_awal,
                    SUM(CASE WHEN QX.selisih = 0 THEN 1 ELSE 0 END) AS n_tepat,
                    SUM(CASE WHEN QX.selisih > 0 THEN 1 ELSE 0 END) AS n_telat

                FROM (
                    SELECT O.kode_stt,
                        O.tgl_masuk,
                        O.id_perush_asal,
                        CASE
                            WHEN O.id_perush_asal = Q1.id_perush_status THEN 1
                            ELSE 2
                        END AS id_sbg_update,
                        CASE
                            WHEN O.id_perush_asal = Q1.id_perush_status THEN 'SEBAGAI PENGIRIM'
                            ELSE 'SEBAGAI PENERIMA'
                        END AS nm_sbg_update,
                        Q1.*,
                        H2.tgl_update AS tgl_sbl,
                        (Q1.tgl_update - H2.tgl_update) AS dif_date,
                        ((Q1.tgl_update - H2.tgl_update) - 3) AS selisih
                    FROM (
                    SELECT 
                                H.id_history,
                                H.id_stt,
                                H.id_perush,
                                COALESCE(H.id_perush, O.id_perush_asal) AS id_perush_status,
                                H.kode_status,
                                H.tgl_update,
                                H.nm_status,
                                H.place,
                                H.keterangan,
                                (SELECT MAX(id_history) AS m  FROM t_history_stt WHERE id_history < H.id_history AND id_stt = H.id_stt) id_history_sebelum
                    FROM t_history_stt H
                    LEFT JOIN t_order O ON H.id_stt = O.id_stt
                    WHERE  H.tgl_update BETWEEN '$request->status_awal' AND '$request->status_akhir'
                        AND H.id_status > '1'
                        AND COALESCE(H.id_perush, O.id_perush_asal) = $idp

                    ) Q1
                    LEFT JOIN t_history_stt H2 ON Q1.id_history_sebelum = H2.id_history
                    LEFT JOIN t_order O ON Q1.id_stt = O.id_stt
                ) QX

                WHERE QX.id_perush_status = $idp and QX.nm_sbg_update = '$request->nm_sbg'
                GROUP BY QX.id_perush_asal,
                    QX.id_perush_status,
                    QX.id_sbg_update,
                    QX.nm_sbg_update,
                    QX.id_stt,
                    QX.kode_stt
                ORDER BY QX.id_stt");
            }

            // querry raw disini
            // dd($data["sttlist"]);
            $data["status_awal"] = $request->status_awal;
            $data["status_akhir"] = $request->status_akhir;
            $data["nm_sbg"] = $request->nm_sbg;
            return view('operasional::reportstt.rekapstatusbystt', $data);
        } else {
            return redirect()->to("outstandingstt")->withErrors(["error" => "Pastikan Anda Memilih Tanggal Report!"]);
        }
    }

    public function rekapstatusbysttdetail(Request $request)
    {

        if ($request->status_awal != null && trim($request->status_awal) != "" && $request->status_akhir != null && trim($request->status_akhir) != "" && $request->nm_sbg != null && $request->nm_sbg != "") {
            $idstt = $request->segment(2);
            $idp = Session('perusahaan')['id_perush'];


            $data["detailstt"] = Db::select("SELECT O.kode_stt,
                    O.tgl_masuk,
                    O.id_perush_asal,
                    CASE
                        WHEN O.id_perush_asal = Q1.id_perush_status THEN 1
                        ELSE 2
                    END AS id_sbg_update,
                    CASE
                        WHEN O.id_perush_asal = Q1.id_perush_status THEN 'SEBAGAI PENGIRIM'
                        ELSE 'SEBAGAI PENERIMA'
                    END AS nm_sbg_update,
                    Q1.*,
                    H2.tgl_update AS tgl_sbl,
                    (Q1.tgl_update - H2.tgl_update) AS dif_date,
                    ((Q1.tgl_update - H2.tgl_update) - 3) AS selisih,
                    DM.kode_dm,
                    DM.tgl_berangkat
                FROM (
                SELECT 
                    H.id_history,
                            H.id_stt,
                            H.id_perush,
                            COALESCE(H.id_perush, O.id_perush_asal) AS id_perush_status,
                            H.kode_status,
                            H.tgl_update,
                            H.nm_status,
                            H.place,
                            H.keterangan,
                    (SELECT MAX(id_history) AS m  FROM t_history_stt WHERE id_history < H.id_history AND id_stt = H.id_stt) id_history_sebelum
                FROM t_history_stt H
                LEFT JOIN t_order O ON H.id_stt = O.id_stt
                WHERE  H.tgl_update BETWEEN '$request->status_awal' AND '$request->status_akhir'
                    AND H.id_status > '1'
                    AND COALESCE(H.id_perush, O.id_perush_asal) = $idp

                ) Q1
                LEFT JOIN t_history_stt H2 ON Q1.id_history_sebelum = H2.id_history
                LEFT JOIN t_order O ON Q1.id_stt = O.id_stt
                LEFT JOIN (
                    SELECT DISTINCT koli.id_stt, koli.id_dm, dm.kode_dm, dm.tgl_berangkat, dm.tgl_sampai

                    FROM t_dm_koli koli
                    JOIN t_dm dm ON koli.id_dm = dm.id_dm 

                ) DM ON Q1.id_stt = DM.id_stt

                WHERE Q1.id_stt = $idstt
                    
                ORDER BY Q1.tgl_update");
            // querry raw disini
            $data["status_awal"] = $request->status_awal;
            $data["status_akhir"] = $request->status_akhir;
            $data["nm_sbg"] = $request->nm_sbg;
            return view('operasional::reportstt.rekapsttdetail', $data);
        } else {
            return redirect()->to("/outstandingstt")->withErrors(["error" => "Pastikan Anda Memilih Tanggal Report!"]);
        }
    }

    public function SttNoDM(Request $request)
    {
        $data["layanan"] = Layanan::select("id_layanan", "nm_layanan")->get();
        $data["cara"] = CaraBayar::getList();
        $data["status"] = StatusStt::getStatusKosong();
        $stt = SttModel::SttNoDM2();

        if (isset($request->filtertujuan)) {
            $stt = $stt->where('penerima_id_region', $request->filtertujuan);
        }

        if (isset($request->filterasal)) {
            $stt = $stt->where('pengirim_id_region', $request->filterasal);
        }

        if (isset($request->filterstt)) {
            $stt = $stt->where('id_stt', $request->filterstt);
        }

        if (isset($request->filterstatusstt)) {
            $stt = $stt->where('id_status', $request->filterstatusstt);
        }

        if (isset($request->filterlayanan)) {
            $stt = $stt->where('id_layanan', $request->filterlayanan);
        }

        if (isset($request->dr_tgl)) {
            $stt = $stt->where('tgl_masuk', '>=', $request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $stt = $stt->where('tgl_masuk', '<=', $request->sp_tgl);
        }

        if (isset($request->filtercarabayar)) {
            $stt = $stt->where('id_cr_byr_o', $request->filtercarabayar);
        }

        if (isset($request->f_awb)) {
            $stt = $stt->where('no_awb', $request->f_awb);
        }

        if (isset($request->f_pelanggan)) {
            $stt = $stt->where('id_plgn', $request->f_pelanggan);
        }

        if (isset($request->f_pelanggan)) {
            $stt = $stt->where('id_plgn', $request->f_pelanggan);
        }

        $data["data"] = $stt;

        return view('operasional::stt.stt-no-dm', $data);
    }

    public function CetakSttNoDM(Request $request)
    {
        $stt = SttModel::SttNoDM2();

        if (isset($request->filtertujuan)) {
            $stt = $stt->where('penerima_id_region', $request->filtertujuan);
        }

        if (isset($request->filterasal)) {
            $stt = $stt->where('pengirim_id_region', $request->filterasal);
        }

        if (isset($request->filterstt)) {
            $stt = $stt->where('id_stt', $request->filterstt);
        }

        if (isset($request->filterstatusstt)) {
            $stt = $stt->where('id_status', $request->filterstatusstt);
        }

        if (isset($request->filterlayanan)) {
            $stt = $stt->where('id_layanan', $request->filterlayanan);
        }

        if (isset($request->dr_tgl)) {
            $stt = $stt->where('tgl_masuk', '>=', $request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $stt = $stt->where('tgl_masuk', '<=', $request->sp_tgl);
        }

        if (isset($request->filtercarabayar)) {
            $stt = $stt->where('id_cr_byr_o', $request->filtercarabayar);
        }

        if (isset($request->f_awb)) {
            $stt = $stt->where('no_awb', $request->f_awb);
        }

        if (isset($request->f_pelanggan)) {
            $stt = $stt->where('id_plgn', $request->f_pelanggan);
        }

        if (isset($request->f_pelanggan)) {
            $stt = $stt->where('id_plgn', $request->f_pelanggan);
        }

        $data["data"] = $stt;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        $pdf = \PDF::loadview("operasional::stt.cetak-stt-no-dm", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
}
