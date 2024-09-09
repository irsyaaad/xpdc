<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Pelanggan;
use App\Models\Tarif;
use App\Models\Layanan;
use App\Models\Wilayah;
use App\Http\Requests\TarifRequest;
use App\Models\RoleUser;
use DB;
use Auth;
use App\Models\Grouppelanggan;

class TarifPelangganController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    public function index(Request $request)
    {   
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 50;
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        $f_id_plgn_group = $request->f_id_plgn_group;
        $f_id_pelanggan = $request->f_id_pelanggan;

        $pelanggan = Pelanggan::getFilter($id_perush, $f_id_pelanggan, $f_id_plgn_group);
        $data["data"] = $pelanggan->paginate($page);

        if($f_id_pelanggan != null){
            $f_id_pelanggan = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_pelanggan", $f_id_pelanggan)->get()->first();
        }
        
        $data["group"] = Grouppelanggan::select("id_plgn_group", "nm_group", "kode_plgn_group")->get();
        $data["filter"] = array("page"=>$page, "f_id_pelanggan" => $f_id_pelanggan, "f_id_plgn_group" => $f_id_plgn_group);
        
        return view('operasional::tarifpelanggan', $data);
    }
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {   
        $data["layanan"] = Layanan::all();
        $data["datas"]  = [];
        
        return view('operasional::tarifpelanggan', $data);
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
            $tarif->hrg_kubik           = $request->hrg_kubik;
            $tarif->min_vol             = $request->min_vol;
            $tarif->min_brt             = $request->min_brt;
            $tarif->min_kubik           = $request->min_kubik;
            $tarif->estimasi             = $request->estimasi;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->id_pelanggan        = $request->id_pelanggan;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            //cek tarif
            $cek                        = Tarif::where("id_layanan", $request->id_layanan)
                                        ->where("id_asal", $request->id_asal)
                                        ->where("id_tujuan", $request->id_tujuan)
                                        ->where("id_perush", $tarif->id_perush)
                                        ->where("id_pelanggan", $request->id_pelanggan)->get()->first();
            
            if($cek!=null){
                return redirect()->back()->with('error', 'Data tarif sudah ada');
            }
            
            $tarif->save();
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect(url("tarifpelanggan/".$tarif->id_pelanggan."/show"))->with('success', 'Data tarif Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {   
        $data["data"] = Pelanggan::with("wilayah", "perusahaan", "group")->findOrFail($id);
        $data["tarif"] = Tarif::getTarifPelanggan($id);
        
        return view('operasional::tarifpelanggan', $data);
    }

    public function filtershow(Request $request)
    {
        
        $data["data"] = Pelanggan::with("wilayah", "perusahaan", "group")->findOrFail($request->id_pelanggan);
        $tarif = Tarif::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")->where('id_pelanggan',$request->id_pelanggan)->get();
        
        if ($request->method()=="POST") {            
            if(isset($request->filterasal) and $request->filterasal!="0"){
                $tarif = $tarif->where("id_asal",$request->filterasal);
                $session = [];
                $session['id_asal'] = $request->filterasal;
                Session($session);
                $data["asal"]=Wilayah::find(Session('id_asal'));
            }

            if(isset($request->filtertujuan) and $request->filtertujuan!="0"){
                $tarif = $tarif->where("id_tujuan",$request->filtertujuan);
                $session = [];
                $session['id_tujuan'] = $request->filtertujuan;
                Session($session);
                $data["tujuan"]=Wilayah::find(Session('id_tujuan'));
            }
            
        }
        $data["tarif"] = $tarif;
        return view('operasional::tarifpelanggan', $data);
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
        $data["data"]   = Pelanggan::with("wilayah", "perusahaan", "group")->findOrFail($tarif->id_pelanggan);
        $data["layanan"] = Layanan::all();
        
        return view('operasional::tarifpelanggan', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(TarifRequest $request, $id)
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
            $tarif->hrg_kubik           = $request->hrg_kubik;
            $tarif->min_vol             = $request->min_vol;
            $tarif->min_brt             = $request->min_brt;
            $tarif->min_kubik           = $request->min_kubik;
            $tarif->estimasi             = $request->estimasi;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->id_pelanggan        = $request->id_pelanggan;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect(url("tarifpelanggan/".$tarif->id_pelanggan."/show"))->with('success', 'Data tarif Disimpan');
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
