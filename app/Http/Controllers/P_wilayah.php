<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Wilayah;
use DB;
use App\Http\Controllers\Api\LoginController;
use Modules\Busdev\Entities\VendorBusdev;

class P_wilayah extends Controller
{
    protected $ApiLogin;
    public function __construct(LoginController $LoginController)
    {
        $this->ApiLogin = $LoginController;
    }
    
    public function index()
    {
        $page = 1;
        $perpage = 50;
        $wilayah = Wilayah::getwil($page, $perpage);
        $data["data"] = $wilayah;
        $data["filter"] = [];
        
        return view('wilayah', $data);
    }
    
    public function filter(Request $request)
    {
        $id_wil = $request->f_id_wil!=null?$request->f_id_wil:null;
        $page = $request->page!=null?$request->page:1;
        $perpage = $request->shareselect!=null?$request->shareselect:50;
        
        $wilayah = Wilayah::getwil($page, $perpage, $id_wil);
        $data["data"] = $wilayah;
        $wilayah = Wilayah::findOrfail($id_wil);
        $data["filter"] = array('page' => $perpage, 'f_id_wil' => $id_wil, 'wilayah' => $wilayah);
        
        return view('wilayah', $data);
    }
    
    public function public(Request $request){
        $data["data"] = Wilayah::getPublic();
        $data["vendor"] = VendorBusdev::select("id_ven", "nm_ven")->get();
        
        return view('public-wilayah', $data);
    }
    
    public function getwil($id){
        $result = Wilayah::findOrFail($id);
        
        return response()->json($result);
    }
    
    public function create()
    {
        $data["provinsi"] = Wilayah::where('level_wil',1)->get();
        $data["kabupaten"] = Wilayah::where('level_wil',2)->get();
        $data["kecamatan"] = Wilayah::where('level_wil',3)->get();
        
        return view('wilayah', $data);
    }
    
    public function arrange($data)
    {
        foreach ($data as $query)
        {
            if($query->kab_id!=null and $query->prov_id!=null and $query->kec_id==null){
                
                $region = $query->provinsi.", ".$query->kabupaten.", ".$query->nama_wil;
                
            }else if($query->kab_id==null and $query->prov_id!=null){
                
                $region = $query->provinsi.", ".$query->nama_wil;
                
            }else{
                
                $region  = $query->nama_wil;
            }
            
            $results[] = ['kode' => $query->id_wil, 'value' => strtoupper($region)];
        }
        
        return $results;
    }
    
    public function getwilayah(Request $request)
    {
        $data = Wilayah::getWilayah($request->nama_wil);
        $cek = $this->ApiLogin->CheckToken($request->token);
        
        if($cek == false){
            
            $result = [
                "message" => "Token Expired",
                "status"    => 1,
                "data"  => false
            ];
            
            return response()->json($result);
        }
        
        $result = [];
        if (!$data) {
            
            $result = [
                "message" => "Wilayah Tidak Ditemukan",
                "status"    => 1,
                "data"  => []
            ];
            
        }else{
            
            $result = [
                "message" => "Data Sukses",
                "status"    => 0,
                "data"  => $data
            ];
            
        }
        
        return response()->json($result);
    }
    
    public function store(Request $request)
    {
        // dd($request->request);
        try {
            // save to user
            DB::beginTransaction();
            $gen                    = $this->generateId($request);
            $level                  = $request->tingkat;
            
            $wilayah                    = new Wilayah();
            if ($level == 1) {
                $wilayah->id_wil        = $gen["id"];
                $wilayah->nama_wil      = $request->wilayah;
                $wilayah->level_wil     = $request->tingkat;
            }
            
            if ($level == 2) {
                $wilayah->prov_id       = $request->provinsi;
                $wilayah->id_wil        = $gen["id"];
                $wilayah->nama_wil      = $request->wilayah;
                $wilayah->level_wil     = $request->tingkat;
            }
            
            if ($level == 3) {
                $wilayah->prov_id       = $request->provinsi;
                $wilayah->kab_id        = $request->kabupaten;
                $wilayah->id_wil        = $gen["id"];
                $wilayah->id_wil        = $gen["id"];
                $wilayah->nama_wil      = $request->wilayah;
                $wilayah->level_wil     = $request->tingkat;
            }
            
            // dd($wilayah);
            $wilayah->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url("wilayah"))->with('success', 'Data Account  Disimpan');
    }
    
    public function generateId($request)
    {
        $level = $request->tingkat;
        
        if($level == 1){
            $wilayah = Wilayah::where('level_wil',1)->max('id_wil');
            $data["id"] = (int) ($wilayah+1);
        }
        
        if($level == 2){
            $wilayah = Wilayah::where('prov_id',$request->provinsi)->max('id_wil');
            $temp = (int) ($wilayah+1);
            $data["id"] =$temp;
        }
        if($level == 3){
            $wilayah = Wilayah::where('kab_id',$request->kabupaten)->max('id_wil');
            $temp = (int) ($wilayah+1);
            $data["id"] =$temp;
        }
        
        return $data;
    }
    
    public function show($id)
    {
        
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
}
