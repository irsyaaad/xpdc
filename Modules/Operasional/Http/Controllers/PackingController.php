<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\Packing;
use Modules\Operasional\Http\Requests\PackingRequest;
use DB;
use Auth;

class PackingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 50;
        $id_packing = $request->f_id_packing;
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        
        $tipe  = [];
        if($id_packing != null){
            $tipe = Packing::where("id_packing", $id_packing)->orderBy("nm_packing", "asc");
            $id_packing  = Packing::findOrFail($id_packing);
        }else{
            $tipe = Packing::orderBy("nm_packing", "asc");
        }
        
        $data["data"] = $tipe->paginate($page);
        $data["filter"] = array("page" => $page, "id_packing" => $id_packing);

        return view('operasional::packing', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::packing');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PackingRequest $request)
    {
        try {
            DB::beginTransaction();
            $packing                = new Packing();
            $packing->kode_packing   = strtolower($request->id_packing);
            $packing->nm_packing   = $request->nm_packing;
            $packing->id_user       = Auth::user()->id_user;
            $packing->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Packing Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Packing Disimpan');
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
        $data["data"] = Packing::findOrFail($id);
        
        return view('operasional::packing', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PackingRequest $request, $id)
    {
        try {
            
            DB::beginTransaction();
            $packing                = Packing::findOrFail($id);
            $packing->kode_packing   = strtolower($request->id_packing);
            $packing->nm_packing   = $request->nm_packing;
            $packing->id_user       = Auth::user()->id_user;
            $packing->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Packing Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Packing Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{

            $packing                = Packing::findOrFail($id);
            $packing->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data Packing Di Hapus');
    }
}
