<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\GenIdAC;
use DB;
use Auth;

class MasterACController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        session()->forget('ac1');
        session()->forget('ac2');
        session()->forget('ac3');

        $data1 = MasterAC::where("level", "1")->orderBy("id_ac", "asc")->get();
        $data2 = MasterAC::where("level", "2")->orderBy("id_ac", "asc")->get();
        $data3 = MasterAC::where("level", "3")->orderBy("id_ac", "asc")->get();
        
        $a_data2 = [];
        foreach($data2 as $key => $value){
            $a_data2[$value->id_parent][$value->id_ac] = $value;
        }

        $a_data3 = [];
        foreach($data3 as $key => $value){
            $a_data3[$value->id_parent][$value->id_ac] = $value;
        }

        $data["data1"]  = $data1;
        $data["data2"]  = $a_data2;
        $data["data3"]  = $a_data3;

        $data["filter"] = [];
        $data["data"] = MasterAC::all();

        return view('keuangan::master_ac.index',$data);
    }

    public function filter(Request $request)
    {
        // dd($request->request);

        $jenis = $request->jenis;
        $ac1 = $request->ac1;
        $ac2 = $request->ac2;
        $ac3 = $request->ac3;

        $data1 = MasterAC::where("level", "1")->orderBy("id_ac", "asc")->get();
        $data2 = MasterAC::where("level", "2")->orderBy("id_ac", "asc")->get();
        $data3 = MasterAC::where("level", "3")->orderBy("id_ac", "asc")->get();

        if($jenis != "0"){
            $data1 = $data1->where("jenis",$request->jenis);
        }
        if($ac1 != "0"){
            $data1 = $data1->where("id_ac",$request->ac1);
            $data2 = $data2->where("id_parent",$request->ac1);
        }
        if($ac2 != "0"){
            $data2 = $data2->where("id_ac",$request->ac2);
            $data3 = $data3->where("id_parent",$request->ac2);
        }
        // if($ac3 != "0"){
        //     $data3 = $data3->where("id_ac",$request->ac3);
        //     $data4 = $data4->where("id_parent",$request->ac3);
        // }

        $filter = array("jenis"=> $jenis, "ac1"=> $ac1, "ac2"=>$ac2, "ac3"=>$ac3);

        $a_data2 = [];
        foreach($data2 as $key => $value){
            $a_data2[$value->id_parent][$value->id_ac] = $value;
        }

        $a_data3 = [];
        foreach($data3 as $key => $value){
            $a_data3[$value->id_parent][$value->id_ac] = $value;
        }

        $data["data1"]  = $data1;
        $data["data2"]  = $a_data2;
        $data["data3"]  = $a_data3;

        $data["filter"] = $filter;
        $data["data"] = MasterAC::all();

        return view('keuangan::master_ac.index',$data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        return view('keuangan::master_ac.createac');
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        try {
            // save to user
            DB::beginTransaction();
            $params = null;
            $parent = null;
            $lev    = $request->level;
            $id     = null;

            if ($request->level == 2) {
                $parent      = $request->parent1;
            } else {
                $parent      = $request->parent2;
            }

            if (isset($parent)) {
                $params = $parent;
            }else{
                $params = $request->tipe;
            }

            $id = $this->generateId($params)["id_masterac"];

            $ac                     = new MasterAC();
            $ac->id_ac              = $id;
            $ac->nama               = $request->nama;
            $ac->tipe               = $request->tipe;
            $ac->id_parent          = $parent;
            $ac->id_user            = Auth::user()->id_user;
            $ac->level              = $request->level;
            $ac->jenis              = $request->jenis;
            $ac->is_aktif           = true;
            //dd($ac);
            $ac->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("masterac"))->with('success', 'Data Account  Disimpan');
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        return view('keuangan::show');
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $ac = MasterAC::all()->where("id_ac", $id)->first();
        $data = [];

        if ($ac->level==2) {
            $parent2 = MasterAC::with("parents")->findOrFail($id);
            $parent1 = MasterAC::with("parents")->findOrFail($parent2->id_parent);
            $data["parent1"] = $parent1;
            $data["tipe"] = $parent1->tipe;

        }elseif ($ac->level==3) {
            $parent3 = MasterAC::with("parents")->findOrFail($id);
            $parent2 = MasterAC::with("parents")->findOrFail($parent3->id_parent);
            $parent1 = MasterAC::with("parents")->findOrFail($parent2->id_parent);
            $data["parent2"]  = $parent2;
            $data["parent1"]  = $parent1;
            $data["tipe"] = $parent1->tipe;
        }


        $data["data"] = $ac;
        //dd($data);
        return view('keuangan::master_ac.editac',$data);
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
            // save to user
            DB::beginTransaction();
            $ac                       = MasterAC::findOrFail($id);
            // for creator

            $parent_asli = $ac->id_parent;
            if($ac->level==2){
                $ac->id_parent          = $request->parent1;
            }elseif($ac->level==3){
                $ac->id_parent          = $request->parent2;
            }
            if($parent_asli != $ac->id_parent){
                $ac->id_ac = $this->generateId($ac->id_parent)["id_masterac"];
            }
            $ac->nama               = $request->nama;
            $ac->tipe               = $request->tipe;
            $ac->id_user            = Auth::user()->id_user;
            $ac->level              = $request->level;
            $ac->is_aktif          = $request->is_aktif;
            //dd($ac);
            $ac->update();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("masterac"))->with('success', 'Data Account Disimpan');
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

            $detail = MasterAC::findOrFail($id);
            $detail->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Account Gagal dihapus');
        }

        return redirect(url("masterac"))->with('success', 'Data Account Berhasil dihapus');
    }

    public function generateId($params)
    {
        $ac = MasterAC::where("id_parent",$params)->get();
        $max = $ac->max('id_ac');
        $count = $max+1;
        $data["id_masterac"] = $count;
        return $data;
    }

    public function generateId2($params)
    {
        $ac = MasterAC::where("id_parent",$params)->get();
        $count = (count($ac));
        $temp = $params."-".$count;
        $data["id_masterac"] = $temp;
        return $data;
    }
}

