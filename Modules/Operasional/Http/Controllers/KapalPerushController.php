<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\KapalPerush;
use Modules\Operasional\Http\Requests\KapalPerushRequest;
use DB;
use Auth;
Use Exception;

class KapalPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 50;
        $id_kapal_perush = $request->f_id_kapal_perush;
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $kapal = [];
        if($id_kapal_perush != null){
            $kapal = KapalPerush::OrderBy("nm_kapal_perush", "asc")->where("id_kapal_perush", $id_kapal_perush);
            $id_kapal_perush = KapalPerush::findOrFail($id_kapal_perush);
        }else{
            $kapal = KapalPerush::OrderBy("nm_kapal_perush", "asc");
        }

        $data["data"] = $kapal->paginate($page);
        $data["filter"] = array("id_kapal_perush" => $id_kapal_perush, "page" => $page);
        
        return view('operasional::kapalperush', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::kapalperush');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(KapalPerushRequest $request)
    {
        try {

            DB::beginTransaction();
            $kapal                = new KapalPerush();
            $kapal->nm_kapal_perush   = $request->nm_kapal_perush;
            $kapal->alamat   = $request->alamat;
            $kapal->telp   = $request->telp;
            $kapal->id_user       = Auth::user()->id_user;
            $kapal->save();

            DB::commit();
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data Kapal Perusahaan Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Kapal Perusahaan Disimpan');
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
        $data["data"] = KapalPerush::findOrFail($id);
        
        return view('operasional::kapalperush', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(KapalPerushRequest $request, $id)
    {
        try {

            DB::beginTransaction();
            $kapal                = KapalPerush::findOrFail($id);

            $kapal->nm_kapal_perush   = $request->nm_kapal_perush;
            $kapal->alamat   = $request->alamat;
            $kapal->telp   = $request->telp;
            $kapal->id_user       = Auth::user()->id_user;
            $kapal->save();

            DB::commit();
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data Kapal Perusahaan Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Kapal Perusahaan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{

            $kapal                = KapalPerush::findOrFail($id);
            $kapal->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect()->back()->with('success', 'Data Kapal Perusahaan Di Hapus');
    }
}
