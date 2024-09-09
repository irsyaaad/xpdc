<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\Asuransi;
use Auth;
use DB;
use Modules\Keuangan\Entities\InvoiceAsuransi;
use Modules\Keuangan\Entities\DraftInvoiceAsuransi;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Operasional\Entities\CaraBayar;

class InvoiceAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {        
        return view('keuangan::invoiceindex');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $asuransiStt = Asuransi::all();
        $data["stt"] = $asuransiStt;
        $data["pelanggan"] = Perusahaan::all();
        
        return view('keuangan::invoicecreate',$data);
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
            $id                      = $this->generateId();
            
            DB::beginTransaction();
            
            $perush                  = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
            $pelanggan               = Perusahaan::findOrfail($request->id_pelanggan);
            $invoice                 = new InvoiceAsuransi();
            $invoice->kode_invoice   = $id;
            $invoice->id_perush      = $perush->id_perush;
            $invoice->tgl            = $request->tgl;
            $invoice->inv_j_tempo    = $request->inv_j_tempo;
            $invoice->id_plgn        = $request->id_pelanggan;
            $invoice->nm_pelanggan   = $pelanggan->nm_perush;
            $invoice->id_user        = Auth::user()->id_user;
            $invoice->id_status      = 1;
            // dd($invoice);
            $invoice->save();
            $id_invoice = $invoice->id_invoice;
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect()."/".$id_invoice."/show")->with('success', 'Data Disimpan');
    }

    public function generateId()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $temp      = Perusahaan::findOrfail($id_perush);
        $kode_perush = $temp->kode_perush;
        $date = date("ym");
        $data = "INV/".strtoupper($kode_perush)."/".$date."/".substr(crc32(uniqid()),-4);
        
        return $data;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $datax          = InvoiceAsuransi::with("perusahaan")->findOrfail($id);
        $temp           = DraftInvoiceAsuransi::where("id_invoice",$id)->get();
        $data["cara"]   = CaraBayar::getList();
        $data["data"]   = $datax;
        $data["detail"] = $temp;
        $data["akun"]   = ACPerush::where("is_bank",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])
        ->orWhere("is_kas",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])->get();
        
        return view('keuangan::invoiceshow',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('keuangan::edit');
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

    public function tambahasuransi(Request $request, $id)
    {
        $invoice     = InvoiceAsuransi::findOrfail($id);
        $data["data"] = Asuransi::with("pelanggan")->where("id_pelanggan", $invoice->id_plgn)->get();
        // dd($data);
        return view('keuangan::invoicedetailasuransiinvoice', $data);
    }

    public function savedraft(Request $request)
    {
        // dd($request->all());
        $loop                    = $request->stt_id;
        if($loop == null){
            return redirect()->back()->with('error', 'Data STT Tidak dipilih');
        }else{
            try {
                
                DB::beginTransaction();
                foreach ($loop as $key => $value){
                    $draft              = new DraftInvoiceAsuransi();
                    $draft->id_invoice  = $request->id_invoice;
                    $draft->id_asuransi = $value;
                    $draft->id_perush   = Session("perusahaan")["id_perush"];
                    $draft->id_user     = Auth::user()->id_user;
                    $draft->date        = date('Y-m-d');
                    $draft->save();
                }
                $this->updatedata($request->id_invoice);
                
                DB::commit();
                
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Data STT Gagal Disimpan'.$e->getMessage());
            }
            
            return redirect(route_redirect()."/".$request->id_invoice."/show")->with('success', 'Data Disimpan');
        }
        
        
    }

    public function send($id)
    {
        try {
            
            DB::beginTransaction();
            $cek = DraftInvoiceAsuransi::where("id_invoice", $id)->get()->first();
            
            if($cek==null){
                return redirect()->back()->with('error', 'Data Asuransi Invoice Kosong ');
            }
            
            $invoice                     = InvoiceAsuransi::findOrFail($id);
            $invoice->id_status          = (Int)$invoice->id_status+1;
            $invoice->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Invoice Gagal Diterbitkan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Invoice Berhasil Diterbitkan');
    }

    public function updatedata($id)
    {
        $draft = DraftInvoiceAsuransi::where("id_invoice",$id)->get();
        $total = 0;
        
        foreach ($draft as $key => $value) {
            $id_asuransi     =   $value->id_asuransi;
            $asuransi        =   Asuransi::where("id_asuransi",$id_asuransi)->get()->first();
            $total           =   $total + (int)$asuransi->nominal;
            
        }
        
        $hasil["total"] = $total;
        InvoiceAsuransi::where("id_invoice",$id)->update(
            $hasil
        );
        
    }

    public function BayarAsuransi(Request $request, $id) {
        dd($request->all(), $id);
    }

    public function CetakInvoice($id) {
        $data["invoice"]    = InvoiceAsuransi::findOrFail($id);
        $data["detail"]     = DraftInvoiceAsuransi::where("id_invoice",$id)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();

        $pdf = \PDF::loadview("keuangan::invoicecetak-invoiceasuransi",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
