<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Kepegawaian\Entities\Jabatan;
use Auth;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = Jabatan::with("user")->get();
        return view('kepegawaian::jabatan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('kepegawaian::jabatan.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            
            DB::beginTransaction();

            $jabatan                    = new Jabatan();
            $jabatan->nm_jabatan        = $request->nm_jabatan;
            $jabatan->id_user           = Auth::user()->id_user;
            $jabatan->deskripsi         = $request->deskripsi;
            //dd($jabatan);
            $jabatan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jabatan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jabatan Disimpan');
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
        $data["data"] = Jabatan::findOrFail($id);
        return view('kepegawaian::jabatan.index',$data);
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

            $jabatan                  = Jabatan::findOrFail($id);
            $jabatan->nm_jabatan      = $request->nm_jabatan;
            $jabatan->id_user         = Auth::user()->id_user;
            $jabatan->deskripsi       = $request->deskripsi;
            $jabatan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jabatan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jabatan Disimpan');
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

            $jabatan                  = Jabatan::findOrFail($id);
            $jabatan->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jabatan Gagal Dihapus' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jabatan Dihapus');
    }
}
