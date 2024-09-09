<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Penilaian;
use Modules\Kepegawaian\Entities\Objective;
use DB;
use Session;
use Exception;
use Auth;
use Validator;
use App\Models\JenisKaryawan;

class ObjectiveController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $data["data"] = Objective::with("jenis")->orderBy("created_at", "asc")->get();
        $data["jenis"] = JenisKaryawan::select("nm_jenis", "id_jenis")->orderby("nm_jenis", "asc")->get();
        
        return view('kepegawaian::objective', $data);
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
            'keterangan'  => 'bail|required|max:250',
            'id_jenis'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_jenis_karyawan,id_jenis',
            'bobot'  => 'bail|required|digits_between:1,100',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }
        
        try {
            
            DB::beginTransaction();
            $objective                   = new Objective();
            $objective->keterangan             = $request->keterangan;
            $objective->id_jenis             = $request->id_jenis;
            $objective->bobot             = $request->bobot;
            $objective->created_by       = Auth::user()->nm_user;
            
            $objective->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data objective Gagal Disimpan'.$e->getMessage() )->withInput($request->input());
        }
        
        return redirect()->back()->with('success', 'Data objective penilaian');
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
            'keterangan'  => 'bail|required|max:250',
            'id_jenis'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_jenis_karyawan,id_jenis',
            'bobot'  => 'bail|required|digits_between:1,100',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }
        
        try {
            
            DB::beginTransaction();
            $objective                   = Objective::findOrfail($id);
            $objective->keterangan             = $request->keterangan;
            $objective->id_jenis             = $request->id_jenis;
            $objective->bobot             = $request->bobot;
            $objective->updated_by       = Auth::user()->nm_user;
            
            $objective->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data objective Gagal Disimpan' )->withInput($request->input());
        }
        
        return redirect()->back()->with('success', 'Data objective penilaian');
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
            $nilai                   = Objective::FindOrFail($id);
            $nilai->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data objective Gagal dihapus' )->withInput($request->input());
        }
        
        return redirect()->back()->with('success', 'Data objective dihapus');
    }
}
