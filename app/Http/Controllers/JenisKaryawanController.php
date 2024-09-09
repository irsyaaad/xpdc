<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\JenisKaryawan;
use Auth;
use Exception;

class JenisKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["data"] = JenisKaryawan::orderBy("nm_jenis", "asc")->get();

        return view("jeniskaryawan", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("jeniskaryawan");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $cek = JenisKaryawan::where("kode_jenis", $request->id_jenis)->where("nm_jenis", $request->nm_jenis)->get()->first();

        if($cek !=null){
            return redirect(route_redirect())->with('error', 'Data Jenis Karyawan Sudah ada');
        }

        try {

            DB::beginTransaction();

            $jenis_karyawan                  = new JenisKaryawan();
            $jenis_karyawan->kode_jenis      = $request->id_jenis;
            $jenis_karyawan->nm_jenis        = $request->nm_jenis;
            $jenis_karyawan->golongan        = $request->golongan;
            $jenis_karyawan->pangkat         = $request->pangkat;
            $jenis_karyawan->n_gaji          = $request->n_gaji!=null?$request->n_gaji:0;
            $jenis_karyawan->id_user         = Auth::user()->id_user;
            $jenis_karyawan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jenis Karyawan Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["data"] = JenisKaryawan::findOrFail($id);

        return view("jeniskaryawan", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            $jenis_karyawan                  = JenisKaryawan::findOrFail($id);
            $jenis_karyawan->kode_jenis      = $request->id_jenis;
            $jenis_karyawan->nm_jenis        = $request->nm_jenis;
            $jenis_karyawan->golongan        = $request->golongan;
            $jenis_karyawan->pangkat         = $request->pangkat;
            $jenis_karyawan->n_gaji          = $request->n_gaji!=null?$request->n_gaji:0;
            $jenis_karyawan->id_user         = Auth::user()->id_user;
            $jenis_karyawan->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Jenis Karyawan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $marketing                  = JenisKaryawan::findOrFail($id);
            $marketing->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Jenis Karyawan Gagal Dihapus, Masih di pakai tabel lain' );
        }

        return redirect(route_redirect())->with('success', 'Data Jenis Karyawan Dihapus');
    }
}
