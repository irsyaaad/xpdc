<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\MasterCashflow;
use Modules\Keuangan\Entities\MasterCashFlowPerush;
use DB;
use Auth;

class MasterCashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data1 = MasterCashflow::where("level",1)->get();
        $data2 = MasterCashflow::where("level",2)->get();

        $newdata = [];

        foreach ($data2 as $key => $value) {
            $newdata[$value->head][$value->id_cf] = $value;
        }

        $data["head"] = $data1;
        $data["for_filter"] = $data1;
        $data["data"] = $newdata;
        return view('keuangan::cashflow.index',$data);
    }

    public function filter(Request $request)
    {
        // dd($request->request);
        $jenis = $request->jenis;

        if (isset($jenis) and $jenis != "0") {
            $data1 = MasterCashflow::where("level",1)
            ->where("id_cf",$jenis)
            ->get();
            $data2 = MasterCashflow::where("level",2)
            ->where("head",$jenis)
            ->get();
        }else{
            $data1 = MasterCashflow::where("level",1)->get();
        $data2 = MasterCashflow::where("level",2)->get();
        }

        foreach ($data2 as $key => $value) {
            $newdata[$value->head][$value->id_cf] = $value;
        }

        $data["head"] = $data1;
        $data["for_filter"] = MasterCashflow::where("level",1)->get();
        $data["data"] = $newdata;
        $data["filter"] = $jenis;
        return view('keuangan::cashflow.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["kelompok"] = MasterCashflow::where("level",1)->get();
        return view('keuangan::cashflow.index',$data);
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
            $cashflow                       = new MasterCashflow();
            $cashflow->nama_cashflow        = $request->nama_cashflow;
            $cashflow->tipe                 = $request->tipe;
            $cashflow->head                 = $request->head;
            $cashflow->level                = $request->level;
            $cashflow->id_user              = Auth::user()->id_user;

            //dd($cashflow);
            $cashflow->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Biaya Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Group Biaya Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["kelompok"] = MasterCashflow::where("level",1)->get();
        $data["data"] = MasterCashflow::findOrfail($id);
        return view('keuangan::cashflow.index',$data);
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
        //
    }
}
