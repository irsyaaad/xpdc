<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Perusahaan;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {   
        $this->middleware('guest')->except('logout');
    }
    
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) {
       
        if (Auth::attempt ( array (
                'username' => $request->email,
                'password' => $request->password
            ))){
            
            $role_user = RoleUser::where("id_user", Auth::user()->id_user)->OrderBy("created_at", "asc")->first();
                
            $notif = "Akses Role Perusahaan Tidak Terdaftar";
            if($role_user==null){
                
                // akses terbatas
                return redirect('auth/logout/'.$notif);
            }

            $role["role"] = Role::find($role_user->id_role)->toArray();
            
            $perusahaan = Perusahaan::select("nm_perush", "id_perush", "cabang")->where("id_perush", $role_user->id_perush)->OrderBy("created_at", "asc")->first();
            
            if($perusahaan==null){
                // akes terbatas
                return redirect('auth/logout/'.$notif);
            }
            
            $perush = [];
            $role["perusahaan"]["id_perush"] = $perusahaan->id_perush;
            $role["perusahaan"]["nm_perush"] = $perusahaan->nm_perush;
            $role["perusahaan"]["cabang"] = $perusahaan->cabang;
            Session($role);
            
            return redirect('dashboard')->with('success', 'Selamat Datang');

        } else {

            return redirect()->back()->with('error', 'Username atau Password tidak terdaftar');
        }
    }
}
