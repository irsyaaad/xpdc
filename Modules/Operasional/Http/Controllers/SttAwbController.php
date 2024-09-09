<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Http\Requests\SttRequest;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\GenerateStt;
use App\Models\RoleUser;
use DB;
use Auth;
use App\Models\Perusahaan;
use App\Models\Layanan;
use App\Models\Tarif;
use App\Models\Wilayah;
use Modules\Operasional\Entities\Packing;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DetailStt;
use Session;
use App\Libraries\GoogleAuthenticator;
use App\Http\Requests\AuthBoronganRequest;
use DataTables;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Keuangan\Entities\SettingLayananPerush;
use App\Models\Pelanggan;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Asuransi;
use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Http\Controllers\PembayaranController;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\SettingLimitPiutang;
use App\Models\Authenticator;
use App\Models\Grouppelanggan;
use App\Models\CronJob;

class SttAwbController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;
        if(isset($request->page) and $request->page != null) {
            $page = $request->page;
        }
        if(isset($request->shareselect) and $request->shareselect != null) {
            $perpage = $request->shareselect;
        }

        $id_dm = $request->filterdm;
        $no_awb= $request->filterawb;
        $id_asal = $request->filterasal;
        $id_tujuan = $request->filtertujuan;
        $id_status = $request->filterstatusstt;
        $id_layanan = $request->filterlayanan;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;

        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SttModel::getSttAwb($page, $perpage, $id_perush, null, $id_dm, $no_awb, $id_asal , $id_tujuan, $id_status, $id_layanan, $dr_tgl, $sp_tgl);
        $data["layanan"]    = Layanan::select("id_layanan", "nm_layanan")->get(); 
        $data["status"]    = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "asc")->get(); 
        $id_dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $id_dm)->get()->first();
        if($no_awb != null){
            $no_awb = SttModel::getAwb($no_awb, $id_perush);
            $no_awb = $no_awb[0];
        }
        $id_asal = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_asal)->get()->first();
        $id_tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_tujuan)->get()->first();
        
        $data["filter"] = array("id_dm" => $id_dm, "no_awb" => $no_awb, "id_asal" => $id_asal, "page" => $perpage, "id_tujuan" => 
                            $id_tujuan, "id_layanan" => $id_layanan, "id_status" => $id_status,
                            "dr_tgl" => $dr_tgl, "sp_tgl" => $sp_tgl);
        
        return view('operasional::sttawb', $data);
    }

    public function getSttAwb(Request $request)
    {
        $term = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = SttModel::getSelectAwb($term, $id_perush);

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_awb, 'value' => strtoupper($value->no_awb)];
        }

        return response()->json($results);
    }
    
    public function getDmAwb(Request $request)
    {   
        $term = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = DaftarMuat::select("id_dm", "kode_dm")->where("id_perush_tj", $id_perush)->where("kode_dm", 'ILIKE', '%' . $term . '%')->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }
        
        return response()->json($results);
    }

    public function updatestatus(Request $request)
    {
        if($request->c_pro == null){
            return redirect()->back()->with('error', 'Stt Tidak dipilih');
        }

        try{
            $id_perush = Session("perusahaan")["id_perush"];
            foreach($request->c_pro as $key => $value){
                $stt = SttModel::select("id_stt", "no_awb", "kode_stt", "id_status")->where("id_stt", $value)->get()->first();
                $sttawb = SttModel::select("id_stt", "no_awb", "kode_stt", "id_status")->where("kode_stt", $stt->no_awb)->get()->first();
                
                // get last history awb
                $historyawb = HistoryStt::select("no_status", "id_stt", "id_status", "nm_status", "place", "created_at", "id_perush", "id_wil", "keterangan")->where("id_stt", $sttawb->id_stt)->orderby("no_status", "desc")->get()->first();
                
                if($historyawb == null){
                    return redirect()->back()->with('error', 'History Stt '.$sttawb->kode_stt." Terakhir Tidak Update");
                }
                $history = HistoryStt::where("id_stt", $value)->where("id_perush", $historyawb->id_perush)->where("id_status", $historyawb->id_status)->orderBy("no_status", "asc")->get()->first();
                if($history == null){
                    $status = $historyawb->id_status;
                    $history = HistoryStt::where("id_stt", $value)->where("id_status", $status)->orderBy("no_status", "desc")->get()->first();
                }

                $history = HistoryStt::where("id_stt", $value)->where("no_status", ">", $history->no_status)->get();

                $hs_awb = [];
                $cron_hs = [];
                $no = $historyawb->no_status;
                foreach($history as $key1 => $value1){
                    $status = [];
                    $status["id_status"] = $value1->id_status;
                    SttModel::where("id_stt", $historyawb->id_stt)->update($status);
                    $status = StatusStt::select("nm_alias")->where("id_ord_stt_stat", $value1->id_status)->get()->first();
                    $wilayah = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $value1->id_wil)->first();
                    $no +=1;
                    $hs_awb[$key1]["id_stt"] = $historyawb->id_stt;
                    $hs_awb[$key1]["id_status"] = $value1->id_status;
                    $hs_awb[$key1]["no_status"] = $no;
                    $hs_awb[$key1]["id_user"] = $value1->id_user;
                    $hs_awb[$key1]["place"] = $value1->place;
                    $hs_awb[$key1]["keterangan"] = $status->nm_alias." ( ".$wilayah->id_wil." - ".$wilayah->nama_wil." )";
                    $hs_awb[$key1]["nm_user"] = $value1->nm_user;
                    $hs_awb[$key1]["nm_pengirim"] = $value1->nm_pengirim;
                    $hs_awb[$key1]["nm_status"] = $status->nm_alias;
                    $hs_awb[$key1]["id_wil"] = $wilayah->id_wil;
                    $hs_awb[$key1]["id_perush"] = $id_perush;
                    $hs_awb[$key1]["id_sopir"] = $value1->id_sopir;
                    $hs_awb[$key1]["nm_sopir"] = $value1->nm_sopir;
                    $hs_awb[$key1]["nm_penerima"] = $value1->nm_penerima;
                    $hs_awb[$key1]["gambar1"] = $value1->gambar1;
                    $hs_awb[$key1]["gambar2"] = $value1->gambar2;
                    $hs_awb[$key1]["created_at"] = $value1->created_at;
                    $hs_awb[$key1]["updated_at"] = $value1->updated_at;

                    $cron_hs[$key1]["tipe"] = "stt";
                    $cron_hs[$key1]["id_wil"] = $wilayah->id_wil;
                    $cron_hs[$key1]["status"] = $value1->id_status;
                    $cron_hs[$key1]["place"] =  $value1->place;
                    $cron_hs[$key1]["info"] = $value1->keterangan;
                    $cron_hs[$key1]["id_user"] = $value1->id_user;
                    $cron_hs[$key1]["id_stt"] = $historyawb->id_stt;
                    $cron_hs[$key1]["id_dm"] = null;
                    $cron_hs[$key1]["status"] = "1";
                    $cron_hs[$key1]["created_at"] = $value1->created_at;
                    $cron_hs[$key1]["updated_at"] = $value1->updated_at;
                }
                
                HistoryStt::insert($hs_awb);
                CronJob::insert($cron_hs);
            }
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Gagal Update Status'.$e->getMessage());
        }   
        
        return redirect()->back()->with('success', 'Berhasil Update Status AWB');
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        return view('operasional::create');
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
        return view('operasional::show');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        return view('operasional::edit');
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
}
