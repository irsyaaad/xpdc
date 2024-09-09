<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\InvoiceHandling;
use Modules\Keuangan\Entities\InvHandlingStt;
use Modules\Keuangan\Entities\SettingHandlingPerush;
use Modules\Operasional\Entities\BiayaHandling;
use Modules\Operasional\Entities\Handling;
use Auth;
use DB;
use App\Models\Perusahaan;
use App\Http\Controllers\CronJobController;
use App\Models\CronJob;
use Modules\Operasional\Entities\SttModel;
use Modules\Keuangan\Entities\InvoiceHandlingPendapatan;
use Exception;
use Validator;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\InvHandlingPendapatanBayar;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Keuangan\Entities\BiayaHpp;
use Modules\Operasional\Entities\TandaTangan;

class InvoiceHandlingTerimaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $perpage = 50;
        $page = 1;
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = InvoiceHandling::getListTerima($perpage, $page, $id_perush);
        $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();

        return view('keuangan::invoicehandlingtj.invoicehandlingtj', $data);
    }

    public function filter(Request $request)
    {
        // dd($request->request);

        $perpage = 50;
        $page = 1;

        $id_perush = Session("perusahaan")["id_perush"];
        $id_invoice = null;
        $id_perush_tj = null;
        $dr_tgl = null;
        $sp_tgl = null;
        $status = null;

        if(isset($request->id_invoice)){
            $id_invoice = $request->id_invoice;
        }
        if(isset($request->id_perush)){
            $id_perush_tj = $request->id_perush;
        }
        if(isset($request->dr_tgl)){
            $dr_tgl = $request->dr_tgl;
        }
        if(isset($request->sp_tgl)){
            $sp_tgl = $request->sp_tgl;
        }

        $invoice = InvoiceHandling::select("id_invoice", "kode_invoice")->where("id_invoice", $id_invoice)->get()->first();
        $perusahaan = Perusahaan::select("id_perush", "nm_perush")->where("id_perush",$id_perush_tj)->get()->first();
        $filter = array("invoice"=> $invoice, "perusahaan"=>$id_perush_tj, "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl);
        $data["data"] = InvoiceHandling::getListTerima($perpage, $page, $id_perush, $id_perush_tj,$id_invoice, $dr_tgl, $sp_tgl);
        $data["filter"] = $filter;
        $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();

        return view('keuangan::invoicehandlingtj.invoicehandlingtj', $data);
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
        abort(404);
    }

    public function showbayar($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data = BiayaHpp::getInvoiceBayar($id);
        $a_data = [];
        $no = 0;
        $total = 0;
        foreach($data as $key => $value){
            $debit = ACPerush::select("nama")->where("id_ac", $value->ac4_debit)->get()->first();
            $kredit = ACPerush::select("nama")->where("id_ac", $value->ac4_kredit)->get()->first();
            $total += $value->n_bayar;
            $no++;
            $a_data[$key] = "<tr><td>".$value->nm_biaya_grup."</td><td>".dateindo($value->created_at)."</td><td>".$debit->nama."</td><td>".$kredit->nama."</td><td>".torupiah($value->n_bayar)."</td></tr>";
        }
        $a_data[$no] = "<tr><td class='text-right' colspan='4'>Total : </td><td class='text-right'><b>".torupiah($total)."</td></tr>";
        return response()->json($a_data);
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $page = 100;
        $id_perush = Session("perusahaan")["id_perush"];
        $invoice = InvoiceHandling::getDetailTerima($id);
        if($invoice == null){
            abort(404);
        }

        $data["bayar"] =InvoiceHandlingPendapatan::where("id_invoice", $id)->sum("dibayar");
        $biaya = InvoiceHandlingPendapatan::getBiaya($page, $id);

        if($invoice->id_status > 2 ){
            $biaya = BiayaHpp::getBiayaInvoice($id);
        }

        $data["biaya"] = $biaya;
        $data["akun"] = ACPerush::getList($id_perush);
        $data["kasbank"] = ACPerush::getKasBank($id_perush);
        $data["bank"] = ACPerush::getBank($invoice->id_perush);
        $data["data"] = $invoice;

        return view('keuangan::invoicehandlingtj.showinvoice', $data);
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
        //
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

    public function terima($id)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        DB::beginTransaction();
        try {
            $cek = InvoiceHandlingPendapatan::where("id_invoice", $id)->get()->first();
            if(!$cek){
                return redirect()->back()->with('error', 'Biaya Invoice Belum Ada');
            }

            $invoice = InvoiceHandling::findOrFail($id);
            $setting = SettingHandlingPerush::where("id_perush", $invoice->id_perush)->get()->first();
            if(!$setting){
                return redirect()->back()->with('error', 'Setting Biaya Handling Belum Ada');
            }

            $biaya = InvoiceHandlingPendapatan::where("id_invoice", $id)->get();
            $a_biaya = [];
            foreach($biaya as $key  => $value){
                $a_biaya[$key]["nominal"] = $value->nominal;
                $a_biaya[$key]["id_user"] = Auth::user()->id_user;
                $a_biaya[$key]["id_biaya_grup"] = $value->id_biaya_grup;
                $a_biaya[$key]["is_lunas"] = false;
                $a_biaya[$key]["id_perush_dr"] = $value->id_perush_tj;
                $a_biaya[$key]["ac4_kredit"] = $setting->ac4_biaya;
                $a_biaya[$key]["ac4_debit"] = $setting->ac4_hutang;
                $a_biaya[$key]["id_perush_tj"] = $value->id_perush;
                $a_biaya[$key]["id_handling"] = $value->id_handling;
                $a_biaya[$key]["kode_handling"] = $value->kode_handling;
                $a_biaya[$key]["n_bayar"] = 0;
                $a_biaya[$key]["id_dm"] = $value->id_dm;
                $a_biaya[$key]["kode_dm"] = $value->kode_dm;
                $a_biaya[$key]["id_stt"] = $value->id_stt;
                $a_biaya[$key]["kode_stt"] = $value->kode_stt;
                $a_biaya[$key]["created_at"] = date("Y-m-d H:i:s");
                $a_biaya[$key]["updated_at"] = date("Y-m-d H:i:s");
                $a_biaya[$key]["id_invoice"] = $value->id_invoice;
                $a_biaya[$key]["id_inv_pend"] = $value->id_biaya_pend;
            }

            ProyeksiDm::insert($a_biaya);
            // this for invoice handling
            $invoice->is_confirm = true;
            $invoice->tgl_confirm = date("Y-m-d");
            $invoice->id_status = $invoice->id_status+1;
            $invoice->id_penerima = Auth::user()->id_user;
            $invoice->save();

            // sum total biaya dm
            $sum = ProyeksiDm::where("id_dm", $invoice->id_dm)->sum("nominal");
            $sumdm = [];
            $sumdm["c_pro"] =$sum;
            DaftarMuat::where("id_dm", $invoice->id_dm)->update($sumdm);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Invoice Gagal Diterima '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Invoice Diterima');
    }

    public function cetakInvoice($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $inv = InvoiceHandling::getDetailTerima($id);
        if($inv == null){
            abort(404);
        }

        $data["bayar"] =InvoiceHandlingPendapatan::where("id_invoice", $id)->sum("dibayar");
        $data["invoice"] = $inv;

        $biaya = InvoiceHandlingPendapatan::getBiayaBayar($id);

        $data["ttd"] = TandaTangan::where("id_ref",$id)->get()->first();
        $data["biaya"] = $biaya;
        $data["akun"] = ACPerush::getList($id_perush);
        $data["ac"] = ACPerush::getKasBank($id_perush);
        $data["perusahaan"] = Perusahaan::findOrFail($inv->id_perush);
        $data["perushtj"] = Perusahaan::findOrFail($inv->id_perush_tj);

        // dd($data);
        return view('keuangan::invoicehandling.showtocetakinv',$data);
    }

    public function ttd($id)
    {
        $data["data"] = InvoiceHandling::findOrFail($id);
        return view('keuangan::invoicehandling.cetakinvhandling',$data);
    }

    public function savettd(Request $request)
    {
        try {
            DB::beginTransaction();

            $ttd                    = new TandaTangan();
            $ttd->id_ref            = $request->id_ref;
            $ttd->type_dok          = "invoicehandlingtj";
            $ttd->id_user           = Auth::user()->id_user;
            $ttd->ttd_admin         = $request->img;
            $ttd->id_perush         = Session("perusahaan")["id_perush"];

            // dd($ttd);
            $ttd->save();

            DB::commit();

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
        $data = [
            'status' => true,
            'data'   => $ttd,
            'url'    => (url("invoicehandlingterima/".$request->id_ref."/cetak")),
        ];
        return response()->json($data);
    }
}
