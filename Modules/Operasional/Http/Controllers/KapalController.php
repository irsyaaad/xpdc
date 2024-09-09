<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Http\Requests\KapalRequest;
use DB;
use Auth;
Use Exception;
use Modules\Operasional\Entities\KapalPerush;

class KapalController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {   
        $page = 50;
        $f_id_perush = $request->f_id_perush;
        $id_kapal = $request->f_id_kapal;

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        
        $kapal = Kapal::getFilter($page, $f_id_perush, $id_kapal)->paginate($page);
        
        if($id_kapal != null){
            $id_kapal = Kapal::findOrFail($id_kapal);
        }

        $data["data"] = $kapal;
        $data["perush"] = KapalPerush::select("id_kapal_perush", "nm_kapal_perush")->get();
        $data["filter"] = array("page" => $page, "f_id_perush" => $f_id_perush, "id_kapal" => $id_kapal);

        return view('operasional::kapal', $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        return view('operasional::kapal');
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(KapalRequest $request)
    {
        try {
            
            DB::beginTransaction();
            $kapal                = new Kapal();
            $kapal->id_kapal_perush   = $request->id_kapal_perush;
            $kapal->nm_kapal   = $request->nm_kapal;
            $kapal->dr_rute   = $request->dr_rute;
            $kapal->ke_rute   = $request->ke_rute;
            $kapal->is_aktif   = $request->is_aktif;
            $kapal->def_tarif  = $request->def_tarif!=null?$request->def_tarif:0;
            $kapal->id_user       = Auth::user()->id_user;
            $kapal->save();
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Kapal Gagal Disimpan');
        }
        
        return redirect(route_redirect())->with('success', 'Data Kapal Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        return view('operasional::kapal');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {   $data["data"] = Kapal::where("id_kapal", $id)->with("kapalperush")->get()->first();
        //dd($data);
        return view('operasional::kapal', $data);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(KapalRequest $request, $id)
    {
        try {
            
            DB::beginTransaction();
            
            $kapal                = Kapal::findOrFail($id);
            $kapal->id_kapal_perush   = $request->id_kapal_perush;
            $kapal->nm_kapal   = $request->nm_kapal;
            $kapal->dr_rute   = $request->dr_rute;
            $kapal->ke_rute   = $request->ke_rute;
            $kapal->is_aktif   = $request->is_aktif;
            $kapal->def_tarif  = $request->def_tarif!=null?$request->def_tarif:0;
            $kapal->id_user       = Auth::user()->id_user;
            
            $kapal->save();
            
            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Kapal Gagal Disimpan');
        }
        
        return redirect(route_redirect())->with('success', 'Data Kapal Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {   
        try{
            $kapal                = Kapal::findOrFail($id);
            $kapal->delete();
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect()->back()->with('success', 'Data Kapal Di Hapus');
    }
}
