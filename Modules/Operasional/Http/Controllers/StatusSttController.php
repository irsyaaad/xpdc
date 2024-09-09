<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Auth;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Http\Requests\StatusSttRequest;
Use Exception;
use Modules\Operasional\Entities\StatusDM;
class StatusSttController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = StatusStt::with("dm")->orderBy("id_ord_stt_stat")->get();

        return view('operasional::statusstt', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $data["dm"] = StatusDM::getList();
        return view('operasional::statusstt', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StatusSttRequest $request)
    {
        try {

            DB::beginTransaction();
            
            $status                     = new StatusStt();
            $status->id_ord_stt_stat    = $request->id_ord_stt_stat;
            $status->nm_ord_stt_stat    = $request->nm_ord_stt_stat;
            $status->nm_alias           = $request->nm_alias;
            $status->is_aktif           = $request->is_aktif;
            $status->id_status           = $request->id_status;
            $status->id_user            = Auth::user()->id_user;
            $status->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Kirim Gagal Disimpan'.$e->getMessage())->withInput($request->all());
        }

        return redirect(route_redirect())->with('success', 'Data Status Kirim Disimpan');
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
        $data["data"] = StatusStt::where("kode_status", $id)->firstOrFail();
        $data["dm"] = StatusDM::getList();
        
        return view('operasional::statusstt', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StatusSttRequest $request, $id)
    {
        try {
            
            DB::beginTransaction();

            $status                     =  StatusStt::where("kode_status", $id)->firstOrFail();
            $data = $request->all();
            unset($data["_method"]);
            unset($data["_token"]);
            StatusStt::where("kode_status", $id)->update($data);

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Kirim Gagal Disimpan'.$e->getMessage())->withInput($request->all());
        }

        return redirect(route_redirect())->with('success', 'Data Status Kirim Disimpan');
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            
            $status                =  StatusStt::where("kode_status", $id)->firstOrFail();
            StatusStt::where("kode_status", $id)->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data Status Kirim Gagal Disimpan'.$e->getMessage())->withInput($request->all());
        }

        return redirect()->back()->with('success', 'Data Status Kirim dihapus');
    }
}
