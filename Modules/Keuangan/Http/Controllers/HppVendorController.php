<?php

namespace Modules\Keuangan\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Keuangan\Entities\BiayaHpp;
use Modules\Keuangan\Entities\BiayaHppDel;
use Modules\Keuangan\Entities\SettingBiaya;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\SttModel;
use DB;
use Auth;
Use Exception;
use App\Models\Perusahaan;
use App\Models\Vendor;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\SettingBiayaVendor;
use Modules\Keuangan\Entities\SettingBiayaPerush;
use Validator;

class HppVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->page!=null?$request->page:1;
        $perpage = $request->shareselect!=null?$request->shareselect:50;
        $f_id_dm = $request->f_id_dm!=null?$request->f_id_dm:null;
        $f_perushtj = $request->f_perushtj!=null?$request->f_perushtj:null;
        $f_id_ven = $request->f_id_ven!=null?$request->f_id_ven:null;
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:null;
        $sp_tgl = $request->sp_tgl!=null?$request->sp_tgl:null;
        $id_stt = $request->f_id_stt!=null?$request->f_id_stt:null;
        $f_no = $request->f_no!=null?$request->f_no:null;
        $id_perush = Session("perusahaan")["id_perush"];

        $biaya = DaftarMuat::getBiayaHpp($page, $perpage, $f_id_dm, true, $id_stt, $id_perush, $f_perushtj, $f_id_ven, $dr_tgl, $sp_tgl, $f_no);
        $data["data"] = $biaya;
        $data["perush"] = Perusahaan::getDataExept();
        $data["vendor"] = Vendor::getData($id_perush);
        $data["no_dm"] = DaftarMuat::where("id_perush_dr", $id_perush)->where("is_vendor", true)
        ->select("id_dm", "kode_dm")->get();
        $data["no_stt"] = SttModel::select("id_stt", "kode_stt")->where("id_perush_asal", $id_perush)->get();
        $data["no_container"] = DaftarMuat::getNoConatiner();
        $data["filter"] = array("f_id_dm"=>$f_id_dm,
        "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl, "f_id_ven"=>$f_id_ven,
        "f_no" => $f_no,"f_id_stt"=>$id_stt, "page" => $perpage);

        return view('keuangan::biayavendor.index', $data);
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

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dm = DaftarMuat::findOrFail($id);
        $data["dm"] = $dm;
        $data["stt"] = SttModel::getSttDM($id);
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["date"] = date("Y-m-d");
        $bstt = ProyeksiDm::getProyeksi($id, "0");
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $bvendor = ProyeksiDm::getProyeksi($id, "2");
        $data["bstt"] = $bstt;
        $data["bumum"] = $bumum;
        $data["bvendor"] = $bvendor;
        
        return view('keuangan::biayavendor.detail', $data);
    }

    public function listbayar($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dm = DaftarMuat::with("vendor")->findOrFail($id);
        $data["dm"] = $dm;
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["date"] = date("Y-m-d");
        $data["akun"] = ACPerush::getACDebit();
        $data["bstt"] = BiayaHpp::getbayarVendor($id, $id_perush, 0);
        $data["bumum"] = BiayaHpp::getbayarVendor($id, $id_perush, 1);
        $data["bvendor"] = BiayaHpp::getbayarVendor($id, $id_perush, 2);
        
        return view('keuangan::biayavendor.listbayar', $data);
    }

    public function updatebayar(Request $request, $id)
    {
        $bayar = BiayaHpp::findOrFail($id);

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
        }else{
            DB::beginTransaction();
            try {

                $bayar = BiayaHpp::findOrFail($id);
                $bayar->n_bayar = $request->n_bayar;
                $bayar->tgl_bayar = $request->tgl_bayar;
                $bayar->ac4_kredit = $request->ac4_k;
                $bayar->user_edit = Auth::user()->id_user;

                $bayar->save();
                
                $sum = BiayaHpp::where("id_proyeksi", $bayar->id_proyeksi)->sum("n_bayar");
                $proyeksi = ProyeksiDm::findOrFail($bayar->id_proyeksi);
                
                if($sum > $proyeksi->nominal){
                    DB::rollback();
                    return redirect()->back()->with('error', 'Total Pembayaran Biaya tidak boleh lebih');
                }

                $a_data["n_bayar"] = $sum;
                $a_data["is_lunas"] = false;
                if($sum == $proyeksi->nominal){
                    $a_data["is_lunas"] = true;
                }
                
                ProyeksiDm::where("id_pro_bi", $bayar->id_proyeksi)->update($a_data);
                DaftarMuat::where("id_dm", $bayar->id_dm)->update($a_data);

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Pembayaran gagal disimpan, '.$e->getMessage());
            }
    
            return redirect()->back()->with('success', 'Pembayaran Berhasil disimpan');
        }
    }
    
    public function bayar($id)
    {
        $dm = DaftarMuat::findOrFail($id);
        $bayar = BiayaHpp::select(DB::raw('SUM(n_bayar) as n_bayar'))->where("id_dm", $id)->get()->first();
        $data["n_bayar"] = $bayar;
        $data["dm"] = $dm;
        $bstt = ProyeksiDm::getProyeksi($id, "0");
        $bumum = ProyeksiDm::getProyeksi($id, "1");
        $bvendor = ProyeksiDm::getProyeksi($id, "2");
        $data["bstt"] = $bstt;
        $data["bumum"] = $bumum;
        $data["bvendor"] = $bvendor;
        $biaya = BiayaHpp::select("id_proyeksi", DB::raw('SUM(n_bayar) as n_bayar'))->groupBy('id_proyeksi')->get();
        $data["cara"] = CaraBayar::all();
        $data["akun"] = ACPerush::getACDebit();
        
        return view('keuangan::biayavendor.bayar', $data);
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
        abort(404);
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

            $bayar = BiayaHpp::findOrFail($id);
            $del = new BiayaHppDel();

            $del->id_biaya = $bayar->id_biaya;
            $del->id_perush = $bayar->id_perush;
            $del->id_proyeksi = $bayar->id_proyeksi;
            $del->id_biaya_grup = $bayar->id_biaya_grup;
            $del->biaya = $bayar->biaya;
            $del->n_bayar = $bayar->n_bayar;
            $del->info =$bayar->info;
            $del->created_at = $bayar->created_at;
            $del->updated_at = $bayar->updated_at;
            $del->tgl_bayar = $bayar->tgl_bayar;
            $del->id_user =$bayar->id_user;
            $del->id_perush_tj = $bayar->id_perush_tj;
            $del->id_ven = $bayar->id_ven;
            $del->ac4_debit =$bayar->ac4_debit; 
            $del->ac4_kredit = $bayar->ac4_kredit;
            $del->id_stt = $bayar->id_stt;
            $del->kode_stt = $bayar->kode_stt;
            $del->id_handling = $bayar->id_handling;
            $del->kode_handling = $bayar->kode_handling;
            $del->id_dm = $bayar->id_dm;
            $del->kode_dm = $bayar->kode_dm;
            $del->tipe = $bayar->tipe;
            $del->id_invoice = $bayar->id_invoice;
            $del->id_inv_pend = $bayar->id_inv_pend;
            $del->keterangan = $bayar->keterangan;
            $del->user_edit = $bayar->user_edit;
            $del->save();

            $bayar->delete();
            
            $sum = BiayaHpp::where("id_proyeksi", $bayar->id_proyeksi)->sum("n_bayar");
            
            $a_data["n_bayar"] = $sum;
            $a_data["is_lunas"] = false;
            
            ProyeksiDm::where("id_pro_bi", $bayar->id_proyeksi)->update($a_data);
            DaftarMuat::where("id_dm", $bayar->id_dm)->update($a_data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Pembayaran gagal dihapus, '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Pembayaran Berhasil dihapus');
    }

    public function print($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["dm"] = DaftarMuat::findOrFail($id);
        $data["biaya"] = ProyeksiDm::where("id_dm", $id)->orderBy("created_at", "asc")->get();
        $data["group"] = SettingBiayaPerush::getData($id_perush);
        $data["stt"] = SttDm::getStt($id);
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        return view('keuangan::biaya.print', $data);
    }
}
