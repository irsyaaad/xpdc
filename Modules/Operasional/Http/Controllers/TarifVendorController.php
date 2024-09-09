<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Vendor;
use App\Models\Tarif;
use DB;
use Auth;
use App\Models\Layanan;
use App\Models\Wilayah;
use App\Http\Requests\TarifRequest;
use App\Models\RoleUser;
use Session;
use App\Models\GroupVendor;

class TarifVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {   
        $page = 50;
        $f_id_grup_ven = $request->f_id_grup_ven;
        $f_id_ven = $request->f_id_ven;
        $id_perus = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data = [];
        $data["data"] = Vendor::getListVendor($page, $f_id_grup_ven, $f_id_ven);
        $data["group"] = GroupVendor::select("id_grup_ven", "nm_grup_ven")->get();
        
        if($f_id_ven != null){
            $f_id_ven = Vendor::select("id_ven", "nm_ven")->where("id_ven", $f_id_ven)->get()->first();
        }

        $data["filter"] = array("f_id_ven" => $f_id_ven, "f_id_grup_ven" => $f_id_grup_ven, "page"=> $page);
        

        return view('operasional::tarifvendor', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $data["layanan"] = Layanan::all();

        return view('operasional::tarifvendor', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(TarifRequest $request)
    {   
        try {

            DB::beginTransaction();
            $tarif                      = new Tarif();

            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_asal             = $request->id_asal;
            $tarif->id_tujuan           = $request->id_tujuan;
            $tarif->id_layanan          = $request->id_layanan;
            $tarif->hrg_vol             = $request->hrg_vol;
            $tarif->hrg_brt             = $request->hrg_brt;
            $tarif->min_vol             = $request->min_vol;
            $tarif->min_brt             = $request->min_brt;
            $tarif->estimasi             = $request->estimasi;
            $tarif->hrg_beli_kilo             = $request->hrg_beli_kilo;
            $tarif->hrg_beli_vol             = $request->hrg_beli_vol;
            $tarif->hrg_beli_borongan             = $request->hrg_beli_borongan;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_ven              = $request->id_ven;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            //cek tarif
            $cek                        = Tarif::where("id_layanan", $request->id_layanan)
                    ->where("id_asal", $request->id_asal)
                    ->where("id_tujuan", $request->id_tujuan)
                    ->where("id_perush",$tarif->id_perush)
                    ->where("id_ven", $request->id_ven)->get()->first();

            if($cek!=null){
                return redirect()->back()->with('error', 'Data tarif sudah ada');
            }

            $tarif->save();
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }

        return redirect(url("tarifvendor/".$tarif->id_ven."/show"))->with('success', 'Data tarif Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        session()->forget('id_asal');
        session()->forget('id_tujuan');
        $data["data"] = Vendor::findOrFail($id);
        $data["tarif"] = Tarif::getTarifVendor($id);
        //dd($data);
        return view('operasional::tarifvendor', $data);
    }

    public function filtershow(Request $request)
    {
        //dd($request->request);
        $data["data"] = Vendor::findOrFail($request->id_ven);
        $tarif = Tarif::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")->where('id_ven',$request->id_ven)->get();
        if ($request->method()=="POST") {            
            if(isset($request->filterasal) and $request->filterasal!="0"){
                $tarif = $tarif->where("id_asal",$request->filterasal);
                $session = [];
                $session['id_asal'] = $request->filterasal;
                Session($session);
                $data["asal"] = Wilayah::find(Session('id_asal'));
            }

            if(isset($request->filtertujuan) and $request->filtertujuan!="0"){
                $tarif = $tarif->where("id_tujuan",$request->filtertujuan);
                $session = [];
                $session['id_tujuan'] = $request->filtertujuan;
                Session($session);
                $data["tujuan"] = Wilayah::find(Session('id_tujuan'));
            }
            
        }
        $data["tarif"] = $tarif;
        //dd($data,$request->request,Session('id_asal'),Session('id_tujuan'));
        return view('operasional::tarifvendor', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $tarif          = Tarif::with("asal", "tujuan", "perusahaan", "pelanggan", "layanan")->findOrFail($id);
        $data["datas"]  = $tarif;
        $data["data"]   = Vendor::with("perusahaan", "group", "wilayah", "cara")->findOrFail($tarif->id_ven);
        $data["layanan"] = Layanan::all();

        return view('operasional::tarifvendor', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            $tarif                      = Tarif::findOrFail($id);
            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_asal             = $request->id_asal;
            $tarif->id_tujuan           = $request->id_tujuan;
            $tarif->id_layanan          = $request->id_layanan;
            $tarif->hrg_vol             = $request->hrg_vol;
            $tarif->hrg_brt             = $request->hrg_brt;
            $tarif->min_vol             = $request->min_vol;
            $tarif->min_brt             = $request->min_brt;
            $tarif->estimasi             = $request->estimasi;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_ven              = $request->id_ven;
            $tarif->hrg_beli_kilo             = $request->hrg_beli_kilo;
            $tarif->hrg_beli_vol             = $request->hrg_beli_vol;
            $tarif->hrg_beli_borongan             = $request->hrg_beli_borongan;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            $tarif->save();

            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }

        return redirect(url("tarifvendor/".$tarif->id_ven."/show"))->with('success', 'Data tarif Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            
            $tarif = Tarif::findOrFail($id);
            $tarif->delete();
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data tarif dihapus');
    }
}
