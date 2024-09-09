<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Invoice;
use Modules\Keuangan\Entities\DraftSttInvoice;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\CaraBayar;
use Auth;
use DB;
use App\Models\Perusahaan;
use App\Models\Pelanggan;
use App\Models\CronJob;
use Modules\Keuangan\Entities\GenIdBayar;
use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\DrafSttInvoice;
use Exception;
use Modules\Keuangan\Http\Requests\PembayaranRequest;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page!=null?$request->page:1;
        $perpage = $request->shareselect!=null?$request->shareselect:50;
        $id_perush = Session("perusahaan")["id_perush"];
        $id_invoice = $request->id_invoice;
        $id_pelanggan = $request->id_pelanggan;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $id_stt = $request->id_stt;
        $status = $request->status!=null?$request->status:0;
        $data["data"] = Invoice::getInvoice($page, $perpage, $id_perush, $id_invoice, $id_pelanggan, $dr_tgl, $sp_tgl, $id_stt, $status);
        $data["cara"]    = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["akun"]   = ACPerush::where("is_bank",true)
        ->where("id_perush", $id_perush)
        ->orWhere("is_kas",true)
        ->where("id_perush", $id_perush)->get();
        
        $filter = array(
            "id_perush"=> $id_perush, 
            "pelanggan"=> $id_pelanggan, 
            "invoice"=>[],
            "id_stt" => $id_stt, 
            "dr_tgl"=>$dr_tgl, 
            "sp_tgl"=>$sp_tgl,
            "status"=>$status,
            "page" => $perpage
        );
        
        $data["filter"] = $filter;
        
        return view('keuangan::invoice.indeks', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["stt"] = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->where("id_perush_asal", Session("perusahaan")["id_perush"])->paginate(5);
        $data["pelanggan"] = Pelanggan::where("id_perush", Session("perusahaan")["id_perush"])->get();
        
        return view('keuangan::invoice.createinvoice',$data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $id_invoice = null;
        try {
            $id                      = $this->generateId();
            
            DB::beginTransaction();
            
            $perush = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
            //dd($perush);
            $pelanggan  = Pelanggan::findOrfail($request->id_pelanggan);
            $invoice                 = new Invoice();
            $invoice->kode_invoice   = $id;
            $invoice->id_perush      = $perush->id_perush;
            $invoice->tgl            = $request->tgl;
            $invoice->inv_j_tempo    = $request->inv_j_tempo;
            $invoice->id_plgn        = $request->id_pelanggan;
            $invoice->nm_pelanggan   = $pelanggan->nm_pelanggan;
            $invoice->kontak         = $request->kontak;
            $invoice->hp             = $request->hp;
            $invoice->id_user        = Auth::user()->id_user;
            $invoice->id_status      = 1;
            
            $invoice->save();
            $id_invoice = $invoice->id_invoice;
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect()."/".$id_invoice."/show")->with('success', 'Data Disimpan');
        
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id, Request $request)
    {
        $datax  = Invoice::with("perusahaan","pelanggan","status")->findOrfail($id);
        $temp = DraftSttInvoice::getDetail($id);
        $data["cara"] = CaraBayar::getList();
        $data["data"] = $datax;
        $data["detail"] = $temp;
        $data["akun"]   = ACPerush::where("is_bank",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])
        ->orWhere("is_kas",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])->get();
        
        $id_invoice = $request->id_invoice;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $status = $request->status!=null?$request->status:0;
        $page = $request->page;
        
        $data["filter"] = array(
            "dr_tgl"=>$dr_tgl, 
            "sp_tgl"=>$sp_tgl,
            "status"=>$status,
            "page" => $page
        );
        
        return view('keuangan::invoice.show',$data);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["data"] = Invoice::findOrFail($id);
        $data["stt"] = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->where("id_perush_asal", Session("perusahaan")["id_perush"])->paginate(5);
        $data["pelanggan"] = Pelanggan::where("id_perush", Session("perusahaan")["id_perush"])->get();
        
        return view('keuangan::invoice.createinvoice',$data);
    }
    
    public function tambahstt(Request $request, $id)
    {
        $inv = Invoice::findOrFail($id);
        // dd($inv->id_perush, $inv->id_plgn);
        $data["data"] = SttModel::forInvoice($inv->id_perush, $inv->id_plgn);
        
        return view('keuangan::invoice.detailsttinvoice', $data);
    }
    
    public function savedraft(Request $request)
    {
        $loop                    = $request->stt_id;
        if($loop == null){
            return redirect()->back()->with('error', 'Data STT Tidak dipilih');
        }else{
            try {
                
                DB::beginTransaction();
                $cron=[];
                foreach ($loop as $key => $value){
                    $cron[$key]["id_cron"] = time();
                    $cron[$key]["tipe"] = 'INV';
                    $cron[$key]["id_stt"] = $value;
                    $cron[$key]["status"] = 1;
                    $cron[$key]["info"] = "INVOICE tagihan untuk Stt ".$value." dibuat";
                    $cron[$key]["created_at"] = date('Y-m-d h:i:s');
                    $cron[$key]["updated_at"] = date('Y-m-y h:i:s');
                    $draft              = new DraftSttInvoice();
                    $draft->id_invoice  = $request->id_invoice;
                    $draft->id_stt      = $value;
                    $draft->id_perush   = Session("perusahaan")["id_perush"];
                    $draft->id_user     = Auth::user()->id_user;
                    $draft->save();
                    $key += 1;
                }
                
                CronJob::insert($cron);
                $this->updatedata($request->id_invoice);
                
                DB::commit();
                
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Data STT Gagal Disimpan'.$e->getMessage());
            }
            
            return redirect(route_redirect()."/".$request->id_invoice."/show")->with('success', 'Data Disimpan');
        }
        
        
    }
    
    public function hapusdraft(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $draft                     = DraftSttInvoice::findorFail($id);
            $draft->delete();
            $this->updatedata($request->id_invoice);
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Invoice Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Invoice Berhasil Dihapus ');
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        //dd($request);
        try {
            
            DB::beginTransaction();
            
            $pelanggan  = Pelanggan::findOrfail($request->id_pelanggan);
            $invoice    = Invoice::findOrFail($id);
            $invoice->tgl            = $request->tgl;
            $invoice->inv_j_tempo    = $request->inv_j_tempo;
            $invoice->id_plgn        = $request->id_pelanggan;
            $invoice->kontak         = $request->kontak;
            $invoice->hp             = $request->hp;
            $invoice->nm_pelanggan   = $pelanggan->nm_pelanggan;
            $invoice->id_user        = Auth::user()->id_user;
            $invoice->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Invoice Disimpan');
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
            
            $invoice                     = Invoice::findOrFail($id);
            if($invoice->id_status!="1"){
                return redirect()->back()->with('error', 'Data Invoice Sudah Terbit, Tidak Bisa Dihapus');
            }
            
            // delete draft
            DraftSttInvoice::where("id_invoice",$id)->delete();
            
            // delete invoice
            $invoice->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Dihapus '.$e->getMessage());
        }
        return redirect(route_redirect())->with('success', 'Data Invoice Dihapus');
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
    
    public function updatedata($id)
    {
        $draft = DraftSttInvoice::where("id_invoice",$id)->get();
        $total = 0;
        
        foreach ($draft as $key => $value) {
            $id_stt     =   $value->id_stt;
            $stt        =   SttModel::where("id_stt",$id_stt)->get()->first();
            $total      =   $total + (int)$stt->c_total;
            
        }
        
        $hasil["total"] = $total;
        Invoice::where("id_invoice",$id)->update(
            $hasil
        );
        
    }
    
    public function generateDraft($id_invoice,$id_stt)
    {
        $data = [];
        $id_perush = Session("perusahaan")["id_perush"];
        $stt_id = substr($id_stt, -3);
        $data["id_draft"] = $id_invoice."-".$stt_id;
        
        return $data;
    }
    
    public function autosave($id)
    {
        $stt = SttModel::findOrfail($id);
        $ldate = date('Y-m-d');
        
        try {
            $id_invoice              = $this->generateId()["id_invoice"];
            DB::beginTransaction();
            
            $perush = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
            
            $pelanggan  = Pelanggan::findOrfail($stt->id_plgn);
            //dd($perush);
            $invoice                 = new Invoice();
            
            $invoice->id_invoice     = strtoupper($id_invoice);
            $invoice->id_perush      = $stt->id_perush_asal;
            $invoice->tgl            = $ldate;
            $invoice->inv_j_tempo    = $stt->tgl_tempo;
            $invoice->id_plgn        = $stt->id_plgn;
            $invoice->nm_pelanggan   = $pelanggan->nm_pelanggan;
            $invoice->kontak         = $pelanggan->nm_kontak;
            $invoice->hp             = $pelanggan->no_kontak;
            $invoice->total          = $stt->c_total;
            $invoice->id_user        = Auth::user()->id_user;
            $invoice->id_status      = 1;
            
            //dd($invoice);
            $draft              = new DraftSttInvoice();
            $draft->id_invoice  = strtoupper($id_invoice);
            $draft->id_stt      = $id;
            $draft->id_draft    = $this->generateDraft($id_invoice,$id)["id_draft"];
            $draft->save();
            $invoice->save();
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan '.$e->getMessage());
        }
        
    }
    
    public function send($id)
    {
        try {
            
            DB::beginTransaction();
            $cek = DraftSttInvoice::where("id_invoice", $id)->get()->first();
            
            if($cek==null){
                return redirect()->back()->with('error', 'Data STT Invoice Kosong ');
            }
            
            $invoice                     = Invoice::findOrFail($id);
            $invoice->id_status          = (Int)$invoice->id_status+1;
            $invoice->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Diterbitkan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Invoice Berhasil Diterbitkan');
    }
    
    public function batal($id)
    {
        try {
            
            DB::beginTransaction();
            $invoice                     = Invoice::findOrFail($id);
            $invoice->id_status          = 1;
            $invoice->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal batalkan '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Invoice Berhasil Di batalkan');
    }
    
    public function bayarall($id, PembayaranRequest $request){
        $id_perush =  Session("perusahaan")["id_perush"];
        $sum = Invoice::getSumBayar($id);
        $sum2 = $sum[0]->bayar+$request->n_bayar;

        if($sum2>$sum[0]->total){
            return redirect()->back()->with('error', 'Nominal bayar tidak boleh lebih besar ')->withInput($request->all());
        }
        
        DB::beginTransaction();

        try {
            
            $stt = Invoice::getSttTotal($id, $id_perush);
            
            $a_bayar = [];
            $total = $request->n_bayar;
            foreach($stt as $key => $value){
                $sisa = $value->c_total - $value->bayar;
                $bayar = 0;
                if($total<$sisa){
                    $bayar = $total;
                }elseif($total>$sisa){
                    $bayar = $sisa;
                }elseif($total==$sisa){
                    $bayar = $request->n_bayar;
                }
                
                $a_bayar[$value->id_stt]["id_stt"] = $value->id_stt;
                $a_bayar[$value->id_stt]["tgl"] = $request->tgl_bayar;
                $a_bayar[$value->id_stt]["info"] = $request->info;
                $a_bayar[$value->id_stt]["no_bayar"] = $request->referensi;
                $a_bayar[$value->id_stt]["id_plgn"] = $value->id_plgn;
                $a_bayar[$value->id_stt]["id_perush"] =$id_perush;
                $a_bayar[$value->id_stt]["id_user"] = Auth::user()->id_user;
                $a_bayar[$value->id_stt]["nm_bayar"] = $request->nm_bayar!=null?$request->nm_bayar:$value->pengirim_nm;
                $a_bayar[$value->id_stt]["ac4_k"] = $value->c_ac4_piut;
                $a_bayar[$value->id_stt]["is_aktif"] = true;
                $a_bayar[$value->id_stt]["ac4_d"] = $request->ac4_d;
                $a_bayar[$value->id_stt]["id_cr_byr"] = $request->id_cr_byr;
                $a_bayar[$value->id_stt]["ac4_d"] = $request->ac4_d;
                $a_bayar[$value->id_stt]["n_bayar"] = $bayar;
                $a_bayar[$value->id_stt]["created_at"] = date("Y-m-d H:i:s");
                $a_bayar[$value->id_stt]["updated_at"] = date("Y-m-d H:i:s");

                if($total>0){
                    Pembayaran::Insert($a_bayar[$value->id_stt]);
                    $id_update = DB::getPdo()->lastInsertId();
                    $update = [];
                    $update["no_kwitansi"] = $id_update."/".$id_perush."/KW".date("m")."/".date("Y");
                    Pembayaran::where("id_order_pay", $id_update)->update($update);
                }

                $total -= $value->c_total;
            }

            $stt = Invoice::getSttPiutang($id, $id_perush);
            foreach($stt as $key => $value){
                $a_data["is_lunas"] = false;
                $a_data["x_n_bayar"] = $value->bayar;
                $a_data["x_n_piut"] = $value->c_total-$value->bayar;
                if($value->c_total==$value->bayar){
                    $data["is_lunas"]   =   true;
                }
                SttModel::where("id_stt", $value->id_stt)->update($a_data);
            }

            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pembayaran Berhasil');
    }
    
    public function bayarstt(Request $request)
    {
        try {
            
            DB::beginTransaction();
            $bayar                  = new Pembayaran();
            $bayar->id_stt          = $request->id_stt;
            $bayar->n_bayar         = $request->n_bayar;
            $bayar->tgl             = $request->tgl_bayar;
            $bayar->info            = $request->info;
            $bayar->no_bayar        = $request->referensi;
            
            $bayar->tgl_bg          = $request->tgl_bg;
            $bayar->id_plgn         = $request->id_plgn;
            $bayar->id_perush       = Session("perusahaan")["id_perush"];
            $bayar->id_user         = Auth::user()->id_user;
            $bayar->is_aktif        = true;
            
            // for ac kredit
            $stt                    = SttModel::findOrfail($bayar->id_stt);
            $bayar->ac4_k           = $stt->c_ac4_piut;
            $bayar->nm_bayar        = $stt->pengirim_nm;
            
            if(isset($request->nm_bayar) && $request->nm_bayar!=null){
                $bayar->nm_bayar     = $request->nm_bayar;
            }
            
            $bayar->ac4_d           = $request->ac4_d;
            $bayar->id_cr_byr       = $request->id_cr_byr;
            
            if(isset($request->id_cr_byr) && $request->id_cr_byr!="1"){
                $bayar->no_bayar = $request->no_bayar;
                $bayar->nm_bayar = $request->nm_bayar;
                $bayar->tgl_bg = $request->tgl_bg;
                $bayar->id_plgn = $request->id_plgn;
            }
            
            $data = [];
            $data["x_n_bayar"]      =   $stt->x_n_bayar+$bayar->n_bayar;
            if ($stt->x_n_piut > 0) {
                $data["x_n_piut"]   =   $stt->x_n_piut-$bayar->n_bayar;
            }
            
            $data["is_bayar"]       =   true;
            if (isset($stt->x_n_piut) and $stt->x_n_piut != 0) {
                if (($stt->x_n_piut - $bayar->n_bayar) == 0) {
                    $data["is_lunas"]   =   true;
                }
                
            }
            
            SttModel::where("id_stt", $request->id_stt)->update($data);
            $bayar->save();
            
            $id_update = DB::getPdo()->lastInsertId();
            $update = [];
            $update["no_kwitansi"] = $id_update."/".$bayar->id_perush."/KW".date("m")."/".date("Y");
            Pembayaran::where("id_order_pay", $id_update)->update($update);
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->with('error', 'Pembayaran gagal'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pembayaran Berhasil');
    }
    
    public function bayar(Request $request)
    {
        $stt    = [];
        $bayar  = [];
        
        try {
            $n_bayar     = $request->n_bayar;
            $id_stt      = $request->id_stt;
            $kurang      = $request->kurang;
            
            for ($i=0; $i <count($id_stt) ; $i++) {
                $newdata = SttModel::findOrFail($id_stt[$i]);
                if ($kurang[$i]>0 and $n_bayar >= $kurang[$i]) {
                    $bayar[$i] = [
                        'id_stt'        => $id_stt[$i],
                        'n_bayar'       => $kurang[$i],
                        'tgl'           => $request->tgl,
                        'info'          => $request->info,
                        'no_bayar'      => $request->no_bayar,
                        'tgl_bg'        => $request->tgl_bg,
                        'id_plgn'       => $request->id_plgn,
                        'id_perush'     => Session("perusahaan")["id_perush"],
                        'id_user'       => Auth::user()->id_user,
                        'ac4_k'         => $newdata->c_ac4_piut,
                        'ac4_d'         => $request->id_ac,
                        'id_cr_byr'     => $request->id_cr_byr,
                        'is_aktif'      => true,
                    ];
                    
                    $data                       =   [];
                    if(isset($newdata->x_n_bayar) and $newdata->x_n_bayar > 0){
                        $data["x_n_bayar"]          =   $newdata->x_n_bayar+$kurang[$i];
                    }else{
                        $data["x_n_bayar"]          =   $kurang[$i];
                    }
                    if(isset($newdata->x_n_piut) and $newdata->x_n_piut > 0){
                        $data["x_n_piut"]           =   $newdata->x_n_piut-$kurang[$i];
                    } else {
                        $data["x_n_piut"]           =   0;
                    }
                    $data["is_bayar"]           =   true;
                    
                    if (isset($newdata->x_n_piut) and ($newdata->x_n_piut-$kurang[$i]) == 0) {
                        $data["is_lunas"]           =   true;
                    }else if($newdata->c_total == $kurang[$i]){
                        $data["is_lunas"]           =   true;
                    }
                    
                    SttModel::where("id_stt", $id_stt[$i])->update($data);
                    $n_bayar-=$kurang[$i];
                    
                } else {
                    $bayar[$i] = [
                        'id_stt'        => $id_stt[$i],
                        'n_bayar'       => $n_bayar,
                        'tgl'           => $request->tgl,
                        'info'          => $request->info,
                        'no_bayar'      => $request->no_bayar,
                        'tgl_bg'        => $request->tgl_bg,
                        'id_plgn'       => $request->id_plgn,
                        'id_perush'     => Session("perusahaan")["id_perush"],
                        'id_user'       => Auth::user()->id_user,
                        'ac4_k'         => $newdata->c_ac4_piut,
                        'ac4_d'         => $request->id_ac,
                        'id_cr_byr'     => $request->id_cr_byr,
                        'is_aktif'      => true,
                    ];
                    
                    $data                       =   [];
                    if(isset($newdata->x_n_bayar) and $newdata->x_n_bayar > 0){
                        $data["x_n_bayar"]          =   $newdata->x_n_bayar+$n_bayar;
                    }else{
                        $data["x_n_bayar"]          =   $n_bayar;
                    }
                    if(isset($newdata->x_n_piut) and $newdata->x_n_piut > 0){
                        $data["x_n_piut"]           =   $newdata->x_n_piut-$n_bayar;
                    } else {
                        $data["x_n_piut"]           =   $newdata->c_total-$n_bayar;
                    }
                    $data["is_bayar"]               =   true;
                    
                    if ($data["x_n_piut"] == 0) {
                        $data["is_lunas"]           =   true;
                    }
                    
                    SttModel::where("id_stt", $id_stt[$i])->update($data);
                    $n_bayar-=$kurang[$i];
                }
                
                if ($n_bayar <= 0) {
                    break;
                }
            }
            
        } catch (Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', 'Data Invoice Dibayar '.$e->getMessage());
        }
        
        DB::commit();
        
        return redirect(route_redirect()."/".$request->id_invoice."/show")->with('success', 'Data Invoice Dibayar');
    }
    
    public function CetakInvoice($id)
    {
        $data["invoice"] = Invoice::with("pelanggan","stt","status","user")->where("id_invoice", $id)->get()->first();
        $temp = DraftSttInvoice::where("id_invoice",$id)->get();
        $sttx = DraftSttInvoice::getSttInvoice($id);
        $data["stt"] = $sttx;
        $data["perusahaan"] = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();

        $pdf = \PDF::loadview("keuangan::invoice.new-cetak2",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
        
        return $pdf->stream();
    }
    
    public function CetakInvoice1($id)
    {
        $data["invoice"] = Invoice::with("pelanggan","stt","status","user")->where("id_invoice", $id)->get()->first();
        $temp = DraftSttInvoice::where("id_invoice",$id)->get();
        $datastt = [];
        foreach ($temp as $key => $value) {
            $datastt[$value->id_stt] =  SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->where("id_stt",$value->id_stt)->get()->first();
        }
        //$data["bank"] = BankPerush::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["stt"] = $datastt;
        $data["perusahaan"] = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        
        return view('keuangan::invoice.cetak',$data);
    }
    
    public function setppn(Request $request)
    {
        // dd($request->request);
        $id_stt         = $request->stt;
        $stt            = SttModel::findOrFail($id_stt);
        $n_hrg_bruto    = $stt->n_hrg_bruto;
        $n_asuransi     = $stt->n_asuransi;
        $n_diskon       = $stt->n_diskon;
        $n_ppn          = $request->n_ppn;
        
        $total          = $n_hrg_bruto+$n_asuransi+$n_ppn-$n_diskon;
        
        try {
            DB::beginTransaction();
            
            $data['n_ppn']  = $n_ppn;
            $data['c_total']= $total;
            
            // dd($stt->c_total,$stt->n_ppn,$data);
            SttModel::where("id_stt", $id_stt)->update($data);
            $this->updatedata($request->id_invoice);
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Set PPN Gagal '.$e->getMessage());
        }
        
        return redirect(route_redirect()."/".$request->id_invoice."/show")->with('success', 'Set PPN Berhasil');
    }
    
    
}
