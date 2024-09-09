<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Asuransi\Entities\InvoicePay;
use Modules\Asuransi\Entities\AsuransiPay;
use Modules\Asuransi\Entities\SettingPelanggan;

class InvoicePayController extends Controller
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
                'assets-templatev2/js/asuransi/invoice-pay-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = AsuransiPay::with("pelanggan", "asuransi")->paginate(10);
        // dd($data);
        return view('asuransi::invoice-pay.index', $data);
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
        //
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
            $this->delete_from_jurnal('t_asuransi_pay', $id);
            $setting = SettingPelanggan::where('id_pelanggan', $request->id_pelanggan)->first();

            DB::beginTransaction();
            $bayar = AsuransiPay::findOrFail($id);
            $bayar->id_asuransi = $request->id_asuransi;
            $bayar->nm_bayar = $request->nm_bayar;
            $bayar->no_bayar = $request->no_referensi;
            $bayar->tgl_bayar = $request->tgl_bayar;
            $bayar->info = $request->info;
            $bayar->n_bayar = $request->n_bayar;
            $bayar->id_user = Auth::user()->id_user;
            $bayar->save();

            $jurnal = $this->save_pay_to_jurnal($setting, $bayar, $request->akun, $request->tgl_bayar);
            DB::commit();

        } catch (Exception $e) {
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
        try {
            DB::beginTransaction();

            $this->delete_from_jurnal('keu_invoice_asuransi_pay', $id);
            $invoice = InvoicePay::findOrFail($id);
            $invoice->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
