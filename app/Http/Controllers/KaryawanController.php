<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanRequest;
use App\Models\JenisKaryawan;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Auth;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Modules\Kepegawaian\Entities\DetailKaryawan;
use Modules\Kepegawaian\Entities\Jabatan;
use Modules\Kepegawaian\Entities\MesinFinger;
use Modules\Kepegawaian\Entities\SettingJam;
use Modules\Kepegawaian\Entities\StatusKaryawan;
use Modules\Keuangan\Entities\DetailGajiKaryawan;
use Response;
use Session;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {

        $id_perush = $request->f_id_perush != null ? $request->f_id_perush : Session("perusahaan")["id_perush"];
        $is_aktif = $request->f_is_aktif != null ? $request->f_is_aktif : null;
        $f_karyawan = $request->f_karyawan != null ? $request->f_karyawan : null;
        $page = $request->shareselect != null ? $request->shareselect : 50;

        if (get_admin()) {
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        } else {
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $karyawan = Karyawan::getFilter($id_perush, $f_karyawan, $is_aktif);
        $data["data"] = $karyawan->paginate($page);
        $data["karyawan"] = Karyawan::select("nm_karyawan", "id_karyawan")->where('id_perush', $id_perush)->get();
        $data["filter"] = array("page" =>
            $page, "f_is_aktif" => $is_aktif,
            "f_id_perush" => $id_perush,
            "f_karyawan" => $f_karyawan);

        return view("karyawan", $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["jenis"] = JenisKaryawan::all();

        if (get_admin()) {
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        } else {
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $data["jam"] = SettingJam::where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["mesin"] = MesinFinger::where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data["jabatan"] = Jabatan::all();
        $data["statuskaryawan"] = StatusKaryawan::all();

        return view("karyawan", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KaryawanRequest $request)
    {
        try {
            // save to marketing
            DB::beginTransaction();
            $karyawan = new Karyawan();
            $karyawan->id_perush = $request->id_perush;
            $perush = Perusahaan::select("id_perush", "kode_perush")->where("id_perush", $request->id_perush)->get()->first();
            // if(!get_admin()){
            //     $karyawan->id_perush       = Session("perusahaan")["id_perush"];
            // }

            $karyawan->nm_karyawan = $request->nm_karyawan;
            $karyawan->id_jenis = $request->id_jenis;
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->no_hp = $request->no_hp;
            $karyawan->is_aktif = $request->is_aktif;
            $karyawan->tgl_masuk = $request->tgl_masuk;
            $karyawan->id_finger = $request->id_finger;
            $karyawan->id_jam_kerja = $request->id_jam_kerja;
            $karyawan->id_mesin = $request->id_mesin;
            $karyawan->id_jabatan = $request->id_jabatan;
            $karyawan->n_gaji = $request->n_gaji;
            $karyawan->biaya_bpjs = $request->n_bpjs;
            $karyawan->n_tunjangan = $request->n_tunjangan;
            $karyawan->golongan = $request->golongan;
            $karyawan->pangkat = $request->pangkat;
            $karyawan->id_status_karyawan = $request->id_status_karyawan;
            $karyawan->id_user = Auth::user()->id_user;
            $karyawan->tgl_mulai_sk = $request->tgl_mulai_sk;
            $karyawan->tgl_selesai_sk = $request->tgl_selesai_sk;
            $karyawan->kode_perush = $perush->kode_perush;
            $karyawan->is_sopir = $request->is_sopir;
            $karyawan->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Karyawan Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Karyawan Disimpan');
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
        $karyawan = Karyawan::with("perusahaan")->findOrFail($id);
        $data["jenis"] = JenisKaryawan::all();
        $data["jam"] = SettingJam::where("id_perush", $karyawan->id_perush)->get();
        $data["mesin"] = MesinFinger::where("id_perush", $karyawan->id_perush)->get();
        $data["jabatan"] = Jabatan::all();
        $data["statuskaryawan"] = StatusKaryawan::all();

        if (get_admin()) {
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        } else {
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }
        $data["data"] = $karyawan;
        //dd($data);
        return view("karyawan", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KaryawanRequest $request, $id)
    {
        try {
            // save to marketing
            DB::beginTransaction();
            $karyawan = Karyawan::findOrFail($id);
            // update user if exist
            $cek = User::where("id_user", $karyawan->id_user)->get()->first();
            if ($cek != null) {
                $user = [];
                $user["nm_user"] = $request->nm_karyawan;
                $user["telp"] = $request->no_hp;
                User::where("id_user", $karyawan->id_user)->update($user);
            }

            $perush = Perusahaan::select("id_perush", "kode_perush")->where("id_perush", $request->id_perush)->get()->first();

            $karyawan->id_perush = $request->id_perush;
            $karyawan->nm_karyawan = $request->nm_karyawan;
            $karyawan->id_jenis = $request->id_jenis;
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->no_hp = $request->no_hp;
            $karyawan->is_aktif = $request->is_aktif;
            $karyawan->tgl_masuk = $request->tgl_masuk;
            $karyawan->id_user = Auth::user()->id_user;
            $karyawan->id_finger = $request->id_finger;
            $karyawan->id_jam_kerja = $request->id_jam_kerja;
            $karyawan->id_mesin = $request->id_mesin;
            $karyawan->id_jabatan = $request->id_jabatan;
            $karyawan->n_gaji = $request->n_gaji;
            $karyawan->biaya_bpjs = $request->n_bpjs;
            $karyawan->n_tunjangan = $request->n_tunjangan;
            $karyawan->golongan = $request->golongan;
            $karyawan->pangkat = $request->pangkat;
            $karyawan->id_status_karyawan = $request->id_status_karyawan;
            $karyawan->tgl_mulai_sk = $request->tgl_mulai_sk;
            $karyawan->tgl_selesai_sk = $request->tgl_selesai_sk;
            $karyawan->kode_perush = $perush->kode_perush;
            $karyawan->is_sopir = $request->is_sopir;
            $karyawan->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Karyawan Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Karyawan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $karyawan = Karyawan::findOrFail($id);
            $karyawan->is_aktif = false;
            $karyawan->save();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect(route_redirect())->with('success', 'Data Karyawan dihapus');
    }

    public function setakses(Request $request, $id)
    {
        $cek = Karyawan::with("perusahaan")->findOrFail($id);
        if ($cek->user == true) {
            return redirect()->back()->with('error', 'User Karyawan Sudah Ada');
        }

        if (isset($request->username)) {
            $this->validate($request, [
                'nm_user' => 'max:100|regex:/(^[A-Za-z0-9 ]+$)+/|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_karyawan,nm_karyawan',
                'email' => 'max:40|email:rfc,dns|unique:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.users,email',
                'username' => 'required|alpha_num|min:4|max:40|unique:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.users,username',
                'password' => 'required|alpha_num|min:4|max:40',
                'id_perush' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.s_perusahaan,id_perush',
                'id_role' => 'required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.role,id_role',
            ]);
            DB::beginTransaction();

            try {
                $karyawan = Karyawan::findOrFail($id);
                // save to user

                $user = new User();
                // $user->last_id        = $this->generatedId($karyawan->id_perush);
                $user->id_perush = $karyawan->id_perush;
                $user->id_karyawan = $id;
                $user->nm_user = $karyawan->nm_karyawan;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->telp = $karyawan->no_hp;
                $user->password = Hash::make($request->password);
                $user->is_kacab = $request->is_kacab == 1 ? $request->is_kacab : 0;
                $user->save();

                // set akses
                $role = new RoleUser();
                $role->id_user = $user->id_user;
                $role->id_role = $request->id_role;
                $role->id_perush = $request->id_perush;
                $role->save();

                // update karyawan
                $karyawan->is_user = true;
                $karyawan->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput($request->all())->with('error', 'Data user Karyawan Gagal Disimpan' . $e->getMessage());
            }

            return redirect(route_redirect())->with('success', 'Data User Karyawan berhasil dibuat');
        }

        $data["data"] = $cek;
        $data["role"] = Role::getRole();
        $data["perush"] = Perusahaan::getRoleUser();

        return view("karyawan", $data);
    }

    public function generatedId($id_perush)
    {
        $id = User::where("id_perush", $id_perush)->orderBy("last_id", "desc")->get()->first();

        $last = 0;
        if ($id != null) {
            $last = (Int) $id->last_id + 1;
        }

        return $last;
    }

    public function detail_karyawan($id)
    {
        $data["data"] = DetailKaryawan::where('id_karyawan', $id)->get()->first();
        $data["perusahaan"] = Perusahaan::where("id_perush", Session("perusahaan")["id_perush"])->get()->first();
        return view("kepegawaian::karyawan.detailkaryawan", $data);
    }

    public function set_gaji($id)
    {
        $data["data"] = Karyawan::with("perusahaan")->findOrFail($id);
        $data["gaji"] = DetailGajiKaryawan::where('id_karyawan', $id)->get()->first();
        // dd($data);
        return view('kepegawaian::karyawan.detail-gaji', $data);
    }

    public function save_detail_gaji(Request $request)
    {
        // dd($request->all());
        if (!in_array(strtolower(Session("role")["nm_role"]), ['keuangan'])) {
            return redirect()->back()->withInput($request->input())->with('error', "Yang Bisa Edit Gaji Adalah Keuangan !!");
        }
        try {
            // save to marketing
            DB::beginTransaction();
            DetailGajiKaryawan::where("id_karyawan", $request->id_karyawan)->delete();

            $karyawan = new DetailGajiKaryawan();
            $karyawan->id_karyawan = $request->id_karyawan;
            $karyawan->n_gaji = $request->n_gaji;
            $karyawan->n_tunjangan_jabatan = $request->n_tunjangan_jabatan;
            $karyawan->n_tunjangan_kinerja = $request->n_tunjangan_kinerja;
            $karyawan->n_tunjangan_kpi = $request->n_tunjangan_kpi;
            $karyawan->n_tunjangan_kesehatan = $request->n_tunjangan_kesehatan;
            $karyawan->n_tunjangan_jht = $request->n_tunjangan_jht;
            $karyawan->n_tunjangan_jkk = $request->n_tunjangan_jkk;
            $karyawan->n_tunjangan_jkm = $request->n_tunjangan_jkm;
            $karyawan->n_tunjangan_jp = $request->n_tunjangan_jp;
            $karyawan->n_potongan_pph = $request->n_potongan_pph;
            $karyawan->n_potongan_kesehatan = $request->n_potongan_kesehatan;
            $karyawan->n_potongan_jht = $request->n_potongan_jht;
            $karyawan->n_potongan_jp = $request->n_potongan_jp;
            $karyawan->id_user = Auth::user()->id_user;
            $karyawan->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Detail Gaji Karyawan Gagal Disimpan' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Detail Gaji Karyawan Berhasil Disimpan');
    }
}
