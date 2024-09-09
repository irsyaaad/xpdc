<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SettingStatusWajib;
use Modules\Operasional\Entities\StatusStt;
use Exception;
use Auth;
use Session;
use DB;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\HistoryStt;

class SettingStatusWajibController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SettingStatusWajib::where('id_perush', $id_perush)->get();
        $data["status"] = StatusStt::getStatusKosong($dooring = true);
        // dd($data);
        return view('operasional::statuswajib.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $id_perush = Session("perusahaan")["id_perush"];
            $pengirim  = $request->pengirim;
            $penerima  = $request->penerima;
            SettingStatusWajib::where("id_perush", $id_perush)->delete();

            $status = [];
            $key = 0;
            if (!empty($pengirim)) {
                foreach ($pengirim as $value) {
                    $status[$key]['id_status'] = $value;
                    $status[$key]['id_user'] = Auth::user()->id_user;
                    $status[$key]['type'] = 1;
                    $status[$key]['id_perush'] = $id_perush;
                    $key++;
                }
            }
            if (!empty($penerima)) {
                foreach ($penerima as $value) {
                    $status[$key]['id_status'] = $value;
                    $status[$key]['id_user'] = Auth::user()->id_user;
                    $status[$key]['type'] = 2;
                    $status[$key]['id_perush'] = $id_perush;
                    $key++;
                }
            }
            SettingStatusWajib::insert($status);

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Wajib Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Status Wajib Sukses Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            $cek = SettingStatusWajib::where("id_status", $request->id_status)
                ->where("id_perush", Session("perusahaan")["id_perush"])
                ->where("type", $request->type)->get()->first();

            if ($cek != null) {
                return redirect()->back()->with('error', 'Status Sudah ada');
            }

            $status                       = SettingStatusWajib::findOrFail($id);
            $status->id_status = $request->id_status;
            $status->id_user = Auth::user()->id_user;
            $status->type = $request->type == 1 ? $request->type : 2;
            $status->id_perush = Session("perusahaan")["id_perush"];
            $status->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Wajib Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Status Wajib Sukses Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function detailpengirim(Request $request)
    {
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = $request->id_perush != null ? $request->id_perush : Session("perusahaan")["id_perush"];
        $data["data"] = SettingStatusWajib::getDetailFix($id_perush, $dr_tgl, $sp_tgl);
        // dd($data);
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["perush"] = Perusahaan::findOrFail($id_perush);

        return view('operasional::statuswajib.laporandetail', $data);
    }

    public function detailpenerima($id, Request $request)
    {
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = $id;
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        $data["perush"] = Perusahaan::findOrFail($id_perush);
        dd($data);
        return view('operasional::statuswajib.laporandetailpenerima', $data);
    }

    public function laporan(Request $request)
    {
        $dr_tgl = $request->dr_tgl != null ? $request->dr_tgl : date("Y-m-01");
        $sp_tgl = $request->sp_tgl != null ? $request->sp_tgl : date("Y-m-t");

        $id_perush = $request->id_perush != null ? $request->id_perush : Session("perusahaan")["id_perush"];
        $data["perush"] = Perusahaan::findOrFail($id_perush);
        $data["status"] = SettingStatusWajib::getDataFix($id_perush, $dr_tgl, $sp_tgl);
        $data["wajib_pengirim"] = SettingStatusWajib::where("id_perush", $id_perush)->where("type", 1)->count(DB::raw('DISTINCT id_status'));
        $data["wajib_penerima"] = SettingStatusWajib::where("id_perush", $id_perush)->where("type", 2)->count(DB::raw('DISTINCT id_status'));
        $data["dr_tgl"] = $dr_tgl;
        $data["sp_tgl"] = $sp_tgl;
        // dd($data);
        return view('operasional::statuswajib.laporan', $data);
    }

    public function detailstatusstt($id, Request $request)
    {
        $status_pengirim = SettingStatusWajib::where('id_perush', Session("perusahaan")["id_perush"])->where('type', 1)->get()->toArray();
        $status_penerima = SettingStatusWajib::where('id_perush', Session("perusahaan")["id_perush"])->where('type', 2)->get()->toArray();
        $data['data'] = HistoryStt::getHistory($id);        
        $data['status_pengirim'] = array_column($status_pengirim, 'id_status');
        $data['status_penerima'] = array_column($status_penerima, 'id_status');
        $data['url_back'] = $request->url_back . '?' . 'id_perush=' . $request->id_perush . '&dr_tgl=' . $request->dr_tgl . "&sp_tgl=" . $request->sp_tgl;
        return view('operasional::statuswajib.detail-status-stt', $data);
    }
}
