<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Kepegawaian\Entities\Marketing;
use Auth;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Session;
use Validator;

class MarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = Marketing::where("id_perush", $id_perush)->paginate(25);

        return view('kepegawaian::marketing', $data);
    }

    public function getMarketing($id)
    {
        $data = Marketing::getMarketing($id);
        
        return Response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["karyawan"] = Karyawan::getKaryawanAll();
        
        return view('kepegawaian::marketing', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'telp'  => 'bail|required|max:16',
            'id_karyawan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan'
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
        }

        $cek = Marketing::where("id_karyawan", $request->id_karyawan)->where("id_perush", $request->id_perush)->get()->first();
        
        if($cek){
            return redirect()->back()->with('error', 'Marketing Sudah terdaftar di perusahaan ini');
        }

        DB::beginTransaction();
        try {

            $marketing = new Marketing();
            $karyawan = Karyawan::findOrFail($request->id_karyawan);
            $marketing->nm_marketing = $karyawan->nm_karyawan;
            $marketing->id_karyawan = $request->id_karyawan;
            $marketing->id_perush = Session("perusahaan")["id_perush"];
            $marketing->telp = $request->telp;
            $marketing->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Marketing Gagal Disimpan '.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Marketing  Disimpan');
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
        $id_perush = Session("perusahaan")["id_perush"];
        $data["karyawan"] = Karyawan::getKaryawanAll();
        $data["data"] = Marketing::findOrFail($id);
        
        return view('kepegawaian::marketing', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'telp'  => 'bail|required|max:16',
            'id_karyawan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
        }

        $cek = Marketing::where("id_karyawan", $request->id_karyawan)->where("id_perush", $request->id_perush)->where("telp", $request->telp)->get()->first();
        
        if($cek){
            return redirect()->back()->with('error', 'Marketing Sudah terdaftar di perusahaan ini');
        }

        DB::beginTransaction();
        try {

            $marketing = Marketing::findOrFail($id);
            $karyawan = Karyawan::findOrFail($request->id_karyawan);
            $marketing->nm_marketing = $karyawan->nm_karyawan;
            $marketing->id_karyawan = $request->id_karyawan;
            $marketing->id_perush = Session("perusahaan")["id_perush"];
            $marketing->telp = $request->telp;
            $marketing->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Marketing Gagal Disimpan '.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Marketing  Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $marketing = Marketing::findOrFail($id);
            $marketing->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Marketing Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Marketing  Dihapus');
    }
}
