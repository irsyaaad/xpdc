<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\MesinFinger;
use App\Models\Perusahaan;
use DB;
use Auth;
use Exception;

class MesinFingerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        if (get_admin()) {
            $data["data"] = MesinFinger::all();
        }else {
            $data["data"] = MesinFinger::where("id_perush", $id_perush)->get();
        }   

        return view('kepegawaian::mesinfinger', $data);
    }

    public function getMesinFinger($id)
    {
        $data = MesinFinger::select("id_mesin", "nm_mesin")->where("id_perush", $id)->get();
        
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["perush"] = Perusahaan::getRoleUser();

        return view('kepegawaian::mesinfinger', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {   
        DB::beginTransaction();

        try{
            $mesin = new MesinFinger();
            $mesin->id_perush = $request->id_perush;
            $mesin->id_user = Auth::user()->id_user;
            $mesin->cloud_id = strtoupper($request->cloud_id);
            $mesin->authorization = strtoupper($request->authorization);
            $mesin->nm_mesin = $request->nm_mesin;
            $mesin->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Mesin Finger Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Mesin Finger Disimpan');
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
        $data["perush"] = Perusahaan::all();
        $data["data"] = MesinFinger::findOrfail($id);

        return view('kepegawaian::mesinfinger', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try{
            $mesin = MesinFinger::findOrfail($id);
            $mesin->id_perush = $request->id_perush;
            $mesin->id_user = Auth::user()->id_user;
            $mesin->cloud_id = strtoupper($request->cloud_id);
            $mesin->authorization = strtoupper($request->authorization);
            $mesin->nm_mesin = $request->nm_mesin;
            $mesin->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Mesin Finger Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Mesin Finger Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try{
            $mesin = MesinFinger::findOrfail($id);
            $mesin->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Mesin Finger Gagal Dihapus, Masih Dibuat Tabel Lain' .$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Mesin Finger Dihapus');
    }
}
