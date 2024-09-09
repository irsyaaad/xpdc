<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Http\Requests\HandlingRequest;
use Modules\Operasional\Http\Requests\SttDmHandlingRequest;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DMTiba;
use App\Models\Perusahaan;
use App\Models\Wilayah;
use App\Models\Layanan;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\HistoryStt;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\SttDm;
use DB;
use Auth;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Entities\Handling;
use Modules\Operasional\Entities\HandlingStt;
use Modules\Operasional\Entities\BiayaHandling;
use Modules\Operasional\Entities\ProyeksiHandling;
use Modules\Operasional\Entities\HandlingDetailPro;
use Modules\Operasional\Http\Requests\BiayaProyeksiRequest;
use Modules\Keuangan\Entities\GroupBiaya;
use Validator;
use App\Models\Vendor;

class HandlingVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;
        
        $id_perush = Session("perusahaan")["id_perush"];
        $id_handling = $request->id_handling;
        $id_wil = $request->id_wil;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $id_status = $request->id_status;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $id_wil = $request->id_wil;
        $id_perush_dr = null;
        $id_ven = $request->id_ven;

        if(isset($request->shareselect) and $request->shareselect != null){
            $perpage = $request->shareselect;
        }

        if(isset($request->page) and $request->page != null){
            $page = $request->page;
        }
        
        $data["data"]= Handling::getHandling($page, $perpage, $id_perush,2, $id_ven, $id_handling, $id_perush_dr, $id_wil, $id_sopir, $id_armada, $dr_tgl, $sp_tgl, $id_status);
        $data["sopir"] = Sopir::select("id_sopir", "nm_sopir")->where("id_perush", $id_perush)->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();;
        $data["perusahaan"] = Perusahaan::getDataExept();
        $data["status_handling"] = StatusDM::select("id_status", "nm_status")->where("tipe", "2")->get();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->get();

        $id_handling = Handling::select("id_handling", "kode_handling")->where("id_handling", $id_handling)->get()->first();
        
        $id_wil = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $id_wil)->get()->first();
        $filter = array("page"=>$perpage, "id_perush_dr"=>$id_perush_dr, "id_wil" => $id_wil, "id_handling"=>$id_handling, "id_status" => $id_status, "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl,  "id_ven" => $id_ven, "id_sopir"=>$id_sopir, "id_armada" => $id_armada);
        $data["filter"] = $filter;

        return view('operasional::handling.handlingvendor', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::where("id_perush", $id_perush)->get();
        
        if(get_admin()){
            $sopir = Sopir::all();
        }
        
        $perush = Perusahaan::findOrFail($id_perush);
        
        $data["sopir"] = $sopir;
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::select("id_armada", "nm_armada", "no_plat")->where("id_perush", $id_perush)->get();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", $id_perush)->get();

        return view('operasional::handling.handlingvendor', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sopir'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'id_ven'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
            'region_tuju' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'keterangan'  => 'bail|nullable|min:4|max:150',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        
        $id_perush = Session("perusahaan")["id_perush"];
        $perush = Perusahaan::FindOrFail($id_perush);
        $id_handling =null;

        DB::beginTransaction();
        try {
            
            $gen = $this->generateId($id_perush);
            $status                        = StatusDM::select("id_status")->where("tipe", "2")->orderBy("id_status", "asc")->get()->first();
            $handling                      = new Handling();
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr            = $perush->id_region;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_ven              = $request->id_ven;
            $handling->id_user             = Auth::user()->id_user;
            $handling->kode_handling       = strtoupper($gen["kode_handling"]);
            $handling->id_handling          = strtoupper($gen["id_handling"]);
            $handling->id_sopir            = $request->id_sopir;
            $handling->id_status           = $status->id_status;
            $handling->keterangan          = $request->keterangan;
            $handling->save();
                
            $id_handling = $handling->id_handling;

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("handlingvendor/".$id_handling."/show"))->with('success', 'Data Handling Disimpan');
    }

    public function generateId($id_perush)
    {
        $time = substr(time(), 3,10);
        $data["kode_handling"] = "HV".$id_perush.$time;
        $data["id_handling"] = $id_perush.$time;
        
        return $data;
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
        $handling = Handling::findOrFail($id);
        if($handling->id_ven == null){
            return redirect()->back()->with('error', 'Data Handling Bukan Handling Vendor ');
        }
        $id_perush = Session("perusahaan")["id_perush"];
        $sopir = Sopir::where("id_perush", $id_perush)->get();
        
        if(get_admin()){
            $sopir = Sopir::all();
        }
        
        $perush = Perusahaan::findOrFail($id_perush);
        $data["data"] = $handling;
        $data["sopir"] = $sopir;
        $data["region"] = Wilayah::findOrFail($perush->id_region);
        $data["armada"] = Armada::select("id_armada", "nm_armada", "no_plat")->where("id_perush", $id_perush)->get();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", $id_perush)->get();
        $data["tujuan"] = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $handling->region_tuju)->get()->first();

        return view('operasional::handling.handlingvendor', $data);
    }
    
    public function gethandling(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = Handling::select("id_handling", "kode_handling")->where("id_perush", $id_perush)->whereNotNull("id_ven")->where("kode_handling", 'LIKE', '%' . strtoupper($term) . '%')->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_handling, 'value' => strtoupper($value->kode_handling)];
        }  
        
        return response()->json($results); 
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
            'id_sopir'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'id_armada'  => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'id_ven'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
            'region_tuju' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'keterangan'  => 'bail|nullable|min:4|max:150',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors());
        }
        
        $id_perush = Session("perusahaan")["id_perush"];
        $perush = Perusahaan::FindOrFail($id_perush);

        DB::beginTransaction();
        try {
            
            $handling                      = Handling::findOrFail($id);
            $handling->id_perush           = $id_perush;
            $handling->id_armada           = $request->id_armada;
            $handling->region_dr            = $perush->id_region;
            $handling->region_tuju         = $request->region_tuju;
            $handling->id_ven              = $request->id_ven;
            $handling->id_user             = Auth::user()->id_user;
            $handling->id_sopir            = $request->id_sopir;
            $handling->keterangan          = $request->keterangan;
            $handling->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Handling Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(route_redirect())->with('success', 'Data Handling Disimpan');
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
}
