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
use Modules\Operasional\Entities\SttModel;
use App\Models\Layanan;
use Modules\Operasional\Entities\HistoryStt;
use DB;

class MasterController extends Controller
{
    protected $ApiLogin; 
    public function __construct(LoginController $LoginController)
    {
        $this->ApiLogin = $LoginController;
    }
    
    public function getkodestt(Request $request)
    {
        // $cek = $this->ApiLogin->CheckToken($request->token);
        $cek = true;
        if($cek == false){
            
            $result = [
                "message" => "Token Expired",
                "status"    => 1,
                "data"  => false
            ];
            
            return response()->json($result);
        }else{
            
            $perush                         = Perusahaan::where("kode_ref", $request->kode_perush)->get()->first();
            $layanan                        = Layanan::find($request->id_layanan);
            $date                           = date("ym");
            
            $b                              = substr(crc32(uniqid()),-4);
            $id                             = strtoupper($layanan->id_layanan.$perush->id_perush.$date.$b);
            $kode                           = strtoupper($layanan->kode_layanan.$perush->kode_ref.$date.$b);
            
            $data = [];
            $data["kode_stt"] = $kode;
            $data["id_perush_asal"] = $perush->id_perush;
            $data["id_layanan"] = $request->id_layanan;
            $data["is_booking"] = false;
            $cek = true;
            $data["kode_perush"] = $request->kode_perush;
            $result = [];
            if (!$cek) {
                
                $result = [
                    "message" => "Kode Stt gagal di generate",
                    "status"    => 1,
                    "data"  => $data
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
    }
    
    public function track(Request $request)
    {
        $data = SttModel::getApi($request->kode_stt);
        $result = [];
        if (!$data) {
            
            $result = [
                "message" => "Stt Tidak Ditemukan",
                "status"    => 1,
                "data"  => []
            ];
            
        }else{
            $status = HistoryStt::getHistory($data->id_stt);
            $result = [
                "message" => "Data Sukses",
                "status"    => 0,
                "data"  => array(
                    'stt' => $data,
                    'status' => $status,
                    )
            ];
            
        }
            
        return response()->json($result); 
    }
    
    public function checkunique(Request $request)
    {
        $cek = $this->ApiLogin->CheckToken($request->token);

        if($cek == false){
            
            $result = [
                "message" => "Token Expired",
                "status"    => 1,
                "data"  => false
            ];
            
            return response()->json($result);
        }
        
        $number = Pelanggan::getNumber($request->phone);
        $perush = Perusahaan::where("kode_ref", $request->kode_perush)->get()->first();
        $cek = Pelanggan::where("telp", $number)
        ->where("id_perush", $perush->id_perush)->get()->first();
        
        if($cek==null) 
        {
            $result = [
                "message" => "Pelanggan Belum Terdaftar",
                "status"    => 0,
                "data"  => array(
                    'phone' => $number,
                    'id_perush' => $perush->id_perush,
                    'kode_perush' => $perush->kode_ref
                    )
                ];
        }else{
            $result = [
                "message" => "No Telp. Pelanggan Sudah Terdaftar",
                "status"    => 1,
                "data"  => []
            ];
        }

        return response()->json($result);
    }

    public function insertpelanggan(Request $request)
    {
        // $cek = $this->ApiLogin->CheckToken($request->token);
        // $result = [];
        // if($cek == false){
            
        //     $result = [
        //         "message" => "Token Expired",
        //         "status"    => 1,
        //         "data"  => false
        //     ];
            
        //     return response()->json($result);
        // }
        $number = Pelanggan::getNumber($request->phone);
        $perush = Perusahaan::where("kode_ref", $request->kode_perush)->get()->first();
        
        $data = [];
        
        $data["id_perush"] = $perush->id_perush;
        $data["nm_pelanggan"] = $request->nama;
        $data["telp"] = $number;
        $data["alamat"] = $request->alamat;
        $data["id_plgn_group"] = $request->id_group;
        $insert = Pelanggan::insert($data);
        $data["id_pelanggan"] = DB::getPdo()->lastInsertId();
        $data["kode_perush"] = $request->kode_perush;
        $data["n_limit_piutang"] = 2000000;
        
        if(!$insert){

            $result = [
                "message" => "Pelanggan Gagal Insert",
                "status"    => 1,
                "data"  => false
            ];

        }else{
            $result = [
                "message" => "Pelanggan Berhasil Insert",
                "status"    => 0,
                "data"  => $data
            ];
        }

        return response()->json($result);
    }

public function syncpelanggan(Request $request)
    {
        $cek = $this->ApiLogin->CheckToken($request->token);
        $result = [];
        if($cek == false){
            
            $result = [
                "message" => "Token Expired",
                "status"    => 1,
                "data"  => false
            ];
            
            return response()->json($result);
        }
        $perush = Perusahaan::where("kode_ref", $request->kode_perush)->get()->first();
        
        $data = Pelanggan::select("id_pelanggan", "nm_pelanggan", "alamat", "id_plgn_group", "telp")
        ->where("id_perush", $perush->id_perush)->get();
        
        if(!$data){

            $result = [
                "message" => "Data tidak ditemukan",
                "status"    => 1,
                "data"  => false
            ];

        }else{
            $result = [
                "message" => "Data sukses ditemukan",
                "status"    => 0,
                "data"  => $data
            ];
        }

        return response()->json($result);
    }

    public function getPerush(Request $request)
    {
        $cek = $this->ApiLogin->CheckToken($request->token);
        
        if($cek == false){
            
            $result = [
                "message" => "Token Expired",
                "status"    => 1,
                "data"  => false
            ];
            
            return response()->json($result);
        }
        
        $data = Perusahaan::getKodePerush($request->kode_perush);
        
        $result = [];
        if (!$data) {
            
            $result = [
                "message" => "Cabang Perusahaan Tidak Ditemukan",
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
        
        public function getRole(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
                    "status"    => 1,
                    "data"  => false
                ];
                
                return response()->json($result);
            }
            
            $data = RoleUser::ChekRole($request->id_user);
            
            $result = [];
            if (!$data) {
                
                $result = [
                    "message" => "Role Tidak Ditemukan",
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
        
        public function getDriver(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
                    "status"    => 1,
                    "data"  => false
                ];
                
                return response()->json($result);
            }
            
            $data = Sopir::getDriver($request->id_user);
            
            $result = [];
            if (!$data) {
                
                $result = [
                    "message" => "Driver Tidak Ditemukan",
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
        
        public function getPelanggan(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
                    "status"    => 1,
                    "data"  => false
                ];
                
                return response()->json($result);
            }
            
            $data = Pelanggan::getPelanggan($request->kode_perush);
            
            $result = [];
            if (!$data) {
                
                $result = [
                    "message" => "Pelanggan Tidak Ditemukan",
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
        
        public function getListKaryawan(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
                    "status"    => 1,
                    "data"  => false
                ];
                
                return response()->json($result);
            }
            
            $data = DB::table("m_karyawan as k")->where("p.kode_perush", $request->kode_perush)
            ->where("k.is_aktif", true)
            ->select("k.*")
            ->join("s_perusahaan as p", "k.id_perush", "p.id_perush")
            ->orderBy("k.updated_at", "asc")
            ->orderBy("k.nm_karyawan", "asc")->get();
            
            $result = [];
            if (!$data) {
                
                $result = [
                    "message" => "Cabang Perusahaan Tidak Ditemukan",
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
        
        public function getWilayah(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
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
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
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
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
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
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
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
        
        public function getListVendor(Request $request)
        {
            $cek = $this->ApiLogin->CheckToken($request->token);
            
            if($cek == false){
                
                $result = [
                    "message" => "Token Expired",
                    "status"    => 1,
                    "data"  => false
                ];
                
                return response()->json($result);
            }
            
            $data = DB::table("m_vendor as v")->where("p.kode_ref", $request->kode_perush)
            ->where("v.is_aktif", true)
            ->select("v.*")
            ->join("s_perusahaan as p", "v.id_perush", "p.id_perush")
            ->orderBy("v.updated_at", "asc")
            ->orderBy("v.nm_ven", "asc")->get();
            
            $result = [];
            if (!$data) {
                
                $result = [
                    "message" => "Cabang Vendor Tidak Ditemukan",
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
        
    }
    
    