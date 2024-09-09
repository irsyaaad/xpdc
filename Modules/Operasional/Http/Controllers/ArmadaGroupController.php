<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\ArmadaGroup;
use Modules\Operasional\Http\Requests\ArmadaGroupRequest;
use DB;
use Auth;
use Exception;

class ArmadaGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 50;
        $f_group = $request->f_group;
        if(isset($request->shareselect) and $request->shareselect!=null){
            $page = $request->shareselect;
        }
        $armada = ArmadaGroup::orderBy("nm_armd_grup", "asc");
        if($f_group != null){
            $armada  = $armada->where("gr_armada", $f_group);
        }
        $group = array('1' => 'Darat', '2'=>'Laut', '3' =>'Udara');
        $data["group"] = $group;
        $data["data"]  = $armada->paginate($page);
        $data["filter"] = array("page" => $page, "f_group" => $f_group);

        return view('operasional::armadagroup', $data);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $group = array('1' => 'Darat', '2'=>'Laut', '3' =>'Udara');
        $data["group"] = $group;
        
        return view('operasional::armadagroup', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ArmadaGroupRequest $request)
    {
        try {
            // save to user
            DB::beginTransaction();
            $group                      = new ArmadaGroup();
            $group->nm_armd_grup       = $request->nm_armd_grup;
            $group->gr_armada          = $request->gr_armada;
            $group->is_aktif           = $request->is_aktif;
            $group->id_user            = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Armada Group Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Armada Group Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {   
        $data["data"] = ArmadaGroup::findOrFail($id);
        $group = array('1' => 'Darat', '2'=>'Laut', '3' =>'Udara');
        $data["group"] = $group;
        
        return view('operasional::armadagroup', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {   $data["data"] = ArmadaGroup::findOrFail($id);
        $group = array('1' => 'Darat', '2'=>'Laut', '3' =>'Udara');
        $data["group"] = $group;
        
        return view('operasional::armadagroup', $data);
    }
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ArmadaGroupRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $group                     = ArmadaGroup::findOrFail($id);
            $group->nm_armd_grup       = $request->nm_armd_grup;
            $group->gr_armada          = $request->gr_armada;
            $group->is_aktif           = $request->is_aktif;
            $group->id_user            = Auth::user()->id_user;
            $group->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Armada Group Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Armada Group Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            
            $armada                = ArmadaGroup::findOrFail($id);
            $armada->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data Armada Group Di Hapus');
    }
}
