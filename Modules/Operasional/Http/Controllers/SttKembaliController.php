<?php

namespace Modules\Operasional\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Wilayah;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\HistoryDokumenStt;
use Modules\Operasional\Entities\StatusDokumenStt;
use Modules\Operasional\Entities\SttKembali;
use Modules\Operasional\Entities\SttKembaliDetail;
use Modules\Operasional\Entities\SttModel;
use Session;

class SttKembaliController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-01-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-12-31');
        $stt = isset($request->id_stt) ? SttModel::findOrFail($request->id_stt) : null;

        $agendaStt = SttKembali::select('t_stt_kembali.*')->where('t_stt_kembali.id_perush', Session("perusahaan")["id_perush"])
            ->where('tgl', '>=', $dr_tgl)
            ->where('tgl', '<=', $sp_tgl);

        if (isset($request->id_stt)) {
            $agendaStt->leftjoin('t_stt_kembali_detail', 't_stt_kembali_detail.id_stt_kembali', '=', 't_stt_kembali.id_stt_kembali');
            $agendaStt->where('t_stt_kembali_detail.id_stt', $request->id_stt);
        }

        if (isset($request->agenda)) {
            $agendaStt->where('t_stt_kembali.id_stt_kembali', $request->agenda);
        }

        $data['data'] = $agendaStt->orderBy('tgl', 'DESC')->paginate(10);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'stt' => $stt,
        ];
        // dd($data);
        return view('operasional::sttkembali.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // dd(Auth::user()->nm_user);
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();
        $stt = SttModel::with("pelanggan", "asal", "tujuan", "layanan")
            ->join(DB::raw('
                    (
                    SELECT DISTINCT ON (id_stt) *, (current_date - tgl_update) as diff_date
                    FROM t_history_stt
                    ORDER BY id_stt, id_history DESC) AS history'
            ), 't_order.id_stt', '=', 'history.id_stt')
            ->whereRaw('t_order.id_stt NOT IN (SELECT t_stt_kembali_detail.id_stt FROM t_stt_kembali_detail)')
            ->where('id_perush_asal', Session("perusahaan")["id_perush"])
            ->orderBy('tgl_masuk')
            ->get();
        $data["detail"] = $stt;
        $data["status"] = [
            'STT TUNAI/LUNAS',
            'STT BAYAR TUJUAN',
            'STT DI EKSPEDISI TERUSAN',
            'STT DITAHAN DI STORE',
            'MASIH DI CABANG PENERIMA',
            'STT SIAP DIKIRIM',
            'STT SUDAH KEMBALI KE CAB PENGIRIM',
            'STT SUDAH DITERIMA CAB PENGIRIM',
            'STT JADI INVOICE',
        ];
        return view('operasional::sttkembali.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'id_stt' => 'required',
            'status' => 'bail|required',
            'cabang_tujuan' => 'bail|required',
            'keterangan' => 'bail|nullable|max:256',
        ]);

        DB::beginTransaction();
        $dok = new SttKembali();
        $dok->kode_stt_kembali = "AG/" . Session("perusahaan")["id_perush"] . "/" . date('Ym') . "/" . substr(crc32(uniqid()), -4);
        $dok->id_perush = Session("perusahaan")["id_perush"];
        $dok->id_perush_tujuan = $request->cabang_tujuan;
        $dok->tgl = $request->tgl;
        $dok->keterangan = $request->keterangan;
        $dok->id_user = Auth::user()->id_user;
        $dok->status = $request->status;
        $dok->save();
        $id_dok = DB::getPdo()->lastInsertId();

        $status_dok = StatusDokumenStt::where('nm_ord_stt_stat_dok', $request->status)->first();
        try {
            foreach ($request->id_stt as $value) {
                $detail = new SttKembaliDetail();
                $detail->id_perush = Session("perusahaan")["id_perush"];
                $detail->id_stt_kembali = $id_dok;
                $detail->id_user = Auth::user()->id_user;
                $detail->status = $status_dok->id_ord_stt_stat_dok;
                $detail->id_stt = $value;
                $detail->save();

                $history = new HistoryDokumenStt();
                $history->id_stt = $value;
                $history->id_status = 8;
                $history->kode_status = $status_dok->id_ord_stt_stat_dok;
                $history->id_user = Auth::user()->id_user;
                $history->keterangan = $status_dok->nm_ord_stt_stat_dok;
                $history->nm_status = $status_dok->nm_ord_stt_stat_dok;
                $history->id_wil = $request->id_kota;
                $history->tgl_update = $request->tgl;
                $history->nm_user = Auth::user()->nm_user;
                $history->created_at = date("Y-m-d H:i:s");
                $history->updated_at = date("Y-m-d H:i:s");

                $history->save();
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Disimpan' . $e->getMessage());
        }

        return redirect(url("sttkembali"))->with('success', 'Data Dokumen STT Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $dok = SttKembali::with("user")->findOrFail($id);
        $stt = SttKembaliDetail::where('id_stt_kembali', $id)->get();
        $temp = [];
        foreach ($stt as $value) {
            $temp[] = $value->id_stt;
        }
        $detail = SttModel::with("pelanggan", "asal", "tujuan", "layanan", "status", "tipekirim")->whereIn('t_order.id_stt', $temp)
            ->join('t_stt_kembali_detail', 't_stt_kembali_detail.id_stt', '=', 't_order.id_stt')->get();
        $data["data"] = $dok;
        $data["detail"] = $detail;

        return view('operasional::sttkembali.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = SttKembali::with("karyawan")->findOrFail($id);
        $detail = SttKembaliDetail::select("id_stt")->where('id_stt_kembali', $id)->get()->toArray();
        $detail = array_column($detail, 'id_stt');
        $data["detail_stt"] = SttModel::with("pelanggan", "asal", "tujuan", "layanan")->whereIn('t_order.id_stt', $detail)->get();
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();
        $stt = SttModel::with("pelanggan", "asal", "tujuan", "layanan")
            ->join(DB::raw('
                    (
                    SELECT DISTINCT ON (id_stt) *, (current_date - tgl_update) as diff_date
                    FROM t_history_stt
                    ORDER BY id_stt, id_history DESC) AS history'
            ), 't_order.id_stt', '=', 'history.id_stt')
            ->whereRaw('t_order.id_stt NOT IN (SELECT t_stt_kembali_detail.id_stt FROM t_stt_kembali_detail)')
            ->where('id_perush_asal', Session("perusahaan")["id_perush"])
            ->orderBy('tgl_masuk')
            ->get();
        $data["detail"] = $stt;
        $data["status"] = [
            'STT TUNAI/LUNAS',
            'STT BAYAR TUJUAN',
            'STT DI EKSPEDISI TERUSAN',
            'STT DITAHAN DI STORE',
            'MASIH DI CABANG PENERIMA',
            'STT SIAP DIKIRIM',
            'STT SUDAH KEMBALI KE CAB PENGIRIM',
            'STT SUDAH DITERIMA CAB PENGIRIM',
            'STT JADI INVOICE',
        ];
        return view('operasional::sttkembali.index', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // 'id_stt' => 'required',
            'status' => 'bail|required',
            'cabang_tujuan' => 'bail|required',
            'keterangan' => 'bail|nullable|max:256',
        ]);

        DB::beginTransaction();

        $status_dok = StatusDokumenStt::where('nm_ord_stt_stat_dok', $request->status)->first();
        try {
            $dok = SttKembali::findOrFail($id);
            $dok->id_perush = Session("perusahaan")["id_perush"];
            $dok->id_perush_tujuan = $request->cabang_tujuan;
            $dok->tgl = $request->tgl;
            $dok->keterangan = $request->keterangan;
            $dok->id_user = Auth::user()->id_user;
            $dok->status = $request->status;
            $dok->save();

            if (!empty($request->id_stt) && count($request->id_stt) > 0) {
                foreach ($request->id_stt as $value) {
                    $detail = new SttKembaliDetail();
                    $detail->id_perush = Session("perusahaan")["id_perush"];
                    $detail->id_stt_kembali = $id;
                    $detail->id_user = Auth::user()->id_user;
                    // $detail->status = "1";
                    $detail->id_stt = $value;
                    $detail->save();
                }

            }

            $dokumen_detail = SttKembaliDetail::where('id_stt_kembali', $id)->get();

            foreach ($dokumen_detail as $key => $value) {
                $history = new HistoryDokumenStt();
                $history->id_stt = $value->id_stt;
                $history->id_status = 8;
                $history->kode_status = $status_dok->id_ord_stt_stat_dok;
                $history->id_user = Auth::user()->id_user;
                $history->keterangan = $status_dok->nm_ord_stt_stat_dok;
                $history->nm_status = $status_dok->nm_ord_stt_stat_dok;
                $history->id_wil = $request->id_kota;
                $history->tgl_update = $request->tgl;
                $history->nm_user = Auth::user()->nm_user;
                $history->created_at = date("Y-m-d H:i:s");
                $history->updated_at = date("Y-m-d H:i:s");

                $history->save();
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Dokumen STT Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */

    public function destroy($id)
    {
        dd($id);
        $dok = SttKembali::findOrFail($id);
        if ($dok->status != "1") {
            return redirect()->back()->with('error', 'Data Dokumen Sudah Di Proses, Tidak Bisa Dihapus');
        }

        DB::beginTransaction();
        try {
            // update stt kembali
            $data = SttKembaliDetail::select("id_stt")->where("id_kembali", $id)->get();
            $a_stt = [];
            $a_stt["status_kembali"] = "0";
            foreach ($data as $key => $value) {
                SttModel::where("id_stt", $value->id_stt)->update($a_stt);
            }

            // delete detail stt
            SttKembaliDetail::where("id_kembali", $id)->delete();

            // delete dokumen stt kembali
            $dok->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Disimpan');
        }

        return redirect()->back()->with('success', 'Data Dokumen STT Disimpan');
    }

    public function deleteDokumen($id)
    {
        $dok = SttKembali::findOrFail($id);
        if ($dok->status != "1") {
            return redirect()->back()->with('error', 'Data Dokumen Sudah Di Proses, Tidak Bisa Dihapus');
        }

        DB::beginTransaction();
        try {
            SttKembaliDetail::where("id_stt_kembali", $id)->delete();
            $dok->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Dihapus');
        }

        return redirect()->back()->with('success', 'Data Dokumen STT Dihapus');

    }

    public function deletestt($id)
    {
        DB::beginTransaction();
        try {

            // find detail
            $stt = SttKembaliDetail::where('id_stt', $id);
            $stt->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Dihapus' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Dokumen STT Dihapus');
    }

    public function sendstt($id)
    {
        // find detail
        $dok = SttKembali::findOrFail($id);

        if ($dok->status != "1") {
            return redirect()->back()->with('error', 'Data Dokumen STT Sudah Dikirim');
        }

        DB::beginTransaction();
        try {

            $dok->status = "2";
            $dok->tgl_kirim = date("Y-m-d");

            // update detail stt
            $stt = SttKembaliDetail::select("id_stt")->where("id_kembali", $id)->get();
            foreach ($stt as $key => $value) {
                // update stt
                $a_stt = [];
                $a_stt["status_kembali"] = "2";
                SttModel::where("id_stt", $value->id_stt)->update($a_stt);
            }

            $dok->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal dikirim' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Dokumen STT dikirim');
    }

    public function cetak($id)
    {
        $dok = SttKembali::with("user")->findOrFail($id);
        $stt = SttKembaliDetail::where('id_stt_kembali', $id)->get();
        $temp = [];
        foreach ($stt as $value) {
            $temp[] = $value->id_stt;
        }
        $detail = SttModel::with("pelanggan", "asal", "tujuan", "layanan", "status", "tipekirim")->whereIn('id_stt', $temp)->get();
        $data["data"] = $dok;
        $data["detail"] = $detail;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        $pdf = \PDF::loadview("operasional::sttkembali.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function getAgenda(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term = $request->term;
        $data = SttKembali::where("kode_stt_kembali", 'ILIKE', '%' . $term . '%')->where("id_perush", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt_kembali, 'value' => strtoupper($value->kode_stt_kembali)];
        }

        return response()->json($results);
    }

    public function rekapitulasi_stt_kembali(Request $request)
    {
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $id_perush = Session("perusahaan")["id_perush"];

        $rekap = SttKembali::rekapitulasi_stt_kembali($id_perush, $dr_tgl, $sp_tgl);
        $datanya = [];
        foreach ($rekap as $key => $value) {
            $datanya[$value->provinsi][] = $value;
        }

        $data['data'] = $datanya;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('operasional::sttkembali.rekapitulasi', $data);
    }

    public function detail_rekapitulasi_stt_kembali(Request $request)
    {
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $id_perush = Session("perusahaan")["id_perush"];

        if (isset($request->id_tujuan)) {
            $id_tujuan = $request->id_tujuan;
        } else {
            return redirect()->back()->with('error', 'Wilayah Kosong !' . $e->getMessage());
        }

        $rekap = SttKembali::detail_rekapitulasi_stt_kembali($id_perush, $dr_tgl, $sp_tgl, $id_tujuan);

        $data['data'] = $rekap;
        $data['wilayah'] = Wilayah::findOrFail($request->id_tujuan);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('operasional::sttkembali.rekapitulasi-detail', $data);
    }

    public function rekapitulasi_stt_kembali_by_dokumen(Request $request)
    {
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $id_perush = Session("perusahaan")["id_perush"];

        $rekapStatusDokumen = SttKembali::rekapitulasi_stt_kembali_by_dokumen($id_perush, $dr_tgl, $sp_tgl);
        $rekapStatusBarang = SttKembali::rekapitulasi_stt_kembali_by_status_barang($id_perush, $dr_tgl, $sp_tgl);
        // dd($rekap);
        $data['rekapStatusDokumen'] = $rekapStatusDokumen;
        $data['rekapStatusBarang'] = $rekapStatusBarang;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('operasional::sttkembali.rekapitulasi-by-dokumen', $data);
    }

    public function detail_rekapitulasi_stt_kembali_by_dokumen(Request $request)
    {
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $id_perush = Session("perusahaan")["id_perush"];
        $kode_status = isset($request->kode_status) ? $request->kode_status : 10;

        $rekapStatusDokumen = SttKembali::detail_rekapitulasi_stt_kembali_by_status_barang($id_perush, $dr_tgl, $sp_tgl, $kode_status);
        // dd($rekapStatusDokumen);
        $data['rekapStatusDokumen'] = $rekapStatusDokumen;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('operasional::sttkembali.detail-rekapitulasi-by-dokumen', $data);
    }

    public function update_dokumen(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $foto = $request->is_foto;
            $fisik = $request->is_fisik;
            if (isset($foto)) {
                $detail = SttKembaliDetail::whereIn('id_stt', $foto)->update(['is_foto' => 1]);
            }
            if (isset($fisik)) {
                $detail = SttKembaliDetail::whereIn('id_stt', $fisik)->update(['is_fisik' => 1]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Disimpan' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Dokumen STT Berhasil Disimpan');
    }
}
