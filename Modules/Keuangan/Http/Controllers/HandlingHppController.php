<?php
namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\BiayaHpp;
use Modules\Operasional\Entities\BiayaHandling;
use Modules\Keuangan\Entities\GenIdBiayaHpp;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Handling;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Keuangan\Entities\HandlingBiaya;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;
use App\Models\Perusahaan;
use Auth;
use DB;
Use Exception;
use Modules\Keuangan\Http\Controllers\InvoiceHandlingController;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Operasional\Http\Requests\HandlingRequest;
use Modules\Operasional\Http\Requests\SttDmHandlingRequest;
use Modules\Operasional\Http\Requests\BiayaProyeksiRequest;
use Modules\Operasional\Entities\SttModel;

class HandlingHppController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function __construct()
    {

    }

    public function index()
    {
        $perpage = 50;
        $page = 1;
        if(isset($request->shareselect)){
            $perpage = $request->shareselect;
        }

        if(isset($request->page)){
            $page = $request->page;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = BiayaHandling::getBiayaHandling($perpage,$page, $id_perush);

        return view('keuangan::handling.index', $data);
    }

    public function filter(Request $request)
    {
        // dd($request->request);

        $perpage = 5;
        $page = 1;
        $id_perush = Session("perusahaan")["id_perush"];
        $id_handling = null;
        // $tgl_handling = null;
        // $tgl_berangkat = null;
        // $tgl_selesai = null;

        if(isset($request->filterperush)){
            $id_perush = $request->filterperush;
        }

        if(isset($request->shareselect)){
            $perpage = $request->shareselect;
        }

        if(isset($request->page)){
            $page = $request->page;
        }

        if(isset($request->id_handling)){
            $id_handling = $request->id_handling;
        }

        $tgl_handling = $request->tgl_handling;
        $tgl_berangkat_dr = $request->tgl_berangkat_dr;
        $tgl_berangkat_sp = $request->tgl_berangkat_sp;
        $tgl_selesai_dr = $request->tgl_selesai_dr;
        $tgl_selesai_sp = $request->tgl_selesai_sp;

        $handling = Handling::select("id_handling", "kode_handling")->where("id_handling", $id_handling)->get()->first();

        $filter = array("page"=>$perpage, "handling"=>$handling, "tgl_berangkat_dr"=>$tgl_berangkat_dr, "tgl_berangkat_sp"=>$tgl_berangkat_sp, "tgl_selesai_dr"=>$tgl_selesai_dr, "tgl_selesai_sp"=>$tgl_selesai_sp);
        $data["data"] = BiayaHandling::getBiayaHandling($perpage,$page, $id_perush, $id_handling, $tgl_handling, $tgl_berangkat_dr, $tgl_berangkat_sp, $tgl_selesai_dr,$tgl_selesai_sp);
        // dd($data,$request->request);
        $data["filter"] = $filter;
        return view('keuangan::handling.index', $data);
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
        if(!isset($request->c_pro) and $request->c_pro == null){
            return redirect()->back()->with('error', 'Komponen Biaya Tidak dipilih');
        }

        DB::beginTransaction();
        try {

            $a_data = [];
            $a_bayar = [];
            $handling = Handling::FindOrFail($request->id_handling);

            if(count($request->c_pro) > 1){
                $total = 0;
                foreach($request->c_pro as $key => $value){
                    $biaya = BiayaHandling::select("id_biaya", "id_stt", "kode_stt", "ac4_debit", "nominal", "n_bayar", "id_biaya_grup", "id_perush")->where("id_biaya", $value)->get()->first();
                    $group = GroupBiaya::select("nm_biaya_grup")->where("id_biaya_grup", $biaya->id_biaya_grup)->get()->first();

                    $sisa = $biaya->nominal - $biaya->n_bayar;

                    $a_data[$key]["id_biaya"] = $biaya->id_biaya;
                    $a_data[$key]["id_handling"] = $request->id_handling;
                    $a_data[$key]["id_stt"] = $biaya->id_stt;
                    $a_data[$key]["kode_stt"] = $biaya->kode_stt;
                    $a_data[$key]["id_perush"] = $biaya->id_perush;
                    $a_data[$key]["id_user"] = Auth::user()->id_user;
                    $a_data[$key]["nominal"] = $sisa;
                    $a_data[$key]["ac4_debit"] =$biaya->ac4_debit;
                    $a_data[$key]["ac4_kredit"] = $request->id_ac;
                    $a_data[$key]["created_at"] = date("Y-m-d h:i:s");
                    $a_data[$key]["updated_at"] = date("Y-m-d h:i:s");
                    $a_data[$key]["kode_handling"] = $handling->kode_handling;
                    $a_data[$key]["id_biaya_grup"] = $biaya->id_biaya_grup;
                    $a_data[$key]["keterangan"] = "Pembayaran Biaya ".$group->nm_biaya_group." Handling ".$handling->kode_handling;

                    $a_bayar[$key]["id_biaya"] = $value;
                    $a_bayar[$key]["n_bayar"] = $biaya->nominal;
                    $a_bayar[$key]["ac4_kredit"] = $request->id_ac;
                    $a_bayar[$key]["is_lunas"] = true;

                    $total += $sisa;
                }

                if($request->n_bayar != $total){
                    return redirect()->back()->with('error', 'Data Biaya Handling Gagal Disimpan, Karena Nominal Tidak Sama');
                }

            }else{
                $biaya = BiayaHandling::select("id_biaya","id_stt", "kode_stt", "ac4_debit", "n_bayar", "nominal", "id_biaya_grup", "id_perush")->where("id_biaya", $request->c_pro)->get()->first();
                $group = GroupBiaya::select("nm_biaya_grup")->where("id_biaya_grup", $biaya->id_biaya_grup)->get()->first();

                $n_bayar = $biaya->n_bayar + $request->n_bayar;
                $sisa = $biaya->nominal - $n_bayar;

                if($n_bayar > $biaya->nominal){
                    return redirect()->back()->with('error', 'Data Biaya Handling Gagal Disimpan, Karena Nominal Terlalu Besar');
                }

                $a_data["id_biaya"] = $biaya->id_biaya;
                $a_data["id_handling"] = $request->id_handling;
                $a_data["id_stt"] = $biaya->id_stt;
                $a_data["kode_stt"] = $biaya->kode_stt;
                $a_data["id_perush"] = $biaya->id_perush;
                $a_data["id_user"] = Auth::user()->id_user;
                $a_data["nominal"] = $request->n_bayar;
                $a_data["ac4_debit"] =$biaya->ac4_debit;
                $a_data["ac4_kredit"] = $request->id_ac;
                $a_data["created_at"] = date("Y-m-d h:i:s");
                $a_data["updated_at"] = date("Y-m-d h:i:s");
                $a_data["kode_handling"] = $handling->kode_handling;
                $a_data["id_biaya_grup"] = $biaya->id_biaya_grup;
                $a_data["keterangan"] = "Pembayaran Biaya ".$group->nm_biaya_group." Handling ".$handling->kode_handling;

                $a_bayar[0]["id_biaya"] = $biaya->id_biaya;
                $a_bayar[0]["ac4_kredit"] = $request->id_ac;
                $a_bayar[0]["n_bayar"] = $n_bayar;
                $a_bayar[0]["is_lunas"] = false;

                if($sisa == 0){
                    $a_bayar[0]["is_lunas"] = true;
                }
            }

            HandlingBiaya::Insert($a_data);
            foreach($a_bayar as $key => $value){
                $bayar["ac4_kredit"] = $value["ac4_kredit"];
                $bayar["n_bayar"] = $value["n_bayar"];
                $bayar["is_lunas"] = $value["is_lunas"];
                BiayaHandling::where("id_biaya", $value["id_biaya"])->update($bayar);
            }

            // update biaya for handling
            $biaya = BiayaHandling::where("id_handling", $request->id_handling)->sum("n_bayar");

            $handling->n_bayar = $biaya;
            $handling->is_lunas = false;

            if($biaya >= $handling->c_biaya){
                $handling->is_lunas = true;
            }

            $handling->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Pembayaran Biaya Handling Gagal Disimpan, '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Pembayaran Biaya Handling Sukses');
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

        $dm = Handling::getById($id);
        $data["handling"] = $dm;
        $detail = HandlingStt::getStt($id);
        $data["stt"] = $detail;
        $data["data"] = BiayaHandling::getDetailBiaya($page, $id);
        $data["dm"] = BiayaHandling::getIdDM($id);
        $data["kasbank"] = ACPerush::getKasBank($id_perush);
        $data["group"] = SettingBiayaPerush::DataHppPerush($id_perush);

        return view('keuangan::handling.detail', $data);
    }

    public function bayar($id)
    {
        $page = 100;
        $id_perush = Session("perusahaan")["id_perush"];
        $dm = Handling::getById($id);
        $data["handling"] = $dm;
        $detail = HandlingStt::getStt($id);
        $data["stt"] = $detail;
        $data["data"] = BiayaHandling::getDetailBiaya($page, $id);
        $data["dm"] = BiayaHandling::getIdDM($id);
        $data["kasbank"] = ACPerush::getKasBank($id_perush);
        $data["group"] = SettingBiayaPerush::DataHppPerush($id_perush);

        return view('keuangan::handling.detail', $data);
    }

    public function savebiaya(BiayaProyeksiRequest $request, $id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $handling = Handling::select("kode_handling")->where("id_handling", $request->id_handling)->get()->first();
        DB::beginTransaction();
        try {

            // save biaya
            $biaya                      = new BiayaHandling();
            $biaya->id_handling         = $id;
            $biaya->id_biaya_grup       = $request->id_biaya_grup;
            $biaya->nominal             = $request->nominal;
            $biaya->id_user             = Auth::user()->id_user;
            $biaya->id_perush           = $id_perush;
            $biaya->is_lunas            = false;
            $biaya->n_bayar             = 0;

            $grup                       = GroupBiaya::findOrFail($request->id_biaya_grup);
            $ac                         = SettingBiayaPerush::where("id_biaya_grup", $request->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();

            $stt                        = SttModel::where("id_stt", $request->id_stt)->get()->first();
            //for keuangan
            $biaya->ac4_debit           = $ac->id_ac_biaya;
            $biaya->ac4_kredit          = $ac->id_ac_hutang;
            $biaya->keterangan          = "Biaya ".$grup->nm_biaya_grup." Handling ".$id." ".date("d/m/Y");
            $biaya->id_stt              = $request->id_stt;

            if(isset($request->id_stt) and $request->id_stt!=null){
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $biaya->kode_stt    = $stt->kode_stt;
            }

            $biaya->kode_handling    = $handling->kode_handling;
            //dd($biaya);
            $biaya->save();

            // sum biaya
            $total = BiayaHandling::where("id_handling", $id)->sum("nominal");
            $a_total["c_biaya"] = $total;

            // update sum biaya
            Handling::where("id_handling", $id)->update($a_total);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Berhasil Disimpan ');
    }

    public function updatebiaya(BiayaProyeksiRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $handling = Handling::select("kode_handling")->where("id_handling", $request->id_handling)->get()->first();
            $biaya = BiayaHandling::findOrFail($id);
            $biaya->id_biaya_grup = $request->id_biaya_grup;
            $biaya->nominal    = $request->nominal;
            if(isset($request->id_stt) and $request->id_stt!=null){
                $stt = SttModel::select("kode_stt")->where("id_stt", $request->id_stt)->get()->first();
                $biaya->kode_stt    = $stt->kode_stt;
            }
            $biaya->kode_handling    = $handling->kode_handling;
            $biaya->id_user    = Auth::user()->id_user;
            $biaya->save();

            // sum biaya
            $total = BiayaHandling::where("id_handling", $request->id_handling)->sum("nominal");
            $a_total["c_biaya"] = $total;

            // update sum biaya
            Handling::where("id_handling", $request->id_handling)->update($a_total);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Berhasil Disimpan ');
    }

    public function deletebiaya($id)
    {
        DB::beginTransaction();

        try {

            $delete = BiayaHandling::findOrFail($id);
            $id_handling = $delete->id_handling;
            $delete->delete();

            // sum biaya
            $total = BiayaHandling::where("id_handling", $id_handling)->sum("nominal");
            $a_total["c_biaya"] = $total;

            // update sum biaya
            Handling::where("id_handling", $id_handling)->update($a_total);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Dihapus');
    }

    public function getBiaya($id)
    {
        $biaya = BiayaHandling::findOrFail($id);

        return response()->json($biaya);
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

    public function batal($id, Request $request)
    {
        $handling = Handling::findOrFail($id);

        DB::beginTransaction();

        try {

            // update biaya handling
            foreach ($request->c_pro as $key => $value) {
                $a_data=[];
                $a_data["is_lunas"] = null;
                $a_data["n_bayar"] = null;
                BiayaHandling::where("id_biaya", $value)->update($a_data);
            }

            // update handling bayar
            foreach ($request->c_pro as $key => $value) {
                HandlingBiaya::where("id_biaya", $value)->delete();
            }

            // update n_bayar handling
            $a_handling =[];
            $n_bayar = BiayaHandling::select(DB::raw('SUM(n_bayar) as n_bayar'))->where("id_handling", $id)
                        ->where("is_lunas", true)->get()->first();

            $a_handling["n_bayar"] = $n_bayar->n_bayar;
            Handling::where("id_handling", $id)->update($a_handling);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Handling Gagal Di Dibatalkan'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Handling Sukses Di Dibatalkan');
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $biaya = InvoiceHandling::findOrfail($id);
            if($biaya->id_status!="1"){
                return redirect()->back()->with('error', 'Data Biaya Handling Gagal Disimpan, Karena sudah di proses');
                DB::rollback();
            }

            $biaya->id_perush_tj = $request->id_perush_tj;
            $biaya->tgl_buat = $request->tgl_buat;
            $biaya->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Handling Gagal Disimpan, '.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Biaya Handling Sukses');
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
            $biaya = InvoiceHandling::findOrfail($id);
            if($biaya->id_status!="1"){
                return redirect()->back()->with('error', 'Data Biaya Handling Gagal Disimpan, Karena sudah di proses');
                DB::rollback();
            }

            $biaya->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Biaya Handling Gagal Dihapus, '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Biaya Handling Dihapus');
    }
}
