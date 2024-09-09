<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\LoginController;
use App\Models\Perusahaan;
use App\Models\RoleUser;
use Modules\Operasional\Entities\Sopir;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use App\Models\Wilayah;
use DB;
use App\Models\WhiteList;

class PublicController extends Controller
{
    
    public function getWilayah(Request $request)
    {     
        $cek = $this->cekIp($request->ip());
        if($cek == false){

            $result = [
                "message" => "IP Address Has Been Black List, Hubungi Administrator",
                "status"    => 1,
                "data"  => false
            ];

            return response()->json($result);
        }  
        $data = Wilayah::get();
        
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

    
    public function getProvinsi(Request $request)
    {
        $cek = $this->cekIp($request->ip());
        if($cek == false){

            $result = [
                "message" => "IP Address Has Been Black List, Hubungi Administrator",
                "status"    => 1,
                "data"  => false
            ];

            return response()->json($result);
        }

        $data = Wilayah::where("level_wil", 1)->get();
        
        if(isset($request->id_provinsi) and $request->id_provinsi != null){
            $data = $data->where("id_wil", $request->id_provinsi);
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

    public function getKabupaten(Request $request)
    {
        $cek = $this->cekIp($request->ip());
        if($cek == false){

            $result = [
                "message" => "IP Address Has Been Black List, Hubungi Administrator",
                "status"    => 1,
                "data"  => false
            ];

            return response()->json($result);
        }

        $data = Wilayah::where("level_wil", 2)->get();
        
        if($request->id_provinsi != null){
            $data->where("id_provinsi", $request->id_provinsi);
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

    public function getKecamatan(Request $request)
    {   
        $cek = $this->cekIp($request->ip());
        if($cek == false){

            $result = [
                "message" => "IP Address Has Been Black List, Hubungi Administrator",
                "status"    => 1,
                "data"  => false
            ];

            return response()->json($result);
        }

        $data = Wilayah::where("level_wil", 3)->get();
        
        if($request->id_kabupaten != null){
            $data->where("id_kabupaten", $request->id_kabupaten);
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
    
    public function cekIp($id)
    {
        $ip = WhiteList::where("ip_address", $id)->get()->first();
        if($ip!= null){
            return true;
        }else{
            return false;
        }
    }
}