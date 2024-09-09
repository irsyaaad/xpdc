<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Pengeluaran;
use Modules\Keuangan\Entities\GenIdPengeluaran;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\PengeluaranDetail;
use Modules\Keuangan\Http\Requests\PendapatanRequest;
use DB;
use Auth;
use Validator;
use Exception;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\TandaTangan;
use App\Models\User;

class PengeluaranController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = $request->shareselect!=null?$request->shareselect:50;
        $id_perush = Session("perusahaan")["id_perush"];
        $f_id_ac = $request->f_id_ac!=null?$request->f_id_ac:null;
        $f_id_pengeluaran = $request->f_id_pengeluaran!=null?$request->f_id_pengeluaran:null;
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date("Y-m-01");
        $sp_tgl =  $request->sp_tgl!=null?$request->sp_tgl:date("Y-m-t");

        $pengeluaran = Pengeluaran::with("perusahaan", "user", "debet")
        ->where("id_perush", $id_perush)
        ->where("tgl_keluar",">=", $dr_tgl)
        ->where("tgl_keluar","<=", $sp_tgl);

        if($f_id_ac!=null){$pengeluaran->where("id_ac", $f_id_ac);}
        if($f_id_pengeluaran!=null){$pengeluaran->where("id_pengeluaran", $f_id_pengeluaran);}
        
        $data["data"] = $pengeluaran->paginate($page);
        $data["filter"] = [
            'page' => $page,
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'f_id_ac' => $f_id_ac,
            'f_id_pengeluaran' => $f_id_pengeluaran
        ];

        $data["pengeluaran"] = Pengeluaran::select("id_pengeluaran", "kode_pengeluaran")->where("id_perush", $id_perush)->get();
        $data["akun"] = ACPerush::getACDebit();

        return view('keuangan::pengeluaran.indekspengeluaran', $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["akun"] = ACPerush::getACDebit();
        $data["data"] = [];
        
        return view('keuangan::pengeluaran.createpengeluaran', $data);
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(PendapatanRequest $request)
    {
        $id =null;
        try {
            DB::beginTransaction();

            $pengeluaran = new Pengeluaran();
            $pengeluaran->id_perush = Session("perusahaan")["id_perush"];
            $pengeluaran->id_ac = $request->id_ac;
            $pengeluaran->terima_dr = $request->terima_dr;
            $pengeluaran->info = $request->info;

            $jenis = "KK";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if($cek->is_bank==true){
                $jenis = "BK";
            }
            
            $pengeluaran->tgl_keluar = $request->tgl_masuk;
            $pengeluaran->id_user = Auth::user()->id_user;
            $pengeluaran->kode_pengeluaran = "JK".Session("perusahaan")["id_perush"].date("ym").substr(crc32(uniqid()),-4);
            $pengeluaran->save();
            $id = $pengeluaran->id_pengeluaran;
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Pengeluaran Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()."/".$id."/show"))->with('success', 'Data Pengeluaran  Disimpan');
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac','m_ac_perush.nama','parent.nama as parent_3')
                        ->join('m_ac AS parent','parent.id_ac','=','m_ac_perush.parent')
                        ->where('m_ac_perush.id_perush','=',Session("perusahaan")["id_perush"])
                        ->orderBy('m_ac_perush.id_ac','ASC')
                        ->get();
        
        return view('keuangan::pengeluaran.detailpengeluaran', $data);
    }

    public function cetak1($id)
    {
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["akun"] = ACPerush::select("id_ac", "nama")->where('id_perush',Session("perusahaan")["id_perush"])->get();
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->get();

        $ttd = TandaTangan::where("id_ref",$id)->get();
        if (isset($ttd) and count($ttd)>0) {
            foreach ($ttd as $key => $value) {
                if ($value->level == 1) {
                    $data["admin"] = $value->id;
                }
                if ($value->level == 2) {
                    $data["penyetor"] = $value->id;
                }
                if ($value->level == 3) {
                    $data["manager"] = $value->id;
                }
                if ($value->level == 4) {
                    $data["direktur"] = $value->id;
                }
            }
        }
        return view('keuangan::pengeluaran.new-cetak', $data);
    }

    public function cetak($id)
    {
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["akun"] = ACPerush::select("id_ac", "nama")->where('id_perush',Session("perusahaan")["id_perush"])->get();
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->get();
        
        $ttd = TandaTangan::where("id_ref",$id)->get();
        if (isset($ttd) and count($ttd)>0) {
            foreach ($ttd as $key => $value) {
                if ($value->level == 1) {
                    $data["admin"] = $value->id;
                }
                if ($value->level == 2) {
                    $data["penyetor"] = $value->id;
                }
                if ($value->level == 3) {
                    $data["manager"] = $value->id;
                }
                if ($value->level == 4) {
                    $data["direktur"] = $value->id;
                }
            }
        }
        return view('keuangan::pengeluaran.cetak', $data);
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

        return view('keuangan::pengeluaran.createpengeluaran', $data);
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(PendapatanRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $pengeluaran = Pengeluaran::findOrfail($id);

            $pengeluaran->id_perush = Session("perusahaan")["id_perush"];
            $pengeluaran->id_ac = $request->id_ac;
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

            $pend = Pengeluaran::findOrfail($id);
            $pend->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengeluaran Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Pengeluaran Dihapus');
    }

    public function genIdPengeluaran($jenis)
    {

        $id_perush = Session("perusahaan")["id_perush"];
        $date = date("ym");

        $cek = GenIdPengeluaran::where("jenis", $jenis)->where("id_perush", $id_perush)->where("date_origin", $date)->get()->first();

        $data = [];
        if($cek == null){
            $p = new GenIdPengeluaran();
            $p->id_perush = $id_perush;
            $p->jenis = $jenis;
            $p->date_origin = $date;
            $p->last_id = "1";

            $p->id_pengeluaran = strtoupper($id_perush.$date.$p->last_id);

            $data["id_pengeluaran"] = $p->id_pengeluaran;
            $data["kode_pengeluaran"] = strtoupper($p->jenis.$p->id_pengeluaran);
            $p->save();

        }else{
            $id = (Int)$cek->last_id+1;
            $cek->id_pengeluaran = strtoupper($id_perush.$cek->date_origin.$id);
            $cek->last_id = $id;
            $data["id_pengeluaran"] = $cek->id_pengeluaran;
            $data["kode_pengeluaran"] = strtoupper($cek->jenis.$cek->id_pengeluaran);
            $cek->save();
        }

        return $data;
    }

    public function detail($id)
    {
        $data["data"] = Pengeluaran::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["detail"] = PengeluaranDetail::with("akun")->where("id_pengeluaran", $id)->get();
        $data["ac"] = ACPerush::select("id_ac", "nama")->where("def_pos", "K")->get();

        return view('keuangan::pengeluaran.detailpengeluaran', $data);
    }

    public function savedetail(Request $request)
    {
        //  validator
        $rules = array(
            'id_pengeluaran'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_pengeluaran,id_pengeluaran',
            'id_ac'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'harga'  => 'bail|required|numeric',
            'harga'  => 'bail|required|numeric',
            'harga'  => 'bail|required|numeric',
           // 'tgl_posting'  => 'bail|required|date',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);

        }
        try {
            DB::beginTransaction();

            $pend = new PengeluaranDetail();
            $pend->id_pengeluaran = $request->id_pengeluaran;
            $pend->id_ac = $request->id_ac;
            $pend->harga = $request->harga;
            $pend->jumlah = $request->jumlah;
           // $pend->tgl_posting = $request->tgl_posting;
            $pend->total = $request->jumlah*$request->harga;
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;
            $pend->save();
            
            // update Pengeluaran total
            $masuk = PengeluaranDetail::where("id_pengeluaran", $request->id_pengeluaran)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pengeluaran::where("id_pengeluaran", $request->id_pengeluaran)->update($a_data);
            
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()
            ->withInput($request->all())
            ->with('error', 'Data Pengeluaran Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pengeluaran Detail  Ditambahkan');
    }

    public function editdetail($id)
    {
        $pend = PengeluaranDetail::findOrFail($id);
        return response()->json($pend);
    }

    public function updatedetail(Request $request, $id)
    {
        //  validator
        $rules = array(
            'id_pengeluaran'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_pengeluaran,id_pengeluaran',
            'id_ac'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'harga'  => 'bail|required|numeric',
            'harga'  => 'bail|required|numeric',
            //'tgl_posting'  => 'bail|required|date',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }

        try {
            DB::beginTransaction();

            $pend = PengeluaranDetail::findOrFail($id);
            $pend->id_pengeluaran = $request->id_pengeluaran;
            $pend->id_ac = $request->id_ac;
            $pend->harga = $request->harga;
            $pend->jumlah = $request->jumlah;
           // $pend->tgl_posting = $request->tgl_posting;
            $pend->total = $request->jumlah*$request->harga;
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;
            $pend->save();
            // update Pengeluaran total
            $masuk = PengeluaranDetail::where("id_pengeluaran", $request->id_pengeluaran)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pengeluaran::where("id_pengeluaran", $request->id_pengeluaran)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()
            ->withInput($request->all())
            ->with('error', 'Data Pengeluaran Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pengeluaran Detail  Ditambahkan');
    }

    public function deletedetail($id)
    {

        $pend = PengeluaranDetail::findOrFail($id);
        $id_pengeluaran = $pend->id_pengeluaran;

        try {
            DB::beginTransaction();

            $pend->delete();
            // update Pengeluaran total
            $masuk = PengeluaranDetail::where("id_pengeluaran", $id_pengeluaran)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pengeluaran::where("id_pengeluaran", $id_pengeluaran)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengeluaran Detail Gagal Dihapus '.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Pengeluaran Detail  Dihapus');
    }

    public function cetakall(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $pengeluaran = Pengeluaran::with("perusahaan", "user", "debet")
        ->where("id_perush", $id_perush)
        ->where("tgl_keluar",">=", $dr_tgl)
        ->where("tgl_keluar","<=", $sp_tgl);

        $data["data"] = $pengeluaran->get();
        $data["user"] = User::with("karyawan")->findOrFail(Auth::user()->id_user);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];
        // dd($data);
        return view('keuangan::pendapatan.cetakall', $data);
    }
}
