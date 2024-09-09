<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\MasterAC;
use DB;
use Modules\Keuangan\Http\Requests\ACPerushRequest;
use Auth;

class ACPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["akun"] = ACPerush::with("perusahaan", "ac3")
                        ->where("id_perush", Session("perusahaan")["id_perush"])
                        ->orderBy("id_ac", "asc")->get();

        $lev3 = MasterAC::where("level",3)->get();
        $lev2 = MasterAC::where("level",2)->get();
        $lev1 = MasterAC::where("level",1)->get();

        $newdata3 = [];
        $newdata2 = [];
        $newdata1 = [];
        foreach ($lev3 as $key => $value) {
            $newdata3[$value->id_ac] = $value;
        }
        foreach ($lev2 as $key => $value) {
            $newdata2[$value->id_ac] = $value;
        }
        foreach ($lev1 as $key => $value) {
            $newdata1[$value->id_ac] = $value;
        }

        $data["data1"]  = $newdata1;
        $data["data2"]  = $newdata2;
        $data["data3"]  = $newdata3;

        return view('keuangan::acperush.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */


    public function filter(Request $request)
    {
        // dd($request->request);
        $ac = ACPerush::where("id_perush",Session("perusahaan")["id_perush"]);

        $jenis = $request->jenis;
        $ac1 = $request->ac1;
        $ac2 = $request->ac2;
        $ac3 = $request->ac3;

        if($jenis != "0"){
            if ($jenis == "N") {
                $ac = $ac->where("id_ac","<",4000);
            }else{
                $ac = $ac->where("id_ac",">=",4000);
            }
        }
        if($ac1 != "0"){
            $ac = $ac->where("id_ac",'like', $ac1 . '%');
            // dd($request->request,$ac->toSql());
        }
        if($ac2 != "0"){
            $ac = $ac->where("id_ac",'like', $ac2 . '%');
        }
        if($ac3 != "0"){
            $ac = $ac->where("parent",$ac3);
        }

        $lev3 = MasterAC::where("level",3)->get();
        $lev2 = MasterAC::where("level",2)->get();
        $lev1 = MasterAC::where("level",1)->get();

        $newdata3 = [];
        $newdata2 = [];
        $newdata1 = [];

        foreach ($lev3 as $key => $value) {
            $newdata3[$value->id_ac] = $value;
        }
        foreach ($lev2 as $key => $value) {
            $newdata2[$value->id_ac] = $value;
        }
        foreach ($lev1 as $key => $value) {
            $newdata1[$value->id_ac] = $value;
        }

        $data["akun"]   = $ac->orderBy('id_ac','ASC')->get();
        $filter         = array("jenis"=> $jenis, "ac1"=> $ac1, "ac2"=>$ac2, "ac3"=>$ac3);
        $data["filter"] = $filter;

        $data["data1"]  = $newdata1;
        $data["data2"]  = $newdata2;
        $data["data3"]  = $newdata3;

        // dd($request->all(), $data);
        return view('keuangan::acperush.index', $data);
    }


    public function create()
    {
        $data["parent"] = MasterAC::select("id_ac", "nama")->where("level", "1")->get();

        return view('keuangan::acperush.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */

     public function getenrateId($parent)
    {
        $ac4 = ACPerush::where("parent", $parent)->where("id_perush", Session("perusahaan")["id_perush"])->get();
        // dd($ac4);
        if (isset($ac4)) {
            $max = $ac4->max('id_ac');
            $count = $max+1;

        }else{
            $max = $parent;
            $count = $parent.'1';
        }
        return $count;
    }

    public function store(ACPerushRequest $request)
    {
        try {

            // save to user
            DB::beginTransaction();
            $ac                  = new ACPerush();
            $ac->id_ac           = $this->getenrateId($request->parent);
            $ac->nama            = $request->nama;
            $ac->def_pos         = $request->def_pos;
            $ac->parent          = $request->parent;
            $ac->kode_debet      = $request->kode_debet;
            $ac->kode_kredit     = $request->kode_kredit;
            $ac->is_aktif        = $request->is_aktif;
            $ac->id_user         = Auth::user()->id_user;

            if($request->jenis=="B"){
                $ac->is_bank = true;
            }elseif($request->jenis=="K"){
                $ac->is_kas = true;
            }else{
                $ac->is_kas = NULL;
                $ac->is_bank = NULL;
            }

            $ac->id_perush       = Session("perusahaan")["id_perush"];
            $ac->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("acperush"))->with('success', 'Data Account  Disimpan');
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
        $ac = ACPerush::findorFail($id);
        $parent3 = MasterAC::findorFail($ac->parent);
        $parent2 = MasterAC::findorFail($parent3->id_parent);
        $parent1 = MasterAC::findorFail($parent2->id_parent);

        $data["level_1"] = MasterAC::select("id_ac", "nama")->where("level", "1")->get();
        $data["level_2"] = MasterAC::select("id_ac", "nama")->where("level", "2")->get();
        $data["level_3"] = MasterAC::select("id_ac", "nama")->where("level", "3")->get();

        $data["parent1"] = $parent1;
        $data["parent2"] = $parent2;
        $data["parent3"] = $parent3;
        $data["data"] = $ac;
// dd($data);
        return view('keuangan::acperush.edit', $data);
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

            $ac                  = ACPerush::findOrFail($id);
            $ac->id_ac           = $request->id_ac;
            $ac->nama            = $request->nama;
            $ac->def_pos         = $request->def_pos;
            $ac->parent          = $request->parent;
            $ac->is_aktif        = $request->is_aktif;
            $ac->kode_debet      = $request->kode_debet;
            $ac->kode_kredit     = $request->kode_kredit;
            $ac->id_user         = Auth::user()->id_user;

            if($request->jenis=="B"){
                $ac->is_bank = true;
            }elseif($request->jenis=="K"){
                $ac->is_kas = true;
            }else{
                $ac->is_kas = NULL;
                $ac->is_bank = NULL;
            }

            $ac->id_perush       = Session("perusahaan")["id_perush"];
            $ac->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url("acperush"))->with('success', 'Data Account  Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {

            // save to user
            DB::beginTransaction();

            $ac                  = ACPerush::findOrFail($id);
            $ac->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Account Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Account  Dihapus');
    }

    public function generate()
    {
        $cek = ACPerush::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        if($cek!==null){
            return redirect()->back()->with('error', 'Pastikan Data Akun Perusahaan Kosong');
        }

        try {

            // save to user
            DB::beginTransaction();

            $ac                  = ACPerush::where("id_perush", "1")->where("is_bank", null)->where("is_kas", null)->get();

            $data = [];
            foreach($ac as $key => $value){
                $data[$key]["id_perush"] = Session("perusahaan")["id_perush"];
                $data[$key]["id_ac"] = $value->id_ac;
                $data[$key]["parent"] = $value->parent;
                $data[$key]["nama"] = $value->nama;
                $data[$key]["is_aktif"] = $value->is_aktif;
                $data[$key]["def_pos"] = $value->def_pos;
                $data[$key]["id_user"] = Auth::user()->id_user;
                $data[$key]["kode_debet"] = $value->kode_debet;
                $data[$key]["kode_kredit"] = $value->kode_kredit;
            }
            
            // insert ac
            ACPerush::insert($data);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Account Gagal Digenerate '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Account  Digenerate');
    }

}
