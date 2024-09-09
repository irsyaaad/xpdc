<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\Perusahaan;
use App\Http\Requests\RequestPelanggan;
use DB;
use App\Models\Pelanggan;
use Auth;
use App\Models\Wilayah;
use App\Models\Grouppelanggan;
use App\Models\RoleUser;
use App\Models\User;
use Hash;
use App\Models\Vendor;
use DataTables;
use Modules\Keuangan\Entities\SettingLimitPiutang;
use Session;
use Validator;

class P_pelanggan extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $f_id_pelanggan = $request->f_id_pelanggan;
        $f_id_plgn_grup = $request->f_id_plgn_grup;
        $f_id_wil =$request->f_id_wil;

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data["data"] = Pelanggan::getFilter($id_perush, $f_id_pelanggan, $f_id_plgn_grup, $f_id_wil)->paginate($page);
        $data["group"] = Grouppelanggan::select("nm_group","kode_plgn_group", "id_plgn_group")->get();

        if($f_id_pelanggan != null){
            $f_id_pelanggan = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_pelanggan", $f_id_pelanggan)->get()->first();
        }

        if($f_id_wil != null){
            $f_id_wil = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_wil)->get()->first();
        }

        $data["filter"] = array("page"=> $page, "f_id_pelanggan"=>$f_id_pelanggan, "f_id_plgn_grup" => $f_id_plgn_grup, "f_id_wil" => $f_id_wil);

        return view("pelanggan", $data);
    }

    public function filter(Request $request)
    {
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $f_id_pelanggan = $request->f_id_pelanggan;
        $f_id_plgn_grup = $request->f_id_plgn_grup;
        $f_id_wil =$request->f_id_wil;

        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data["data"] = Pelanggan::getFilter($id_perush, $f_id_pelanggan, $f_id_plgn_grup, $f_id_wil)->paginate($page);
        $data["group"] = Grouppelanggan::select("nm_group","kode_plgn_group", "id_plgn_group")->get();

        if($f_id_pelanggan != null){
            $f_id_pelanggan = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_pelanggan", $f_id_pelanggan)->get()->first();
        }

        if($f_id_wil != null){
            $f_id_wil = Wilayah::select("id_wil", "nama_wil")->where("id_wil", $f_id_wil)->get()->first();
        }

        $data["filter"] = array("page"=> $page, "f_id_pelanggan"=>$f_id_pelanggan, "f_id_plgn_grup" => $f_id_plgn_grup, "f_id_wil" => $f_id_wil);

        return view("pelanggan", $data);
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $data["data"] = [];
        $data["group"] = Grouppelanggan::select("id_plgn_group as kode", "nm_group", "is_umum")->get();
        $data["limit"] = SettingLimitPiutang::select("nominal", "is_default")->get();

        return view("create-pelanggan", $data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(RequestPelanggan $request)
    {
        DB::beginTransaction();
        $number = Pelanggan::getNumber($request->telp);
        $cekCustomerNumber = Pelanggan::where('telp', '=', $number)->where('id_perush', '=', Session("perusahaan")["id_perush"])->get()->first();

        if (!empty($cekCustomerNumber)) {
            $text = "Nomer Sudah terdaftar Atas Nama {$cekCustomerNumber->nm_pelanggan}";
            return redirect()->back()->withInput($request->input())->with('error', $text);
        }
        try {

            // save to pelanggan

            $pelanggan                      = new Pelanggan();
            $pelanggan->nm_pelanggan        = $request->nm_pelanggan;
            $pelanggan->id_plgn_group       = $request->id_plgn_group;
            $pelanggan->alamat              = substr($request->alamat, 0, 250);
            $pelanggan->id_wil              = $request->id_wil;
            $pelanggan->telp                = Pelanggan::getNumber($request->telp);
            $pelanggan->email               = $request->email;
            $pelanggan->fax                 = $request->fax;
            $pelanggan->nm_kontak           = $request->nm_kontak;
            $pelanggan->no_kontak           = $request->no_kontak;
            $pelanggan->npwp                = $request->npwp;
            $pelanggan->isaktif             = $request->isaktif;
            $pelanggan->is_member           = $request->is_member;
            $pelanggan->id_user             = Auth::user()->id_user;

            if(strtolower(Session("role")["nm_role"]) == "keuangan"){
                $pelanggan->n_limit_piutang  = $request->n_limit_piutang != 0 ? $request->n_limit_piutang : 2000000;
            }

            $pelanggan->id_perush = Session("perusahaan")["id_perush"];
            $pelanggan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pelanggan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("pelanggan")->with('success', 'Data Pelanggan Disimpan');
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
        $data["data"] = Pelanggan::with("perusahaan")->where("id_pelanggan", $id)->get()->first();
        $data["wilayah"] = Wilayah::find($data["data"]->id_wil);
        $data["group"] = Grouppelanggan::select("id_plgn_group as kode", "nm_group", "is_umum")->get();
        $data["limit"] = SettingLimitPiutang::select("nominal", "is_default")->get();

        return view("create-pelanggan", $data);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(RequestPelanggan $request, $id)
    {
        DB::beginTransaction();
        try {

            // save to pelanggan

            $pelanggan               = Pelanggan::findOrFail($id);
            $pelanggan->nm_pelanggan = $request->nm_pelanggan;
            $pelanggan->id_plgn_group  = $request->id_plgn_group;
            $pelanggan->alamat  = substr($request->alamat, 0, 250);
            $pelanggan->id_wil  = $request->id_wil;
            $pelanggan->telp  = Pelanggan::getNumber($request->telp);
            $pelanggan->email  = $request->email;
            $pelanggan->fax  = $request->fax;
            $pelanggan->nm_kontak  = $request->nm_kontak;
            $pelanggan->no_kontak  = $request->no_kontak;
            $pelanggan->npwp  = $request->npwp;
            $pelanggan->isaktif  = $request->isaktif;
            $pelanggan->is_member  = $request->is_member;

            if(strtolower(Session("role")["nm_role"]) == "keuangan"){
                $pelanggan->n_limit_piutang  = $request->n_limit_piutang!=0?$request->n_limit_piutang:2000000;
            }

            $pelanggan->id_user    = Auth::user()->id_user;
            $pelanggan->id_perush           = Session("perusahaan")["id_perush"];
            $pelanggan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pelanggan Gagal Disimpan'.$e->getMessage());
        }

        return redirect("pelanggan")->with('success', 'Data Pelanggan Disimpan');
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
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect("pelanggan")->with('success', 'Data Pelanggan Dihapus');
    }

    public function setAkses($id)
    {
        $cek = User::where("id_pelanggan", $id)->get()->first();

        if($cek != null){

            return redirect()->back()->with('error', 'Akses Pelanggan Telah Didaftarkan');
        }else{
            // save to user
            DB::beginTransaction();
            try {

                $pelanggan = Pelanggan::findOrFail($id);
                // create user
                $user                       = new User();
                $user->last_id        = User::generatedId($pelanggan->id_perush);
                $user->id_perush      = $pelanggan->id_perush;
                $user->nm_user = $pelanggan->nm_pelanggan;
                $user->username  = "plgn".$user->last_id;
                $user->telp  = $pelanggan->telp;
                $user->password    = Hash::make("plgn".$user->last_id);
                $user->id_user = $user->id_perush."usr".$user->last_id;
                $user->id_pelanggan = $pelanggan->id_pelanggan;
                $user->save();

                // update sopir is user
                $pelanggan->is_user = true;
                $pelanggan->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data User Pelanggan Gagal Disimpan'.$e->getMessage());
            }

            return redirect()->back()->with('success', 'Data User Pelanggan Disimpan');
        }
    }

    public function import(Request $request)
    {
        $data = [];
        $id_perush = Session("perusahaan")["id_perush"];
        $data["perusahaan"] = Perusahaan::getPerusahaan();
        $data["vendor"] = Vendor::select("id_ven", "nm_ven")->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $jenis = Grouppelanggan::where("kode_plgn_group", "EA")->get()->first();

        if(isset($request->jenis)){
            DB::beginTransaction();
            try {
                if($request->jenis==1){
                    $perush = Perusahaan::findOrFail($request->id_lsj_ven);

                    $cek = Pelanggan::where("id_perush_cabang", $perush->id_perush)->where("id_perush", $id_perush)->get()->first();

                    if($cek){
                        DB::rollback();
                        return redirect()->back()->with('error', 'Data User Pelanggan Sudah Ada');
                    }

                    $pelanggan = new Pelanggan();
                    $pelanggan->id_plgn_group = $jenis->id_plgn_group;
                    $pelanggan->nm_pelanggan = $perush->nm_perush;
                    $pelanggan->alamat = $perush->alamat;
                    $pelanggan->id_wil = $perush->id_region;
                    $pelanggan->telp = $perush->telp;
                    $pelanggan->fax = $perush->fax;
                    $pelanggan->email = $perush->email;
                    $pelanggan->term = null;
                    $pelanggan->nm_kontak = $perush->nm_cs;
                    $pelanggan->no_kontak = $perush->telp_cs;
                    $pelanggan->id_user = Auth::user()->id_user;
                    $pelanggan->npwp = null;
                    $pelanggan->isaktif = true;
                    $pelanggan->id_perush = Session("perusahaan")["id_perush"];
                    $pelanggan->id_perush_cabang = $perush->id_perush;
                    $pelanggan->n_limit_piutang = 2000000;
                    $pelanggan->id_vendor = null;
                    $pelanggan->save();

                }else{
                    $vendor = Vendor::findOrFail($request->id_ven);
                    $cek = Pelanggan::where("id_vendor", $vendor->id_ven)->get()->first();
                    if($cek){
                        DB::rollback();
                        return redirect()->back()->with('error', 'Data User Pelanggan Sudah Ada');
                    }

                    $pelanggan = new Pelanggan();
                    $pelanggan->id_plgn_group = $jenis->id_plgn_group;
                    $pelanggan->nm_pelanggan = $vendor->nm_ven;
                    $pelanggan->alamat = $vendor->alm_ven;
                    $pelanggan->id_wil = $vendor->id_wil;
                    $pelanggan->telp = $vendor->telp_ven;
                    $pelanggan->fax = $vendor->fax_ven;
                    $pelanggan->email = $vendor->email_ven;
                    $pelanggan->term = null;
                    $pelanggan->nm_kontak = $vendor->kontak_ven;
                    $pelanggan->no_kontak = $vendor->kontak_hp;
                    $pelanggan->id_user = Auth::user()->id_user;
                    $pelanggan->npwp = null;
                    $pelanggan->isaktif = true;
                    $pelanggan->id_perush = Session("perusahaan")["id_perush"];
                    $pelanggan->id_perush_cabang =null;
                    $pelanggan->id_vendor = $vendor->id_ven;
                    $pelanggan->n_limit_piutang = 2000000;
                    $pelanggan->save();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data User Pelanggan Gagal Disimpan'.$e->getMessage());
            }

            return redirect()->back()->with('success', 'Data User Pelanggan Berhasil Disimpan');
        }

        return view("pelanggan", $data);
    }

    public function add(Request $request)
    {
        $rules = [
            'nm_pelanggan' => 'bail|required|max:150',
            'alamat' => 'bail|required|max:250',
            'id_wil' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'telp'  => 'bail|required|digits_between:6,14',
            'id_plgn_group' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_plgn_group,id_plgn_group',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $cekCustomerNumber = Pelanggan::where('telp', '=', $request->telp)->where('id_perush', '=', Session("perusahaan")["id_perush"])->get()->first();

        if (!empty($cekCustomerNumber)) {
            $text = "GAGAL SAVE, Nomer Sudah terdaftar Atas Nama {$cekCustomerNumber->nm_pelanggan}";
            $data = [
                "error" => ["err"=> $text],
            ];
            return response()->json($data);
        }

        DB::beginTransaction();
        try {

            $pelanggan                  = new Pelanggan();
            $pelanggan->nm_pelanggan    = $request->nm_pelanggan;
            $pelanggan->id_plgn_group   = $request->id_plgn_group;
            $pelanggan->alamat          = substr($request->alamat, 0, 250);
            $pelanggan->id_wil          = $request->id_wil;
            $pelanggan->telp            = $request->telp;
            $pelanggan->n_limit_piutang  = 2000000;
            $pelanggan->isaktif         = true;
            $pelanggan->id_user         = Auth::user()->id_user;
            $pelanggan->id_perush       = Session("perusahaan")["id_perush"];
            $pelanggan->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            $data = [
                "error" => ["err"=> $e->getMessage()],
            ];

            return response()->json($data);
        }

        $data = [
            "status" => 0,
            "data"   => $pelanggan,
        ];

        return response()->json($data);
    }

    public function GetForDatatable()
    {
        return Datatables::of(Pelanggan::where('id_perush',Session('perusahaan')['id_perush']))->make(true);
    }
}
