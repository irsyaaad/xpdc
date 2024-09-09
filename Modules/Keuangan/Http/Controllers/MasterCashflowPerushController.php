<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\MasterCashflow;
use Modules\Keuangan\Entities\MasterCashFlowPerush;
use DB;
use Auth;

class MasterCashflowPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $cf             = MasterCashflow::where("head","!=",0)->get();
        $cf_perush      = MasterCashFlowPerush::with("akun","cashflow","user")->where("id_perush",Session("perusahaan")["id_perush"])->get();
        $newdata        = [];

        foreach ($cf_perush as $key => $value) {
            $newdata[$value->id_cf][$key] = $value;
        }

        $data["cashflow"]   = $cf;
        $data["data"]       = $newdata;
        $data["tes"]        = $cf_perush;
        return view('keuangan::cashflow.perush_index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["cashflow"] = MasterCashFlowPerush::where("level",2)->get();
        return view('keuangan::cashflow.perush_index',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //dd($request->request);
        try {

            DB::beginTransaction();
            $cashflow                       = new MasterCashFlowPerush();
            $cashflow->tipe                 = $request->tipe;
            $cashflow->id_ac                = $request->id_ac;
            $cashflow->id_cf                = $request->id_cf;
            $cashflow->id_user              = Auth::user()->id_user;
            $cashflow->id_perush            = Session("perusahaan")["id_perush"];

            //dd($cashflow);
            $cashflow->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
        return redirect()->back()->with('success', 'Data Berhasil Disimpan');   
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
        $data["cashflow"] = MasterCashflow::findOrfail($id);
        $data["detail"] = MasterCashFlowPerush::with("akun","cashflow")
        ->where("id_cf",$id)
        ->where("id_perush",Session("perusahaan")["id_perush"])
        ->get();
        return view('keuangan::cashflow.perush_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $menu = MasterCashFlowPerush::findOrFail($id);            
            $menu->delete();
            
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
        
        return redirect()->back()->with('success', 'Data Berhasil Dihapus'); 
    }

    public function generate()
    {
        $cek = MasterCashFlowPerush::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        if($cek!==null){
            return redirect()->back()->with('error', 'Pastikan Data Kosong');
        }

        try {

            // save to user
            DB::beginTransaction();
            
            $cashflow                  = MasterCashFlowPerush::where("id_perush", "1")->get();
            
            $data = [];
            foreach($cashflow as $key => $value){
                $data[$key]["id_perush"] = Session("perusahaan")["id_perush"];
                $data[$key]["id_cf"] = $value->id_cf;
                $data[$key]["id_ac"] = $value->id_ac;
                $data[$key]["tipe"] = $value->tipe;
                $data[$key]["id_user"] = Auth::user()->id_user;
                $data[$key]["id_perush"] = Session("perusahaan")["id_perush"];
            }
             
            // insert ac
            //dd($data);
            MasterCashFlowPerush::insert($data);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Digenerate '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Berhasil Digenerate');
    }

    
}
