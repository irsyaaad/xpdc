<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use App\Models\Perusahaan;
use DB;
use App\Http\Requests\UserRequest;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegister()
    {
        $data["perusahaan"] = Perusahaan::all();

        return view('auth.register1', $data);
    }

    protected function create(UserRequest $request)
    {   
        try {
            // save to user
            DB::beginTransaction();
            $user                       = new User();
            $user->username  = $request->username;
            $user->email  = $request->email;
            $user->id_perush    = $request->id_perush;
            $user->password    = Hash::make($request->password);
            $user->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'User Gagal Register');
        }

        return redirect('auth/login')->with('success', 'User Sukses Register');
    }
}
