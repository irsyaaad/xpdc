<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\Grouppelanggan;
use App\Http\Requests\Groupplgnreq;
use DB;
use Auth;
class P_grouppelanggan extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["data"] = Grouppelanggan::select("id_plgn_group", "kode_plgn_group as kode", "nm_group", "is_umum")->get();
        
        return view("group_pelanggan", $data);
    }


    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("group_pelanggan");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Groupplgnreq $request)
    {
        try {

            DB::beginTransaction();
            
            $group           = new Grouppelanggan();
            $group->kode_plgn_group = $request->id_plgn_group;
            $group->nm_group = $request->nm_group;
            $group->is_umum = $request->is_umum;
            $group->id_user = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Pelanggan Gagal Disimpan');
        }

        return redirect("groupplgn")->with('success', 'Data Group Pelanggan Disimpan');
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
        $data["data"] = Grouppelanggan::select("id_plgn_group","kode_plgn_group as kode", "nm_group", "is_umum")->where("id_plgn_group", $id)->first();
        
        return view("group_pelanggan", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Groupplgnreq $request, $id)
    {
        try {
            
            DB::beginTransaction();
            $group           = Grouppelanggan::findOrFail($id);
            $group->kode_plgn_group = $request->id_plgn_group;
            $group->nm_group = $request->nm_group;
            $group->is_umum = $request->is_umum;
            $group->id_user = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Pelanggan Gagal Disimpan');
        }

        return redirect("groupplgn")->with('success', 'Data Group Pelanggan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::beginTransaction();

            $group           = Grouppelanggan::findOrFail($id);
            $group->delete();
            
            DB::commit();
        } catch (Exception $e) {

             return redirect()->back()->with('error', 'Data masih digunakan di table lain'.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Group Pelanggan Dihapus');
    }
}
