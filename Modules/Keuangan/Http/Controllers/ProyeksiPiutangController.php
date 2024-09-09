<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ProyeksiPiutang;
use Modules\Keuangan\Entities\ProyeksiPiutangDetail;
use DB;
use Auth;
use Exception;
use Session;
use App\Models\User;

class ProyeksiPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = $request->tahun!=null?$request->tahun:date("Y");
        $data["data"] = ProyeksiPiutang::getProyeksi($id_perush, $tahun);
        $data["tahun"] = ProyeksiPiutang::getTahun();
        $data["filter"] = array("tahun"=>$tahun);

        return view('keuangan::proyeksi-piutang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["data"] = [];
        $data["bulan"] = ProyeksiPiutang::getBulan();
        $data["tahun"] = ProyeksiPiutang::getTahun();

        return view('keuangan::proyeksi-piutang.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ], [
            'bulan.required' => 'Bulan  harus di pilih',
            'tahun.required' => 'Tahun  harus di pilih'
        ]);

        $id_perush = Session("perusahaan")["id_perush"];
        $cek = ProyeksiPiutang::where("bulan", $request->bulan)
                ->where("tahun", $request->tahun)
                ->where("id_perush", $id_perush)
                ->where("id_user", Auth::user()->id_user)->get()->first();

        if($cek!=null){
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Proyeksi Piutang Sudah Ada ');
        }

        $id =null;
        try {
            DB::beginTransaction();

            $proyeksi = new ProyeksiPiutang();
            $proyeksi->bulan = $request->bulan;
            $proyeksi->tahun = $request->tahun;
            $proyeksi->id_perush = $id_perush;
            $proyeksi->id_user = Auth::user()->id_user;
            $proyeksi->save();
            DB::commit();

            $id = DB::getPdo()->lastInsertId();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Proyeksi Piutang Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()."/".$id))->with('success', 'Data Proyeksi Piutang Disimpan');
    }

    public function savedetail(Request $request,$id)
    {
        $request->validate([
            'check' => 'required',
        ], [
            'check.required' => 'No Stt harus di pilih',
        ]);
        
        try {
            DB::beginTransaction();
            $proyeksi = ProyeksiPiutang::findOrFail($id);

            $data = [];
            $piutang = $request->piutang;
            foreach($request->check as $key => $value){
                $data[$key]["id_stt"] = $value;
                $data[$key]["id_user"] = Auth::user()->id_user;
                $data[$key]["created_at"] = date("Y-m-d H:i:s");
                $data[$key]["updated_at"] = date("Y-m-d H:i:s");
                $data[$key]["id_proyeksi"] = $id;
                $data[$key]["bulan"] = $proyeksi->bulan;
                $data[$key]["tahun"] = $proyeksi->tahun;
                $data[$key]["id_perush"] =$proyeksi->id_perush;
                $data[$key]["piutang"] = $piutang[$key];
            }
            ProyeksiPiutangDetail::insert($data);
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Detail Proyeksi Piutang Gagal Disimpan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Detail Proyeksi Piutang Disimpan');
    }

    public function deletedetail($id)
    {
        
        try {
            DB::beginTransaction();
            $proyeksi = ProyeksiPiutangDetail::findOrFail($id);
            $proyeksi->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Detail Proyeksi Piutang Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Detail Proyeksi Piutang Dihapus');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $proyeksi = ProyeksiPiutang::findOrFail($id);
        $data["data"] = $proyeksi;
        $data["bulan"] = ProyeksiPiutang::getBulan();
        $data["tahun"] = ProyeksiPiutang::getTahun();
        $data["sum"] = ProyeksiPiutangDetail::where("id_proyeksi", $id)->sum("piutang");
        $data["stt"] = ProyeksiPiutang::getListStt($proyeksi->bulan, $proyeksi->tahun, $proyeksi->id_perush);
        $data["proyeksi"] = ProyeksiPiutang::getDataProyeksi($id);
        
        return view('keuangan::proyeksi-piutang.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = ProyeksiPiutang::findOrFail($id);
        $data["bulan"] = ProyeksiPiutang::getBulan();
        $data["tahun"] = ProyeksiPiutang::getTahun();

        return view('keuangan::proyeksi-piutang.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ], [
            'bulan.required' => 'Bulan  harus di pilih',
            'tahun.required' => 'Tahun  harus di pilih'
        ]);

        $id_perush = Session("perusahaan")["id_perush"];
        $cek = ProyeksiPiutang::where("bulan", $request->bulan)
                ->where("tahun", $request->tahun)
                ->where("id_perush", $id_perush)
                ->where("id_user", Auth::user()->id_user)->get()->first();

        if($cek!=null){
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Proyeksi Piutang Sudah Ada ');
        }
        try {
            DB::beginTransaction();

            $proyeksi = ProyeksiPiutang::findOrFail($id);
            $proyeksi->bulan = $request->bulan;
            $proyeksi->tahun = $request->tahun;
            $proyeksi->id_perush = $id_perush;
            $proyeksi->id_user = Auth::user()->id_user;
            $proyeksi->save();
            
            $detail = [];
            $detail["bulan"] = $proyeksi->bulan;
            $detail["tahun"] = $proyeksi->tahun;
            $detail["id_user"] = $proyeksi->id_user;
            // update where is detail exists
            ProyeksiPiutangDetail::where("id_proyeksi", $id)->update($detail);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Proyeksi Piutang Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()."/".$id))->with('success', 'Data Proyeksi Piutang Disimpan');
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
            $proyeksi = ProyeksiPiutang::findOrFail($id);
            
            ProyeksiPiutangDetail::where("id_proyeksi", $id)->delete();

            $proyeksi->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Proyeksi Piutang Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Proyeksi Piutang Dihapus');
    }
    
    public function repproyeksipiutang(Request $request){
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = $request->tahun!=null?$request->tahun:date("Y");
        $bulan = $request->bulan!=null?$request->bulan:date("m");

        $tgl_awal = date("Y-m-d", strtotime($tahun."-".$bulan."-"."01"));
        $dates  = date("Y-m-d", strtotime($tahun."-".$bulan));
        $tgl_akhir = date("Y-m-t", strtotime($dates));

        $dr_tgl1 = date("Y-m-d", strtotime($tahun."-".$bulan."-"."01"));
        $sp_tgl1  = date("Y-m-d", strtotime($tahun."-".$bulan."-"."07"));

        $dr_tgl2 = date("Y-m-d", strtotime($tahun."-".$bulan."-"."08"));
        $sp_tgl2  = date("Y-m-d", strtotime($tahun."-".$bulan."-"."14"));

        $dr_tgl3 = date("Y-m-d", strtotime($tahun."-".$bulan."-"."14"));
        $sp_tgl3  = date("Y-m-d", strtotime($tahun."-".$bulan."-"."21"));

        $dr_tgl4 = date("Y-m-d", strtotime($tahun."-".$bulan."-"."21"));
        $sp_tgl4  = date("Y-m-d", strtotime($tahun."-".$bulan."-"."28"));

        $dr_tgl5 = date("Y-m-d", strtotime($tahun."-".$bulan."-"."29"));
        $sp_tgl5  = date("Y-m-t", strtotime($tahun."-".$bulan));
        
        $data["admin"] = ProyeksiPiutang::getAdminPiutang($tahun, $bulan, $id_perush);
        $data["proyeksi"] = ProyeksiPiutang::getRepProyeksi($tahun, $bulan, $id_perush);
        $data["week1"] = ProyeksiPiutang::getPaymentWeek($dr_tgl1, $sp_tgl1, $id_perush);
        //dd($data);
        $data["week2"] = ProyeksiPiutang::getPaymentWeek($dr_tgl2, $sp_tgl2, $id_perush);
        $data["week3"] = ProyeksiPiutang::getPaymentWeek($dr_tgl3, $sp_tgl3, $id_perush);
        $data["week4"] = ProyeksiPiutang::getPaymentWeek($dr_tgl4, $sp_tgl4, $id_perush);
        $data["week5"] = ProyeksiPiutang::getPaymentWeek($dr_tgl5, $sp_tgl5, $id_perush);
        
        // count
        $data["count"] = ProyeksiPiutang::getCountStt($tahun, $bulan, $id_perush, $tgl_awal, $tgl_akhir);
        $data["tahun"] = ProyeksiPiutang::getTahun();
        $data["bulan"] = ProyeksiPiutang::getBulan();
        $data["filter"] = array("tahun"=>$tahun, "bulan" => $bulan);

        return view('keuangan::proyeksi-piutang.laporan', $data);
    }
    
    public function reppdetail($id, Request $request){
        $id_perush = Session("perusahaan")["id_perush"];
        $tahun = $request->tahun!=null?$request->tahun:date("Y");
        $bulan = $request->bulan!=null?$request->bulan:date("m");

        $tgl_awal = date("Y-m-d", strtotime($tahun."-".$bulan."-"."01"));
        $dates  = date("Y-m-d", strtotime($tahun."-".$bulan));
        $tgl_akhir = date("Y-m-t", strtotime($dates));
        
        $data["user"] = User::with("karyawan")->findOrfail($id);
        $data["data"] = ProyeksiPiutang::getSttByAdmin($bulan, $tahun, $id_perush, $id, $tgl_awal, $tgl_akhir);
        $data["invoice"] = ProyeksiPiutang::getInvoice($id_perush);
        $data["filter"] = array("tahun"=>$tahun, "bulan" => $bulan);
        
        return view('keuangan::proyeksi-piutang.laporan-detail', $data);
    }
}
