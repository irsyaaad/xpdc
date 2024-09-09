<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SttKembali;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\SttKembaliDetail;
use App\Models\Perusahaan;
use Session;
use DB;
use Exception;
use Auth;

class SttKembaliTerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        session()->forget('perushtj');
        session()->forget('statusstt');
        session()->forget('dr_tgl_krm');
        session()->forget('sp_tgl_krm');

        $data["data"] = SttKembali::getDokPenerima()->paginate(10);
        $data["filter"] = [];

        return view('operasional::sttkembali.terima', $data);
    }

    public function filter(Request $request)
    {
        $page = 10;
        $dataf = SttKembali::getDokPenerima();
        if ($request->method()=="POST") {

            if (isset($request->perushtj) and $request->perushtj!="0") {
                $dataf = $dataf->where("id_perush", $request->perushtj);
                $session = [];
                $session['perushtj'] = $request->perushtj;
                Session($session);  
                $data["perusahaan"] = Perusahaan::findOrFail($request->perushtj);
            }
            
            if(isset($request->statusstt) and $request->statusstt!="0"){
                $dataf = $dataf->where("status", $request->statusstt);
                $session = [];
                $session['statusstt'] = $request->filterlayanan;
                Session($session);
            }

            if (isset($request->dr_tgl_krm)) {
                $dr = date($request->dr_tgl_krm);
                $session['dr_tgl_krm'] = $request->dr_tgl_krm;
                Session($session);
                if (isset($request->sp_tgl_krm)) {
                    $sp = date($request->sp_tg_krml);
                    $session['sp_tgl_krm'] = $request->sp_tgl_krm;
                    Session($session);
                    $dataf = $dataf->whereBetween("tgl_kirim",[$dr,$sp]);
                }else{
                    $sp = date('Y-m-d');
                    $dataf = $dataf->whereBetween("tgl_kirim",[$dr,$sp]);
                }
            }

            if (isset($request->sp_tgl_krm) and $request->dr_tgl_krm == null) {
                $session['sp_tgl_krm'] = $request->sp_tgl;
                Session($session);
                $dataf = $dataf->where("tgl_kirim",'<=',$request->sp_tgl);
            }

        }
        if (Session('perushtj') !== null) {
            $dataf = $dataf->where("id_perush",Session('perushtj'));    
        }
        
        if (Session('statusstt') !== null) {
            $dataf = $dataf->where("status",Session('statusstt'));     
        }

        if (Session('dr_tgl_krm') !== null) {
            if (Session('sp_tgl_krm') !== null) {
                $dataf = $dataf->whereBetween("tgl_kirim",[Session('dr_tgl_krm'),Session('sp_tgl_krm')]);
            }else{
                $sp = date('Y-m-d');
                $dataf = $dataf->whereBetween("tgl_kirim",[Session('dr_tgl_krm'),$sp]);
            }
        }

        if (Session('sp_tgl_krm') !== null and Session('dr_tgl_krm') == null) {
            $dataf = $dataf->where("tgl_kirim",'<=',Session('sp_tgl_krm'));
        }

        $data["data"] = $dataf->paginate($page);
        $data["filter"] = [];
        return view('operasional::sttkembali.terima', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $dok         = SttKembali::with("perush_asal", "perush_tujuan", "user", "karyawan")->findOrFail($id);
        $data["data"] = $dok;
        $data["detail"] = SttKembaliDetail::getStt($id);

        return view('operasional::sttkembali.terima', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('operasional::edit');
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

    
    public function terima($id)
    {
        // find detail
        $dok = SttKembali::findOrFail($id);
        if($dok->status!="2"){
            return redirect()->back()->with('error', 'Data Dokumen STT Belum Dikirim');
        }

        DB::beginTransaction();
        try {
            
            $dok->status = "3";
            $dok->tgl_tiba = date("Y-m-d");
            $dok->id_karyawan = Auth::user()->id_karyawan;

            // update detail stt
            $stt = SttKembaliDetail::select("id_stt")->where("id_kembali", $id)->get();
            foreach($stt as $key => $value){
                // update stt
                $a_stt = [];
                $a_stt["status_kembali"] = "3";
                SttModel::where("id_stt", $value->id_stt)->update($a_stt);
            }
            
            $dok->save();
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Dokumen STT Gagal Diterima'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Dokumen STT Diterima');
    }
}
