<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Auth;

class JenisPerijinanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = JenisPerijinan::orderBy("id_jenis", "asc")->get();
        
        return view('kepegawaian::jenisperijinan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('kepegawaian::jenisperijinan.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $cek = JenisPerijinan::where("id_jenis", $request->id_jenis)->where("nm_jenis", $request->nm_jenis)->get()->first();

        if($cek !=null){
            return redirect(route_redirect())->with('error', 'Jenis Perijinan sudah dibuat');
        }
        
        try {

            DB::beginTransaction();

            $ijin                  = new JenisPerijinan();
            $ijin->id_jenis       = $request->id_jenis;
            $ijin->nm_jenis       = $request->nm_jenis;
            $ijin->id_user         = Auth::user()->id_user;
            $ijin->format       = $request->format;
            $ijin->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Perijinan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Perijinan Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('kepegawaian::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = JenisPerijinan::findOrFail($id);
        return view('kepegawaian::jenisperijinan.index',$data);
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

            DB::beginTransaction();

            $ijin                  = JenisPerijinan::findOrFail($id);
            $ijin->nm_jenis       = $request->nm_jenis;
            $ijin->format       = $request->format;
            $ijin->id_user         = Auth::user()->id_user;
            $ijin->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Perijinan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jenis Perijinan Disimpan');
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

            $ijin                  = JenisPerijinan::findOrFail($id);
            $ijin->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Perijinan Gagal Dihapus' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Perijinan Dihapus');
    }

    public function getjenis($id)
    {
        $data = JenisPerijinan::findOrFail($id);
        
        return response()->json($data);
    }
}
