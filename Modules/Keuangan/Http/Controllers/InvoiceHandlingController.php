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
use Modules\Keuangan\Entities\InvHandlingPendapatanBayar;
use Modules\Operasional\Entities\DMTiba;
use Modules\Keuangan\Entities\BiayaHpp;
use Exception;
use Validator;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\TandaTangan;

class InvoiceHandlingController extends Controller
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
        $data["data"] = InvoiceHandling::getListData($perpage, $page, $id_perush);
        // dd($data);
        $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();

        return view('keuangan::invoicehandling.invoicehandling', $data);
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
        $data["data"] = InvoiceHandling::getListData($perpage, $page, $id_perush, $id_perush_tj,$id_invoice, $dr_tgl, $sp_tgl);
        $data["filter"] = $filter;
        $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();

        return view('keuangan::invoicehandling.invoicehandling', $data);
    }

    public function getDm($id_perush)
    {
        $data = DMTiba::getTibaInvoice($id_perush);
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper("( ".dateindo($value->tgl)." )  - ".$value->kode_dm)];
        }

        return response()->json($results);
    }

    public function reset()
    {
        return redirect(route_redirect());
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
        $id_perush = Session("perusahaan")["id_perush"];

        $validator = Validator::make($request->all(), [
            'id_perush_tj'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.s_perusahaan,id_perush',
            'id_dm'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_dm,id_dm',
            'tgl_jatuh_tempo' => 'bail|required|date'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->with('error', $validator->errors());
        }

        DB::beginTransaction();
        try {
            $invoice = new InvoiceHandling();
            $invoice->id_perush =  $id_perush;
            $invoice->tgl_invoice = date("Y-m-d");
            $invoice->id_perush_tj = $request->id_perush_tj;
            $invoice->id_dm = $request->id_dm;
            $invoice->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $invoice->id_user = Auth::user()->id_user;
            $invoice->is_approve = false;
            $invoice->is_confirm = false;
            $invoice->is_lunas = false;
            $invoice->is_approve = false;
            $invoice->id_status = 1;
            $invoice->kode_invoice = "INV".$id_perush.substr(time(), 5, 10);
            $invoice->save();

            // this for detail pendapatan yang di masukan
            $akun = SettingHandlingPerush::where("id_perush", $invoice->id_perush)->get()->first();
            $total = HandlingStt::getTotal($request->id_dm);
            $biaya = GroupBiaya::where('nm_biaya_grup', strtoupper('biaya handling'))->get()->first();
            $dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $request->id_dm)->get()->first();

            $pendapatan = [];
            foreach($total as $key => $value){
                $pendapatan[$key]["id_perush"] =  $id_perush;
                $pendapatan[$key]["id_perush_tj"] =  $invoice->id_perush_tj;
                $pendapatan[$key]["id_invoice"] = $invoice->id_invoice;
                $pendapatan[$key]["id_user"]= Auth::user()->id_user;
                $pendapatan[$key]["id_handling"] = $value->id_handling;
                $pendapatan[$key]["id_dm"] = $request->id_dm;
                $pendapatan[$key]["id_stt"] = null;
                $pendapatan[$key]["ac4_debit"] = null;
                $pendapatan[$key]["ac4_kredit"] = null;
                $pendapatan[$key]["nominal"] = $value->total;
                $pendapatan[$key]["id_biaya"] = 0;
                $pendapatan[$key]["dibayar"] = 0;
                $pendapatan[$key]["is_lunas"] = false;
                $pendapatan[$key]["id_biaya_grup"] = $biaya->id_biaya_grup;
                $pendapatan[$key]["kode_stt"] = null;
                $pendapatan[$key]["kode_dm"] = $dm->kode_dm;
                $pendapatan[$key]["kode_handling"] = $value->kode_handling;
                $pendapatan[$key]["created_at"] = date("Y-m-d H:i:s");
                $pendapatan[$key]["updated_at"] = date("Y-m-d H:i:s");
                $pendapatan[$key]["is_default"] = true;
            }

            InvoiceHandlingPendapatan::insert($pendapatan);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan '.$e->getMessage());
        }

        return redirect(route_redirect()."/".$invoice->id_invoice."/show")->with('success', 'Data Disimpan');
    }

    public function show($id)
    {
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $inv = InvoiceHandling::getDetail($id);
        if($inv == null){
            abort(404);
        }

        $data["bayar"] =InvoiceHandlingPendapatan::where("id_invoice", $id)->sum("dibayar");
        $data["data"] = $inv;

        $biaya = InvoiceHandlingPendapatan::getBiaya($page,$id);
        if($inv->id_status > 2 ){
            $biaya = InvoiceHandlingPendapatan::getBiayaBayar($id);
        }

        $data["biaya"] = $biaya;
        $data["akun"] = ACPerush::getList($id_perush);
        $data["ac"] = ACPerush::getKasBank($id_perush);

        return view('keuangan::invoicehandling.showinvhandling', $data);
    }

    public function showbayar($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data = InvHandlingPendapatanBayar::getBayar($id);

        $a_data = [];
        $no = 0;
        $total = 0;
        foreach($data as $key => $value){
            $total += $value->n_bayar;
            $no++;
            $a_data[$key] = "<tr><td>".$value->nm_biaya_grup."</td><td>".dateindo($value->created_at)."</td><td>".torupiah($value->n_bayar)."</td></tr>";
        }

        $a_data[$no] = "<tr><td class='text-right' colspan='2'>Total : </td><td class='text-right'><b>".torupiah($total)."</td></tr>";
        return response()->json($a_data);
    }

    public function proyeksi($id, Request $request)
    {
        $id_biaya = null;
        $id_stt = null;
        $page = 100;

        $id_perush = Session("perusahaan")["id_perush"];
        if($request->method=="POST"){
            if(isset($request->filter_stt) and $request->filter_stt!= null){
                $id_stt = $request->filter_stt;
            }

            if(isset($request->filter_stt) and $request->filter_stt!= null){
                $id_stt = $request->filter_stt;
            }
        }

        $invoice = InvoiceHandling::findOrfail($id);
        $data["data"] = BiayaHandling::getPerushBiaya($page, $id, $invoice->id_dm, $id_stt, $id_biaya);
        $data["biaya"] = BiayaHandling::getBiaya($id, $invoice->id_dm, $id_biaya);
        $data["dm"] = BiayaHandling::getHandlingDm($id_perush, $invoice->id_perush_tj);
        $data["stt"] = [];

        return view('keuangan::invoicehandling.showproyeksi', $data);
    }

    public function konfirmasibayar($id, Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];

        if(!isset($request->c_pro) and $request->c_pro == null){
            return redirect()->back()->with('error', 'Komponen Biaya Tidak dipilih');
        }

        DB::beginTransaction();
        try {
            $data = [];
            $a_bayar = [];
            $total = 0;
            foreach($request->c_pro as $key => $value){
                $pendapatan = InvoiceHandlingPendapatan::select("ac4_debit", "ac4_kredit", "nominal", "id_biaya_grup")->where("id_biaya_pend", $value)->get()->first();
                $byr = InvHandlingPendapatanBayar::where("id_biaya_pend", $value)->get();
                $group = GroupBiaya::select("nm_biaya_grup")->where("id_biaya_grup", $pendapatan->id_biaya_grup)->get()->first();
                if(count($byr) < 1){
                    return redirect()->back()->with('error', ' Komponen '.$group->nm_biaya_grup.' Belum Dibayar');
                }

                $biaya = 0;
                foreach($byr as $key1 => $value1){
                    $a_bayar[$value1->id_bayar]["ac4_debit"] = $request->ac4_k;
                    $a_bayar[$value1->id_bayar]["ac4_kredit"]  = $pendapatan->ac4_debit;
                    $a_bayar[$value1->id_bayar]["id_biaya_pend"]  = $value;
                    $a_bayar[$value1->id_bayar]["id_bayar"]  = $value1->id_bayar;

                    $total += $value1->nominal;
                    $biaya += $value1->nominal;
                }

                $data[$value]["is_lunas"] = false;
                if($biaya == $pendapatan->nominal){
                    $data[$value]["is_lunas"] = true;
                }

                $data[$value]["dibayar"] = $biaya;
                $data[$value]["id_penerima"] = Auth::user()->id_user;
                $data[$value]["id_pendapatan"] = $value;
            }

            foreach($a_bayar as $key => $value){
                $bayar["ac4_debit"] = $value["ac4_debit"];
                $bayar["ac4_kredit"] = $value["ac4_kredit"];
                $bayar["id_user"] = Auth::user()->id_user;
                InvHandlingPendapatanBayar::where("id_bayar", $value["id_bayar"])->update($bayar);
            }

            foreach($data as $key => $value){
                $n_bayar = [];
                $n_bayar["dibayar"] = $value["dibayar"];
                $n_bayar["id_penerima"] = $value["id_penerima"];
                $n_bayar["is_lunas"] = $value["is_lunas"];
                $n_bayar["id_user"] = Auth::user()->id_user;
                InvoiceHandlingPendapatan::where("id_biaya_pend", $value["id_pendapatan"])->update($n_bayar);
            }

            $sum = InvoiceHandlingPendapatan::where("id_invoice", $id)->sum("nominal");

            $a_inv = [];
            $a_inv["dibayar"] = $sum;
            if($sum == $total){
                $a_inv["is_lunas"] = true;
            }

            InvoiceHandling::where("id_invoice", $id)->update($a_inv);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pembayaran Invoice Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pembayaran Invoice Disimpan');
    }

    public function savebiaya($id, Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $validator = Validator::make($request->all(), [
            'id_biaya'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_handling_biaya,id_biaya',
            'id_dm'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_dm,id_dm',
            'nominal' => 'bail|required|numeric'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $invoice = InvoiceHandling::findOrFail($id);
        $biaya = BiayaHandling::findOrFail($request->id_biaya);

        $dm = DaftarMuat::select("kode_dm")->where("id_dm", $request->id_dm)->get()->first();
        $akun = SettingHandlingPerush::where("id_perush", $invoice->id_perush)->get()->first();

        DB::beginTransaction();
        try {

            // for invoice pendapatan
            $pend = new InvoiceHandlingPendapatan();
            $pend->id_perush =  $id_perush;
            $pend->id_perush_tj =  $invoice->id_perush_tj;
            $pend->id_invoice =$id;
            $pend->id_user = Auth::user()->id_user;
            $pend->id_handling = $biaya->id_handling;
            $pend->id_dm = $request->id_dm;
            $pend->id_stt = $biaya->id_stt;
            $pend->ac4_debit = null;
            $pend->ac4_kredit = null;
            $pend->nominal = $request->nominal;
            $pend->id_biaya = $request->id_biaya;
            $pend->dibayar = 0;
            $pend->is_lunas = false;
            $pend->id_biaya_grup = $biaya->id_biaya_grup;
            $pend->kode_stt = $biaya->kode_stt;
            $pend->kode_dm = $dm->kode_dm;
            $pend->kode_handling = $biaya->kode_handling;
            $pend->created_at = date("Y-m-d H:i:s");
            $pend->updated_at = date("Y-m-d H:i:s");
            $pend->save();

            // for invoice
            $sum = InvoiceHandlingPendapatan::where("id_invoice", $id)->sum("nominal");
            $c_total["total"] = $sum;
            InvoiceHandling::where("id_invoice", $id)->update($c_total);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Invoice Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Invoice Ditambahkan');
    }

    public function updatebiaya($id, Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $validator = Validator::make($request->all(), [
            'id_biaya'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_handling_biaya,id_biaya',
            'id_dm'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_dm,id_dm',
            'nominal' => 'bail|required|numeric'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        DB::beginTransaction();
        try {

            $id_invoice = null;
            // for invoice pendapatan
            $total["nominal"] = $request->nominal;
            $handling = InvoiceHandlingPendapatan::where("id_biaya", $id)->get()->first();
            $biaya = BiayaHandling::where("id_biaya", $id)->get()->first();
            if( $request->nominal < $biaya->nominal ){
                return redirect()->back()->with('error', 'Nominal Biaya Terlalu Kecil');
            }

            $id_invoice = $handling->id_invoice;
            $handling->nominal= $request->nominal;
            $handling->save();

            // for invoice hutang piutang
            InvoiceHandlingPendapatan::where("id_biaya", $request->id_biaya)->update($total);
            // for invoice
            $sum = InvoiceHandlingPendapatan::where("id_invoice", $id_invoice)->sum("nominal");
            $c_total["total"] = $sum;
            InvoiceHandling::where("id_invoice", $id_invoice)->update($c_total);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Invoice Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Invoice Ditambahkan');
    }

    public function deletebiaya($id)
    {
        DB::beginTransaction();
        try {

            $id_invoice = null;
            // for invoice hutang pendapatan
            $handling = InvoiceHandlingPendapatan::where("id_biaya", $id)->get()->first();
            $id_invoice = $handling->id_invoice;
            $handling->delete();

            // sum total
            $sum = InvoiceHandlingPendapatan::where("id_invoice", $id_invoice)->sum("nominal");
            $total["total"] = $sum;
            InvoiceHandling::where("id_invoice", $id_invoice)->update($total);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Invoice Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Invoice Dihapus');
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
        DB::beginTransaction();
        try {

            $inv = InvoiceHandling::findOrfail($id);

            if($inv->id_status >2){
                return redirect()->back()->with('error', 'Data Invoice Gagal Dihapus, Karena Sudah Diproses ');
            }

            InvHandlingPendapatanBayar::where("id_invoice", $id)->delete();
            InvoiceHandlingPendapatan::where("id_invoice", $id)->delete();

            $inv->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Invoice Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Invoice Dihapus');
    }

    public function kirim($id)
    {
        DB::beginTransaction();
        try {

            $cek = InvoiceHandlingPendapatan::where("id_invoice", $id)->get()->first();

            if(!$cek){
                return redirect()->back()->with('error', 'Biaya Invoice Belum Ada');
            }

            $invoice = InvoiceHandling::findOrFail($id);
            $akun = SettingHandlingPerush::where("id_perush", $invoice->id_perush)->get()->first();

            if(!$akun){
                return redirect()->back()->with('error', 'Setting Akun Handling Belum Ada');
            }

            // update for pendapatan
            $ac["ac4_debit"] =  $akun->ac4_piutang_penerima;
            $ac["ac4_kredit"] =$akun->ac4_pend_penerima;
            InvoiceHandlingPendapatan::where("id_invoice", $id)->update($ac);

            $invoice->is_approve = true;
            $invoice->id_status = $invoice->id_status+1;
            $date1 = strtotime(date("Y-m-d"));
            $date2 = strtotime("+14 day", $date1);
            $invoice->id_user = Auth::user()->id_user;
            $invoice->tgl_tagihan = date('Y-m-d', $date1);
            $invoice->tgl_jatuh_tempo = date('Y-m-d', $date2);
            $invoice->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Invoice Gagal Dikirim '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Invoice Dikirim');
    }

    public function batalkirim($id)
    {
        DB::beginTransaction();
        try {
            $data = [];
            $data["is_approve"] =  false;
            $data["id_status"]  =  1;

            InvoiceHandling::where("id_invoice", $id)->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Invoice Gagal Dibatalkan '.$e->getMessage());
        }
        return redirect()->back()->with('success', 'Data Invoice Dibatalkan');
    }

    public function CetakInvoice($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $inv = InvoiceHandling::getDetail($id);
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
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);

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
            $ttd->type_dok          = "invoicehandling";
            $ttd->id_user           = Auth::user()->id_user;
            $ttd->ttd               = $request->img;
            $ttd->level             = 1;
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
            'url'    => (url("invoicehandling/".$request->id_ref."/cetak")),
        ];
        return response()->json($data);
    }


}
