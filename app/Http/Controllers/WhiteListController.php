<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhiteList;
use DB;
use Exception;
use Auth;
use Session;
use Validator;

class WhiteListController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $data["data"] = WhiteList::where("id_perush", Session("perusahaan")["id_perush"])->get();
        
        return view("whitelist", $data);
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        abort(404);
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|min:6|max:50',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $white           = new WhiteList();
            $white->ip_address  = $request->ip_address;
            $white->id_perush  = Session("perusahaan")["id_perush"];
            $white->save();
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Ip WhiteList gagal disimpan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data WhiteList Disimpan');
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
        abort(404);
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
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|min:6|max:50',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $white           = WhiteList::findOrFail($id);
            $white->ip_address  = $request->ip_address;
            $white->id_perush  = Session("perusahaan")["id_perush"];
            $white->save();
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Ip WhiteList gagal disimpan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data WhiteList Disimpan');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $white           = WhiteList::findOrFail($id);
            $white->delete();
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Ip WhiteList gagal dihapus'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data WhiteList dihapus');
    }
}
