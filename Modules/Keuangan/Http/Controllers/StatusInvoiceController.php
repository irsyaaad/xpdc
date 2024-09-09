<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\StatusInvoice;
use DB;
use Auth;

class StatusInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = StatusInvoice::orderBy("id_status")->paginate(10);
        
        return view('keuangan::statusinvoice.indeks', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if(!get_admin()){
            abort(404);
        }
        return view('keuangan::statusinvoice.indeks');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(!get_admin()){
            abort(404);
        }
        try {

            DB::beginTransaction();

            $status                     = new StatusInvoice();
            $status->nm_status          = $request->nm_status;
            $status->id_status          = $request->id_status;
            $status->id_user            = Auth::user()->id_user;
            $status->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Status Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('keuangan::statusinvoice.indeks');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if(!get_admin()){
            abort(404);
        }
        $data["data"] = StatusInvoice::findOrFail($id);

        return view('keuangan::statusinvoice.indeks', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if(!get_admin()){
            abort(404);
        }
        try {

            DB::beginTransaction();
            
            $status                     = StatusInvoice::findOrFail($id);
            $status->nm_status          = $request->nm_status;
            $status->id_status          = $request->id_status;
            $status->id_user            = Auth::user()->id_user;
            $status->update();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Status Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if(!get_admin()){
            abort(404);
        }
        try {

            DB::beginTransaction();
            
            $status                     = StatusInvoice::findOrFail($id);
            $status->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Gagal Dihapus');
        }

        return redirect(route_redirect())->with('success', 'Data Status Dihapus');
    }
}
