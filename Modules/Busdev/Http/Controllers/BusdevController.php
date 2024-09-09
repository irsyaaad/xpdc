<?php

namespace Modules\Busdev\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Module;

class BusdevController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return redirect()->to(url('/hargavendor'));
        // return view('keuangan::dashboard');
        // if (strtolower(Session("role")["nm_role"]) == "busdev") {
        //     // return view('kepegawaian::hargavendor.index', $data);
        //     $data["module"] = Module::getSessionModul();
        //     $data["content"] = 'busdev::contents.adminbusdev.dashboard-contents.dasboard-adminbusdev';
        //     return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
        // } else {
        //     // return view('kepegawaian::hargavendor.card', $data);
        //     $data["module"] = Module::getSessionModul();
        //     $data["content"] = 'busdev::contents.marketing.dashboard-contents.dashboard';
        //     return view('busdev::metroniclapan-template.mainview-busdevs', $data);
        // }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
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
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function templatehargadirect()
    {
        return response()->download(storage_path('/assets-adminbusdev/contohimport/directimport.xlsx'));
    }
}
