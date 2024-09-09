<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\TipeKirim;
use Modules\Operasional\Http\Requests\TipeKirimRequest;
use DB;
use Auth;
use Exception;

class TipeKirimController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 50;
        $id_tipe_kirim = $request->f_id_tipe_kirim;
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        
        $tipe  = [];
        if($id_tipe_kirim != null){
            $tipe = TipeKirim::where("id_tipe_kirim", $id_tipe_kirim)->orderBy("nm_tipe_kirim", "asc");
        }else{
            $tipe = TipeKirim::orderBy("nm_tipe_kirim", "asc");
        }

        $data["data"] = $tipe->paginate($page);
        $id_tipe_kirim = TipeKirim::select("id_tipe_kirim", "nm_tipe_kirim", "kode_tipe_kirim")->where("id_tipe_kirim", $id_tipe_kirim)->get()->first();
        $data["filter"] = array("page" => $page, "id_tipe_kirim" => $id_tipe_kirim);

        return view('operasional::tipekirim', $data);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::tipekirim');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(TipeKirimRequest $request)
    {
        try {

            DB::beginTransaction();

            $tipekirim                       = new TipeKirim();
            $tipekirim->kode_tipe_kirim = getkode(5);
            $tipekirim->nm_tipe_kirim = $request->nm_tipe_kirim;
            $tipekirim->is_aktif      = $request->is_aktif;
            $tipekirim->id_user       = Auth::user()->id_user;
            $tipekirim->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Tipe Kirim Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Tipe Kirim Disimpan');
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
        $data["data"] = TipeKirim::findOrFail($id);

        return view('operasional::tipekirim', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(TipeKirimRequest $request, $id)
    {

        try {

            // save to user
            DB::beginTransaction();
            
            $tipekirim                = TipeKirim::findOrFail($id);
            $tipekirim->nm_tipe_kirim = $request->nm_tipe_kirim;
            $tipekirim->is_aktif      = $request->is_aktif;
            $tipekirim->id_user       = Auth::user()->id_user;
            $tipekirim->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Tipe Kirim Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Tipe Kirim Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{

            $tipekirim                = TipeKirim::findOrFail($id);
            $tipekirim->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data Tipe Kirim dihapus');
    }
}
