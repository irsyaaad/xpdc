<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\Pendapatan;
use Modules\Keuangan\Entities\PendapatanDetail;

class JurnalMasukController extends Controller
{
    use SaveToJurnalAsuransi;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Data Jurnal Masuk",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = 17;
        $pendapatan = Pendapatan::with("perusahaan", "user", "debet")
            ->where("id_perush", $id_perush)
            ->where("tgl_masuk", ">=", $dr_tgl)
            ->where("tgl_masuk", "<=", $sp_tgl);
        $data["data"] = $pendapatan->paginate(10);
        // dd($data);
        return view('asuransi::jurnal-masuk.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [
            'page_title' => "Create Jurnal Masuk",
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
        return view('asuransi::jurnal-masuk.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id_perush = 17;
        // dd($request->all());
        try {
            DB::beginTransaction();
            $pend = new Pendapatan();
            $pend->id_perush = $id_perush;
            $pend->id_ac = $request->id_ac;
            $pend->terima_dr = $request->terima_dr;
            $pend->info = $request->info;
            $pend->tgl_masuk = $request->tgl_masuk;
            $pend->id_user = Auth::user()->id_user;
            $jenis = "KM";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if ($cek->is_bank == true) {
                $jenis = "BM";
            }
            $pend->kode_pendapatan = "JP" . $id_perush . date("ym") . substr(crc32(uniqid()), -4);
            $pend->save();
            $id = $pend->id_pendapatan;

            // dd($pend, $request->all());
            foreach ($request->id_ac_detail as $key => $value) {
                $detail = new PendapatanDetail();
                $detail->id_pendapatan = $id;
                $detail->id_ac = $value;
                $detail->total = $request->harga[$key];
                $detail->id_user = Auth::user()->id_user;
                $detail->info = $request->detail_info[$key];
                $detail->tgl_posting = $request->tgl_masuk;
                $detail->save();

                $jurnal = $this->save_jurnal_to_table_jurnal($request->id_ac, $value, $detail->total, $detail->info, 'masuk', $id, $pend->kode_pendapatan, $pend->tgl_masuk);
            }

            // update pendapatan total
            $masuk = PendapatanDetail::where("id_pendapatan", $id)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pendapatan::where("id_pendapatan", $id)->update($a_data);

            // dd($pend);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Jurnal Masuk Gagal Disimpan ' . $e->getMessage());
        }
        return redirect(url(route_redirect()))->with('success', 'Data Jurnal Masuk  Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = [
            'page_title' => "Show Jurnal Masuk",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = Pendapatan::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["detail"] = PendapatanDetail::with("akun")->where("id_pendapatan", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        // dd($data);
        return view('asuransi::jurnal-masuk.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = [
            'page_title' => "Edit Jurnal Masuk",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/jurnal-masuk-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $id_perush = 17;
        $data["data"] = Pendapatan::findOrFail($id);
        $data["detail"] = PendapatanDetail::with("akun")->where("id_pendapatan", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        $data["debit"] = ACPerush::getACDebit($id_perush);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac', 'm_ac_perush.nama', 'parent.nama as parent_3')
            ->join('m_ac AS parent', 'parent.id_ac', '=', 'm_ac_perush.parent')
            ->where('m_ac_perush.id_perush', '=', $id_perush)
            ->orderBy('m_ac_perush.id_ac', 'ASC')
            ->get();
        return view('asuransi::jurnal-masuk.edit', $data);
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
        // dd($request->all());
        try {
            DB::beginTransaction();
            $delete_jurnal = $this->delete_from_jurnal('keu_pendapatan_det', $id);

            $pend = Pendapatan::findOrFail($id);
            $pend->id_perush = $id_perush;
            $pend->id_ac = $request->id_ac;
            $pend->terima_dr = $request->terima_dr;
            $pend->info = $request->info;
            $pend->tgl_masuk = $request->tgl_masuk;
            $pend->id_user = Auth::user()->id_user;
            $jenis = "KM";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if ($cek->is_bank == true) {
                $jenis = "BM";
            }
            $pend->save();

            PendapatanDetail::where('id_pendapatan', $id)->delete();
            foreach ($request->id_ac_detail as $key => $value) {
                $detail = new PendapatanDetail();
                $detail->id_pendapatan = $id;
                $detail->id_ac = $value;
                $detail->total = $request->harga[$key];
                $detail->id_user = Auth::user()->id_user;
                $detail->info = $request->detail_info[$key];
                $detail->tgl_posting = $request->tgl_masuk;
                $detail->save();

                $jurnal = $this->save_jurnal_to_table_jurnal($request->id_ac, $value, $detail->total, $detail->info, 'masuk', $id, $pend->kode_pendapatan, $pend->tgl_masuk);
            }

            // update pendapatan total
            $masuk = PendapatanDetail::where("id_pendapatan", $id)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pendapatan::where("id_pendapatan", $id)->update($a_data);

            // dd($pend);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Jurnal Masuk Gagal Disimpan ' . $e->getMessage());
        }
        return redirect(url(route_redirect()))->with('success', 'Data Jurnal Masuk  Disimpan');
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

            $delete_jurnal = $this->delete_from_jurnal('keu_pendapatan_det', $id);
            PendapatanDetail::where('id_pendapatan', $id)->delete();
            $pend = Pendapatan::findOrfail($id);
            $pend->delete();
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Pendapatan Dihapus');
    }
}
