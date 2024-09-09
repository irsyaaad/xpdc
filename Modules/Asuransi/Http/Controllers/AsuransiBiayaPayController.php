<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Asuransi\Entities\Asuransi;
use Modules\Asuransi\Entities\AsuransiBiayaPay;
use Modules\Asuransi\Entities\SettingPerusahaan;

class AsuransiBiayaPayController extends Controller
{
    use SaveToJurnalAsuransi;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'page_title' => "Data Invoice Asuransi Pay",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/asuransi-biaya-pay.js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = AsuransiBiayaPay::paginate(10);
        return view('asuransi::asuransi-biaya-pay.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('asuransi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $setting = SettingPerusahaan::where('id_perush_asuransi', $request->id_perush_asuransi)->first();
            if ($setting == null) {
                return redirect()->back()->with('error', 'Setting Perusahaan Asuransi Belum Dibuat, Hubungi Administrator');
            }
            $bayar = new AsuransiBiayaPay();
            $bayar->id_asuransi = $request->id_asuransi;
            $bayar->nm_bayar = $request->nm_bayar;
            $bayar->no_bayar = $request->no_referensi;
            $bayar->tgl_bayar = $request->tgl_bayar;
            $bayar->info = $request->info;
            $bayar->n_bayar = $request->n_bayar;
            $bayar->id_perush_asuransi = $request->id_perush_asuransi;
            $bayar->id_user = Auth::user()->id_user;
            $bayar->save();
            // dd($bayar);

            $jurnal = $this->save_pay_biaya_to_jurnal($setting, $bayar, $request->akun, $bayar->tgl_bayar);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Disimpan');
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
        return view('asuransi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        try {
            $this->delete_from_jurnal('t_asuransi_biaya_pay', $id);
            $setting = SettingPerusahaan::where('id_perush_asuransi', $request->id_perush_asuransi)->first();
            if ($setting == null) {
                return redirect()->back()->with('error', 'Setting Perusahaan Asuransi Belum Dibuat, Hubungi Administrator');
            }

            DB::beginTransaction();
            $bayar = AsuransiBiayaPay::findOrfail($id);
            $bayar->id_asuransi = $request->id_asuransi;
            $bayar->nm_bayar = $request->nm_bayar;
            $bayar->no_bayar = $request->no_referensi;
            $bayar->tgl_bayar = $request->tgl_bayar;
            $bayar->info = $request->info;
            $bayar->n_bayar = $request->n_bayar;
            $bayar->id_perush_asuransi = $request->id_perush_asuransi;
            $bayar->id_user = Auth::user()->id_user;
            $bayar->save();

            $jurnal = $this->save_pay_biaya_to_jurnal($setting, $bayar, $request->akun, $bayar->tgl_bayar);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Disimpan');
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
}
