<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use DB;
use Modules\Kepegawaian\Entities\StatusKaryawan;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\JenisKaryawan;
use Auth;

class StatusKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = StatusKaryawan::all();
        return view("kepegawaian::statuskaryawan", $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view("kepegawaian::statuskaryawan");
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $cek = StatusKaryawan::where("id_status_karyawan", $request->id_status_karyawan)->where("nm_status_karyawan", $request->nm_status_karyawan)->get()->first();

        if($cek !=null){
            return redirect(route_redirect())->with('error', 'Data Status Karyawan Sudah ada');
        }
        
        try {

            DB::beginTransaction();

            $statuskaryawan                            = new StatusKaryawan();
            $statuskaryawan->id_status_karyawan        = $request->id_status_karyawan;
            $statuskaryawan->nm_status_karyawan        = $request->nm_status_karyawan;
            $statuskaryawan->durasi                    = $request->durasi;
            $statuskaryawan->id_user                   = Auth::user()->id_user;
            $statuskaryawan->id_perush                 = Session("perusahaan")["id_perush"];
            $statuskaryawan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jenis Karyawan Disimpan');
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
        $data["data"] = StatusKaryawan::findOrFail($id);
        return view("kepegawaian::statuskaryawan",$data);
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

            $statuskaryawan                            = StatusKaryawan::findOrFail($id);
            $statuskaryawan->id_status_karyawan        = $request->id_status_karyawan;
            $statuskaryawan->nm_status_karyawan        = $request->nm_status_karyawan;
            $statuskaryawan->durasi                    = $request->durasi;
            $statuskaryawan->id_user                   = Auth::user()->id_user;
            $statuskaryawan->id_perush                 = Session("perusahaan")["id_perush"];
            $statuskaryawan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Status Karyawan Disimpan');
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

            $statuskaryawan                  = StatusKaryawan::findOrFail($id);
            $statuskaryawan->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Status Karyawan Gagal Dihapus' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Status Karyawan Dihapus');
    }

    public function laporanStatusKaryawan(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $id_jenis  = $request->f_id_jenis;
        $id_status  = $request->f_id_status;

        if(isset($request->f_perush) and $request->f_perush!= null){
            $id_perush = $request->f_perush;
        }
        
        $karyawan = Karyawan::with("status_karyawan","jenis","jabatan")->where("id_perush", $id_perush);

        if($id_jenis != null){
            $karyawan = $karyawan->where("id_jenis", $id_jenis);
        }

        if($id_status != null){
            $karyawan = $karyawan->where("id_status_karyawan", $id_status);
        }

        $data["data"] = $karyawan->get();
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["jenis"] = JenisKaryawan::all();
        $data["status_karyawan"] = StatusKaryawan::all();
        $data["filter"] = array("f_perush" => $id_perush, "f_id_jenis"=>$id_jenis, "f_id_status" => $id_status);
        return view("kepegawaian::laporanstatuskaryawan",$data);
    }
}
