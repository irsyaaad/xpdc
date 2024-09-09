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
use Modules\Asuransi\Entities\Invoice;
use Modules\Asuransi\Entities\SettingPelanggan;
use Modules\Asuransi\Entities\SettingPerusahaan;
use Modules\Operasional\Entities\PerusahaanAsuransi;
use Modules\Operasional\Entities\SttModel;

class AsuransiController extends Controller
{
    use SaveToJurnalAsuransi;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => "Data Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $asuransi = Asuransi::orderBy('id_asuransi', 'DESC');

        if (isset($request->search)) {
            $asuransi = $asuransi->where('id_stt', 'ilike', "%{$request->search}%")
                ->orWhere('asal', 'ilike', "%{$request->search}%")
                ->orWhere('tujuan', 'ilike', "%{$request->search}%")
                ->orWhere('nominal_jual', 'ilike', "%{$request->search}%");
        }

        if (isset($request->f_id_pelanggan)) {
            $asuransi = $asuransi->where('id_pelanggan', $request->f_id_pelanggan);
        }

        $data["data"] = $asuransi->paginate(10);
        $data["filter"] = [
            'search' =>  $request->search ?? '',
        ];
        return view('asuransi::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [
            'page_title' => "Create Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];

        $data['perush_asuransi'] = PerusahaanAsuransi::with('tarif')->get();
        $data["perusahaan"] = Perusahaan::all();
        return view('asuransi::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {

            $setting_pelanggan = SettingPelanggan::where('id_pelanggan', $request->id_pelanggan)->first();
            if ($setting_pelanggan == null) {
                return redirect()->back()->with('error', 'Setting Pelanggan Belum Dibuat');
            }
            $setting_perusahaan = SettingPerusahaan::where('id_perush_asuransi', $request->broker)->first();
            if ($setting_perusahaan == null) {
                return redirect()->back()->with('error', 'Setting Perusahaan Belum Dibuat');
            }

            DB::beginTransaction();
            $asuransi = new Asuransi();
            $asuransi->id_stt = $request->id_stt_dm;
            $asuransi->id_pelanggan = $request->id_pelanggan;
            $asuransi->nm_pengirim = $request->nm_pengirim;
            $asuransi->id_asal = $request->id_asal;
            $asuransi->asal = $request->asal;
            $asuransi->id_tujuan = $request->id_tujuan;
            $asuransi->tujuan = $request->tujuan;
            $asuransi->tgl_berangkat = $request->tgl_berangkat;
            $asuransi->tgl_sampai = $request->tgl_sampai;
            $asuransi->id_tipe_barang = $request->id_tipe_barang;
            $asuransi->nm_tipe_barang = $request->nm_tipe_barang;
            $asuransi->broker = $request->broker;
            $asuransi->nm_kapal = $request->nm_kapal;
            $asuransi->no_identity = $request->no_identity;
            $asuransi->qty = $request->qty;
            $asuransi->status = 'Belum Lunas';
            $asuransi->harga_pertanggungan = $request->harga_pertanggungan;
            $asuransi->nominal_jual = $request->nominal_jual;
            $asuransi->nominal_beli = $request->nominal_beli;
            $asuransi->keterangan = $request->keterangan;
            $asuransi->id_user = Auth::user()->id_user;
            // dd($asuransi);
            $asuransi->save();

            $jurnal = $this->save_to_jurnal($setting_pelanggan, $setting_perusahaan, $asuransi);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = [
            'page_title' => "Show Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = Asuransi::with("pelanggan", "tipebarang", "perush_asuransi")->findOrFail($id);
        $data["bayar"] = AsuransiPay::where('id_asuransi', $id)->get()->sum('n_bayar');
        $data["invoice"] = Invoice::join('keu_invoice_asuransi_detail', 'keu_invoice_asuransi_detail.id_invoice', '=', 'keu_invoice_id_invoice')
            ->where('keu_invoice_asuransi_detail.id_asuransi', $id)
            ->first();
        // dd($data);
        return view('asuransi::show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = [
            'page_title' => "Create Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = Asuransi::findOrFail($id);
        $data['perush_asuransi'] = PerusahaanAsuransi::all();
        $data["perusahaan"] = Perusahaan::all();
        return view('asuransi::create', $data);
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

            $this->delete_from_jurnal('t_asuransi', $id);
            $setting_pelanggan = SettingPelanggan::where('id_pelanggan', $request->id_pelanggan)->first();
            if ($setting_pelanggan == null) {
                return redirect()->back()->with('error', 'Setting Pelanggan Belum Dibuat');
            }
            $setting_perusahaan = SettingPerusahaan::where('id_perush_asuransi', $request->broker)->first();
            if ($setting_perusahaan == null) {
                return redirect()->back()->with('error', 'Setting Perusahaan Belum Dibuat');
            }

            $asuransi = Asuransi::findOrFail($id);
            $asuransi->id_stt = $request->id_stt_dm;
            $asuransi->id_pelanggan = $request->id_pelanggan;
            $asuransi->nm_pengirim = $request->nm_pengirim;
            $asuransi->id_asal = $request->id_asal;
            $asuransi->asal = $request->asal;
            $asuransi->id_tujuan = $request->id_tujuan;
            $asuransi->tujuan = $request->tujuan;
            $asuransi->tgl_berangkat = $request->tgl_berangkat;
            $asuransi->tgl_sampai = $request->tgl_sampai;
            $asuransi->id_tipe_barang = $request->id_tipe_barang;
            $asuransi->nm_tipe_barang = $request->nm_tipe_barang;
            $asuransi->broker = $request->broker;
            $asuransi->nm_kapal = $request->nm_kapal;
            $asuransi->no_identity = $request->no_identity;
            $asuransi->qty = $request->qty;
            $asuransi->status = 'Belum Lunas';
            $asuransi->harga_pertanggungan = $request->harga_pertanggungan;
            $asuransi->nominal_jual = $request->nominal_jual;
            $asuransi->nominal_beli = $request->nominal_beli;
            $asuransi->keterangan = $request->keterangan;
            $asuransi->id_user = Auth::user()->id_user;
            // dd($asuransi);
            $asuransi->save();

            $jurnal = $this->save_to_jurnal($setting_pelanggan, $setting_perusahaan, $asuransi);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan' . $e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Asuransi Disimpan');
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

            $this->delete_from_jurnal('t_asuransi', $id);
            $asuransi = Asuransi::findOrFail($id);
            $asuransi->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Stt Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data Tipe Kirim dihapus');
    }

    public function search_stt($id_stt)
    {
        $result = substr($id_stt, 0, 2);
        $array = ['TR', 'LC', 'FC'];
        if (!in_array($result, $array)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.lsjexpress.co.id/GetSTTAsuransi");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "LSJ-API-KEY=93c0a246-8056-4011-8833-13488bc3aa47&no_stt=" . $id_stt);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            $json_dec = json_decode($server_output);
            $pelanggan = Perusahaan::where('kode_ref', $result)->first();
            if ($json_dec->status) {
                $data_stt = $json_dec->data->data_stt[0];
                $data = [
                    'PENGIRIM_NM' => $data_stt->PENGIRIM_NM,
                    'KOTA_ASAL' => $data_stt->KOTA_ASAL,
                    'KOTA_TUJUAN' => $data_stt->KOTA_TUJUAN,
                    'NM_KAPAL' => $data_stt->NM_KAPAL,
                    'NM_TIPE_KIRIM' => $data_stt->NM_TIPE_KIRIM,
                    'N_KOLI' => $data_stt->N_KOLI,
                    'TGL_BERANGKAT' => date('Y-m-d', strtotime($data_stt->TGL_BERANGKAT)),
                    'TGL_SAMPAI' => date('Y-m-d', strtotime($data_stt->TGL_SAMPAI)),
                    'PELANGGAN' => $pelanggan->id_perush,
                ];
            }
            return response()->json($data);
        } else {
            $term = $id_stt;
            $result = SttModel::with("asal", "tujuan", "tipekirim")
                ->leftJoin('t_order_dm', 't_order.id_stt', '=', 't_order_dm.id_stt')
                ->leftJoin('t_dm', 't_order_dm.id_dm', '=', 't_dm.id_dm')
                ->leftJoin('m_kapal', 't_dm.id_kapal', '=', 'm_kapal.id_kapal')
                ->where("kode_stt", 'ILIKE', '%' . $term . '%')
                ->first();
            if (isset($result)) {
                $data = [
                    'PENGIRIM_NM' => $result->pengirim_nm,
                    'KOTA_ASAL' => $result->asal->nama_wil,
                    'KOTA_TUJUAN' => $result->tujuan->nama_wil,
                    'NM_KAPAL' => $result->nm_kapal,
                    'NM_TIPE_KIRIM' => $result->tipekirim->nm_tipe_kirim,
                    'N_KOLI' => $result->n_koli,
                    'TGL_BERANGKAT' => date('Y-m-d', strtotime($result->tgl_berangkat)),
                    'TGL_SAMPAI' => date('Y-m-d', strtotime($result->tgl_sampai)),
                    'PELANGGAN' => $result->id_perush_asal,
                ];
            }
            return response()->json($data);
        }

    }
}
