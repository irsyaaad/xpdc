<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CabangGroup;
use DB;
use Validator;
use App\Http\Requests\RoleRequest;
use Auth;
use Exception;

class P_Cabgroup extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["data"] = CabangGroup::paginate(10);
        
        return view("cabanggroup", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("cabanggroup");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nm_cabgroup' => 'required|min:4|max:25'
         ]);
        
         $cek = CabangGroup::where("nm_cabgroup", $request->nm_cabgroup)->get()->first();
            
         if($cek!=null){
            return redirect()->back()->with('error', 'Data role Sudah Ada');
         }

         try {
            DB::beginTransaction();

            $group                     = new CabangGroup();
            $group->nm_cabgroup        = $request->nm_cabgroup;
            $group->id_user            = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Group Cabang Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Group Cabang Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["data"]  = CabangGroup::findorfail($id);
        
        return view("cabanggroup", $data); 
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
        $this->validate($request,[
            'nm_cabgroup' => 'required|min:4|max:25'
         ]);
        
         $cek = CabangGroup::where("nm_cabgroup", $request->nm_cabgroup)->get()->first();
            
         if($cek!=null){
            return redirect()->back()->with('error', 'Data role Sudah Ada');
         }

         try {
            DB::beginTransaction();

            $group                     = CabangGroup::findorfail($id);
            $group->nm_cabgroup        = $request->nm_cabgroup;
            $group->id_user            = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Group Cabang Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Group Cabang Disimpan');
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
            
            $group                     = CabangGroup::findorfail($id);
            $group->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Group Cabang Gagal Dihapus'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Group Cabang Dihapus');
    }
}
