<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\GroupVendor;
use DB;
use Auth;
use App\Http\Requests\GroupVendorRequest;

class GroupVendorController extends Controller
{

    public function index()
    {
        $data["data"] = GroupVendor::get();

        return view("groupvendor", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("groupvendor");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupVendorRequest $request)
    {
        try {
            // save to group
            DB::beginTransaction();

            $group                  = new GroupVendor();
            $group->id_grup_ven        = $request->id_grup_ven;
            $group->nm_grup_ven        = $request->nm_grup_ven;
            $group->is_aktif        = $request->is_aktif;
            $group->id_user         = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Group Vendor Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Group Vendor Disimpan');
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
        $data["data"] = GroupVendor::findOrFail($id);
        
        return view("groupvendor", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupVendorRequest $request, $id)
    {
        try {
            // save to group
            DB::beginTransaction();

            $group                  = GroupVendor::findOrFail($id);
            $group->id_grup_ven        = $request->id_grup_ven;
            $group->nm_grup_ven     = $request->nm_grup_ven;
            $group->is_aktif        = $request->is_aktif;
            $group->id_user         = Auth::user()->id_user;
            $group->save();

            DB::commit();
        } catch (Exception $e) {
            
            return redirect()->back()->with('error', 'Data Group Vendor Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Data Group Vendor Disimpan');
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

            $group = GroupVendor::findOrFail($id);
            $group->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect(route_redirect())->with('success', 'Data Group Vendor Disimpan');
    }
}
