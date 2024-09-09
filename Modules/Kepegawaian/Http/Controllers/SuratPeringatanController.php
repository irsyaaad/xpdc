<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use App\Models\User;

class SuratPeringatanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('kepegawaian::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('kepegawaian::create');
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
        return view('kepegawaian::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('kepegawaian::edit');
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

    public function cetak($id)
    {
        // dd($id);
        $karyawan               =    Karyawan::findOrFail($id);
        $perusahaan             =    Perusahaan::findOrFail($karyawan->id_perush);
        $data["karyawan"]       =    $karyawan;
        $data["perusahaan"]     =    $perusahaan;
        $use          = User::getKacab($karyawan->id_perush);
        $kacab = null;
        if(count($use)>=1){
            $kacab = $use[0]->nm_karyawan;
        }
        
        $data["kacab"] = $kacab;
        return view('kepegawaian::suratperingatan.surat_peringatan_1', $data);
    }
}
