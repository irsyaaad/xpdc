<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Auth;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Http\Requests\StatusDMRequst;
Use Exception;

class StatusDMController extends Controller
{   

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {   
        $data["data"] = StatusDM::orderBy("id_status")->get();
        
        return view('operasional::daftarmuat.statusdm', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        return view('operasional::daftarmuat.statusdm');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StatusDMRequst $request)
    {
        try {

            DB::beginTransaction();

            $status                     = new StatusDM();
            $status->nm_status          = $request->nm_status;
            $status->id_status            = $request->id_status;
            $status->id_user            = Auth::user()->id_user;
            $status->tipe            = $request->tipe;
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
        return view('operasional::daftarmuat.statusdm');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {   
        $data["data"] = StatusDM::findOrFail($id);

        return view('operasional::daftarmuat.statusdm', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */

    public function update(StatusDMRequst $request, $id)
    {   
        try {

            DB::beginTransaction();
            
            $status                     = StatusDM::findOrFail($id);
            $status->nm_status          = $request->nm_status;
            $status->id_status            = $request->id_status;
            $status->id_user            = Auth::user()->id_user;
            $status->tipe            = $request->tipe;
            $status->save();

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
        try {

            DB::beginTransaction();
            
            $status                     = StatusDM::findOrFail($id);
            $status->delete();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Gagal Dihapus');
        }

        return redirect(route_redirect())->with('success', 'Data Status Dihapus');
    }

}
