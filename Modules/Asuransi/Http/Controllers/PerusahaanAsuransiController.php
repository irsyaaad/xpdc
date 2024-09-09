<?php

namespace Modules\Asuransi\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\PerusahaanAsuransi;

class PerusahaanAsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'page_title' => "Data Perusahaan Asuransi",
            'page_plugin_js' => [
                'assets-templatev2/js/asuransi/perusahaan-js',
            ],
            'page_plugin_drawer' => [
                'templatev2.partials.drawers._chat-messenger',
            ],
        ];
        $data["data"] = PerusahaanAsuransi::paginate(10);
        // dd($data);
        return view('asuransi::perusahaan-index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('asuransi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {

            // save to user
            DB::beginTransaction();

            $perusahaan = new PerusahaanAsuransi();
            $perusahaan->nm_perush_asuransi = $request->nm_perush_asuransi;
            $perusahaan->alamat = $request->alamat;
            $perusahaan->fax = $request->fax;
            $perusahaan->email = $request->email;
            $perusahaan->npwp = $request->npwp;
            $perusahaan->id_creator = Auth::user()->id_user;
            $perusahaan->cp = $request->cp;
            $perusahaan->no_cp = $request->no_cp;
            $perusahaan->jenis_asuransi = $request->jenis_asuransi;
            $perusahaan->jenis_resiko = $request->jenis_resiko;
            // dd($perusahaan);
            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan' . $e->getMessage());
        }

        return redirect("perusahaan-asuransi")->with('success', 'Data Perusahaan Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('asuransi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('asuransi::edit');
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

            // save to user
            DB::beginTransaction();

            $perusahaan = PerusahaanAsuransi::findOrFail($id);
            $perusahaan->nm_perush_asuransi = $request->nm_perush_asuransi;
            $perusahaan->alamat = $request->alamat;
            $perusahaan->fax = $request->fax;
            $perusahaan->email = $request->email;
            $perusahaan->npwp = $request->npwp;
            $perusahaan->id_creator = Auth::user()->id_user;
            $perusahaan->cp = $request->cp;
            $perusahaan->no_cp = $request->no_cp;
            $perusahaan->jenis_asuransi = $request->jenis_asuransi;
            $perusahaan->jenis_resiko = $request->jenis_resiko;
            // dd($perusahaan);
            $perusahaan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Perusahaan Gagal Disimpan' . $e->getMessage());
        }

        return redirect("perusahaan-asuransi")->with('success', 'Data Perusahaan Disimpan');
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
            $perusahaan = PerusahaanAsuransi::findOrFail($id);
            $perusahaan->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data dihapus');
    }
}
