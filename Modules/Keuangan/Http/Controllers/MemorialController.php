<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Memorial;
use Modules\Keuangan\Entities\MemorialDetail;
use Modules\Keuangan\Entities\GenIdMemorial;
use Modules\Keuangan\Entities\ACPerush;
use DB;
use Auth;
use Validator;
use Exception;
use App\Models\Perusahaan;

class MemorialController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = $request->shareselect;
        $id_memo = $request->id_memo;
        $id_debet = $request->debet;
        $id_kredit = $request->kredit;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;

        $a_data = Memorial::with("perusahaan","debet","kredit")->where("id_perush", $id_perush);
        if($id_memo != null){
            $a_data = $a_data->where("id_memorial", $id_memo);
        }
        if($id_debet != null){
            $a_data = $a_data->where("id_ac_debet", $id_debet);
        }
        if($id_kredit != null){
            $a_data = $a_data->where("id_ac_kredit", $id_kredit);
        }
        if($dr_tgl != null){
            $a_data = $a_data->where("tgl",">=", $dr_tgl);
        }
        if($sp_tgl != null){
            $a_data = $a_data->where("tgl", "<=", $sp_tgl);
        }
        
        $memorial = Memorial::select("id_memorial", "kode_memorial")->where("id_memorial", $id_memo)->get()->first();
        $id_perush = Perusahaan::select("id_perush", "nm_perush")->where("id_perush", $id_perush)->get()->first();
        $debet = ACPerush::select("id_ac", "nama")->where("id_ac", $id_debet)->get()->first();
        $kredit = ACPerush::select("id_ac", "nama")->where("id_ac", $id_kredit)->get()->first();

        $filter = array("id_perush"=> $id_perush, "id_memo"=> $memorial, "debet"=>$debet, "kredit"=>$kredit, "dr_tgl"=>$dr_tgl, "sp_tgl"=>$sp_tgl);
        $data["data"] = $a_data->paginate($page);
        $data["filter"] = $filter;

        return view('keuangan::memorial.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('keuangan::memorial.creatememorial');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //dd($request->request);
        try {
            DB::beginTransaction();

            $memorial = new Memorial();

            $memorial->id_perush        = Session("perusahaan")["id_perush"];
            $memorial->info             = $request->info;
            $memorial->tgl              = $request->tgl_masuk;
            $memorial->id_user          = Auth::user()->id_user;
            $memorial->id_ac_debet      = $request->id_ac_debet;
            $memorial->id_ac_kredit     = $request->id_ac_kredit;
            $memorial->nominal          = $request->nominal;
            $memorial->no_referensi     = $request->no_referensi;
            $memorial->kode_memorial    = "JM".Session("perusahaan")["id_perush"].date("ym").substr(crc32(uniqid()),-4);
            $memorial->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Pengeluaran Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()))->with('success', 'Data Memorial  Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = Memorial::with("perusahaan", "user")->findOrFail($id);
        $data["detail"] = MemorialDetail::with("kredit", "debet")->where("id_memo", $id)->get();
        $data["nominal"] = MemorialDetail::where("id_memo", $id)->sum("n_debet");
        $data["ac"] = ACPerush::select("id_ac", "nama")->where("id_perush", $id_perush)->get();

        return view('keuangan::memorial.detailmemorial',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["akun"] = ACPerush::getACDebit();

        return view('keuangan::memorial.creatememorial',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        dd($request);
        try {
            DB::beginTransaction();

            $pengeluaran = Pengeluaran::findOrfail($id);

            $pengeluaran->id_perush = Session("perusahaan")["id_perush"];
            $pengeluaran->terima_dr = $request->terima_dr;
            $pengeluaran->info = $request->info;
            $pengeluaran->jenis = $request->jenis;
            $pengeluaran->tgl_keluar = $request->tgl_masuk;
            $pengeluaran->id_user = Auth::user()->id_user;

            $pengeluaran->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengeluaran Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()))->with('success', 'Data Pengeluaran  Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pend = Memorial::findOrfail($id);
            $pend->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Memorial Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Memorial Dihapus');
    }

    public function genIdMemorial()
    {

        $id_perush = Session("perusahaan")["id_perush"];
        $date = date("ym");

        $cek = GenIdMemorial::where("id_perush", $id_perush)->where("date_origin", $date)->get()->first();
        $data = [];

        $jenis = "JM";

        if($cek == null){
            $p = new GenIdMemorial();
            $p->id_perush = $id_perush;
            $p->date_origin = $date;
            $p->last_id = "1";

            $p->id_memorial = strtoupper($id_perush.$date.$p->last_id);
            $data["id_memorial"] = $p->id_memorial;
            $data["kode_memorial"] = strtoupper($p->id_memorial);
            $p->save();

        }else{
            $id = (Int)$cek->last_id+1;
            $cek->id_memorial = strtoupper($id_perush.$cek->date_origin.$id);
            $cek->last_id = $id;
            $data["id_memorial"] = $cek->id_memorial;
            $data["kode_memorial"] = strtoupper($cek->id_memorial);
            $cek->save();
        }

        return $data;
    }

    public function savedetail(Request $request)
    {
        //dd($request->request);
        //  validator
        $rules = array(
            'id_memorial'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_memorial,id_memorial',
            'id_ac_debet'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'id_ac_kredit'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'nominal'  => 'bail|required|numeric',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }

        $cek = MemorialDetail::where("id_memo", $request->id_memorial)->where("id_ac_debet", $request->id_ac_debet)
                ->where("id_ac_kredit", $request->id_ac_kredit)->get()->first();

        if($cek!=null){
            return redirect()->back()->with('error', 'Data Memorial Detail Sudah Ada ');
        }

        try {
            DB::beginTransaction();

            $pend = new MemorialDetail();
            $last = count(MemorialDetail::all())+1;

            $pend->id_detail = $request->id_memorial.$last;
            $pend->id_memo = $request->id_memorial;
            $pend->id_ac_debet = $request->id_ac_debet;
            $pend->id_ac_kredit = $request->id_ac_kredit;
            $pend->n_debet = $request->nominal;
            $pend->n_kredit = $request->nominal;
            $pend->id_perush = Session("perusahaan")["id_perush"];
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;

            $pend->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Memorial Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Memorial Detail  Ditambahkan');
    }

    public function editdetail($id)
    {
        $pend = MemorialDetail::findOrFail($id);
        return response()->json($pend);
    }

    public function updatedetail(Request $request, $id)
    {
        //  validator
        $rules = array(
            'id_memorial'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_memorial,id_memorial',
            'id_ac_debet'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'id_ac_kredit'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'nominal'  => 'bail|required|numeric',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }

        try {
            DB::beginTransaction();

            $pend = Memorial::findOrFail($id);
            $pend->id_detail = $request->id_memorial.$last;
            $pend->id_memo = $request->id_memorial;
            $pend->id_ac_debet = $request->id_ac_debet;
            $pend->id_ac_kredit = $request->id_ac_kredit;
            $pend->n_debet = $request->nominal;
            $pend->n_kredit = $request->nominal;
            $pend->id_perush = Session("perusahaan")["id_perush"];
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;

            $pend->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengeluaran Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pengeluaran Detail  Ditambahkan');
    }

    public function deletedetail($id)
    {

        $pend = MemorialDetail::findOrFail($id);
        $id_memo = $pend->id_memo;

        try {
            DB::beginTransaction();

            $pend->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Memorial Detail Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Memorial Detail  Dihapus');
    }
}
