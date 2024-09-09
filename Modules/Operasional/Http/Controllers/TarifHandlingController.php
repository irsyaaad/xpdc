<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Wilayah;
use DB;
use Auth;
use Modules\Operasional\Entities\TarifHandling;
use Modules\Operasional\Http\Requests\TarifHandlingRequest;
use Session;

class TarifHandlingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 50;
        $newdata = TarifHandling::where("id_perush", $id_perush)->paginate($page);

        $data["data"] = $newdata;
        return view('operasional::tarifhandling.index',$data);
    }

    public function create()
    {   
        return view('operasional::tarifhandling.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(TarifHandlingRequest $request)
    {

        try {
            
            DB::beginTransaction();
            $tarif                      = new TarifHandling();
            
            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_asal             = $request->id_asal;
            $tarif->id_tujuan           = $request->id_tujuan;
            $tarif->hrg_brt             = $request->hrg_brt;
            $tarif->hrg_borongan        = $request->hrg_borongan;
            $tarif->hrg_volume          = $request->hrg_volume;
            $tarif->hrg_kubik           = $request->hrg_kubik;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
            // cek tarif
            $cek                        = TarifHandling::where("id_asal", $request->id_asal)
            ->where("id_tujuan", $request->id_tujuan)
            ->where("id_perush",Session("perusahaan")["id_perush"])
            ->get()->first();
            
            if($cek!=null){
                return redirect()->back()->with('error', 'Data tarif sudah ada');
            }
            // dd($tarif);
            $tarif->save();
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect("tarifhandling")->with('success', 'Data tarif Disimpan');
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
        $tarif              = TarifHandling::find($id);
        if(get_admin()!=true and $tarif->id_perush!=Session("perusahaan")["id_perush"]){
            
            return redirect()->back()->with('error', 'Akses Terbatas');
        }
        
        $data["asal"]       = Wilayah::find($tarif->id_asal);
        $data["tujuan"]     = Wilayah::find($tarif->id_tujuan);
        $data["data"]       = $tarif;
        
        return view('operasional::tarifhandling.index',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(TarifHandlingRequest $request, $id)
    {
        try {
            
            DB::beginTransaction();
            $tarif                      = TarifHandling::findOrFail($id);
            
            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_asal             = $request->id_asal;
            $tarif->id_tujuan           = $request->id_tujuan;
            $tarif->hrg_brt             = $request->hrg_brt;
            $tarif->hrg_borongan        = $request->hrg_borongan;
            $tarif->hrg_volume          = $request->hrg_volume;
            $tarif->hrg_kubik           = $request->hrg_kubik;
            $tarif->info                = $request->info;
            $tarif->is_aktif            = $request->is_aktif;
            $tarif->is_standart         = $request->is_standart;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            
           
            //dd($tarif);
            $tarif->save();
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif Gagal Disimpan');
        }
        
        return redirect("tarifhandling")->with('success', 'Data tarif Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            
            $tarif = TarifHandling::findOrFail($id);
            $tarif->delete();
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect()->back()->with('success', 'Data tarif dihapus');
    }
}
