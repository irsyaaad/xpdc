<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;

use App\Models\Tarif;
use App\Models\Wilayah;
use App\Models\Layanan;
use App\Http\Requests\TarifRequest;
use DB;
use Auth;
use App\Models\Pelanggan;
use App\Models\Proyeksi;
use App\Models\Roleuser;
use Modules\Operasional\Entities\TarifProyeksi;

class P_tarif extends Controller
{
    public function index(Request $request)
    {   
        $page = 50;
        $f_id_asal = $request->f_id_asal;
        $f_id_tujuan = $request->f_id_tujuan;
        $f_id_layanan = $request->f_id_layanan;

        if(isset($request->shareselect) and $request->shareselect != null){
            $page = $request->shareselect;
        }

        if($f_id_asal != null){
            $f_id_asal = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_asal)->get()->first();
        }

        if($f_id_tujuan != null){
            $f_id_tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_tujuan)->get()->first();
        }

        $dataku= Tarif::getTarif($f_id_asal, $f_id_tujuan, $f_id_layanan);  
        $data['data'] = $dataku->paginate($page);    
        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        $data["filter"] = array("page" => $page, "f_id_asal" => $f_id_asal, "f_id_tujuan"=>$f_id_tujuan, "f_id_layanan" => $f_id_layanan);

        return view("tarif", $data);
    }

    public function filter(Request $request)
    {   
        $page = 50;
        $f_id_asal = $request->f_id_asal;
        $f_id_tujuan = $request->f_id_tujuan;
        $f_id_layanan = $request->f_id_layanan;

        if(isset($request->shareselect) and $request->shareselect != null){
            $page = $request->shareselect;
        }

        $dataku= Tarif::getTarif($f_id_asal, $f_id_tujuan, $f_id_layanan);  
        if($f_id_asal != null){
            $f_id_asal = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_asal)->get()->first();
        }

        if($f_id_tujuan != null){
            $f_id_tujuan = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_tujuan)->get()->first();
        }

        $data['data'] = $dataku->paginate($page);    
        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        $data["filter"] = array("page" => $page, "f_id_asal" => $f_id_asal, "f_id_tujuan"=>$f_id_tujuan, "f_id_layanan" => $f_id_layanan);
        
        return view("tarif", $data);
    }

    public function create()
    {
        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        
        return view("tarif", $data);
    }   
    
    public function store(TarifRequest $request)
    {   
        
        //dd($request->request);

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
            $tarif->estimasi            = $request->estimasi;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->hrg_coly            = $request->hrg_coly;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            // cek tarif
            $cek                        = Tarif::where("id_layanan", $request->id_layanan)
            ->where("id_asal", $request->id_asal)
            ->where("id_tujuan", $request->id_tujuan)
            ->where("id_perush",Session("perusahaan")["id_perush"])
            ->get()->first();
            
            if($cek!=null){
                return redirect()->back()->with('error', 'Data tarif sudah ada');
            }
            //dd($tarif);
            $tarif->save();
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect("tarif")->with('success', 'Data tarif Disimpan');
    }
    
    public function show($id)
    {
        $tarif              = Tarif::find($id);
        if(get_admin()!=true and $tarif->id_perush!=Session("perusahaan")["id_perush"]){
            return redirect()->back()->with('error', 'Akses Terbatas');
        }
        
        $data["layanan"]    = Layanan::all();
        $data["asal"]       = Wilayah::find($tarif->id_asal);
        $data["tujuan"]     = Wilayah::find($tarif->id_tujuan);
        $data["pelanggan"]  = Pelanggan::find($tarif->id_pelanggan);
        $data["data"]       = $tarif;
        $data["proyeksi"]   = TarifProyeksi::getData()->where("id_tarif", $id)->get();
        
        return view("tarif", $data);
    }
    
    public function edit($id)
    {
        $tarif              = Tarif::find($id);
        if(get_admin()!=true and $tarif->id_perush!=Session("perusahaan")["id_perush"]){
            
            return redirect()->back()->with('error', 'Akses Terbatas');
        }
        
        $data["layanan"]    = Layanan::all();
        $data["asal"]       = Wilayah::find($tarif->id_asal);
        $data["tujuan"]     = Wilayah::find($tarif->id_tujuan);
        $data["pelanggan"]  = Pelanggan::find($tarif->id_pelanggan);
        $data["data"]       = $tarif;
        
        return view("tarif", $data);
    }
    
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
            $tarif->hrg_kubik             = $request->hrg_kubik;
            $tarif->min_kubik             = $request->min_kubik;
            $tarif->min_vol             = $request->min_vol;
            $tarif->min_brt             = $request->min_brt;
            $tarif->estimasi             = $request->estimasi;
            $tarif->sat_vol             = 1;
            $tarif->sat_brt             = 1;
            $tarif->hrg_coly            = $request->hrg_coly;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect("tarif")->with('success', 'Data tarif Disimpan');
    }
    
    public function createproyeksi($id)
    {
        $data["data"] = Tarif::with("asal", "tujuan", "layanan")->findOrFail($id);
        $data["proyeksi"] = Proyeksi::getByTarif($id);
        $data["layanan"] = Layanan::select("id_layanan", "kode_layanan", "nm_layanan")->get();
        
        return view('operasional::tarifproyeksi', $data);
    }
    
    public function editproyeksi($id)
    {
        $data["data"] = TarifProyeksi::getData()->findOrFail($id);
        $data["proyeksi"] = Proyeksi::all();
        $data["layanan"] = Layanan::all();
        
        return view('operasional::tarifproyeksi', $data);
    }
    
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
