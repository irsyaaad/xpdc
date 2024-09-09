<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\RoleMenu;
use DB;
use Validator;
use App\Http\Requests\RoleMenuRequest;
use App\Models\Role;
use App\Models\Menu;
use App\Models\User;
use App\Models\Perusahaan;
use Auth;
use Session;
use App\Models\Module;

class P_rolemenu extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
       $page = 50;
       $id_module= $request->f_id_module;
       $id_role = $request->f_id_role;

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }
        
        $data["data"] = RoleMenu::getFilter($page, $id_role, $id_module);
        $data["role"] = Role::select("id_role", "nm_role")->get();
        $data["module"] = Module::select("id_module", "nm_module")->get();
        $data["filter"] = array("page" => $page, "f_id_role" => $id_role, "f_id_module" => $id_module);

        return view("rolemenu", $data);
    }

    public function filter(Request $request)
    {
       $page = 50;
       $id_module= $request->f_id_module;
       $id_role = $request->f_id_role;

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data["data"] = RoleMenu::getFilter($page, $id_role, $id_module);
        $data["role"] = Role::select("id_role", "nm_role")->get();
        $data["module"] = Module::select("id_module", "nm_module")->get();
        $data["filter"] = array("page" => $page, "f_id_role" => $id_role, "f_id_module" => $id_module);
        return view("rolemenu", $data);
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $data["role"] = Role::select("id_role", "nm_role")->get();
        $data["module"] = Module::select("id_module", "nm_module")->get();
        $data["menu"] = Menu::select("id_menu", "nm_menu")->get();
        $data["data"] = [];

        return view("create-rolemenu", $data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // check if exsist
        $rolemenu = RoleMenu::where("id_menu", $request->id_menu)->where("id_role", $request->id_role)->get()->first();

        if(!is_null($rolemenu)){
            return redirect()->back()->with('error', 'Role Akses Sudah ada')->withInput($request->all());
        }

        $chek = RoleMenu::where("id_role", $request->id_role)
        ->where("id_menu", $request->id_menu)->first();

        if($chek!=null){
            return redirect()->back()->with('error', 'Role Sudah Ada')->withInput($request->all());
        }

        try {
            // save to user
            DB::beginTransaction();

            $role               = new RoleMenu();
            $role->id_menu      = $request->id_menu;
            $role->id_role      = $request->id_role;
            $menu               = Menu::findOrFail($request->id_menu);
            $role->id_module    = $menu->id_module;
            $role->c_read       = $request->c_read;
            $role->c_insert     = $request->c_insert;
            $role->c_update     = $request->c_update;
            $role->c_delete     = $request->c_delete;
            $role->c_other      = $request->c_other;
            $role->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Role User Gagal Disimpan' . $e->getMessage() )->withInput($request->all());
        }

        return redirect("rolemenu")->with('success', 'Data Role User Disimpan');
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
        $data["role"] = Role::all();
        $data["module"] = Module::select("id_module", "nm_module")->get();
        $data["data"] = RoleMenu::findOrFail($id);
        $data["menu"] = Menu::select("id_menu", "nm_menu")->get();

        return view("create-rolemenu", $data);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(RoleMenuRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();

            $role               = RoleMenu::findOrFail($id);
            $role->id_menu      = $request->id_menu;
            $role->id_role      = $request->id_role;

            $menu               = Menu::findOrFail($request->id_menu);
            $role->id_module    = $menu->id_module;

            $role->c_read       = $request->c_read;
            $role->c_insert     = $request->c_insert;
            $role->c_update     = $request->c_update;
            $role->c_delete     = $request->c_delete;
            $role->c_other      = $request->c_other;
            //dd($role);
            $role->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Role User Gagal Disimpan');
        }

        return redirect("rolemenu")->with('success', 'Data Role User Disimpan');
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

            $role = RoleMenu::findOrFail($id);
            $role->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data Role User dihapus');
    }
}
