<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use App\Http\Requests\UserRequest;
use Modules\Operasional\Entities\Sopir;
use App\Models\Pelanggan;
use DB;
use Auth;
use Hash;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->shareselect?$request->shareselect:50;
        $f_id_karyawan = $request->f_id_karyawan;
        $f_id_perush = $request->f_id_perush?$request->f_id_perush:null;
        $user = User::with("perusahaan", "sopir", "pelanggan", "karyawan")
        ->orderBy("nm_user", "asc");
        
        if(isset($f_id_perush)){
            $user->where("id_perush", $f_id_perush);
        }

        if($f_id_karyawan!=null){
            $user->where("id_karyawan", $f_id_karyawan);
        }
        
        $data["data"] = $user->paginate($page);
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["karyawan"] = Karyawan::select("id_karyawan", "nm_karyawan")->get();
        $data["filter"] = array("f_id_perush"=>$f_id_perush, "f_id_karyawan" => $f_id_karyawan, "page" => $page);
        
        return view("user", $data);
    }
    
    public function create()
    {
        $data["data"] = [];
        $data["karyawan"] = Karyawan::getKaryawanNotUser();
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["role"] = Role::all();
        
        return view("create-user", $data);
    }
    
    public function store(UserRequest $request)
    {
        $cek = User::where("id_karyawan", $request->id_karyawan)->get()->first();
        if($cek!=null){
            return redirect()->back()->with('error', 'Karyawan sudah terdaftar sebagai user')->withInput($request->all());
        }

        try {
            DB::beginTransaction();
            
            $karyawan = Karyawan::findOrFail($request->id_karyawan);
            $user                       = new User();
            $user->id_perush      = $request->id_perush;
            $user->id_karyawan      = $request->id_karyawan;
            $user->username  = $request->username;
            $user->nm_user  = $karyawan->nm_karyawan;
            $user->email  = $karyawan->email;
            $user->telp  = $karyawan->telp;
            $user->password    = Hash::make($request->password);
            $user->save();
            // add roles
            $role = new RoleUser();
            $role->id_user = DB::getPdo()->lastInsertId();
            $role->id_role = $request->id_role;
            $role->id_perush = $request->id_perush;
            $role->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data User Gagal Disimpan'.$e->getMessage())->withInput($request->all());
        }
        
        return redirect('user')->with('success', 'Data User Disimpan');
    }
    
    public function show($id)
    {
        abort(404);
    }

    public function getkaryawannouser(String $id){
        $data = Karyawan::getKaryawanNotUser($id);

        return Response()->json($data);
    }

    public function getkaryawanuser(String $id){
        $data = Karyawan::getkaryawanuser($id);

        return Response()->json($data);
    }
    
    public function profile()
    {
        $data["data"] = User::findOrFail(Auth::user()->id_user);
        
        return view("profile", $data);
    }
    
    public function saveprofile(Request $request)
    {
        $id = Auth::user()->id_user;
        $validator = Validator::make($request->all(), [
            'email'  => 'max:40|email:rfc,dns|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.users,email,'.$id.',id_user',
            'username' => 'required|alpha_num|min:4|max:40|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.users,username,'.$id.',id_user',
            'password' => 'nullable|alpha_num|min:4|max:40',
            'telp' => 'required|digits_between:8,16'
        ]);
        
        if ($validator->fails())
        {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }
        
        try {
            DB::beginTransaction();
            $data = [];
            $data["id_perush"] = $request->id_perush;
            $data["username"] = $request->username;
            $data["email"] = $request->email;
            $data["telp"] = $request->telp;
            
            if(isset($request->password) and $request->password!=null){
                $data["password"] = Hash::make($request->password);
            }
            User::where("id_user", Auth::user()->id_user)->update($data);
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->withInput($request->input())->with('error', 'Data User Gagal Diupdate');
        }
        
        return redirect("auth/logout")->with('success', 'Data User Diupdate');
    }
    
    public function edit($id)
    {
        $user = User::FindOrFail($id);
        $data["karyawan"] = Karyawan::where("id_karyawan", $user->id_karyawan)->get();
        $data["perush"] = Perusahaan::select("id_perush", "nm_perush")->get();
        $data["data"] = $user;

        return view("create-user", $data);
    }
    
    public function update(UserRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $data = [];
            $data["username"] = $request->username;
            if(isset($request->password) and $request->password!=null){
                $data["password"] = Hash::make($request->password);
            }
            User::where("id_user", $id)->update($data);
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data User Gagal Disimpan');
        }
        
        return redirect('user')->with('success', 'Data User Disimpan');
    }
    
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try{
            
            $user = User::findOrFail($id);
            
            if(isset($user->id_pelanggan) and $user->id_pelanggan!=null){
                $pelanggan = Pelanggan::findOrFail($user->id_pelanggan);
                $pelanggan->is_user = false;
                $pelanggan->save();
            }
            
            if(isset($user->id_sopir) and $user->id_sopir!=null){
                $sopir = Sopir::findOrFail($user->id_sopir);
                $sopir->is_user = false;
                $sopir->save();
            }
            
            if(isset($user->id_karyawan) and $user->id_karyawan!=null){
                $karyawan = Karyawan::findOrFail($user->id_karyawan);
                $karyawan->is_user = false;
                $karyawan->save();
            }
            
            $user->delete();
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect()->back()->with('success', 'Data User Dihapus');
    }
    
    public function logout($id = null)
    {
        Auth::logout();
        
        if(isset($id) && $id !=null){
            return redirect('auth/login')->with('error', $id);
        }else{
            return redirect('auth/login')->with('error', 'Anda keluar Aplikasi');
        }
    }
}
