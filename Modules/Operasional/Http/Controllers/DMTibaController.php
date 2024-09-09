<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Http\Requests\DMTibaRequest;
use Auth;
use DB;
use Exception;
use App\Models\Layanan;
use App\Models\Perusahaan;
use App\Models\Pelanggan;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\SttDm;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Entities\DMTiba;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Http\Requests\UpdateStatusRequest;
use Session;
use App\Http\Controllers\EmailController;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Entities\Packing;
use Modules\Operasional\Entities\CaraBayar;
use App\Models\Tarif;
use App\Models\CronJob;
use App\Models\Wilayah;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DetailStt;
use App\Libraries\PHPGangsta_GoogleAuthenticator;
use App\Http\Requests\AuthBoronganRequest;
use DataTables;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Keuangan\Entities\SettingGroupLayanan;
use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Http\Controllers\PembayaranController;
use Modules\Operasional\Http\Controllers\SttController;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\SettingLimitPiutang;
use Modules\Operasional\Http\Requests\SttRequest;
use Modules\Operasional\Entities\GenerateStt;
use Validator;
use Modules\Keuangan\Entities\SettingLayananPerush;
use Modules\Operasional\Entities\Armada;
use Modules\Kepegawaian\Entities\Marketing;

class DMTibaController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    protected $EmailController;
    protected $pembayaran;
    protected $sttid;
    public function __construct(EmailController $EmailController, PembayaranController $pembayaran, SttController $stt)
    {
        $this->EmailController = $EmailController;
        $this->pembayaran = $pembayaran;
        $this->sttid = $stt;
    }

    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;

        if(isset($request->shareselect)){
            $perpage = $request->shareselect;
        }

        if(isset($request->page)){
            $page = $request->page;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $id_perush_dr = $request->id_perush_dr;
        $id_layanan = $request->id_layanan;
        $id_status = $request->id_status;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $id_dm = $request->id_dm;
        $is_tiba = $request->is_tiba;

        $id_perush = Session("perusahaan")["id_perush"];
        $dm = DaftarMuat::getDmTiba($page, $perpage, $id_perush, $id_perush_dr, $id_dm, $id_layanan,$tgl_awal, $tgl_akhir, $id_status, $is_tiba);
        $data["data"] = $dm;
        $data["layanan"] = Layanan::getLayanan();
        $data["perusahaan"] = Perusahaan::getPerusahaan();
        $data["status"] = StatusDM::getList(1);

        $id_dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $id_dm)->get()->first();

        $filter = array("page"=>$perpage, "id_dm" => $id_dm, "id_perush" => $id_perush, "id_perush_dr"=> $id_perush_dr,"id_layanan" => $id_layanan, "id_status" => $id_status, "tgl_awal" => $tgl_awal, "tgl_akhir"=>$tgl_akhir, "is_tiba"=>$is_tiba);
        $data["filter"] = $filter;

        return view('operasional::daftarmuat.dmtiba', $data);
    }
    /**
    * Show the form for creating a new resource.
    * @return Response
    */

    public function cetaktally($id)
    {
        $data["dm"] = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"] = SttModel::getDM($id)->get();
        $data["perusahaan"] = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"] = $id;

        return view('operasional::daftarmuat.cetaktally', $data);
    }

    public function create()
    {
        abort(404);
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(DMTibaRequest $request)
    {
        abort(404);
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;
        $data["status"] = StatusDM::getList();
        $data["detail"] = SttModel::getSttDM($id);
        $data["sttstat"] = StatusStt::getList();
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $data["bumum"] = $bumum;
        $data["stt"] = SttDm::getStt($id);
        $data["group"] = SettingBiayaPerush::DataHppPerush($dm->id_perush_dr);

        return view('operasional::daftarmuat.showdm', $data);
    }

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
    public function update(DMTibaRequest $request, $id)
    {
        abort(404);
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

    public function updatestt(UpdateStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $wilayah = Wilayah::findOrfail($request->id_kota_stt);
            $id_perush = Session("perusahaan")["id_perush"];
            $stt = SttModel::findOrFail($id);

            $status = [];
            $stt_stat = StatusStt::findOrFail($request->id_status2);
            $status["id_status"] = $stt_stat->id_ord_stt_stat;
            $status["tgl_update"] = $request->tgl_update;
            if($stt_stat->id_ord_stt_stat=="3"){
                $status["tgl_keluar"] = date("Y-m-d");
            }

            SttModel::where("id_stt", $stt->id_stt)->update($status);

            // add history status
            $hs = new HistoryStt();
            $hs->id_stt = $stt->id_stt;
            $hs->id_status = $status["id_status"];
            $hs->id_user    = Auth::user()->id_user;
            $hs->keterangan = $request->keterangan;
            $hs->place    = $wilayah->nama_wil;
            $hs->keterangan = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$hs->keterangan;
            $hs->nm_user    = Auth::user()->nm_user;
            $hs->nm_pengirim = $stt->pengirim_nm;
            $hs->nm_status  = $stt_stat->nm_ord_stt_stat;
            $hs->id_wil         = $wilayah->id_wil;
            $hs->id_perush      = Session("perusahaan")["id_perush"];
            $hs->tgl_update         = $request->tgl_update;

            $a_cron = [];
            $a_cron["tipe"] = "stt";
            $a_cron["id_wil"] = $hs->id_wil;
            $a_cron["status"] = $hs->id_status;
            $a_cron["place"] = $hs->place;
            $a_cron["info"] = $hs->keterangan;
            $a_cron["id_user"] = Auth::user()->id_user;
            $a_cron["id_stt"] = $hs->id_stt;
            $a_cron["id_dm"] = $id;
            $a_cron["status"] = "1";

            CronJob::insert($a_cron);

            $hs->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }
    public function updatestt2(UpdateStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $wilayah = Wilayah::findOrfail($request->id_kota_stt);
            $id_perush = Session("perusahaan")["id_perush"];
            $stt = SttModel::findOrFail($id);

            $status = [];
            $stt_stat = StatusStt::where("id_ord_stt_stat", ">", $stt->id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
            if($request->id_status){
                $stt_stat = $request->id_status;
            }
            $status["id_status"] = $stt_stat->id_ord_stt_stat;

            if($stt_stat->id_ord_stt_stat=="3"){
                $status["tgl_keluar"] = date("Y-m-d");
            }

            SttModel::where("id_stt", $stt->id_stt)->update($status);

            $d_stt = HistoryStt::where("id_stt", $stt->id_stt)->orderBy("no_status", "desc")->get()->first();
            $no = 1;
            if($d_stt != null and $d_stt->no_status != null){
                $no = $d_stt->no_status+1;
            }

            // add history status
            $hs = new HistoryStt();
            $hs->id_stt = $stt->id_stt;
            $hs->id_status = $status["id_status"];
            $hs->no_status = $no;
            $hs->id_user    = Auth::user()->id_user;
            $hs->keterangan = $request->keterangan;
            $hs->place    = $wilayah->nama_wil;
            $hs->keterangan = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$hs->keterangan;
            $hs->nm_user    = Auth::user()->nm_user;
            $hs->nm_pengirim = $stt->pengirim_nm;
            $hs->nm_status  = $stt_stat->nm_ord_stt_stat;
            $hs->id_wil         = $wilayah->id_wil;
            $hs->id_perush      = Session("perusahaan")["id_perush"];

            $a_cron = [];
            $a_cron["tipe"] = "stt";
            $a_cron["id_wil"] = $hs->id_wil;
            $a_cron["status"] = $hs->id_status;
            $a_cron["place"] = $hs->place;
            $a_cron["info"] = $hs->keterangan;
            $a_cron["id_user"] = Auth::user()->id_user;
            $a_cron["id_stt"] = $hs->id_stt;
            $a_cron["id_dm"] = $id;
            $a_cron["status"] = "1";

            CronJob::insert($a_cron);

            $hs->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }

    public function updateStatusDmVen(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $stt = DaftarMuat::getTotalKoli($id);

            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            if(isset($request->id_kota) and $request->id_kota != null){
                $wilayah = Wilayah::findOrfail($request->id_kota);
            }
            $id_stt = [];
            if ($stt==null) {
                return redirect()->back()->with('error', 'Stt Tidak Ditemukan ');
            }else{

                if (isset($dm->id_kapal)) {
                    $kapal = Kapal::findOrFail($dm->id_kapal);
                }else{
                    $kapal = null;
                }

                $status_dm = $request->id_status;
                $a_cron = [];
                $stt_awb = [];
                $cron_hs = [];

                if($status_dm->id_status!=6){
                    foreach ($stt as $key => $value) {
                        $status = [];
                        $id_stt[$key] = $value->id_stt;
                        $stt_stat = StatusStt::where("id_ord_stt_stat", ">", $value->id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
                        $status["id_status"] = $stt_stat->id_ord_stt_stat;

                        if($stt_stat->id_ord_stt_stat=="3"){
                            $status["tgl_keluar"] = date("Y-m-d");
                        }

                        SttModel::where("id_stt", $value->id_stt)->update($status);
                        $d_stt = HistoryStt::where("id_stt", $value->id_stt)->orderBy("no_status", "desc")->get()->first();
                        $no = 1;
                        if($d_stt != null and $d_stt->no_status != null){
                            $no = $d_stt->no_status+1;
                        }

                        // add history status
                        $hs = new HistoryStt();
                        $hs->id_stt = $value->id_stt;
                        $hs->id_status = $status["id_status"];
                        $hs->no_status = $no;
                        $hs->id_user    = Auth::user()->id_user;
                        $hs->keterangan = $request->keterangan;
                        $hs->place    = $wilayah->nama_wil;
                        $hs->keterangan = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$hs->keterangan;
                        $hs->nm_user    = Auth::user()->nm_user;
                        $hs->nm_pengirim = $value->pengirim_nm;
                        $hs->nm_status  = $stt_stat->nm_ord_stt_stat;
                        $hs->id_wil         = $wilayah->id_wil;
                        $hs->id_perush      = Session("perusahaan")["id_perush"];

                        $cek_awb = SttModel::where("kode_stt", $value->no_awb)->get()->first();
                        if($cek_awb){
                            SttModel::where("kode_stt", $value->no_awb)->update($status);
                            $d_stt = HistoryStt::where("id_stt", $cek_awb->id_stt)->orderBy("no_status", "desc")->get()->first();
                            $no = 1;
                            if($d_stt != null){
                                $no = $d_stt->no_status+1;
                            }

                            $hs_awb = new HistoryStt();
                            $hs_awb->id_stt = $cek_awb->id_stt;
                            $hs_awb->id_status = $hs->id_status;
                            $hs_awb->no_status = $no;
                            $hs_awb->id_user    = $hs->id_user;
                            $hs_awb->place    = $hs->place;
                            $hs_awb->keterangan = $hs->keterangan;
                            $hs_awb->nm_user    = $hs->nm_user;
                            $hs_awb->nm_pengirim = $hs->nm_pengirim;
                            $hs_awb->nm_status  = $hs->nm_status;
                            $hs_awb->id_wil         = $hs->id_wil;
                            $hs_awb->id_perush      = $hs->id_perush;
                            $hs_awb->save();

                            $cron_hs[$key]["tipe"] = "stt";
                            $cron_hs[$key]["id_wil"] = $hs->id_wil;
                            $cron_hs[$key]["status"] = $hs->id_status;
                            $cron_hs[$key]["place"] = $hs->place;
                            $cron_hs[$key]["info"] = $hs->keterangan;
                            $cron_hs[$key]["id_user"] = Auth::user()->id_user;
                            $cron_hs[$key]["id_stt"] = $cek_awb->id_stt;
                            $cron_hs[$key]["id_dm"] = $id;
                            $cron_hs[$key]["status"] = "1";
                        }

                        $a_cron[$key]["tipe"] = "stt";
                        $a_cron[$key]["id_wil"] = $hs->id_wil;
                        $a_cron[$key]["status"] = $hs->id_status;
                        $a_cron[$key]["place"] = $hs->place;
                        $a_cron[$key]["info"] = $hs->keterangan;
                        $a_cron[$key]["id_user"] = Auth::user()->id_user;
                        $a_cron[$key]["id_stt"] = $hs->id_stt;
                        $a_cron[$key]["id_dm"] = $id;
                        $a_cron[$key]["status"] = "1";

                        $hs->save();
                    }

                    CronJob::insert($a_cron);
                    CronJob::insert($cron_hs);
                }

                $dm->id_status = $status_dm->id_status;
                if($dm->id_status=="3"){
                    $dm->atd       = date("Y-m-d H:i:s");
                }

                if($dm->id_status=="4"){
                    $dm->ata       = date("Y-m-d H:i:s");
                }

                $dm->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }

    public function updatestatusdm(Request $request, $id)
    {
        // dd($kode);
        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $stt = DaftarMuat::getTotalKoli($id);
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            $request->id_kota != null ? $wilayah = Wilayah::findOrfail($request->id_kota):$wilayah;
            $id_stt = [];

            if ($stt==null) {
                return redirect()->back()->with('error', 'Stt Tidak Ditemukan ');
            }else{
                $dm->id_kapal!=null ? $kapal = Kapal::findOrFail($dm->id_kapal):$kapal = null;
                $status_dm = StatusDM::findOrFail($request->id_status);
                $stt_stat = StatusStt::where("id_status", $request->id_status)->get()->first();

                $a_cron = [];
                $stt_awb = [];
                $cron_hs = [];
                $hs = [];
                $hs_awb = [];
                foreach ($stt as $key => $value) {
                    if($value->id_status < 7){
                        $status = [];
                        $id_stt[$key] = $value->id_stt;
                        $status["id_status"] = $stt_stat->id_ord_stt_stat;
                        $status["tgl_update"] = $request->tgl_update;
                        if($stt_stat->id_ord_stt_stat=="3"){
                            $status["tgl_keluar"] = date("Y-m-d");
                        }
                        SttModel::where("id_stt", $value->id_stt)->update($status);
                        $hs[$key]["id_stt"] = $value->id_stt;
                        $hs[$key]["id_status"] = $status["id_status"];
                        $hs[$key]["id_user"]    = Auth::user()->id_user;
                        $hs[$key]["keterangan"] = $request->keterangan;
                        $hs[$key]["place"]    = $wilayah->nama_wil;
                        $hs[$key]["keterangan"] = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$request->keterangan;
                        $hs[$key]["nm_user"]    = Auth::user()->nm_user;
                        $hs[$key]["nm_pengirim"] = $value->pengirim_nm;
                        $hs[$key]["nm_status"]  = $stt_stat->nm_ord_stt_stat;
                        $hs[$key]["id_wil"]         = $wilayah->id_wil;
                        $hs[$key]["id_perush"]      = Session("perusahaan")["id_perush"];
                        $hs[$key]["tgl_update"] = $request->tgl_update;

                        $cek_awb = SttModel::where("kode_stt", $value->no_awb)->get()->first();
                        if($cek_awb){
                            SttModel::where("kode_stt", $value->no_awb)->update($status);
                            $hs_awb[$key]["id_stt"] = $cek_awb->id_stt;
                            $hs_awb[$key]["id_status"] = $hs[$key]["id_status"];
                            $hs_awb[$key]["id_user"]    = $hs[$key]["id_user"];
                            $hs_awb[$key]["place"]    = $hs[$key]["place"];
                            $hs_awb[$key]["keterangan"] = $hs[$key]["keterangan"];
                            $hs_awb[$key]["nm_user"]    = $hs[$key]["nm_user"];
                            $hs_awb[$key]["nm_pengirim"] = $hs[$key]["nm_pengirim"];
                            $hs_awb[$key]["nm_status"]  = $hs[$key]["nm_status"];
                            $hs_awb[$key]["id_wil"]         = $hs[$key]["id_wil"];
                            $hs_awb[$key]["id_perush"]      = $hs[$key]["id_perush"];
                            $hs_awb[$key]["tgl_update"] = $hs[$key]["tgl_update"];

                            $cron_hs[$key]["tipe"] = "stt";
                            $cron_hs[$key]["id_wil"] = $hs[$key]["id_wil"];
                            $cron_hs[$key]["place"] = $hs[$key]["place"];
                            $cron_hs[$key]["info"] = $hs[$key]["keterangan"];
                            $cron_hs[$key]["id_user"] = $hs[$key]["id_user"];
                            $cron_hs[$key]["id_stt"] = $hs[$key]["id_wil"];
                            $cron_hs[$key]["id_dm"] = $id;
                            $cron_hs[$key]["status"] = "1";
                        }

                        $a_cron[$key]["tipe"] = "stt";
                        $a_cron[$key]["id_wil"] = $hs[$key]["id_wil"];
                        $a_cron[$key]["place"] = $hs[$key]["place"];
                        $a_cron[$key]["info"] = $hs[$key]["keterangan"];
                        $a_cron[$key]["id_user"] = $hs[$key]["id_user"];
                        $a_cron[$key]["id_stt"] = $hs[$key]["id_stt"];
                        $a_cron[$key]["id_dm"] = $id;
                        $a_cron[$key]["status"] = "1";
                    }

                }

                HistoryStt::insert($hs);
                HistoryStt::insert($hs_awb);
                CronJob::insert($a_cron);
                CronJob::insert($cron_hs);

                $dm->id_status = $status_dm->id_status;
                if($dm->id_status=="3"){ $dm->atd = date("Y-m-d H:i:s"); }
                if($dm->id_status=="4"){ $dm->ata       = date("Y-m-d H:i:s"); }
                $dm->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }

    public function updatestatus(Request $request, $id)
    {
        // dd($kode);
        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $stt = DaftarMuat::getTotalKoli($id);
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            $request->id_kota != null ? $wilayah = Wilayah::findOrfail($request->id_kota):$wilayah;
            $id_stt = [];

            if ($stt==null) {
                return redirect()->back()->with('error', 'Stt Tidak Ditemukan ');
            }else{
                $dm->id_kapal!=null ? $kapal = Kapal::findOrFail($dm->id_kapal):$kapal = null;
                $status_dm = StatusDM::findOrFail($request->id_status);
                $stt_stat = StatusStt::where("id_status", $request->id_status)->get()->first();

                $a_cron = [];
                $stt_awb = [];
                $cron_hs = [];
                $hs = [];
                $hs_awb = [];
                foreach ($stt as $key => $value) {
                    if($value->id_status < 7){
                        $status = [];
                        $id_stt[$key] = $value->id_stt;
                        $status["id_status"] = $stt_stat->id_ord_stt_stat;
                        $status["tgl_update"] = $request->tgl_update;
                        if($stt_stat->id_ord_stt_stat=="3"){
                            $status["tgl_keluar"] = date("Y-m-d");
                        }
                        SttModel::where("id_stt", $value->id_stt)->update($status);
                        $hs[$key]["id_stt"] = $value->id_stt;
                        $hs[$key]["id_status"] = $status["id_status"];
                        $hs[$key]["id_user"]    = Auth::user()->id_user;
                        $hs[$key]["keterangan"] = $request->keterangan;
                        $hs[$key]["place"]    = $wilayah->nama_wil;
                        $hs[$key]["keterangan"] = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$request->keterangan;
                        $hs[$key]["nm_user"]    = Auth::user()->nm_user;
                        $hs[$key]["nm_pengirim"] = $value->pengirim_nm;
                        $hs[$key]["nm_status"]  = $stt_stat->nm_ord_stt_stat;
                        $hs[$key]["id_wil"]         = $wilayah->id_wil;
                        $hs[$key]["id_perush"]      = Session("perusahaan")["id_perush"];
                        $hs[$key]["tgl_update"] = $request->tgl_update;

                        $cek_awb = SttModel::where("kode_stt", $value->no_awb)->get()->first();
                        if($cek_awb){
                            SttModel::where("kode_stt", $value->no_awb)->update($status);
                            $hs_awb[$key]["id_stt"] = $cek_awb->id_stt;
                            $hs_awb[$key]["id_status"] = $hs[$key]["id_status"];
                            $hs_awb[$key]["id_user"]    = $hs[$key]["id_user"];
                            $hs_awb[$key]["place"]    = $hs[$key]["place"];
                            $hs_awb[$key]["keterangan"] = $hs[$key]["keterangan"];
                            $hs_awb[$key]["nm_user"]    = $hs[$key]["nm_user"];
                            $hs_awb[$key]["nm_pengirim"] = $hs[$key]["nm_pengirim"];
                            $hs_awb[$key]["nm_status"]  = $hs[$key]["nm_status"];
                            $hs_awb[$key]["id_wil"]         = $hs[$key]["id_wil"];
                            $hs_awb[$key]["id_perush"]      = $hs[$key]["id_perush"];
                            $hs_awb[$key]["tgl_update"] = $hs[$key]["tgl_update"];

                            $cron_hs[$key]["tipe"] = "stt";
                            $cron_hs[$key]["id_wil"] = $hs[$key]["id_wil"];
                            $cron_hs[$key]["place"] = $hs[$key]["place"];
                            $cron_hs[$key]["info"] = $hs[$key]["keterangan"];
                            $cron_hs[$key]["id_user"] = $hs[$key]["id_user"];
                            $cron_hs[$key]["id_stt"] = $hs[$key]["id_wil"];
                            $cron_hs[$key]["id_dm"] = $id;
                            $cron_hs[$key]["status"] = "1";
                        }

                        $a_cron[$key]["tipe"] = "stt";
                        $a_cron[$key]["id_wil"] = $hs[$key]["id_wil"];
                        $a_cron[$key]["place"] = $hs[$key]["place"];
                        $a_cron[$key]["info"] = $hs[$key]["keterangan"];
                        $a_cron[$key]["id_user"] = $hs[$key]["id_user"];
                        $a_cron[$key]["id_stt"] = $hs[$key]["id_stt"];
                        $a_cron[$key]["id_dm"] = $id;
                        $a_cron[$key]["status"] = "1";
                    }

                }

                HistoryStt::insert($hs);
                HistoryStt::insert($hs_awb);
                CronJob::insert($a_cron);
                CronJob::insert($cron_hs);

                $dm->id_status = $status_dm->id_status;
                if($dm->id_status=="3"){ $dm->atd = date("Y-m-d H:i:s"); }
                if($dm->id_status=="4"){ $dm->ata       = date("Y-m-d H:i:s"); }
                $dm->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }

    public function updatestatus2(Request $request, $id)
    {
        // dd($kode);
        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $stt = DaftarMuat::getTotalKoli($id);

            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            if(isset($request->id_kota) and $request->id_kota != null){
                $wilayah = Wilayah::findOrfail($request->id_kota);
            }
            $id_stt = [];
            if ($stt==null) {
                return redirect()->back()->with('error', 'Stt Tidak Ditemukan ');
            }else{

                if (isset($dm->id_kapal)) {
                    $kapal = Kapal::findOrFail($dm->id_kapal);
                }else{
                    $kapal = null;
                }

                $status_dm = StatusDM::where("id_status", ">", $dm->id_status)->orderBy("id_status", "asc")->get()->first();

                $a_cron = [];
                $stt_awb = [];
                $cron_hs = [];

                if($status_dm->id_status!=6){
                    foreach ($stt as $key => $value) {
                        $status = [];
                        $id_stt[$key] = $value->id_stt;
                        $stt_stat = StatusStt::where("id_ord_stt_stat", ">", $value->id_status)->orderBy("id_ord_stt_stat", "asc")->get()->first();
                        $status["id_status"] = $stt_stat->id_ord_stt_stat;

                        if($stt_stat->id_ord_stt_stat=="3"){
                            $status["tgl_keluar"] = date("Y-m-d");
                        }

                        SttModel::where("id_stt", $value->id_stt)->update($status);
                        $d_stt = HistoryStt::where("id_stt", $value->id_stt)->orderBy("no_status", "desc")->get()->first();
                        $no = 1;
                        if($d_stt != null and $d_stt->no_status != null){
                            $no = $d_stt->no_status+1;
                        }

                        // add history status
                        $hs = new HistoryStt();
                        $hs->id_stt = $value->id_stt;
                        $hs->id_status = $status["id_status"];
                        $hs->no_status = $no;
                        $hs->id_user    = Auth::user()->id_user;
                        $hs->keterangan = $request->keterangan;
                        $hs->place    = $wilayah->nama_wil;
                        $hs->keterangan = $stt_stat->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$hs->keterangan;
                        $hs->nm_user    = Auth::user()->nm_user;
                        $hs->nm_pengirim = $value->pengirim_nm;
                        $hs->nm_status  = $stt_stat->nm_ord_stt_stat;
                        $hs->id_wil         = $wilayah->id_wil;
                        $hs->id_perush      = Session("perusahaan")["id_perush"];

                        $cek_awb = SttModel::where("kode_stt", $value->no_awb)->get()->first();
                        if($cek_awb){
                            SttModel::where("kode_stt", $value->no_awb)->update($status);
                            $d_stt = HistoryStt::where("id_stt", $cek_awb->id_stt)->orderBy("no_status", "desc")->get()->first();
                            $no = 1;
                            if($d_stt != null){
                                $no = $d_stt->no_status+1;
                            }
                            $hs_awb = new HistoryStt();
                            $hs_awb->id_stt = $cek_awb->id_stt;
                            $hs_awb->id_status = $hs->id_status;
                            $hs_awb->no_status = $no;
                            $hs_awb->id_user    = $hs->id_user;
                            $hs_awb->place    = $hs->place;
                            $hs_awb->keterangan = $hs->keterangan;
                            $hs_awb->nm_user    = $hs->nm_user;
                            $hs_awb->nm_pengirim = $hs->nm_pengirim;
                            $hs_awb->nm_status  = $hs->nm_status;
                            $hs_awb->id_wil         = $hs->id_wil;
                            $hs_awb->id_perush      = $hs->id_perush;
                            $hs_awb->save();

                            $cron_hs[$key]["tipe"] = "stt";
                            $cron_hs[$key]["id_wil"] = $hs->id_wil;
                            $cron_hs[$key]["status"] = $hs->id_status;
                            $cron_hs[$key]["place"] = $hs->place;
                            $cron_hs[$key]["info"] = $hs->keterangan;
                            $cron_hs[$key]["id_user"] = Auth::user()->id_user;
                            $cron_hs[$key]["id_stt"] = $cek_awb->id_stt;
                            $cron_hs[$key]["id_dm"] = $id;
                            $cron_hs[$key]["status"] = "1";
                        }

                        $a_cron[$key]["tipe"] = "stt";
                        $a_cron[$key]["id_wil"] = $hs->id_wil;
                        $a_cron[$key]["status"] = $hs->id_status;
                        $a_cron[$key]["place"] = $hs->place;
                        $a_cron[$key]["info"] = $hs->keterangan;
                        $a_cron[$key]["id_user"] = Auth::user()->id_user;
                        $a_cron[$key]["id_stt"] = $hs->id_stt;
                        $a_cron[$key]["id_dm"] = $id;
                        $a_cron[$key]["status"] = "1";

                        $hs->save();
                    }

                    CronJob::insert($a_cron);
                    CronJob::insert($cron_hs);
                }

                $dm->id_status = $status_dm->id_status;
                if($dm->id_status=="3"){
                    $dm->atd       = date("Y-m-d H:i:s");
                }

                if($dm->id_status=="4"){
                    $dm->ata       = date("Y-m-d H:i:s");
                }

                $dm->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Update Status Berhasil');
    }

    public function showstt($id)
    {
        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->findOrfail($id);
        $data["detail"] = DetailStt::where("id_stt", $id)->get();

        if($data["data"]==null){
            return redirect()->back()->with('error', 'Data STT tidak ada');
        }

        return view('operasional::detail-stt', $data);
    }

    public function detailstt($id, $id_stt)
    {
        $stt = SttModel::getIdSttKoli($id_stt);
        $dm  = DaftarMuat::findOrFail($id);
        if($dm->id_perush_dr!=Session("perusahaan")["id_perush"]){
            return redirect()->back()->with('error', 'Anda Tidak Memilik Akses');
        }
        $data["data"] = $stt;
        $data["koli"] = DmKoli::getKoliStt($id, $id_stt);

        return view('operasional::daftarmuat.showkoli', $data);
    }

    public function print($id)
    {
        $dm = DaftarMuat::findOrFail($id);
        // dd($dm);
        $data["data"] = $dm;
        $status = StatusDM::select("id_status", "nm_status")->get();
        $stt = [];
        foreach ($status as $key => $value) {
            $stt[$value->id_status] = $value;
        }

        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["sttstat"] = StatusStt::getList();
        $data["status"] = $stt;
        $data["detail"] = SttModel::getSttKoli($dm->id_perush_dr, $dm->id_perush_tj, $dm->id_layanan);

        return view('operasional::daftarmuat.showdm', $data);
    }

    public function detailven($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);

        $data["data"] = $dm;
        $data["detail"] = SttModel::getSttKoliVendor($dm->id_perush_dr, $dm->id_wil_asal);
        $data["sttstat"] = StatusStt::getList();
        $data["tipe"] = TipeKirim::getList();
        $data["packing"] = Packing::getArray();

        return view('operasional::daftarmuat.showdm', $data);
    }

    public function import($id, Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cara = CaraBayar::getList();
        $data["cara"] = $cara->where("id_cr_byr_o", "!=", "dp");
        $stt = SttModel::with("marketing", "pelanggan", "tipekirim")->findOrfail($id);
        if($stt->is_import == true){
            return redirect()->back()->with('error', 'Stt Sudah Di Import');
        }
        $data["packing"] = Packing::getList();
        $data["layanan"]    = Layanan::getLayanan();
        $data["tarif"] = Tarif::getListTarif($stt->pengirim_id_region, $stt->penerima_id_region, $stt->id_layanan, $stt->id_plgn);
        $data["data"] = $stt;

        $pelanggan = Pelanggan::where("id_perush_cabang", $stt->id_perush_asal)->where("id_perush", $id_perush)->get()->first();
        $data["id_dm"] = $request->id_dm_tiba;
        if($pelanggan == null){
            return redirect()->back()->with('error', 'Pelanggan Belum Terdaftar di Perusahaan');
        }

        $data["marketing"] = Marketing::getMarketing($id_perush);
        $data["pelanggan"] = $pelanggan;
        $data["perush"] = Perusahaan::findOrFail($stt->id_perush_asal);

        return view('operasional::importstt', $data);
    }

    public function doimport(SttRequest $request)
    {
        DB::beginTransaction();
        try {
            // cek limit piutang
            $cek = SettingLimitPiutang::ceklimit($request->id_pelanggan);

            if($cek["piutang"]>$cek["limit"]){
                return redirect()->back()->with('error', 'Stt Gagal Dibuat, Karena Limit Piutang Pelanggan Terbatas !');
            }

            $id_dm_tiba = $request->id_dm_tiba;
            $id = $this->sttid->generate2($request->id_layanan);
            $stt                            = new SttModel();
            // for creator
            $stt->id_user                   = Auth::user()->id_user;
            $stt->id_perush_asal            = $id["id_perush"];
            $stt->id_stt                    = $id["id_stt"];
            $stt->kode_stt                  = strtoupper($id["kode_stt"]);
            $stt->tgl_masuk                 = date("Y-m-d h:i:s");

            if(isset($request->tgl_masuk)){
                $stt->tgl_masuk             = $request->tgl_masuk;
            }

            $stt->tgl_keluar                = $request->tgl_keluar;

            // for pengirim
            $stt->pengirim_perush           = $request->pengirim_perush;
            $stt->id_plgn                   = $request->id_pelanggan;
            $stt->pengirim_nm               = $request->pengirim_nm;
            $stt->pengirim_alm              = $request->pengirim_alm;
            $stt->pengirim_telp             = $request->pengirim_telp;
            $stt->pengirim_kodepos          = $request->pengirim_kodepos;
            $stt->pengirim_id_region        = $request->pengirim_id_region;
            $stt->no_awb                    = $request->no_awb;

            // for penerima
            $stt->penerima_perush           = $request->penerima_perush;
            $stt->penerima_nm               = $request->penerima_nm;
            $stt->penerima_alm              = $request->penerima_alm;
            $stt->penerima_telp             = $request->penerima_telp;
            $stt->pengirim_telp             = $request->pengirim_telp;
            $stt->penerima_kodepos          = $request->penerima_kodepos;
            $stt->penerima_id_region        = $request->penerima_id_region;

            // for detail kirim
            $stt->id_layanan                = $request->id_layanan;
            $stt->id_tarif                  = $request->id_tarif;
            $stt->id_cr_byr_o               = $request->id_cr_byr_o;
            $stt->id_tipe_kirim             = $request->id_tipe_kirim;
            $stt->id_marketing              = $request->id_marketing;
            $stt->info_kirim                = $request->info_kirim;
            //$stt->instruksi_kirim           = $request->instruksi_kirim;

            // for count stt
            $stt->n_berat                   = (Double)$request->n_berat;
            $stt->n_volume                  = (Double)$request->n_volume;
            $stt->n_kubik                   = (Double)$request->n_kubik;
            $stt->n_koli                    = $request->n_koli;
            $stt->n_tarif_brt               = (Double)$request->n_tarif_brt;
            $stt->n_tarif_vol               = (Double)$request->n_tarif_vol;
            $stt->n_tarif_kubik             = (Double)$request->n_tarif_kubik;
            $stt->n_tarif_borongan          = (Double)$request->n_tarif_borongan;
            $stt->n_hrg_bruto               = (Double)$request->n_hrg_bruto;
            $stt->n_terusan                 = (Double)$request->n_terusan;
            $stt->n_hrg_terusan             = (Double)$request->n_hrg_terusan;
            $stt->n_diskon                  = (Double) $request->n_diskon;
            $stt->n_materai                 = (Double)$request->n_materai;
            $stt->is_ppn                    = $request->is_ppn;
            $stt->n_ppn                     = (Double)$request->n_ppn;
            $stt->id_asuransi               = $request->id_asuransi;
            $stt->n_asuransi                = (Double)$request->n_asuransi;
            $stt->is_bayar                  = false;
            $stt->is_asuransi               = $request->is_asuransi;
            $stt->is_packing                = $request->is_packing;

            // for id wil
            $perusahaan                    = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
            $stt->id_wil                   = $perusahaan->id_region;
            // for mathematic sum all
            $min_brt                        = (Double)$request->cm_brt;
            $min_vol                        = (Double)$request->cm_vol;
            $min_vol                        = (Double)$request->cm_kubik;

            if($stt->n_berat<$min_brt){
                $stt->n_berat = $min_brt;
            }
            if($stt->n_volume<$min_vol){
                $stt->n_volume = $min_vol;
            }
            // for cek to bruto
            $c_cek = $request->c_hitung;
            if($c_cek==1){

                $stt->n_hrg_bruto = (double)$stt->n_berat*$stt->n_tarif_brt;
                $stt->c_tarif     = 1;
            }elseif($c_cek==2){

                $stt->n_hrg_bruto = (double)$stt->n_volume*$stt->n_tarif_vol;
                $stt->c_tarif     = 2;

            }elseif($c_cek==4){

                $stt->n_hrg_bruto = (double)$stt->n_kubik*$stt->n_tarif_kubik;
                $stt->c_tarif     = 4;

            }elseif($c_cek==3){

                $stt->n_hrg_bruto = (Double)$stt->n_tarif_borongan;
                $stt->c_tarif     = 3;
            }else{

                return redirect()->back()->with('error', 'Anda memasukan karakter tidak dikenali');
            }

            // for tarif netto
            $stt->n_ppn = 0;
            if (isset($request->n_ppn) and $request->n_ppn != "0") {
                $stt->n_ppn = ($stt->n_hrg_bruto - $stt->n_diskon)*1/100;
            }
            $stt->c_total                   = (double)($stt->n_hrg_bruto+$stt->n_ppn+$stt->n_materai+$stt->n_asuransi)-$stt->n_diskon;

            // tarif koli
            $n_koli = $request->n_koli;
            if($request->n_koli==null or $request->n_koli=="0"){
                $n_koli = 1;
            }

            $stt->n_tarif_koli              = (Double)($stt->c_total/$n_koli);

            //dd($stt);
            $stt->id_status                 = 1;
            $stt->is_aktif                  = true;

            // for keuangan
            $group = SettingLayananPerush::where("id_layanan", $stt->id_layanan)->where("id_perush",Session("perusahaan")["id_perush"])
            ->get()->first();
            if($group==null){
                DB::rollback();
                return redirect()->back()->with('error', "Setting Group Layanan Keuangan Belum Ada");
            }

            $stt->c_ac4_pend    = $group->ac_pendapatan;
            $stt->c_ac4_disc    = $group->ac_diskon;
            $stt->c_ac4_ppn     = $group->ac_ppn;
            $stt->c_ac4_mat     = $group->ac_materai;
            $stt->c_ac4_piut    = $group->ac_piutang;
            $stt->c_ac4_asur    = $group->ac_asuransi;
            //dd($request->request,$stt);

            // for stt cash
            if($stt->id_cr_byr_o=="1"){
                $bayar                  = new Pembayaran();
                $bayar->id_cr_byr       = $stt->id_cr_byr_o;
                $bayar->id_stt          = $stt->id_stt;
                $bayar->n_bayar         = $stt->c_total;
                $bayar->tgl             = $stt->tgl_masuk;
                $bayar->info            = "Pembayar Cash STT ".$stt->id_stt;
                $bayar->id_plgn         = $request->id_pelanggan;
                $bayar->no_bayar        = null;
                $bayar->tgl_bg          = null;
                $bayar->id_perush       = Session("perusahaan")["id_perush"];
                $bayar->id_user         = Auth::user()->id_user;
                $bayar->is_aktif        = true;
                $bayar->is_konfirmasi   = true;
                $bayar->ac4_k           = $stt->c_ac4_piut;
                $bayar->nm_bayar        = $stt->pengirim_nm;

                $ac = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])
                ->where("is_kas", true)
                ->get()
                ->first();

                if($ac==null){
                    DB::rollback();
                    return redirect()->back()->with('error', "Perkiraan Akun Kas Keuangan Belum Ada");
                }

                $bayar->ac4_d = $ac->id_ac;
                $id = $this->pembayaran->genIdBayar(Session("perusahaan")["id_perush"]);
                $bayar->no_kwitansi = $id["no_kwitansi"];
                //dd($bayar);
                $bayar->save();
                $stt->is_bayar = true;
                $stt->is_lunas = true;
            }

            for ($i=1; $i <= $stt->n_koli; $i++) {

                $koli = new OpOrderKoli();
                $koli->id_koli  = $stt->id_stt.$i;
                $koli->id_stt   = $stt->id_stt;
                $koli->no_koli = $i;
                $koli->dr_koli = $stt->n_koli;
                $koli->info    = $stt->info_kirim." Koli ".$i;
                $koli->id_user = $stt->id_user;
                $koli->status  = 1;
                $koli->status_dm_ven  = "0";
                $koli->save();
            }

            //add history stt
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);

            $stat = StatusStt::where("id_ord_stt_stat", "1")->get()->first();
            $hs                 = new HistoryStt();
            $hs->id_stt         = $stt->id_stt;
            $hs->id_status      = "1";
            $hs->id_user        = Auth::user()->id_user;
            $hs->keterangan     = "Barang Diterima Dari ".$stt->pengirim_nm;
            $hs->nm_user        = Auth::user()->nm_user;
            $hs->place          = $wilayah->nama_wil;
            $hs->nm_pengirim    = $stt->pengirim_nm;
            $hs->nm_status      = $stat->nm_ord_stt_stat;
            $hs->id_wil         = $wilayah->id_wil;
            $hs->id_perush      = Session("perusahaan")["id_perush"];
            $hs->no_status      = 1;
            $hs->save();

            $stt->save();

            $sttawb = SttModel::where("kode_stt", $stt->no_awb)->get()->first();
            $detail = DetailStt::where("id_stt", $sttawb->id_stt)->get();
            $a_detail = [];
            foreach($detail as $key => $value){
                $a_detail[$key]["id_stt"] = $stt->id_stt;
                $a_detail[$key]["ket_koli"] = $value->ket_koli;
                $a_detail[$key]["keterangan"] = $value->keterangan;
                $a_detail[$key]["id_user"] = Auth::user()->id_user;
                $a_detail[$key]["created_at"] = date("Y-m-d h:i:s");
                $a_detail[$key]["updated_at"] = date("Y-m-d h:i:s");
            }
            DetailStt::insert($a_detail);

            $hasil["is_import"] = true;
            SttModel::where("kode_stt", $request->no_awb)->update(
                $hasil
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data STT Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("dmtiba/".$id_dm_tiba."/show"))->with('success', 'Data STT  Disimpan');
    }

    public function terima($id)
    {
        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $dm->status_dm_ven = (Int)$dm->status_dm_ven+1;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Dm Vendor Gagal Diterima '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Dm Vendor Sudah Diterima');
    }

    public function AmbilDiGudang($id, Request $request)
    {
        $rules = array(
            'dok1'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif',
            'dok2'  => 'bail|required|image|mimes:jpg,png,jpeg,svg,gif',
            'keterangan'  => 'bail|nullable|max:100',
            'id_stt'  => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }else{
            $perush = Perusahaan::findOrfail(Session("perusahaan")["id_perush"]);
            $wilayah = Wilayah::findOrfail($perush->id_region);
            DB::beginTransaction();
            try {
                $id_status                 = 0;
                $stt                       = SttModel::findOrFail($request->id_stt);
                $hs                         = new HistoryStt();
                $id_status                 = $stt->id_status;

                if(isset($request->dok1) and $request->file('dok1')!=null){
                    $img = $request->file('dok1');

                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $hs->gambar1 = $image[3];
                }

                if(isset($request->dok2) and $request->file('dok2')!=null){
                    $img = $request->file('dok2');

                    $path_img = $img->store('public/uploads/handling');
                    $image = explode("/", $path_img);
                    $hs->gambar2 = $image[3];
                }

                $statusstt = StatusStt::select("id_ord_stt_stat", "nm_ord_stt_stat")->orderBy("id_ord_stt_stat", "desc")->get()->first();
                $a_data = [];
                $a_data["id_status"] = $statusstt->id_ord_stt_stat;
                $a_data["status_kembali"] = "0";
                $cron_hs = [];

                SttModel::where("id_stt", $stt->id_stt)->update($a_data);
                $d_stt = HistoryStt::where("id_stt", $stt->id_stt)->orderBy("no_status", "desc")->get()->first();
                $no = 1;
                if($d_stt != null and $d_stt->no_status != null){
                    $no = $d_stt->no_status+1;
                }

                // add history status
                $hs->id_stt = $stt->id_stt;
                $hs->id_status = $statusstt->id_ord_stt_stat;
                $hs->no_status = $no;
                $hs->id_user    = Auth::user()->id_user;
                $hs->keterangan = $request->keterangan;
                $hs->place    = $wilayah->nama_wil;
                $hs->keterangan = $statusstt->nm_ord_stt_stat." ( ".$wilayah->nama_wil." ) ".$hs->keterangan;
                $hs->nm_user    = Auth::user()->nm_user;
                $hs->nm_pengirim = $stt->pengirim_nm;
                $hs->nm_status  = $statusstt->nm_ord_stt_stat;
                $hs->id_wil         = $wilayah->id_wil;
                $hs->nm_penerima         = $request->nm_penerima;
                $hs->id_perush      = Session("perusahaan")["id_perush"];
                $hs->save();

                //array cron
                $a_cron = [];
                $a_cron["tipe"] = "stt";
                $a_cron["id_wil"] = $hs->id_wil;
                $a_cron["status"] = $hs->id_status;
                $a_cron["place"] = $hs->place;
                $a_cron["info"] = $hs->nm_status;
                $a_cron["id_user"] = Auth::user()->id_user;
                $a_cron["id_stt"] = $hs->id_stt;
                $a_cron["id_handling"] = $stt->id_stt;
                $a_cron["status"] = "1";
                $a_cron["created_at"] = date("Y-m-d h:i:s");
                $a_cron["updated_at"] = date("Y-m-d h:i:s");
                CronJob::insert($a_cron);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Update Status Gagal '.$e->getMessage());
            }

            return redirect()->back()->with('success', 'Barang Sudah Terima');
        }
    }

    public function cetakDM($id)
    {
        $cara_bayar             = CaraBayar::all();
        $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"]            = SttModel::getDM($id)->get();
        $stt                    = SttModel::getDM($id)->get();
        $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"]             = $id;
        $temp                   = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"]           = $temp;
        $data["carabayar"]      = $cara_bayar;
        return view('operasional::daftarmuat.cetakdm', $data);
    }

    public function cetakDMBarcode($id)
    {
        $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"]            = SttModel::getDM($id)->get();
        $stt                    = SttModel::getDM($id)->get();
        $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"]             = $id;
        $temp                   = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"]           = $temp;
        $data["carabayar"]      = $cara_bayar;

        return view('operasional::daftarmuat.cetakdmbarcode', $data);
    }

}
