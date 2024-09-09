<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\PiutangKaryawan;
use Modules\Kepegawaian\Entities\DetailPiutang;
use Modules\Keuangan\Entities\ACPerush;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Perusahaan;
use Session;
use DB;
use Exception;
use Auth;
use Validator;
use Modules\Keuangan\Entities\MasterAC;

class PiutangKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function index(Request $request)
    {
        $f_id_perush = Session("perusahaan")["id_perush"];
        $f_id_karyawan = null;
        $f_tgl_awal = date("Y-m-d");
        $f_tgl_akhir = date("Y-m-d");

        $piutang = PiutangKaryawan::getData();
        if($request->filterperush and $request->filterperush != null){
            $f_id_perush = $request->filterperush;
            $piutang->where("k.id_perush", $f_id_perush);
        }
        if($request->f_id_karyawan and $request->f_id_karyawan != null){
            $f_id_karyawan = $request->f_id_karyawan;
            $piutang->where("k.id_karyawan", $f_id_karyawan);
        }
        if($request->f_tgl_awal and $request->f_tgl_awal != null){
            $f_tgl_awal = $request->f_tgl_awal;
            $piutang->where("kep_piutang_karyawan.tgl_piutang", ">=", $f_tgl_awal);
        }
        if($request->f_tgl_akhir and $request->f_tgl_akhir != null){
            $f_tgl_akhir = $request->f_tgl_akhir;
            $piutang->where("kep_piutang_karyawan.tgl_piutang", "<=", $f_tgl_akhir);
        }
        $data["data"] = $piutang->paginate(10);
        $data["ac"] = ACPerush::getACDebit();
        $data["piutang"] = ACPerush::getPiutang("piutang karyawan", $f_id_perush);
        $data["filter"]= array("filterperush"=> $f_id_perush, "f_id_karyawan" => $f_id_karyawan, "f_tgl_awal"=> $f_tgl_awal, "f_tgl_akhir" => $f_tgl_akhir);
        $data["role_perush"] = Perusahaan::getRoleUser();
        $data["karyawan"] = Karyawan::select("id_karyawan", "nm_karyawan")->where("id_perush", $f_id_perush)->get();

        return view('kepegawaian::piutangkaryawan.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan");

        if(!get_admin()){
            $karyawan->where("id_perush", Session("perusahaan")["id_perush"]);
        }

        $data["karyawan"] = $karyawan->get();
        return view('kepegawaian::piutangkaryawan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'id_karyawan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
            'nominal' => 'bail|required|max:10',
            'keperluan' => 'bail|required|min:4|max:200',
            'frekuensi' => 'bail|required|max:2'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);
        }
        
        $cek = PiutangKaryawan::where("id_karyawan", $request->id_karyawan)->where("is_lunas", "!=", true)->get()->first();
        if($cek){
            return redirect()->back()->with('error', 'Ada Piutang Yang Belum Selesai');
        }

        DB::beginTransaction();
        try {
            $karyawan = Karyawan::findOrFail($request->id_karyawan);

            $piutang = new PiutangKaryawan();
            $piutang->id_karyawan = $request->id_karyawan;
            $piutang->nominal = $request->nominal;
            $piutang->frekuensi = $request->frekuensi;
            $piutang->keperluan = $request->keperluan;
            $piutang->id_perush = $karyawan->id_perush;
            $piutang->n_angsuran = $request->nominal / $request->frekuensi;
            $piutang->status = 1;
            $piutang->id_user = Auth::user()->id_user;
            $piutang->approve = false;
            $piutang->is_lunas = false;
            $piutang->id_piutang = strtolower($karyawan->id_karyawan.$karyawan->id_perush.date("ym"));
            $piutang->save();

            DB::commit();
            } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Piutang Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Piutang Karyawan Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $piutang = PiutangKaryawan::with("perusahaan", "karyawan")->findOrFail($id);
        $bln = date('Y-m-d', strtotime("+".$piutang->frekuensi." months", strtotime($piutang->tgl_piutang)));
        $piutang->tgl_selesai = $bln;

        $data["debet"] = ACPerush::getACDebit();
        $data["kredit"] = ACPerush::getPiutang("piutang karyawan", Session("id_perush"));
        $data["data"] = $piutang;
        $data["detail"] = DetailPiutang::where("id_piutang", $id)->get();

        return view('kepegawaian::piutangkaryawan.detail', $data);
    }

    public function bayar(Request $request, $id)
    {
        $rules = array(
            'ac4_debet' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac4_kredit' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'n_bayar' => 'bail|required|numeric',
            'tgl_bayar' => 'bail|required|date'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $piutang = PiutangKaryawan::findOrFail($id);

            $piutang->bayar += $request->n_bayar;

            if($piutang->bayar > $piutang->nominal){
                return redirect()->back()->with('error', 'Nominal Bayar Piutang Karyawan Terlalu besar');
            }

            $detail = new DetailPiutang();
            $detail->ac4_debit = $request->ac4_debet;
            $detail->ac4_kredit = $request->ac4_kredit;
            $detail->tgl_bayar = $request->tgl_bayar;
            $detail->n_bayar    = $request->n_bayar;
            $detail->id_perush = Session("perusahaan")["id_perush"];
            $detail->id_karyawan = $piutang->id_karyawan;
            $detail->id_user = Auth::user()->id_user;
            $detail->id_piutang = $id;
            $detail->save();

            $piutang->angsuran_ke = $piutang->angsuran_ke+1;
            $piutang->sisa = $piutang->nominal-$piutang->bayar;

            if($piutang->bayar == $piutang->nominal){
                $piutang->is_lunas = true;
                $piutang->tgl_selesai = date("Y-m-d");
            }

            $piutang->save();

            DB::commit();
            } catch (Exception $e) {
            return redirect()->back()->with('error', 'Bayar Piutang Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Bayar Piutang Karyawan Disimpan');
    }

    public function edit_bayar(Request $request, $id)
    {
        $rules = array(
            'id_detail' => 'bail|required',
            'ac4_debet' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac4_kredit' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'n_bayar' => 'bail|required|numeric',
            'tgl_bayar' => 'bail|required|date'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $piutang = PiutangKaryawan::findOrFail($id);

            $piutang->bayar += $request->n_bayar;

            if($piutang->bayar > $piutang->nominal){
                return redirect()->back()->with('error', 'Nominal Bayar Piutang Karyawan Terlalu besar');
            }

            $detail = DetailPiutang::findOrFail($request->id_detail);
            $detail->ac4_debit = $request->ac4_debet;
            $detail->ac4_kredit = $request->ac4_kredit;
            $detail->tgl_bayar = $request->tgl_bayar;
            $detail->n_bayar    = $request->n_bayar;
            $detail->id_perush = Session("perusahaan")["id_perush"];
            $detail->id_karyawan = $piutang->id_karyawan;
            $detail->id_user = Auth::user()->id_user;
            $detail->id_piutang = $id;
            $detail->save();

            DB::commit();
            } catch (Exception $e) {
            return redirect()->back()->with('error', 'Bayar Piutang Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect()->back()->with('success', 'Bayar Piutang Karyawan Disimpan');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan");
        $data["data"] = PiutangKaryawan::findOrFail($id);

        if(!get_admin()){
            $karyawan->where("id_perush", Session("perusahaan")["id_perush"]);
        }

        $data["karyawan"] = $karyawan->get();
        return view('kepegawaian::piutangkaryawan.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'id_karyawan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
            'keperluan' => 'bail|required|min:4|max:200',
            'frekuensi' => 'bail|required|max:2'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $karyawan = Karyawan::findOrFail($request->id_karyawan);

            $piutang = PiutangKaryawan::findOrFail($id);

            if($piutang->is_approve==true){
                return redirect()->back()->with('error', 'Data Piutang Karyawan Sudah Di Approve');
            }

            $piutang->id_karyawan = $request->id_karyawan;
            $piutang->nominal = $request->nominal;
            $piutang->frekuensi = $request->frekuensi;
            $piutang->keperluan = $request->keperluan;
            $piutang->id_perush = $karyawan->id_perush;
            $piutang->n_angsuran = $request->nominal / $request->frekuensi;
            $piutang->id_user = Auth::user()->id_user;
            $piutang->save();

            DB::commit();
            } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Piutang Karyawan Gagal Disimpan' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Piutang Karyawan Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $piutang = PiutangKaryawan::findOrFail($id);

            if($piutang->is_approve==true){
                return redirect()->back()->with('error', 'Data Piutang Karyawan Sudah Di Approve');
            }

            $piutang->delete();

            DB::commit();
            } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Piutang Karyawan Gagal dihapus' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Piutang Karyawan dihapus');
    }

    public function approve(Request $request, $id)
    {
        $rules = array(
            'ac_kredit' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac_debit' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $piutang = PiutangKaryawan::findOrFail($id);
            $karyawan = Karyawan::findOrFail($piutang->id_karyawan);
            $piutang->ac4_kredit = $request->ac_kredit;
            $piutang->ac4_debit = $request->ac_debit;
            // update piutang
            $piutang->id_user = Auth::user()->id_user;
            $piutang->approve = true;
            $piutang->tgl_piutang = date("Y-m-d");
            $piutang->keterangan = "Piutang Karyawan ".$karyawan->nm_karyawan." sejumlah ".$piutang->nominal." di approve ";
            $piutang->save();

            DB::commit();
            } catch (Exception $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'Data Piutang Karyawan Gagal diapprove' .$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Piutang Karyawan diapprove');
    }
}
