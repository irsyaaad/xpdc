<?php

namespace Modules\Operasional\Http\Controllers;

use App\Models\Perusahaan;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\JenisKomplain;
use Modules\Operasional\Entities\Komplain;
use Modules\Operasional\Entities\KomplainDetail;
use Modules\Operasional\Entities\SttModel;

class KomplainController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $dr_tgl = isset($request->dr_tgl) ? $request->dr_tgl : date('Y-m-01');
        $sp_tgl = isset($request->sp_tgl) ? $request->sp_tgl : date('Y-m-t');
        $stt = isset($request->id_stt) ? SttModel::findOrFail($request->id_stt) : null;

        $complain = Komplain::where('id_perush', Session('perusahaan')['id_perush']);

        if (isset($request->id_stt)) {
            $complain = $complain->where('id_stt', $request->id_stt);
        }

        if (isset($request->dr_tgl)) {
            $complain = $complain->where('tgl_complain', '>=', $request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $complain = $complain->where('tgl_complain', '<=', $request->sp_tgl);
        }

        if (isset($request->complain)) {
            $complain = $complain->where('id', $request->complain);
        }

        $data["data"] = $complain->paginate(25);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'stt' => $stt,
        ];

        return view('operasional::komplain.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["jenis_komplain"] = JenisKomplain::all();
        $data["perusahaan"] = Perusahaan::all();
        $data["data"] = [];
        return view('operasional::komplain.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl' => 'required',
            'jenis' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_complain_jenis,id',
            'id_perush_tujuan' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.s_perusahaan,id_perush',
            'pelapor' => 'bail|required',
            'hp_pelapor' => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
            'id_stt' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_order,id_stt',
            'keterangan' => 'bail|required',
        ]);

        DB::beginTransaction();
        try {
            $komplain = new Komplain();
            $komplain->tgl_complain = $request->tgl;
            $komplain->no_ticket = "COMP/" . Session("perusahaan")["id_perush"] . "/" . date('Ym') . "/" . substr(crc32(uniqid()), -4);
            $komplain->id_jenis_complain = $request->jenis;
            $komplain->id_perush = Session("perusahaan")["id_perush"];
            $komplain->id_perush_tujuan = $request->id_perush_tujuan;
            $komplain->pelapor = $request->pelapor;
            $komplain->hp_pelapor = $request->hp_pelapor;
            $komplain->id_stt = $request->id_stt;
            $komplain->keterangan = $request->keterangan;
            $komplain->created_by = Auth::user()->id_user;
            $komplain->created_at = date('Y-m-d H:i:s');
            $komplain->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Complain Gagal Disimpan' . $e->getMessage());
        }

        return redirect(url("complain"))->with('success', 'Data Complain Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $complain = Komplain::findOrFail($id);
        $data["stt"] = SttModel::findOrFail($complain->id_stt);
        $data["detail"] = KomplainDetail::where('id_complain', $id)->get();
        $data["data"] = $complain;

        return view('operasional::komplain.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $complain = Komplain::findOrFail($id);
        $data["stt"] = SttModel::findOrFail($complain->id_stt);
        $data["data"] = $complain;
        $data["jenis_komplain"] = JenisKomplain::all();
        $data["perusahaan"] = Perusahaan::all();

        return view('operasional::komplain.create', $data);
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
            'tgl' => 'required',
            'jenis' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_complain_jenis,id',
            'id_perush_tujuan' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.s_perusahaan,id_perush',
            'pelapor' => 'bail|required',
            'hp_pelapor' => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
            'id_stt' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_order,id_stt',
            'keterangan' => 'bail|required',
        ]);

        DB::beginTransaction();
        try {
            $komplain = Komplain::findOrFail($id);
            $komplain->tgl_complain = $request->tgl;
            $komplain->id_jenis_complain = $request->jenis;
            $komplain->id_perush = Session("perusahaan")["id_perush"];
            $komplain->id_perush_tujuan = $request->id_perush_tujuan;
            $komplain->pelapor = $request->pelapor;
            $komplain->hp_pelapor = $request->hp_pelapor;
            $komplain->id_stt = $request->id_stt;
            $komplain->keterangan = $request->keterangan;
            $komplain->updated_by = Auth::user()->id_user;
            $komplain->updated_at = date('Y-m-d H:i:s');
            $komplain->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Complain Gagal DiUpdate' . $e->getMessage());
        }

        return redirect(url("complain"))->with('success', 'Data Complain DiUpdate');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dok = Komplain::findOrFail($id);
            KomplainDetail::where("id_complain", $id)->delete();
            $dok->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Complain Gagal Dihapus');
        }

        return redirect()->back()->with('success', 'Data Complain Dihapus');
    }

    public function save_process(Request $request)
    {
        $request->validate([
            'tgl_update' => 'required',
            'id_complain' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.t_complain,id',
            'petugas' => 'bail|required',
            'keterangan' => 'bail|required',
        ]);

        DB::beginTransaction();
        try {
            $komplain = new KomplainDetail();
            $komplain->tgl_update = $request->tgl_update;
            $komplain->id_complain = $request->id_complain;
            $komplain->petugas = $request->petugas;
            $komplain->keterangan = $request->keterangan;
            $komplain->created_by = Auth::user()->id_user;
            $komplain->created_at = date('Y-m-d H:i:s');
            $komplain->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Complain Gagal Disimpan' . $e->getMessage());
        }

        return redirect(url("complain/" . $request->id_complain . "/show"))->with('success', 'Data Complain Disimpan');
    }

    public function getComplain(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = Komplain::where("no_ticket", 'ILIKE', '%' . $term . '%')->where("id_perush", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id, 'value' => strtoupper($value->no_ticket)];
        }

        return response()->json($results);
    }
}
