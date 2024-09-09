<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\TarifPacking;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Entities\Packing;
use DB;
use Auth;
use Exception;
use Session;

class TarifPackingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = TarifPacking::with("packing")->where("id_perush", $id_perush)->paginate(50);
        
        return view('operasional::packing.tarifpacking', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    
    public function create()
    {
        $data["packing"] = Packing::getList();

        return view('operasional::packing.tarifpacking', $data);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $volume = $request->panjang * $request->lebar * $request->tinggi;
        try {
            
            DB::beginTransaction();
            $tarif                      = new TarifPacking();
            
            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            $tarif->id_jenis            = $request->id_jenis_packing;
            $tarif->panjang             = $request->panjang;
            $tarif->lebar               = $request->lebar;
            $tarif->tinggi              = $request->tinggi;
            $tarif->tarif              = $request->tarif;
            $tarif->volume              = $volume;

            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data tarif packing Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect("tarifpacking")->with('success', 'Data tarif packing Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        abort(404);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data["packing"] = Packing::getList();
        $data["data"] = TarifPacking::findOrFail($id);
        
        return view('operasional::packing.tarifpacking', $data);
    }
    
    public function gettarifpacking(Request $request)
    {
        $data = TarifPacking::getTarifPacking($request->id_jenis_packing, $request->volume);
        
        return Response()->json($data);
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

            $volume = $request->panjang * $request->lebar * $request->tinggi;

            DB::beginTransaction();
            $tarif                      = TarifPacking::findOrFail($id);
            $tarif->id_user             = Auth::user()->id_user;
            $tarif->id_perush           = Session("perusahaan")["id_perush"];
            $tarif->id_jenis            = $request->id_jenis_packing;
            $tarif->panjang             = $request->panjang;
            $tarif->lebar               = $request->lebar;
            $tarif->tinggi              = $request->tinggi;
            $tarif->tarif              = $request->tarif;
            $tarif->volume              = $volume;

            $tarif->save();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif packing Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect("tarifpacking")->with('success', 'Data tarif packing Disimpan');
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
            $tarif                      = TarifPacking::findOrFail($id);
            $tarif->delete();
            
            DB::commit();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data tarif packing Gagal Dihapus'.$e->getMessage());
        }
        
        return redirect("tarifpacking")->with('success', 'Data tarif packing Dihapus');
    }
}
