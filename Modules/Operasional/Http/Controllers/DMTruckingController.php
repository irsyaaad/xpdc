<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Http\Requests\DaftarMuatRequest;
use Auth;
use DB;
use Exception;
use App\Models\Layanan;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\DetailProyeksi;
use Modules\Operasional\Entities\ProyeksiDm;
use App\Models\Proyeksi;
use Modules\Operasional\Http\Requests\DMProyeksiRequest;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\StatusStt;
use Modules\Keuangan\Entities\BiayaHpp;
use App\Models\Vendor;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\Notifikasi;
use App\Models\Wilayah;

class DMTruckingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;

        if (isset($request->page)) {
            $page = $request->page;
        }
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get()->first();
        $id_dm = $request->id_dm;
        $id_perush_tj = $request->id_perush_tj;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $tglberangkat = $request->tglberangkat;
        $tgltiba = $request->tglsampai;
        $id_status = $request->id_status;
        $is_kota = 0;
        $id_stt = isset($request->filterstt) ? $request->filterstt : null;

        $data["layanan"] = $layanan;
        $data["data"] = DaftarMuat::getFilter($page, $perpage, $id_perush, $id_dm, 0, null, $id_perush_tj, $id_sopir, $id_armada, $tglberangkat, $tgltiba, $id_status, $is_kota, null, $id_stt);
        $data["status"] = StatusDM::getList(1);
        $data["perusahaan"] = Perusahaan::getDataExept();
        $data["sopir"] = Sopir::getData($id_perush);
        $data["armada"] = Armada::getData($id_perush);
        $data["filter"] = [
            'id_stt' => isset($id_stt) ? SttModel::findOrFail($id_stt) : null,
        ];

        return view('operasional::daftarmuat.dmtrucking', $data);
    }

    public function getdm(Request $request)
    {
        $term = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get()->first();
        $data = DaftarMuat::select("id_dm", "kode_dm")->where("is_vendor", false)
            ->where(DB::raw("lower(kode_dm)"), "LIKE", "%" . strtolower($term) . "%")->where("id_layanan", $layanan->id_layanan);

        if (!get_admin()) {
            $data = $data->where("id_perush_dr", $id_perush);
        }

        $data = $data->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    public function getdmvendor(Request $request)
    {
        $term = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = DaftarMuat::select("id_dm", "kode_dm")->where("is_vendor", true)
            ->where(DB::raw("lower(kode_dm)"), "LIKE", "%" . strtolower($term) . "%")->where("id_perush_dr", $id_perush);

        $data = $data->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    public function get_all_dm(Request $request)
    {
        $term = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = DaftarMuat::select("id_dm", "kode_dm")->where(DB::raw("lower(kode_dm)"), "LIKE", "%" . strtolower($term) . "%")->where("id_perush_dr", $id_perush);

        $data = $data->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    public function getdmtiba(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term = $request->term;
        $data = DaftarMuat::select("id_dm", "kode_dm")->where("kode_dm", 'LIKE', '%' . $term . '%')->where("id_perush_tj", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    public function create()
    {
        $data["data"] = [];
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::all();
        if ($layanan == null) {
            return redirect()->back()->with('error', 'Layanan Belum ada');
        }

        $data["layanan"] = $layanan;
        $data["perusahaan"] = Perusahaan::where("id_perush", $id_perush)->get()->first();
        $data["perush_tj"] = Perusahaan::getDataExept();
        $data["kapal"] = Kapal::select("id_kapal", "nm_kapal")->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();
        $data["sopir"] = Sopir::getSopirInActive($id_perush);

        return view('operasional::daftarmuat.dmtrucking', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(DaftarMuatRequest $request)
    {
        $id_dm = null;
        try {

            DB::beginTransaction();

            $perush = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
            $gen = $this->generate($request->id_layanan);
            $daftar = new DaftarMuat();
            $daftar->kode_dm = $gen["kode_dm"];
            $daftar->id_perush_dr = $perush->id_perush;
            $daftar->id_layanan = $request->id_layanan;
            $daftar->id_perush_tj = $request->id_perush_tj;
            $daftar->id_kapal = $request->id_kapal;
            $daftar->id_sopir = $request->id_sopir;
            $daftar->id_armada = $request->id_armada;
            $daftar->tgl_berangkat = $request->tgl_berangkat;
            $daftar->tgl_sampai = $request->tgl_sampai;
            $daftar->nm_dari = $request->nm_dari;
            $daftar->nm_tuju = $request->nm_tuju;
            $daftar->nm_pj_dr = $request->nm_pj_dr;
            $daftar->nm_pj_tuju = $request->nm_pj_tuju;
            $daftar->id_user = Auth::user()->id_user;
            $daftar->id_status = 1;
            $daftar->is_vendor = 0;
            $daftar->keterangan = $request->keterangan;
            $perusahaan = Perusahaan::findOrFail($request->id_perush_tj);
            $daftar->id_wil_asal = $perush->id_region;
            $daftar->id_wil_tujuan = $perusahaan->id_region;
            $daftar->save();
            $id_dm = $daftar->id_dm;

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Daftar Muat Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect() . "/" . $id_dm . "/show")->with('success', 'Data Daftar Muat Disimpan');
    }

    public function generate($id_layanan)
    {
        $id_perush = Session("perusahaan")["id_perush"];

        $time = substr(time(), 3, 10);
        $data = [];
        $data["kode_dm"] = "DMT" . $id_perush . $id_layanan . $time;

        return $data;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function detailstt($id, $id_stt)
    {
        $data["data"] = SttModel::findOrFail($id_stt);
        $data["koli"] = DmKoli::where("id_dm", $id)->where("id_stt", $id_stt)->get();

        return view('operasional::daftarmuat.showkoli', $data);
    }

    public function show($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;
        $data["status"] = StatusDM::getList();
        $data["detail"] = SttModel::getSttDM($id);
        $data["sttstat"] = StatusStt::getList();
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $data["bumum"] = $bumum;
        $data["stt"] = SttDm::getStt($id);
        $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);

        return view('operasional::dmtrucking.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);

        if ($dm->id_status != "1") {
            return redirect("stt")->with('error', 'Access Terbatas');
        }

        $data["data"] = $dm;
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "trucking")->get();
        if ($layanan == null) {
            return redirect()->back()->with('error', 'Layanan Belum ada');
        }

        $data["layanan"] = $layanan;
        $data["perusahaan"] = Perusahaan::where("id_perush", $id_perush)->get()->first();
        $data["perush_tj"] = Perusahaan::getDataExept();
        $data["kapal"] = Kapal::select("id_kapal", "nm_kapal")->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();
        $data["sopir"] = Sopir::getSopirInActive($id_perush);

        return view('operasional::daftarmuat.dmtrucking', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(DaftarMuatRequest $request, $id)
    {
        try {

            DB::beginTransaction();
            $daftar = DaftarMuat::findOrFail($id);
            $daftar->id_layanan = $request->id_layanan;
            $daftar->id_perush_tj = $request->id_perush_tj;
            $daftar->id_kapal = $request->id_kapal;
            $daftar->id_sopir = $request->id_sopir;
            $daftar->id_armada = $request->id_armada;
            $daftar->tgl_berangkat = $request->tgl_berangkat;
            $daftar->tgl_sampai = $request->tgl_sampai;
            $daftar->nm_dari = $request->nm_dari;
            $daftar->nm_tuju = $request->nm_tuju;
            $daftar->nm_pj_dr = $request->nm_pj_dr;
            $daftar->nm_pj_tuju = $request->nm_pj_tuju;
            $daftar->id_user = Auth::user()->id_user;
            $daftar->keterangan = $request->keterangan;

            //dd($daftar);
            $daftar->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Daftar Muat Gagal Disimpan');
        }

        return redirect(route_redirect() . "/" . $daftar->id_dm . "/show")->with('success', 'Data Daftar Muat Disimpan');
    }

    public function getstt(Request $request)
    {
        $data = SttModel::where("pengirim_id_region", $request->id_asal)
            ->where("penerima_id_region", $request->id_tujuan)
            ->where("id_layanan", $request->id_layanan)->get();

        $a_data = [];
        foreach ($data as $key => $value) {
            $a_data[$key] = '<tr><td>' . strtoupper($value->id_stt) . '</td><td>' . date_format(date_create($value->tgl_masuk), "d-m-Y") . '</td><td>' . strtoupper($value->pengirim_nm) . '</td><td class="text-center"><button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button></td></tr>';
        }

        return response()->json($a_data);
    }

    public function detail($id = null, Request $request)
    {
        $dm = DaftarMuat::findOrFail($id);
        if ($dm->id_status != "1") {
            return redirect()->back()->with('error', 'Access Terbatas');
        }

        $data["stt"] = SttModel::getSttKoli($id, $dm->id_perush_dr, $dm->id_perush_tj, $dm->id_layanan, 1);

        if (isset($request->id_stt)) {
            $data["data"] = SttModel::getIdSttKoli($request->id_stt);
            $data["koli"] = OpOrderKoli::getKoliStt($request->id_stt, 1);

            if (count($data["koli"]) < 1) {
                return redirect()->back()->with('error', 'Data Stt Tidak Ditemukan');
            }
        }

        return view('operasional::daftarmuat.detaildm', $data);
    }

    public function import($id = null, Request $request)
    {

        $dm = DaftarMuat::findOrFail($id);
        if ($dm->id_status != "1") {
            return redirect()->back()->with('error', 'Access Terbatas');
        }

        $data["stt"] = SttModel::getSttKoli($id, $dm->id_perush_dr, $dm->id_perush_tj, $dm->id_layanan);
        $data["perusahaan"] = Perusahaan::where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();

        if (isset($request->id_stt)) {
            $data["data"] = SttModel::getIdSttKoli($request->id_stt);
            $data["koli"] = OpOrderKoli::getSttKoliVendor($request->id_stt, "1");

            if (count($data["koli"]) < 1) {
                return redirect()->back()->with('error', 'Data Stt Tidak Ditemukan');
            }
        }

        return view('operasional::daftarmuat.detail-import', $data);
    }

    public function savekoli($id, Request $request)
    {
        $dm = DaftarMuat::findOrFail($id);
        $id_perush = Session("perusahaan")["id_perush"];
        if ($request->c_koli == null) {
            return redirect()->back()->with('error', 'Koli tidak di pilih');
        }

        // after cek of value loop
        try {
            DB::beginTransaction();

            // save koli
            $i = 0;
            foreach ($request->c_koli as $key => $value) {
                $koli[] = [
                    "id_koli" => $value,
                    "id_dm" => $id,
                    "id_stt" => $request->kode_stt,
                    "id_user" => Auth::user()->id_user
                ];

                $i++;
                $data_koli["status"] = "2";

                OpOrderKoli::where("id_koli", $value)->update($data_koli);
            }
            $dmkoli = DmKoli::insert($koli);

            $stt = SttDm::where('id_stt', $request->kode_stt)
                ->where('id_dm', $id)
                ->first();

            if ($stt != null) {
                $n_koli = ($stt->n_koli + $i);
                SttDm::where("id_stt", $request->kode_stt)->where("id_dm", $id)->update(["n_koli" => $n_koli]);
            } else {
                $stt = new SttDm();
                $stt->id_stt = $request->kode_stt;
                $stt->id_dm = $id;
                $stt->n_koli = $i;
                $stt->save();
            }

            // update total pendapatan
            $stt = SttModel::findOrFail($request->kode_stt);

            // update dm
            $dm = DaftarMuat::findOrFail($id);
            $total = (float) $dm->c_total + ($stt->n_tarif_koli * $i);
            //dd($total);
            $dm->c_total = $total;

            if ($dm->is_vendor == true) {
                $vendor = Vendor::findOrFail($dm->id_ven);
                $proyeksi_dm = new ProyeksiDm();
                $proyeksi_dm->id_stt = $stt->id_stt;
                $proyeksi_dm->kode_stt = $stt->kode_stt;
                $proyeksi_dm->nominal = 0;

                if ($dm->cara == 1) {
                    $proyeksi_dm->nominal = $stt->n_berat * $dm->n_harga;
                } elseif ($dm->cara == 2) {
                    $proyeksi_dm->nominal = $stt->n_volume * $dm->n_harga;
                } elseif ($dm->cara == 3) {
                    $proyeksi_dm->nominal = $stt->n_kubik * $dm->n_harga;
                } elseif ($dm->cara == 4) {
                    $stt_dm = SttDm::where("id_dm", $id)->count("id_stt");
                    $proyeksi_dm->nominal = $dm->n_harga / $stt_dm;
                }

                $proyeksi_dm->id_dm = $id;
                $proyeksi_dm->kode_dm = $dm->kode_dm;
                $proyeksi_dm->keterangan = "Biaya Vendor STT " . $stt->kode_stt;
                $proyeksi_dm->id_user = Auth::user()->id_user;
                $proyeksi_dm->id_biaya_grup = $vendor->id_biaya_grup;
                $proyeksi_dm->tgl_posting = date("Y-m-d");
                $proyeksi_dm->ac4_debit = $vendor->ac4_debet;
                $proyeksi_dm->ac4_kredit = $vendor->ac4_kredit;
                $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
                $proyeksi_dm->id_jenis = 0;
                $proyeksi_dm->id_ven = $dm->id_ven;
                $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
                $proyeksi_dm->save();
            }
            $sum = ProyeksiDm::where("id_dm", $id)->sum("nominal");
            $dm->c_pro = $sum;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Muatan Koli di gagal di tambahkan' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Muatan Koli di berhasil di tambahkan');
    }

    public function deletestt($id, Request $request)
    {
        try {

            DB::beginTransaction();

            // update total pendapatan
            $dm_koli = SttDm::where("id_stt", $id)->where("id_dm", $request->kode_dm)->get()->first();
            $stt = SttModel::findOrFail($id);

            // update dm
            $dm = DaftarMuat::findOrFail($request->kode_dm);
            if ($dm->id_perush_dr != Session("perusahaan")["id_perush"]) {
                return redirect()->back()->with('error', 'Anda Tidak Memilik Akses');
            }

            $cek_bayar = BiayaHpp::where("id_stt", $id)
                ->where("id_dm", $request->kode_dm)
                ->get()->first();

            if ($cek_bayar != null) {
                return redirect()->back()->with('error', 'Stt Tidak Dapat Dihapus, karena biaya sudah di bayar');
            } else {
                ProyeksiDm::where("id_stt", $id)
                    ->where("id_dm", $request->kode_dm)
                    ->delete();
            }

            $koli = 0;
            if ($dm_koli != null) {
                $koli = $dm_koli->n_koli;
            }

            $total = (float) $dm->c_total - ($koli * $stt->n_tarif_koli);
            $dm->c_total = $total;
            $dm->save();

            // delete dm koli
            DmKoli::where("id_stt", $id)->delete();

            // delete stt dm
            SttDm::where("id_stt", $id)->where("id_dm", $request->kode_dm)->delete();
            $data_koli["status"] = "1";
            OpOrderKoli::where("id_stt", $id)->update($data_koli);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data masih digunakan di table lain' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Muatan Di Hapus');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $cek = SttDm::getStt($id);
            $cek2 = ProyeksiDm::getProyeksi($id);
            if ($cek != null or $cek2 != null) {
                return redirect()->back()->with('error', 'DM sudah ada stt dan proyeksi biaya tidak bisa di hapus');
            } else {
                DaftarMuat::where("id_dm", $id)->delete();
                SttDm::where("id_dm", $id)->delete();
                DmKoli::where("id_dm", $id)->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Daftar muat gagal di hapus' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Daftar muat berhasil di hapus');
    }

    public function deletekoli($id, Request $request)
    {
        try {

            DB::beginTransaction();
            // select dm koli
            $dm_koli = DmKoli::findOrFail($id);

            // daftar muat
            $dm = DaftarMuat::findOrFail($dm_koli->id_dm);

            // update koli
            $koli = OpOrderKoli::findOrFail($request->id_koli);
            $koli->status = "1";

            $koli->save();

            // update stt dm
            $cek = SttDm::where("id_stt", $dm_koli->id_stt)->where("id_dm", $dm_koli->id_dm)->get()->first();
            $cek_koli = DmKoli::where("id_stt", $dm_koli->id_stt)->count();
            $cek_koli = $cek_koli - 1;

            // cek if dm koli count same as n_koli
            if ($cek->n_koli == $cek_koli) {

                SttDm::where("id_stt", $dm_koli->id_stt)->where("id_dm", $dm_koli->id_dm)->delete();
            } else {

                $n_koli["n_koli"] = $cek_koli;
                SttDm::where("id_stt", $dm_koli->id_stt)->where("id_dm", $dm_koli->id_dm)->update($n_koli);
            }

            // delete dm koli
            $dm_koli->delete();

            // update total pendapatan
            $stt = SttModel::findOrFail($dm_koli->id_stt);
            // update dm
            $total = ($stt->n_tarif_koli * $cek_koli);
            $dm->c_total = $total;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal di hapus, Data masih digunakan di table lain' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Muatan Di Hapus');
    }

    public function proyeksi($id)
    {
        abort(404);
        $id_perush = Session("perusahaan")["id_perush"];
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $detail = ProyeksiDm::getProyeksi($id);
        $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);
        $data["data"] = $dm;
        $data["detail"] = $detail;
        $data["stt"] = SttDm::getStt($id);

        return view("operasional::daftarmuat.proyeksi", $data);
    }

    public function generateproyeksi($id)
    {
        $dm = DaftarMuat::findOrFail($id);
        $id_perush = Session("perusahaan")["id_perush"];
        $proyeksi = Proyeksi::where("id_perush", $dm->id_perush_dr)->where("id_perush_tj", $dm->id_perush_tj)->where("id_layanan", $dm->id_layanan)->get()->first();
        $detail = [];

        if ($proyeksi != null) {
            $detail = DetailProyeksi::with("grup")->where("id_proyeksi", $proyeksi->id_proyeksi)->get();
        }

        $cek = BiayaHpp::where("id_dm", $id)->get()->first();
        if ($cek != null) {
            return redirect()->back()->with('error', 'Biaya hpp sudah ada yang dibayar tidak bisa di generate');
        }

        $total = ProyeksiDm::with("dm", "proyeksi", "group")->where("id_dm", $id)->get();

        // jika sudah ada proyeksi dibuat
        if (count($total) == 0 and $detail != null) {
            try {

                DB::beginTransaction();
                // clear all biaya
                ProyeksiDm::where("id_dm", $id)->delete();

                // save all biaya
                $total = 0;
                foreach ($detail as $key => $value) {
                    $proyeksi_dm = new ProyeksiDm();
                    $proyeksi_dm->id_dm = $id;
                    $proyeksi_dm->nominal = $value->nominal;
                    $proyeksi_dm->tgl_posting = date("Y-m-d");
                    $total += $value->nominal;
                    $proyeksi_dm->id_user = Auth::user()->id_user;
                    $proyeksi_dm->id_biaya_grup = $value->id_biaya_grup;
                    $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_perush", $id_perush)->where("id_biaya_grup", $value->id_biaya_grup)->get()->first();

                    if ($ac == null) {
                        return redirect()->back()->with('error', 'Setting Biaya Perusahaan Belum Dibuat');
                    }

                    $proyeksi_dm->id_jenis = 1;
                    $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
                    $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
                    $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
                    $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
                    $proyeksi_dm->id_ven = $dm->id_ven;
                    $proyeksi_dm->save();
                }

                $dm->c_pro = $total;
                $dm->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Berhasil generate proyeksi');
    }

    public function saveproyeksi(DMProyeksiRequest $request, $id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        try {

            DB::beginTransaction();
            $proyeksi_dm = new ProyeksiDm();
            $proyeksi_dm->id_stt = null;
            $proyeksi_dm->kode_stt = null;

            if (isset($request->id_stt) and $request->id_stt != null) {
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $proyeksi_dm->id_stt = $request->id_stt;
                $proyeksi_dm->kode_stt = $stt->kode_stt;
            }

            $dm = DaftarMuat::findOrFail($id);
            $proyeksi_dm->id_dm = $id;
            $proyeksi_dm->kode_dm = $dm->kode_dm;
            $proyeksi_dm->nominal = $request->nominal != null ? $request->nominal : 0;
            $proyeksi_dm->keterangan = $request->keterangan;
            $proyeksi_dm->id_user = Auth::user()->id_user;
            $proyeksi_dm->id_biaya_grup = $request->id_biaya_grup;
            $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_perush", $id_perush)->where("id_biaya_grup", $request->id_biaya_grup)->get()->first();
            if ($ac == null) {
                return redirect()->back()->with('error', 'Setting Biaya Perusahaan Belum Dibuat');
            }
            $proyeksi_dm->id_jenis = $request->id_jenis;
            $proyeksi_dm->tgl_posting = $request->tgl_posting;
            $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
            $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
            $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
            $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
            $proyeksi_dm->id_ven = $dm->id_ven;
            $proyeksi_dm->save();

            $sum = ProyeksiDm::where("id_dm", $id)->sum("nominal");
            $dm->c_pro = $sum;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Proyeksi Biaya Sukses Dibuat');
    }

    public function showproyeksi($id)
    {

        $data = ProyeksiDm::with("group")->findOrFail($id);

        $a_data = [];
        $a_data["id_pro_bi"] = $id;
        $a_data["nominal"] = $data->nominal;
        $a_data["id_biaya_grup"] = $data->id_biaya_grup;
        $a_data["nm_biaya_grup"] = $data->group->nm_biaya_grup;
        $a_data["id_ven"] = $data->id_ven;
        $a_data["keterangan"] = $data->keterangan;
        $a_data["tgl_posting"] = $data->tgl_posting;
        $a_data["id_stt"] = $data->id_stt;

        return response()->json($a_data);
    }

    public function counting($id)
    {
        try {

            DB::beginTransaction();
            $dm = DaftarMuat::findOrFail($id);
            if ($dm->is_vendor == true) {
                $pro = ProyeksiDm::where("id_dm", $id)->where("id_jenis", 0)->get();
                foreach ($pro as $key => $value) {
                    $proyeksi_dm = ProyeksiDm::findOrFail($value->id_pro_bi);
                    $stt = SttModel::findOrfail($value->id_stt);
                    $proyeksi_dm->id_stt = $value->id_stt;
                    $proyeksi_dm->nominal = 0;

                    if ($dm->cara == 1) {
                        $proyeksi_dm->nominal = $stt->n_berat * $dm->n_harga;
                    } elseif ($dm->cara == 2) {
                        $proyeksi_dm->nominal = $stt->n_volume * $dm->n_harga;
                    } elseif ($dm->cara == 3) {
                        $proyeksi_dm->nominal = $stt->n_kubik * $dm->n_harga;
                    } elseif ($dm->cara == 4) {
                        $stt_dm = SttDm::where("id_dm", $id)->count("id_stt");
                        $proyeksi_dm->nominal = $dm->n_harga / $stt_dm;
                    }

                    $proyeksi_dm->save();
                }
            }
            $proyeksi = ProyeksiDm::where("id_dm", $id)->sum("nominal");
            $bayar = ProyeksiDm::where("id_dm", $id)->sum("nominal");

            if ($dm->is_vendor == true) {
                $total = SttDm::getTotalPendapatan($id);
            } else {
                $total = SttDM::hitungOmset($id)->first();
                $total = $total->total;
            }
            $dm->c_pro = $proyeksi;
            $dm->n_bayar = BiayaHpp::where("id_dm", $id)->sum("n_bayar");
            $dm->c_total = $total;
            $dm->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menghitung Ulang silahkan hubungi administrator' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Sukses Menghitung Ulang Estimasi Pendapatan');
    }

    public function updateproyeksi(Request $request, $id)
    {

        $id_perush = Session("perusahaan")["id_perush"];
        try {
            DB::beginTransaction();

            $proyeksi_dm = ProyeksiDm::findOrFail($id);

            $proyeksi_dm->id_stt = null;
            $proyeksi_dm->kode_stt = null;

            if (isset($request->id_stt) and $request->id_stt != null) {
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $proyeksi_dm->id_stt = $request->id_stt;
                $proyeksi_dm->kode_stt = $stt->kode_stt;
            }

            $dm = DaftarMuat::findOrFail($proyeksi_dm->id_dm);

            $proyeksi_dm->nominal = $request->nominal != null ? $request->nominal : 0;
            $proyeksi_dm->id_user = Auth::user()->id_user;
            $proyeksi_dm->id_biaya_grup = $request->id_biaya_grup;
            $ac = SettingBiayaPerush::select("id_ac_biaya", "id_ac_hutang")->where("id_perush", $id_perush)->where("id_biaya_grup", $request->id_biaya_grup)->get()->first();

            if ($ac == null) {
                return redirect()->back()->with('error', 'Setting Biaya Perusahaan Belum Dibuat');
            }
            $proyeksi_dm->tgl_posting = $request->tgl_posting;
            $proyeksi_dm->ac4_debit = $ac->id_ac_hutang;
            $proyeksi_dm->ac4_kredit = $ac->id_ac_biaya;
            $proyeksi_dm->id_perush_dr = $dm->id_perush_dr;
            $proyeksi_dm->id_perush_tj = $dm->id_perush_tj;
            $proyeksi_dm->kode_dm = $dm->kode_dm;
            $proyeksi_dm->id_ven = $dm->id_ven;
            $proyeksi_dm->keterangan = $request->keterangan;
            $proyeksi_dm->save();

            $total = ProyeksiDm::where("id_dm", $proyeksi_dm->id_dm)->sum("nominal");

            $dm->c_pro = $total;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat proyeksi silahkan hubungi administrator' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Proyeksi Biaya Sukses Diubah');
    }

    public function deleteproyeksi($id)
    {
        try {

            DB::beginTransaction();

            $proyeksi_dm = ProyeksiDm::findOrFail($id);

            $dm = DaftarMuat::findOrFail($proyeksi_dm->id_dm);
            $dm->c_pro -= (float) $proyeksi_dm->nominal;
            $dm->save();

            $proyeksi_dm->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus proyeksi silahkan hubungi administrator');
        }

        return redirect()->back()->with('success', 'Proyeksi Biaya Sukses dihapus');
    }

    public function cetakDM($id)
    {
        $cara_bayar = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"] = DaftarMuat::with("kapal", "armada", "sopir", "perush_tujuan")->where("id_dm", $id)->get()->first();
        $data["stt"] = SttModel::getDM($id)->get();
        $stt = SttModel::getDM($id)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $data["id"] = $id;
        $temp = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"] = $temp;
        $data["carabayar"] = $cara_bayar;
        return view('operasional::daftarmuat.cetakdm', $data);
    }

    public function cetakDMNoTarif($id)
    {
        $cara_bayar = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"] = DaftarMuat::with("kapal", "armada", "sopir", "perush_tujuan")->where("id_dm", $id)->get()->first();
        $data["stt"] = SttModel::getDM($id)->get();
        $stt = SttModel::getDM($id)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $data["id"] = $id;
        $temp = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"] = $temp;
        $data["carabayar"] = $cara_bayar;

        return view('operasional::daftarmuat.cetakdm', $data);
    }

    public function cetakDMBarcode($id)
    {
        $cara_bayar = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"] = DaftarMuat::with("kapal", "armada", "sopir", "perush_tujuan")->where("id_dm", $id)->get()->first();
        $data["stt"] = SttModel::getDM($id)->get();
        $stt = SttModel::getDM($id)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        $data["id"] = $id;
        $temp = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"] = $temp;
        $data["carabayar"] = $cara_bayar;

        return view('operasional::daftarmuat.cetakdmbarcode', $data);
    }

    public function updatestatus($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;
        $data["detail"] = SttModel::getUpdateStt($id);
        $data["status"] = StatusStt::getStatusKosong();

        return view('operasional::daftarmuat.updatestatus', $data);
    }

    public function saveupdatestatus(Request $request)
    {
        $request->validate([
            'id_status' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_ord_stt_stat,kode_status',
            'id_kota' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'id_stt.*' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_order,id_stt',
        ]);

        $ids = $request->id_stt;
        $status = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat", "kode_status")
            ->where("kode_status", $request->id_status)->firstOrfail();

        $kota = Wilayah::select("nama_wil")->where("id_wil", $request->id_kota)->get()->first();
        $nama_kota = $kota->nama_wil;
        $keterangan = $status->nm_ord_stt_stat . " ( " . $nama_kota . " )";
        if (isset($request->keterangan)) {
            $keterangan .= ", ".$request->keterangan;
        }
        $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        if (isset($request->id_stt) and count($ids) != 0) {

            if ($request->id_status == null) {
                return redirect()->back()->with('error', 'Tidak ada Status yang dipilih');
            }

            try {
                DB::beginTransaction();
                $history = [];
                foreach ($ids as $key => $value) {
                    $history[$key]['id_stt'] = $value;
                    $history[$key]["id_status"] = $status->id_ord_stt_stat;
                    $history[$key]["kode_status"] = $status->kode_status;
                    $history[$key]["id_user"] = Auth::user()->id_user;
                    $history[$key]["keterangan"] = $keterangan;
                    $history[$key]["nm_status"] = $status->nm_ord_stt_stat;
                    $history[$key]["place"] = $nama_kota;
                    $history[$key]["id_wil"] = $request->id_kota;
                    $history[$key]["tgl_update"] = $request->tgl_update;
                    $history[$key]["nm_user"] = Auth::user()->nm_user;
                    $history[$key]["created_at"] = date("Y-m-d H:i:s");
                    $history[$key]["updated_at"] = date("Y-m-d H:i:s");

                    $stt = SttModel::findOrFail($value);

                    $pesan = "Hi {$stt->pengirim_nm}, \n";
                    $pesan .= "STT : {$stt->kode_stt}, untuk {$stt->penerima_nm} telah *{$keterangan}* pada tanggal : " . dateindo($request->tgl_update);
                    $pesan .= "\n\n - " . Session("perusahaan")["nm_perush"] . " -";
                    $pesan .= "\n\n_Pesan ini dikirim otomatis oleh sistem_";
                    $pesan .= "\n_Informasi detail klik";
                    if (!empty($perusahaan->website)) {
                        $pesan .= " {$perusahaan->website}";
                    }
                    $pesan .= "_\n_Customer Support {$perusahaan->telp_cs}_";

                    $notifikasi = new Notifikasi();
                    $notifikasi->pesan = $pesan;
                    $notifikasi->id_user = Auth::user()->id_user;
                    $notifikasi->id_perush = Session("perusahaan")["id_perush"];
                    $notifikasi->device_id = $perusahaan->device_id;
                    $notifikasi->no_hp_customer = detect_chat_id($stt->pengirim_telp);

                    $notifikasi->save();

                    $stt->id_status = $status->id_ord_stt_stat;
                    $stt->save();
                }

                HistoryStt::insert($history);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal Update Status, ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Tidak ada Stt yang dipilih');
        }
        return redirect()->back()->with('success', 'Berhasil Update Status STT')->withInput($request->input());
    }

    public function saveupdatestatusajax(Request $request)
    {
        $request->validate([
            'id_status' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_ord_stt_stat,kode_status',
            'id_kota' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'nostt' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_order,id_stt',
        ]);

        DB::commit();
        try {

            DB::beginTransaction();
            $ids = $request->nostt;
            $status = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat", "kode_status")
                ->where("kode_status", $request->id_status)->firstOrfail();

            $stt = SttModel::findOrFail($ids);

            $stt->id_status = $status->id_ord_stt_stat;
            $stt->save();

            $kota = Wilayah::select("nama_wil")->where("id_wil", $request->id_kota)->get()->first();

            $nama_kota = $kota->nama_wil;
            $keterangan = $status->nm_ord_stt_stat . " ( " . $nama_kota . " )";

            $history = [];
            $history['id_stt'] = $ids;
            $history["id_status"] = $status->id_ord_stt_stat;
            $history["kode_status"] = $status->kode_status;
            $history["id_user"] = Auth::user()->id_user;
            $history["keterangan"] = $keterangan;
            $history["nm_status"] = $status->nm_ord_stt_stat;
            $history["place"] = $nama_kota;
            $history["id_wil"] = $request->id_kota;
            $history["tgl_update"] = $request->tgl_update;
            $history["nm_user"] = Auth::user()->nm_user;
            $history["created_at"] = date("Y-m-d H:i:s");
            $history["updated_at"] = date("Y-m-d H:i:s");
            HistoryStt::insert($history);


            $perusahaan = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $pesan = "Hi {$stt->pengirim_nm}, \n";
            $pesan .= "STT : {$stt->kode_stt}, untuk {$stt->penerima_nm} telah *{$keterangan}* pada tanggal : " . dateindo($request->tgl_update);
            $pesan .= "\n\n - " . Session("perusahaan")["nm_perush"] . " -";
            $pesan .= "\n\n _Pesan ini dikirim otomatis oleh sistem_";
            $pesan .= "\n\n _Informasi detail klik";
            if (!empty($perusahaan->website)) {
                $pesan .= " {$perusahaan->website}";
            }
            $pesan .= " Customer Support_ {$perusahaan->telp_cs}";

            $notifikasi = new Notifikasi();
            $notifikasi->pesan = $pesan;
            $notifikasi->id_user = Auth::user()->id_user;
            $notifikasi->id_perush = Session("perusahaan")["id_perush"];
            $notifikasi->device_id = $perusahaan->device_id;
            $notifikasi->no_hp_customer = detect_chat_id($stt->pengirim_telp);

            $notifikasi->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Update Status, ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Berhasil Update Status STT');
    }

    public function deletehistory(string $id)
    {
        DB::commit();
        try {

            DB::beginTransaction();

            $history = HistoryStt::findOrFail($id);
            $history->delete();

            $stt = SttModel::findOrfail($history->id_stt);
            $stt->id_status = $history->id_status - 1;
            $stt->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Hapus Status, ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Berhasil Hapus Status STT');
    }
}
