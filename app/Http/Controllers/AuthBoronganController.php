<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\GoogleAuthenticator;
use App\Http\Requests\AuthBoronganRequest;
use Auth;
use App\Models\Authenticator;
use Session;
use DB;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use App\Models\RoleUser;

class AuthBoronganController extends Controller
{
	public function index()
	{	
		$id_perush = null;
		if(!get_admin()){
			$id_perush = Session("perusahaan")["id_perush"];
		}

		// $perusahaan = Perusahaan::orderBy("id_perush", "asc")->get();
		// foreach($perusahaan as $key => $value){
		// 	$data["token"] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);
		// 	Perusahaan::where("id_perush", $value->id_perush)->update($data);
		// }
		
		$data["data"] = Authenticator::getAuth($id_perush);
		
		return view("authborongan", $data);
	}
	
	public function create(Request $request)
	{	
		$id_perush = Session("perusahaan")["id_perush"];
		$perusahaan = Perusahaan::findOrfail($id_perush);
		$kode = strtoupper($perusahaan->token);
		$ga = new GoogleAuthenticator();
		$ga->setSecret(strtoupper($kode));
		$ga->secret_code;
		$qrCodeUrl = $ga->getQRCodeGoogleUrl($perusahaan->nm_perush, $ga->secret_code);
		$data["secret"] = $ga->secret_code;
		$data["qrcode"] = $qrCodeUrl;
		$data["karyawan"] = RoleUser::getUserRole($id_perush);
		
		if(get_admin()){
			$data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
		}else{
			$data["perusahaan"] = Perusahaan::getRoleUser();
		}
		$data["id_perush"] = $id_perush;
		
		return view("authborongan", $data);
	}
	
	public function edit($id)
	{
		abort(404);
	}
	
	public function show($id)
	{
		$auth = Authenticator::findOrFail($id);
		$perusahaan = Perusahaan::findOrfail($auth->id_perush);
		$kode = strtoupper($perusahaan->token);
		$ga = new GoogleAuthenticator();
		$ga->setSecret($kode);
		$ga->secret_code;
		$qrCodeUrl = $ga->getQRCodeGoogleUrl($perusahaan->nm_perush, $ga->secret_code);
		$data["secret"] = $ga->secret_code;
		$data["qrcode"] = $qrCodeUrl;
		
		return view("authborongan", $data);
	}
	
	public function store(Request $request)
	{	
		DB::beginTransaction();
		$id_perush = Session("perusahaan")["id_perush"];
		$perusahaan = Perusahaan::findorFail($id_perush);
		try {
			
			$auth = new Authenticator();
			$auth->id_perush = $request->id_perush;
			$auth->id_karyawan = $request->id_user;
			$auth->id_admin = Auth::user()->id_user;
			$auth->auth_kode = strtoupper($request->secret);
			$auth->save();
			
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Data Autentikasi '.$perusahaan->nm_perush.' Gagal Disimpan' .$e->getMessage());
		}
		
		$url = route_redirect()."/".$auth->id_auth."/show";
		return redirect($url)->with('success', 'Data Autentikasi '.$perusahaan->nm_perush.' Berhasil Disimpan');
	}
	
	public function destroy($id, Request $request)
	{
		DB::beginTransaction();
		$id_perush = Session("perusahaan")["id_perush"];
		$perusahaan = Perusahaan::findOrfail($id_perush);
		try {
			
			$auth = Authenticator::findOrFail($id);
			$perusahaan = Perusahaan::findOrfail($auth->id_perush);
			$auth->delete();
			
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Data Autentikasi '.$perusahaan->nm_perush.' Gagal Disimpan' .$e->getMessage());
		}
		
		return redirect()->back()->with('success', 'Data Autentikasi '.$perusahaan->nm_perush.' Berhasil Disimpan');
	}
	
}
