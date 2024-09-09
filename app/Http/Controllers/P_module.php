<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\Module;
use DB;
use Validator;
use Session;
use Auth;
use App\Models\Menu;
use Modules\Keuangan\Entities\Invoice;
use Modules\Keuangan\Entities\InvoiceHandling;

use App\Http\Requests\ModuleRequest;

class P_module extends Controller
{
    public function index()
    {
        $data["data"] = Module::all();
        
        return view("module", $data);
    }
    
    public function dashboard()
    {   
        $role = Session('role')['nm_role'];
        switch (strtolower($role)) {
            case 'admin':
                $module["module"] = Module::where('nm_module', 'Operasional')->first()->toArray();
                break;
            case 'keuangan':
                $module["module"] = Module::where('nm_module', 'Keuangan')->first()->toArray();
                break;
            case 'asuransi':
                $module["module"] = Module::where('nm_module', 'Asuransi')->first()->toArray();
                break;            
            default:
                $module["module"] = Module::where('nm_module', 'Operasional')->first()->toArray();
                break;
        }
        $data["module"] = Module::getSessionModul();
        
        Session($module);
        
        return redirect(strtolower($module["module"]["nm_module"]))->with('success', 'Selamat Datang Di Module '.$module["module"]["nm_module"]);

        // return view('template.menu', $data);
    }

    public function create()
    {   
        $data["data"] = [];

        return view("module", $data);
    }

    public function store(ModuleRequest $request)
    {
        try {
            
            // save to user
            DB::beginTransaction();
            $module                       = new Module();
            $module->nm_module = $request->nm_module;
            $module->icon  = $request->icon;
            $module->color  = $request->color;
            $module->id_creator    = Auth::user()->id;

            if(strtolower($module->nm_module)=="administrator"){
                return redirect()->back()->with('error', 'Akses Terbatas');
            }

            $module->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data module Gagal Disimpan');
        }

        return redirect("module")->with('success', 'Data module Disimpan');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data["data"] = Module::find($id);

        return view("module", $data);
    }

    public function update(ModuleRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();

            $module                 = Module::findOrFail($id);

            $module->nm_module      = $request->nm_module;
            $module->icon           = $request->icon;
            $module->color          = $request->color;
            $module->id_creator     = Auth::user()->id;
            
            if(strtolower($module->nm_module)=="administrator"){
                return redirect()->back()->with('error', 'Akses Terbatas');
            }

            $module->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data module Gagal Disimpan');
        }

        return redirect("module")->with('success', 'Data module Disimpan');
    }

    public function destroy($id)
    {   
        try{

            $module = Module::findOrFail($id);
            if(strtolower($module->nm_module)=="administrator"){
                return redirect()->back()->with('error', 'Akses Terbatas');
            }

            $module->delete();
            
        } catch (Exception $e) {

           return redirect()->back()->with('error', 'Data masih digunakan di table lain');
       }

        return redirect("module")->with('success', 'Data Berhail Dihapus');
    }
    
    public function choose($id)
    {
        $module["module"] = Module::find($id)->toArray();
        
        Session($module);
        
        return redirect(strtolower($module["module"]["nm_module"]))->with('success', 'Selamat Datang Di Module '.$module["module"]["nm_module"]);
    }

    public function getmenu($id)
    {   
        $data = Menu::select("id_menu", "nm_menu", "level")->where("id_module", $id)->orderBy("parent", "asc")->orderBy("id_menu", "asc")->get();
        
        $a_data = [];
        foreach ($data as $key => $value) {
            $a_data[$value->id_menu] = $value->nm_menu;
        }
        
        return response()->json($data);
    }

    public function is_read($id)
    {
        //dd($id);
        try {
            DB::beginTransaction();

            $hasil["is_read"] = true;
            Invoice::where("id_invoice",$id)->update(
                $hasil
            );
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan'.$e->getMessage());
        }
        return redirect("/invoice"."/".$id."/show");
    }

    public function is_readhandling($id)
    {
        //dd($id);
        try {
            DB::beginTransaction();

            $hasil["is_read"] = true;
            InvoiceHandling::where("id_invoice",$id)->update(
                $hasil
            );
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Invoice Gagal Disimpan'.$e->getMessage());
        }
        return redirect("/invoicehandling"."/".$id."/show");
    }
}
