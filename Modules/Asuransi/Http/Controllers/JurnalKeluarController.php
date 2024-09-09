<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\Pengeluaran;
use Modules\Keuangan\Entities\PengeluaranDetail;

class JurnalKeluarController extends Controller
{
    use SaveToJurnalAsuransi;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Data Jurnal Keluar",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-keluar-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = 17;
        $pendapatan = Pengeluaran::with("perusahaan", "user", "debet")
            ->where("id_perush", $id_perush)
            ->where("tgl_keluar", ">=", $dr_tgl)
            ->where("tgl_keluar", "<=", $sp_tgl);
        $data["data"] = $pendapatan->paginate(10);
        // dd($data);
        return view('asuransi::jurnal-keluar.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [
            'page_title' => "Create Jurnal Keluar",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $id_perush = 17;
        $data["debit"] = ACPerush::getACDebit($id_perush);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac', 'm_ac_perush.nama', 'parent.nama as parent_3')
            ->join('m_ac AS parent', 'parent.id_ac', '=', 'm_ac_perush.parent')
            ->where('m_ac_perush.id_perush', '=', $id_perush)
            ->orderBy('m_ac_perush.id_ac', 'ASC')
            ->get();
        return view('asuransi::jurnal-keluar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id_perush = 17;
        $id = null;
        try {
            DB::beginTransaction();

            $pengeluaran = new Pengeluaran();
            $pengeluaran->id_perush = $id_perush;
            $pengeluaran->id_ac = $request->id_ac;
            $pengeluaran->terima_dr = $request->terima_dr;
            $pengeluaran->info = $request->info;

            $jenis = "KK";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if ($cek->is_bank == true) {
                $jenis = "BK";
            }

            $pengeluaran->tgl_keluar = $request->tgl_masuk;
            $pengeluaran->id_user = Auth::user()->id_user;
            $pengeluaran->kode_pengeluaran = "JK" . $id_perush . date("ym") . substr(crc32(uniqid()), -4);
            $pengeluaran->save();
            $id = $pengeluaran->id_pengeluaran;

            foreach ($request->id_ac_detail as $key => $value) {
                $detail = new PengeluaranDetail();
                $detail->id_pengeluaran = $id;
                $detail->id_ac = $value;
                $detail->total = $request->harga[$key];
                $detail->id_user = Auth::user()->id_user;
                $detail->info = $request->detail_info[$key];
                $detail->tgl_posting = $request->tgl_masuk;
                $detail->save();

                $jurnal = $this->save_jurnal_to_table_jurnal($value, $request->id_ac, $detail->total, $detail->info, 'keluar', $id, $pengeluaran->kode_pengeluaran, $pengeluaran->tgl_keluar);
            }

            $masuk = PengeluaranDetail::where("id_pengeluaran", $id)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pengeluaran::where("id_pengeluaran", $id)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Pengeluaran Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url(route_redirect() . "/" . $id . "/show"))->with('success', 'Data Pengeluaran  Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = [
            'page_title' => "Show Jurnal Keluar",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        // dd($data);
        return view('asuransi::jurnal-keluar.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = [
            'page_title' => "Edit Jurnal Keluar",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $id_perush = 17;
        $data["data"] = Pengeluaran::findOrFail($id);
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        $data["debit"] = ACPerush::getACDebit($id_perush);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac', 'm_ac_perush.nama', 'parent.nama as parent_3')
            ->join('m_ac AS parent', 'parent.id_ac', '=', 'm_ac_perush.parent')
            ->where('m_ac_perush.id_perush', '=', $id_perush)
            ->orderBy('m_ac_perush.id_ac', 'ASC')
            ->get();
        return view('asuransi::jurnal-keluar.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $id_perush = 17;
        try {
            DB::beginTransaction();
            $delete_jurnal = $this->delete_from_jurnal('keu_pengeluaran_det', $id);

            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->id_perush = $id_perush;
            $pengeluaran->id_ac = $request->id_ac;
            $pengeluaran->terima_dr = $request->terima_dr;
            $pengeluaran->info = $request->info;

            $jenis = "KK";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if ($cek->is_bank == true) {
                $jenis = "BK";
            }

            $pengeluaran->tgl_keluar = $request->tgl_masuk;
            $pengeluaran->id_user = Auth::user()->id_user;
            $pengeluaran->save();

            PengeluaranDetail::where('id_pengeluaran', $id)->delete();
            foreach ($request->id_ac_detail as $key => $value) {
                $detail = new PengeluaranDetail();
                $detail->id_pengeluaran = $id;
                $detail->id_ac = $value;
                $detail->total = $request->harga[$key];
                $detail->id_user = Auth::user()->id_user;
                $detail->info = $request->detail_info[$key];
                $detail->tgl_posting = $request->tgl_masuk;
                $detail->save();

                $jurnal = $this->save_jurnal_to_table_jurnal($value, $request->id_ac, $detail->total, $detail->info, 'keluar', $id, $pengeluaran->kode_pengeluaran, $pengeluaran->tgl_keluar);
            }

            $masuk = PengeluaranDetail::where("id_pengeluaran", $id)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pengeluaran::where("id_pengeluaran", $id)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Pengeluaran Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url(route_redirect() . "/" . $id . "/show"))->with('success', 'Data Pengeluaran  Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $delete_jurnal = $this->delete_from_jurnal('keu_pengeluaran_det', $id);
            PengeluaranDetail::where('id_pengeluaran', $id)->delete();
            $pend = Pengeluaran::findOrfail($id);
            $pend->delete();
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengeluaran Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Pengeluaran Dihapus');
    }
}
