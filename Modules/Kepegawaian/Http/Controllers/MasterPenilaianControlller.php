<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Penilaian;
use DB;
use Session;
use Exception;
use Auth;
use Validator;

class MasterPenilaianControlller extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data["data"] = Penilaian::orderby("id_penilaian", "asc")->get();

        return view('kepegawaian::penilaian', $data);
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
        $validator = Validator::make($request->all(), [
            'name'  => 'bail|required|alphanum_spaces|max:50|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,name',
            'id_penilaian'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,id_penilaian',
            'min_nilai'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,min_nilai',
            'max_nilai'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,max_nilai',
            'sign'  => 'bail|required|alpha|min:1|max:1|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,sign',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        try {
            
            DB::beginTransaction();
            $nilai                   = new Penilaian();
            $nilai->name             = $request->name;
            $nilai->id_penilaian             = $request->id_penilaian;
            $nilai->min_nilai             = $request->min_nilai;
            $nilai->max_nilai             = $request->max_nilai;
            $nilai->sign             = $request->sign;
            $nilai->created_by       = Auth::user()->nm_user;
            $nilai->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data penilaian Gagal Disimpan' )->withInput($request->input());
        }
        return redirect()->back()->with('success', 'Data posisi penilaian');
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
        $validator = Validator::make($request->all(), [
            'name'  => 'bail|required|alphanum_spaces|max:50|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,name,'.$id.',id_penilaian',
            'id_penilaian'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,id_penilaian,'.$id.',id_penilaian',
            'min_nilai'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,min_nilai,'.$id.',id_penilaian',
            'max_nilai'  => 'bail|required|digits_between:1,8|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,max_nilai,'.$id.',id_penilaian',
            'sign'  => 'bail|required|alpha|min:1|max:1|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.kpi_penilaian,sign,'.$id.',id_penilaian'
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        try {
            
            DB::beginTransaction();
            $nilai                   = Penilaian::FindOrFail($id);
            $nilai->name             = $request->name;
            $nilai->id_penilaian             = $request->id_penilaian;
            $nilai->min_nilai             = $request->min_nilai;
            $nilai->max_nilai             = $request->max_nilai;
            $nilai->sign             = $request->sign;
            $nilai->updated_by       = Auth::user()->nm_user;
            $nilai->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data penilaian Gagal Disimpan' )->withInput($request->input());
        }
        return redirect()->back()->with('success', 'Data posisi penilaian');
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
            $nilai                   = Penilaian::FindOrFail($id);
            $nilai->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data penilaian Gagal dihapus' )->withInput($request->input());
        }

        return redirect()->back()->with('success', 'Data posisi dihapus');
    }
}
