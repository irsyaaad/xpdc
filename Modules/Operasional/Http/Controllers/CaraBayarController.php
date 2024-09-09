<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\CaraBayar;
use Modules\Operasional\Http\Requests\CaraBayarRequest;
use DB;
use Auth;

class CaraBayarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = CaraBayar::get();
        
        return view('operasional::carabayar', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('operasional::carabayar');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CaraBayarRequest $request)
    {
        try {

            // save to user
            DB::beginTransaction();
            $carabayar                = new CaraBayar();
            $carabayar->kode_cr_byr_o   = $request->id_cr_byr_o;
            $carabayar->nm_cr_byr_o   = $request->nm_cr_byr_o;
            $carabayar->is_aktif      = $request->is_aktif;
            $carabayar->id_user       = Auth::user()->id_user;
            $carabayar->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Cara Bayar Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Cara Bayar Disimpan');
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
        $data["data"] = CaraBayar::findOrFail($id);
        
        return view('operasional::carabayar', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    
    public function update(CaraBayarRequest $request, $id)
    {
        try {
            
            // save to user
            DB::beginTransaction();

            $carabayar                = CaraBayar::findOrFail($id);
            $carabayar->kode_cr_byr_o   = $request->id_cr_byr_o;
            $carabayar->nm_cr_byr_o   = $request->nm_cr_byr_o;
            $carabayar->is_aktif      = $request->is_aktif;
            $carabayar->id_user       = Auth::user()->id_user;
            $carabayar->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Cara Bayar Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Cara Bayar Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{

            $carabayar                = CaraBayar::findOrFail($id);
            $carabayar->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect()->back()->with('success', 'Data Cara Bayar Di Hapus');
    }
}
