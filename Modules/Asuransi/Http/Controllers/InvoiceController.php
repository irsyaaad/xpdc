<?php

namespace Modules\Asuransi\Http\Controllers;

use App\Models\Perusahaan;
use App\Traits\SaveToJurnalAsuransi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Asuransi\Entities\Asuransi;
use Modules\Asuransi\Entities\AsuransiPay;
use Modules\Asuransi\Entities\DetailInvoice;
use Modules\Asuransi\Entities\Invoice;
use Modules\Asuransi\Entities\InvoicePay;
use Modules\Asuransi\Entities\SettingPelanggan;

class InvoiceController extends Controller
{
    use SaveToJurnalAsuransi;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'page_title' => "Data Invoice Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/invoice-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $data["data"] = Invoice::select('keu_invoice_*', DB::raw('COUNT(keu_invoice_asuransi_detail.id_asuransi) AS total_stt'), DB::raw('SUM(t_asuransi_pay.n_bayar) as total_bayar'))
            ->leftJoin('keu_invoice_asuransi_detail', 'keu_invoice_asuransi_detail.id_invoice', '=', 'keu_invoice_id_invoice')
            ->leftJoin('t_asuransi_pay', 't_asuransi_pay.id_asuransi', '=', 'keu_invoice_asuransi_detail.id_asuransi')
            ->groupBy('keu_invoice_id_invoice')
            ->paginate(10);
        // dd($data);
        return view('asuransi::invoice.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [
            'page_title' => "Create Invoice Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $data["perusahaan"] = Perusahaan::all();
        return view('asuransi::invoice.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $id_invoice = null;
        try {
            $id = $this->generateId();

            DB::beginTransaction();
            $pelanggan = Perusahaan::findOrfail($request->id_pelanggan);
            $invoice = new Invoice();
            $invoice->kode_invoice = $this->generateId();
            $invoice->id_perush = Session("perusahaan")["id_perush"];
            $invoice->tgl = $request->tgl;
            $invoice->inv_j_tempo = $request->inv_j_tempo;
            $invoice->id_pelanggan = $request->id_pelanggan;
            $invoice->id_user = Auth::user()->id_user;
            $invoice->id_status = 1;
            // dd($invoice);
            $invoice->save();
            $id_invoice = $invoice->id_invoice;
            DB::commit();

        } catch (Exception $e) {
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
        $data = [
            'page_title' => "Invoice Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/invoice-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["detail"] = DetailInvoice::where('id_invoice', $id)
            ->select('*', DB::raw('(SELECT SUM(n_bayar) FROM t_asuransi_pay WHERE t_asuransi_pay.id_asuransi = keu_invoice_asuransi_detail.id_asuransi GROUP BY t_asuransi_pay.id_asuransi ) AS bayar'))
            ->get();
        $data["data"] = Invoice::findOrFail($id);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["bayar"] = InvoicePay::where('id_invoice', $id)->get()->sum('n_bayar');
        return view('asuransi::invoice.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = [
            'page_title' => "Edit Invoice Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = Invoice::findOrFail($id);
        $data["perusahaan"] = Perusahaan::all();
        return view('asuransi::invoice.create', $data);
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
            $pelanggan = Perusahaan::findOrfail($request->id_pelanggan);
            $invoice = Invoice::findOrFail($id);
            $invoice->id_perush = Session("perusahaan")["id_perush"];
            $invoice->tgl = $request->tgl;
            $invoice->inv_j_tempo = $request->inv_j_tempo;
            $invoice->id_pelanggan = $request->id_pelanggan;
            $invoice->id_user = Auth::user()->id_user;
            // dd($invoice);
            $invoice->save();
            $id_invoice = $invoice->id_invoice;
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect() . "/" . $id_invoice . "/show")->with('success', 'Data Disimpan');
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

    public function generateId()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $temp = Perusahaan::findOrfail($id_perush);
        $kode_perush = $temp->kode_perush;
        $date = date("ym");
        $data = "INV/" . strtoupper($kode_perush) . "/" . $date . "/" . substr(crc32(uniqid()), -4);

        return $data;
    }

    public function add_stt($id)
    {
        $invoice = Invoice::findOrFail($id);
        $data["data"] = Asuransi::where('id_pelanggan', $invoice->id_pelanggan)->select('*')->whereNotIn('id_asuransi', function ($query) {
            $query->select('id_asuransi')->from('keu_invoice_asuransi_detail');
        })->get();
        return view('asuransi::invoice.add-stt', $data);
    }

    public function save_draft(Request $request, $id)
    {
        $loop = $request->id_asuransi;
        if (count($loop) < 1) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }
        try {
            DB::beginTransaction();
            $detail = [];
            $total = 0;
            foreach ($loop as $key => $value) {
                $detail[$key]['id_invoice'] = $id;
                $detail[$key]['id_asuransi'] = $value;
                $detail[$key]['id_perush'] = Session("perusahaan")["id_perush"];
                $detail[$key]['id_user'] = Auth::user()->id_user;
            }

            DetailInvoice::insert($detail);

            $detailInvoice = DetailInvoice::where('id_invoice', $id)
                ->join('t_asuransi', 't_id_asuransi', '=', 'keu_invoice_asuransi_detail.id_asuransi')
                ->select('t_nominal_jual')
                ->get()->toArray();
            $invoice = Invoice::findOrFail($id);
            $invoice->total = array_sum(array_column($detailInvoice, 'nominal_jual'));
            $invoice->save();
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect() . "/" . $id . "/show")->with('success', 'Data Disimpan');
    }

    public function delete_stt($id)
    {
        try {
            DB::beginTransaction();
            $detail = DetailInvoice::findOrFail($id);
            $detail->delete();

            $detailInvoice = DetailInvoice::where('id_invoice', $detail->id_invoice)
                ->join('t_asuransi', 't_id_asuransi', '=', 'keu_invoice_asuransi_detail.id_asuransi')
                ->select('t_nominal_jual')
                ->get()->toArray();
            $invoice = Invoice::findOrFail($detail->id_invoice);
            $invoice->total = array_sum(array_column($detailInvoice, 'nominal_jual'));
            $invoice->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Stt Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data Asuransi dihapus');
    }

    public function bayar(Request $request, $id)
    {
        $id_invoice = null;
        $invoice = Invoice::with("pelanggan")
            ->select('*', DB::raw('(SELECT SUM(n_bayar) FROM keu_invoice_asuransi_pay WHERE keu_invoice_id_invoice = keu_invoice_asuransi_pay.id_invoice GROUP BY keu_invoice_asuransi_pay.id_invoice ) AS bayar'))->first();
        $sisa = $invoice->total - $invoice->bayar;
        if ((Double) ($request->n_bayar) > (Double) ($sisa)) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan Nominal Bayar lebih besar');
        }
        try {
            $setting = SettingPelanggan::where('id_pelanggan', $request->id_pelanggan)->first();
            $total_bayar = $request->n_bayar;
            $stt = DetailInvoice::getAsuransi($id);
            // dd($stt);
            DB::beginTransaction();
            $bayar = [];
            foreach ($stt as $key => $value) {
                $bayar = new AsuransiPay();
                $bayar->id_asuransi = $value->id_asuransi;
                $bayar->nm_bayar = $request->nm_bayar;
                $bayar->no_bayar = $request->no_referensi;
                $bayar->tgl_bayar = $request->tgl_bayar;
                $bayar->info = $request->info;
                $bayar->n_bayar = $total_bayar > $value->sisa ? $value->sisa : $total_bayar;
                $bayar->id_pelanggan = $request->id_pelanggan;
                $bayar->no_kwitansi = 'KW/' . date('y/m') . '/' . substr(crc32(uniqid()), -4);
                $bayar->id_user = Auth::user()->id_user;
                $bayar->save();

                $total_bayar -= $value->sisa;

                $jurnal = $this->save_pay_to_jurnal($setting, $bayar, $request->akun, $bayar->tgl_bayar);

                if ($total_bayar <= 0) {
                    break;
                }
            }

            $stt = DetailInvoice::getAsuransi($id);
            foreach ($stt as $key => $value) {
                if ($value->sisa == 0) {
                    $a_data["status"] = 'Lunas';
                } else {
                    $a_data["status"] = 'Belum Lunas';
                }
                $a_data["status"] = 'Belum Lunas';
                Asuransi::where("id_asuransi", $value->id_asuransi)->update($a_data);
            }

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect() . "/" . $id . "/show")->with('success', 'Data Disimpan');
    }
}
