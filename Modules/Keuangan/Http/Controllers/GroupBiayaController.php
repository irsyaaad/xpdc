<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Http\Requests\GroupBiayaRequest;
use DB;
use Auth;

class GroupBiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        session()->forget('klp');
        $page = 50;
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data["filter"] = [];
        $data["data"] = GroupBiaya::with("user")->paginate($page);
        $data["page"] = $page;

        return view('keuangan::groupbiaya', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    public function filter(Request $request)
    {
        $page = 50;
        // dd($request->request);

        $datax = GroupBiaya::with("user");
        $klp = $request->filter;
        if(isset($klp) and $klp != null){
            $datax = $datax->where("klp", $klp);
        }

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data["req"] = $request->filter;
        $data["data"] = $datax->paginate($page);
        $data["filter"] = [];
        $data["page"] = $page;

        return view('keuangan::groupbiaya', $data);
    }
    public function create()
    {
        return view('keuangan::groupbiaya');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(GroupBiayaRequest $request)
    {
        try {

            DB::beginTransaction();
            $grup                   = new GroupBiaya();
            $grup->nm_biaya_grup    = $request->nm_biaya_grup;
            $grup->klp              = $request->klp;
            $grup->id_user          = Auth::user()->id_user;
            $grup->save();

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
        return view('keuangan::groupbiaya');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = GroupBiaya::with("user")->findOrFail($id);

        return view('keuangan::groupbiaya', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(GroupBiayaRequest $request, $id)
    {
        try {

            DB::beginTransaction();
            $grup                = GroupBiaya::findOrFail($id);
            $grup->nm_biaya_grup   = $request->nm_biaya_grup;
            $grup->klp   = $request->klp;
            $grup->id_user       = Auth::user()->id_user;
            $grup->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Biaya Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Group Biaya Disimpan');
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
            $grup                = GroupBiaya::findOrFail($id);
            $grup->delete();
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Biaya Gagal Dihapus'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Group Biaya Dihapus');
    }
}
