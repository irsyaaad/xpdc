<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Keuangan\Entities\BiayaHpp;
use Modules\Keuangan\Entities\GenIdBiayaHpp;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\SttDm;
use DB;
use Auth;
Use Exception;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\SettingBiayaVendor;
use Modules\Keuangan\Entities\InvHandlingPendapatanBayar;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Modules\Keuangan\Entities\SettingHandlingPerush;
use Validator;
use Modules\Operasional\Entities\SttModel;

class BiayaHppController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = $request->shareselect!=null?$request->shareselect:50;
        $f_id_dm = $request->f_id_dm!=null?$request->f_id_dm:null;
        $f_perushtj = $request->f_perushtj!=null?$request->f_perushtj:null;
        $vendor = $request->id_vendor!=null?$request->id_vendor:null;
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:null;
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:null;
        $a_data = DaftarMuat::getListBiaya()
                    ->where(
                        function ($query) { 
                            $query->whereRaw('is_vendor != true')
                                ->orWhereNull('is_vendor');
                    });

        $id_perush = Session("perusahaan")["id_perush"];
        
        if($f_id_dm != null){
            $a_data = $a_data->where("id_dm", $f_id_dm);
        }
        if($f_perushtj != null){
            $a_data = $a_data->where("id_perush_tj", $f_perushtj);
        }
        if($dr_tgl != null){
            $a_data = $a_data->where("tgl_berangkat",">=", $dr_tgl);
        }
        if($sp_tgl != null){
            $a_data = $a_data->where("tgl_berangkat", "<=", $sp_tgl);
        }
        
        $data["perush"] = Perusahaan::getDataExept();
        $data["no_dm"] = DaftarMuat::where("id_perush_dr", $id_perush)
                        ->where(
                                function ($query) { 
                                $query->whereRaw('is_vendor != true')
                                    ->orWhereNull('is_vendor');
                        })
                        ->select("id_dm", "kode_dm")->get();
        $filter = array("f_perushtj"=> $f_perushtj, "f_id_dm"=>$f_id_dm, "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl, "page" => $page);
        $data["filter"] = $filter;
        $data["data"] = $a_data->paginate($page);
        return view('keuangan::biaya.index', $data);
    }
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        abort(404);
    }

    public function listbayar($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dm = DaftarMuat::with("vendor")->findOrFail($id);
        $data["dm"] = $dm;
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["date"] = date("Y-m-d");
        $data["akun"] = ACPerush::getACDebit();
        $data["bayar"] = BiayaHpp::getbayar($id, $id_perush);
        
        return view('keuangan::biaya.listbayar', $data);
    }

    public function cetakbayar($id)
    {
        $newdata = BiayaHpp::findOrFail($id);
        $data["data"] = $newdata;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        // dd($newdata);
        return view('keuangan::biaya.cetak-transaksi', $data);
    }

    public function bayar($id)
    {
        $dm = DaftarMuat::findOrFail($id);
        $data["dm"] = $dm;
        $data["biaya"] = ProyeksiDm::with("group", "proyeksi", "user")->where("id_dm", $id)->get();

        $biaya = BiayaHpp::select("id_proyeksi", DB::raw('SUM(n_bayar) as n_bayar'))->groupBy('id_proyeksi')->get();
        $data["cara"] = CaraBayar::all();
        $data["akun"] = ACPerush::getACDebit();
        $data["ac"] = ACPerush::getACDebit($dm->id_perush_tj);

        return view('keuangan::biaya.index', $data);
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'ac4_k' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'n_bayar' => 'bail|numeric|required',
            'tgl_bayar' => 'bail|date|required'
        ])->setAttributeNames([
            'ac4_k' => 'akun kredit',
            'n_bayar' => 'nilai bayar',
            'tgl_bayar' => 'tanggal pembayaran',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if(!isset($request->c_pro) && $request->c_pro==null){
            return redirect()->back()->with('error', 'Komponen Biaya Tidak dipilih');
        }
        
        DB::beginTransaction();
        try {

            $id_perush = Session("perusahaan")["id_perush"];
            // jika yang dipilih 1 komponen biaya
            $dm = DaftarMuat::findOrFail($request->id_dm);
            $total = 0;
            $a_data = [];
            $a_proyeksi = [];
            $a_bayar = [];

            if(count($request->c_pro) > 1){
                $total = 0;
                foreach($request->c_pro as $key => $value){
                    $proyeksi = ProyeksiDM::findOrfail($value);
                    $biaya = BiayaHpp::select(DB::raw('SUM(n_bayar) as n_bayar'))->where("id_proyeksi", $value)->get()->first();
                    $ac = SettingBiayaPerush::where("id_biaya_grup", $proyeksi->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
                    $group = GroupBiaya::select("nm_biaya_grup")->where("id_biaya_grup", $proyeksi->id_biaya_grup)->get()->first();

                    $total += $proyeksi->nominal - $proyeksi->n_bayar;
                    $sisa = $proyeksi->nominal - $biaya->n_bayar;

                    if($ac==null){
                        return redirect()->back()->with('error', 'Group Biaya '.$group->nm_biaya_grup.' Belum Di Mapping');
                    }
                    
                    $a_data[$key]["id_user"] = Auth::user()->id_user;
                    $a_data[$key]["info"] = $request->info;
                    $a_data[$key]["tgl_bayar"] = $request->tgl_bayar;
                    $a_data[$key]["id_perush_tj"] = $dm->id_perush_tj;
                    $a_data[$key]["id_perush"] = $id_perush;
                    $a_data[$key]["id_dm"] = $request->id_dm;
                    $a_data[$key]["id_proyeksi"] = $value;
                    $a_data[$key]["id_biaya_grup"] = $proyeksi->id_biaya_grup;
                    $a_data[$key]["biaya"] = $proyeksi->nominal;
                    $a_data[$key]["n_bayar"] =  $sisa;
                    $a_data[$key]["ac4_debit"] = $ac->id_ac_hutang;
                    $a_data[$key]["ac4_kredit"] = $request->ac4_k;
                    $a_data[$key]["created_at"] = date("Y-m-d H:i:s");
                    $a_data[$key]["updated_at"] = date("Y-m-d H:i:s");
                    $a_data[$key]["id_stt"] =  $proyeksi->id_stt;
                    $a_data[$key]["kode_stt"] =  $proyeksi->kode_stt;
                    $a_data[$key]["kode_dm"] =  $proyeksi->kode_dm;
                    $a_data[$key]["info"] =  "Pembayaran ".$group->nm_biaya_grup." DM ".$dm->kode_dm;
                    $a_data[$key]["id_invoice"] =  $proyeksi->id_invoice;
                    $a_data[$key]["id_inv_pend"] =  $proyeksi->id_inv_pend;
                    $a_data[$key]["id_handling"] =  null;
                    $a_data[$key]["kode_handling"] =  null;
                    $a_data[$key]["id_jenis"] =  $proyeksi->id_jenis;
                    $a_data[$key]["id_ven"] = null;
                    if($proyeksi->id_jenis!=1){
                        $a_data[$key]["id_ven"] = $dm->id_ven;
                    }

                    if (isset($proyeksi->id_handling) and isset($proyeksi->kode_handling)) {
                        $a_data[$key]["id_handling"] =  $proyeksi->id_handling;
                        $a_data[$key]["kode_handling"] =  $proyeksi->kode_handling;
                    }

                    // for proyeksi hpp dm
                    $a_proyeksi[$key]["id_pro_bi"] = $value;
                    $a_proyeksi[$key]["is_lunas"] = true;
                    $a_proyeksi[$key]["n_bayar"] = $proyeksi->nominal;

                    if(isset($proyeksi->id_inv_pend) and $proyeksi->id_inv_pend != null){
                        $set = SettingHandlingPerush::where("id_perush", $id_perush)->get()->first();
                        $a_data[$key]["ac4_debit"] = $set->ac4_hutang;

                        $a_bayar[$key]["id_biaya_pend"] = $proyeksi->id_inv_pend;
                        $a_bayar[$key]["id_invoice"] = $proyeksi->id_invoice;
                        $a_bayar[$key]["id_perush"] = $proyeksi->id_perush_tj;
                        $a_bayar[$key]["id_perush_tj"] = $proyeksi->id_perush_dr;
                        $a_bayar[$key]["id_handling"] = $proyeksi->id_handling;
                        $a_bayar[$key]["kode_handling"] = $proyeksi->kode_handling;
                        $a_bayar[$key]["id_dm"] = $proyeksi->id_dm;
                        $a_bayar[$key]["kode_dm"] = $proyeksi->kode_dm;
                        $a_bayar[$key]["id_stt"] = $proyeksi->id_stt;
                        $a_bayar[$key]["kode_stt"] = $proyeksi->kode_stt;
                        $a_bayar[$key]["nominal"] = $sisa;
                        $a_bayar[$key]["id_biaya_grup"] = $proyeksi->id_biaya_grup;
                        $a_bayar[$key]["created_at"] = date("Y-m-d H:i:s");
                        $a_bayar[$key]["updated_at"] = date("Y-m-d H:i:s");
                        $a_bayar[$key]["keterangan"] =  "Pembayaran ".$group->nm_biaya_grup." Handling ".$proyeksi->kode_handling;
                    }
                }

                if($request->n_bayar != $total){
                    return redirect()->back()->with('error', 'Nilai Bayar Tidak Sama');
                }

            }else{
                $proyeksi = ProyeksiDM::where("id_pro_bi", $request->c_pro)->get()->first();
                $biaya = BiayaHpp::select(DB::raw('SUM(n_bayar) as n_bayar'))->where("id_proyeksi", $proyeksi->id_pro_bi)->get()->first();
                $ac = SettingBiayaPerush::where("id_biaya_grup", $proyeksi->id_biaya_grup)->where("id_perush", $id_perush)->get()->first();
                $group = GroupBiaya::select("nm_biaya_grup")->where("id_biaya_grup", $proyeksi->id_biaya_grup)->get()->first();

                if($ac==null){
                    return redirect()->back()->with('error', 'Group Biaya '.$group->nm_biaya_grup.' Belum Di Mapping');
                }

                $bayar = $biaya->n_bayar + $request->n_bayar;
                $sisa = $proyeksi->nominal - $bayar;

                if($bayar > $proyeksi->nominal){
                    return redirect()->back()->with('error', 'Nilai Bayar Terlalu Besar');
                }

                $a_data["id_user"] = Auth::user()->id_user;
                $a_data["info"] = $request->info;
                $a_data["tgl_bayar"] = $request->tgl_bayar;
                $a_data["id_perush_tj"] = $dm->id_perush_tj;
                $a_data["id_perush"] = Session("perusahaan")["id_perush"];
                $a_data["id_dm"] = $request->id_dm;
                $a_data["id_proyeksi"] = $proyeksi->id_pro_bi;
                $a_data["id_biaya_grup"] = $proyeksi->id_biaya_grup;
                $a_data["biaya"] = $proyeksi->nominal;
                $a_data["n_bayar"] =  $request->n_bayar;
                $a_data["ac4_debit"] = $ac->id_ac_hutang;
                $a_data["ac4_kredit"] = $request->ac4_k;
                $a_data["created_at"] = date("Y-m-d H:i:s");
                $a_data["updated_at"] = date("Y-m-d H:i:s");
                $a_data["id_stt"] =  $proyeksi->id_stt;
                $a_data["kode_stt"] =  $proyeksi->kode_stt;
                $a_data["kode_dm"] =  $proyeksi->kode_dm;
                $a_data["info"] =  "Pembayaran ".$group->nm_biaya_grup." DM ".$dm->kode_dm;
                $a_data["id_invoice"] =  $proyeksi->id_invoice;
                $a_data["id_inv_pend"] =  $proyeksi->id_inv_pend;
                $a_data["id_jenis"] =  $proyeksi->id_jenis;
                $a_data["id_ven"] = null;

                if($proyeksi->id_jenis!=1){
                    $a_data["id_ven"] = $dm->id_ven;
                }

                if (isset($proyeksi->id_handling) and isset($proyeksi->kode_handling)) {
                    $a_data["id_handling"] =  $proyeksi->id_handling;
                    $a_data["kode_handling"] =  $proyeksi->kode_handling;
                }

                // for proyeksi hpp dm
                $a_proyeksi[0]["id_pro_bi"] = $proyeksi->id_pro_bi;
                $a_proyeksi[0]["is_lunas"] = false;
                $a_proyeksi[0]["n_bayar"] = $bayar;

                if($sisa == 0){
                    $a_proyeksi[0]["is_lunas"] = true;
                }

                if(isset($proyeksi->id_inv_pend) and $proyeksi->id_inv_pend != null){
                    $set = SettingHandlingPerush::where("id_perush", $id_perush)->get()->first();
                    $a_data["ac4_debit"] = $set->ac4_hutang;
                    
                    $a_bayar["id_biaya_pend"] = $proyeksi->id_inv_pend;
                    $a_bayar["id_invoice"] = $proyeksi->id_invoice;
                    $a_bayar["id_perush"] = $proyeksi->id_perush_tj;
                    $a_bayar["id_perush_tj"] = $proyeksi->id_perush_dr;
                    $a_bayar["id_handling"] = $proyeksi->id_handling;
                    $a_bayar["kode_handling"] = $proyeksi->kode_handling;
                    $a_bayar["id_dm"] = $proyeksi->id_dm;
                    $a_bayar["kode_dm"] = $proyeksi->kode_dm;
                    $a_bayar["id_stt"] = $proyeksi->id_stt;
                    $a_bayar["kode_stt"] = $proyeksi->kode_stt;
                    $a_bayar["nominal"] = $request->n_bayar;
                    $a_bayar["id_biaya_grup"] = $proyeksi->id_biaya_grup;
                    $a_bayar["created_at"] = date("Y-m-d H:i:s");
                    $a_bayar["updated_at"] = date("Y-m-d H:i:s");
                    $a_bayar["keterangan"] =  "Pembayaran ".$group->nm_biaya_grup." Handling ".$proyeksi->kode_handling;
                }
            }

            // insert to biaya bayar
            BiayaHpp::insert($a_data);

            // set is lunas in proyeksi biaya
            foreach($a_proyeksi as $key => $value){
                $proyeksi = [];
                $proyeksi["is_lunas"] = $value["is_lunas"];
                $proyeksi["n_bayar"] = $value["n_bayar"];

                ProyeksiDm::where("id_pro_bi", $value["id_pro_bi"])->update($proyeksi);
            }

            // for invoice handling pendapatan
            if(count($a_bayar) > 0){
                InvHandlingPendapatanBayar::insert($a_bayar);
            }

            // sum for dm
            $sum = ProyeksiDm::where("id_dm", $request->id_dm)->sum("n_bayar");
            if($sum == $dm->c_pro){
                $dm->is_lunas = true;
            }
            $dm->n_bayar = $sum;
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pembayaran Biaya Gagal Disimpan'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pembayaran Biaya Sukses Disimpan');
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["dm"] = DaftarMuat::findOrFail($id);
        $data["biaya"] = ProyeksiDm::where("id_dm", $id)->orderBy("tgl_posting", "asc")->orderBy("id_pro_bi", "asc")->get();
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["stt"] = SttModel::getSttDM($id);
        
        return view('keuangan::biaya.index', $data);
    }

    public function approve($id)
    {
        $biaya = ProyeksiDm::where("id_dm", $id)->get()->first();
        $stt = SttDm::where("id_dm", $id)->get()->first();

        if($biaya == null || $stt == null){
            return redirect()->back()->with('error', 'STT dan Biaya Tidak Boleh Kosong ');
        }

        DB::beginTransaction();
        try {
            $dm = DaftarMuat::findOrFail($id);
            $dm->is_approve = true;
            $dm->date_approve = date("Y-m-d");
            $dm->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Biaya Gagal di Approve'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Biaya Berhasil di Approve');
    }

    public function batalapprove($id)
    {
        try {

            DB::beginTransaction();
            $dm = DaftarMuat::findOrFail($id);
            $dm->is_approve = false;
            $dm->date_approve = date("Y-m-d");
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Batal Approve Gagal'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Approve Biaya Di Batalkan');
    }

    public function approvevendor(Request $request, $id)
    {
        $biaya = ProyeksiDm::where("id_dm", $id)->get()->first();
        $stt = SttDm::where("id_dm", $id)->get()->first();
        $dm = DaftarMuat::findOrFail($id);

        if($biaya == null || $stt == null){
            return redirect()->back()->with('error', 'STT dan Biaya Tidak Boleh Kosong ');
        }

        try {

            DB::beginTransaction();
            // update approve biaya
            $dm = DaftarMuat::findOrFail($id);
            $dm->is_approve = true;
            $dm->date_approve = date("Y-m-d");
            $dm->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Biaya Gagal di Approve'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Biaya Berhasil di Approve');
    }

    public function print($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["dm"] = DaftarMuat::findOrFail($id);
        $data["biaya"] = ProyeksiDm::where("id_dm", $id)->orderBy("created_at", "asc")->get();
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["stt"] = SttDm::getStt($id);
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        //dd($data);
        return view('keuangan::biaya.print', $data);
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        return view('keuangan::biaya.index');
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
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
}
