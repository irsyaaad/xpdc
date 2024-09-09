<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\Memorial;

class MemorialController extends Controller
{
    use SaveToJurnalAsuransi;
    protected $id_perush = 17;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Data Memorial",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/memorial.js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = $this->id_perush;
        $pendapatan = Memorial::with("perusahaan", "user", "debet")
            ->where("id_perush", $id_perush)
            ->where("tgl", ">=", $dr_tgl)
            ->where("tgl", "<=", $sp_tgl);
        $data["data"] = $pendapatan->paginate(10);
        // dd($data);
        return view('asuransi::memorial.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [
            'page_title' => "Create Jurnal Memorial",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/memorial.js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $id_perush = $this->id_perush;
        $data["debit"] = ACPerush::getACDebit($id_perush);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac', 'm_ac_perush.nama', 'parent.nama as parent_3')
            ->join('m_ac AS parent', 'parent.id_ac', '=', 'm_ac_perush.parent')
            ->where('m_ac_perush.id_perush', '=', $id_perush)
            ->orderBy('m_ac_perush.id_ac', 'ASC')
            ->get();
        return view('asuransi::memorial.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $id_perush = $this->id_perush;
        try {
            DB::beginTransaction();

            $memorial = new Memorial();

            $memorial->id_perush = $id_perush;
            $memorial->info = $request->info;
            $memorial->tgl = $request->tgl_masuk;
            $memorial->id_user = Auth::user()->id_user;
            $memorial->id_ac_debet = $request->id_ac_debet;
            $memorial->id_ac_kredit = $request->id_ac_kredit;
            $memorial->nominal = $request->nominal;
            $memorial->no_referensi = $request->no_referensi;
            $memorial->kode_memorial = "JM" . $id_perush . date("ym") . substr(crc32(uniqid()), -4);
            $memorial->save();
            // dd($memorial->id_memorial);
            $jurnal = $this->save_jurnal_to_table_jurnal($memorial->id_ac_debet, $memorial->id_ac_kredit, $memorial->nominal, $memorial->info, 'memo', $memorial->id_memorial, $memorial->kode_memorial, $memorial->tgl);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Pengeluaran Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url(route_redirect()))->with('success', 'Data Memorial  Disimpan');
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
        $data = [
            'page_title' => "Edit Jurnal Memorial",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/memorial.js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $id_perush = $this->id_perush;
        $data["debit"] = ACPerush::getACDebit($id_perush);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac', 'm_ac_perush.nama', 'parent.nama as parent_3')
            ->join('m_ac AS parent', 'parent.id_ac', '=', 'm_ac_perush.parent')
            ->where('m_ac_perush.id_perush', '=', $id_perush)
            ->orderBy('m_ac_perush.id_ac', 'ASC')
            ->get();
        $data["data"] = Memorial::findOrFail($id);
        return view('asuransi::memorial.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $id_perush = $this->id_perush;

        try {
            DB::beginTransaction();
            $delete_jurnal = $this->delete_from_jurnal('keu_memorial', $id);
            $memorial = Memorial::findOrFail($id);
            $memorial->id_perush = $id_perush;
            $memorial->info = $request->info;
            $memorial->tgl = $request->tgl_masuk;
            $memorial->id_user = Auth::user()->id_user;
            $memorial->id_ac_debet = $request->id_ac_debet;
            $memorial->id_ac_kredit = $request->id_ac_kredit;
            $memorial->nominal = $request->nominal;
            $memorial->no_referensi = $request->no_referensi;
            $memorial->save();
            // dd($memorial->id_memorial);
            $jurnal = $this->save_jurnal_to_table_jurnal($memorial->id_ac_debet, $memorial->id_ac_kredit, $memorial->nominal, $memorial->info, 'memo', $id, $memorial->kode_memorial, $memorial->tgl);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Data Pengeluaran Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(url(route_redirect()))->with('success', 'Data Memorial  Disimpan');
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

            $delete_jurnal = $this->delete_from_jurnal('keu_memorial', $id);
            $memo = Memorial::findOrfail($id);
            $memo->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Memorial Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Memorial Dihapus');
    }
}
