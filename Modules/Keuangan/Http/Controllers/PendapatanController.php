<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Pendapatan;
use Modules\Keuangan\Entities\MasterAC;
use App\Models\Karyawan;
use App\Models\User;
use Modules\Keuangan\Http\Requests\PendapatanRequest;
use Modules\Keuangan\Entities\PendapatanDetail;
use Modules\Keuangan\Http\Requests\PendapatanDetailRequest;
use DB;
use Auth;
use Validator;
use Modules\Keuangan\Entities\ACPerush;
use Exception;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\TandaTangan;

class PendapatanController extends Controller
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
        $f_id_pendapatan = $request->f_id_pendapatan!=null?$request->f_id_pendapatan:null;
        $dr_tgl = $request->dr_tgl!=null?$request->dr_tgl:date("Y-m-01");
        $sp_tgl =  $request->sp_tgl!=null?$request->sp_tgl:date("Y-m-t");
        
        $pendapatan = Pendapatan::with("perusahaan", "user", "debet")
        ->where("id_perush", $id_perush)
        ->where("tgl_masuk",">=", $dr_tgl)
        ->where("tgl_masuk","<=", $sp_tgl);

        if($f_id_ac!=null){$pendapatan->where("id_ac", $f_id_ac);}
        if($f_id_pendapatan!=null){$pendapatan->where("id_pendapatan", $f_id_pendapatan);}

        $data["data"] = $pendapatan->paginate($page);
        $data["filter"] = [
            'page' => $page,
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'f_id_ac' => $f_id_ac,
            'f_id_pendapatan' => $f_id_pendapatan
        ];

        $data["pendapatan"] = Pendapatan::select("id_pendapatan", "kode_pendapatan")->where("id_perush", $id_perush)->get();
        $data["akun"] = ACPerush::getACDebit();

        return view('keuangan::pendapatan.index', $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["akun"] = ACPerush::getACDebit();
        $data["data"] = [];
        return view('keuangan::pendapatan.index', $data);
    }

    public function getac($id)
    {
        $data = [];
        if($id=="BM"){
            $data = MasterAC::getChild("100-101");
        }else{
            $data = MasterAC::getChild("100-101");
        }
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(PendapatanRequest $request)
    {

        $id = null;
        try {
            DB::beginTransaction();

            $pend = new Pendapatan();

            $pend->id_perush = Session("perusahaan")["id_perush"];
            $pend->id_ac = $request->id_ac;
            $pend->terima_dr = $request->terima_dr;
            $pend->info = $request->info;
            $pend->tgl_masuk = $request->tgl_masuk;
            $pend->id_user = Auth::user()->id_user;
            
            $jenis = "KM";
            $cek = ACPerush::where("id_ac", $request->id_ac)->get()->first();
            if($cek->is_bank==true){
                $jenis = "BM";
            }
            
            $pend->kode_pendapatan = "JP".Session("perusahaan")["id_perush"].date("ym").substr(crc32(uniqid()),-4);
            $pend->save();
            $id = $pend->id_pendapatan;
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
            ->with('error', 'Data Pendapatan Gagal Disimpan '.$e->getMessage());
        }
        
        return redirect(url(route_redirect()."/".$id."/show"))->with('success', 'Data Pendapatan  Disimpan');
    }
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $data["data"] = Pendapatan::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac','m_ac_perush.nama','parent.nama as parent_3')
                        ->join('m_ac AS parent','parent.id_ac','=','m_ac_perush.parent')
                        ->where('m_ac_perush.id_perush','=',Session("perusahaan")["id_perush"])
                        ->orderBy('m_ac_perush.id_ac','ASC')
                        ->get();
        $data["detail"] = PendapatanDetail::with("akun")->where("id_pendapatan", $id)->orderBy("tgl_posting", "asc")->orderBy("id_detail", "asc")->get();
        
        return view('keuangan::pendapatan.index', $data);
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */

    public function edit($id)
    {
        $data["data"] = Pendapatan::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["akun"] = ACPerush::getACDebit();

        return view('keuangan::pendapatan.index', $data);
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

            $pend = Pendapatan::findOrfail($id);

            $pend->id_perush = Session("perusahaan")["id_perush"];
            $pend->id_ac = $request->id_ac;
            $pend->terima_dr = $request->terima_dr;
            $pend->info = $request->info;
            $pend->jenis = $request->jenis;

            if($request->jenis=="BM"){
                $pend->id_bank = $request->id_bank_perush;
            }

            $pend->tgl_masuk = $request->tgl_masuk;
            $pend->id_user = Auth::user()->id_user;
            $pend->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->with('error', 'Data Pendapatan Gagal Disimpan '.$e->getMessage());
        }

        return redirect(url(route_redirect()))->with('success', 'Data Pendapatan  Disimpan');
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

            $pend = Pendapatan::findOrfail($id);
            $pend->delete();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Gagal Dihapus, Masih terhubung dengan detail ');
        }

        return redirect()->back()->with('success', 'Data Pendapatan Dihapus');
    }
    // for detail
    public function savedetail(Request $request)
    {
        //  validator
        $rules = array(
            'id_pendapatan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_pendapatan,id_pendapatan',
            'id_ac'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'harga'  => 'bail|required|numeric',
            'harga'  => 'bail|required|numeric',
            //'tgl_posting'  => 'bail|required|date',
            // 'info'  => 'bail|required|min:4|max:64|regex:/^[a-zA-Z0-9\s]+$/',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }
        
        // $cek = PendapatanDetail::where("id_pendapatan", $request->id_pendapatan)->where("id_ac", $request->id_ac)->get()->first();

        // if($cek!=null){
        //     return redirect()->back()->with('error', 'Data Pendapatan Detail Sudah Ada ');
        // }

        try {
            DB::beginTransaction();

            $pend = new PendapatanDetail();
            $pend->id_pendapatan = $request->id_pendapatan;
            $pend->id_ac = $request->id_ac;
            $pend->harga = $request->harga;
            $pend->jumlah = $request->jumlah;
            //$pend->tgl_posting = $request->tgl_posting;
            $pend->total = $request->jumlah*$request->harga;
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;
            $pend->save();

            // update pendapatan total
            $masuk = PendapatanDetail::where("id_pendapatan", $request->id_pendapatan)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pendapatan::where("id_pendapatan", $request->id_pendapatan)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pendapatan Detail  Ditambahkan');
    }

    public function updatedetail(Request $request, $id)
    {
        //  validator
        $rules = array(
            'id_pendapatan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_pendapatan,id_pendapatan',
            'id_ac'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'harga'  => 'bail|required|numeric',
            'harga'  => 'bail|required|numeric',
            //'tgl_posting'  => 'bail|required|date',
            // 'info'  => 'bail|required|min:4|max:64|regex:/^[a-zA-Z0-9\s]+$/',
            'info'  => 'bail|required|min:4|max:64',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator);

        }

        try {
            DB::beginTransaction();

            $pend = PendapatanDetail::findOrFail($id);
            $pend->id_pendapatan = $request->id_pendapatan;
            $pend->id_ac = $request->id_ac;
            $pend->harga = $request->harga;
            $pend->jumlah = $request->jumlah;
            //$pend->tgl_posting = $request->tgl_posting;
            $pend->total = $request->jumlah*$request->harga;
            $pend->id_user = Auth::user()->id_user;
            $pend->info = $request->info;
            $pend->save();

            // update pendapatan total
            $masuk = PendapatanDetail::where("id_pendapatan", $request->id_pendapatan)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pendapatan::where("id_pendapatan", $request->id_pendapatan)->update($a_data);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Detail Gagal Ditambahkan '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pendapatan Detail  Ditambahkan');
    }

    public function deletedetail($id)
    {
        $pend = PendapatanDetail::findOrFail($id);
        $id_pendapatan = $pend->id_pendapatan;

        try {
            DB::beginTransaction();

            $pend->delete();
            // update pendapatan total
            $masuk = PendapatanDetail::where("id_pendapatan", $id_pendapatan)->sum("total");
            $a_data = [];
            $a_data["c_total"] = $masuk;
            Pendapatan::where("id_pendapatan", $id_pendapatan)->update($a_data);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Detail Gagal Dihapus '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Pendapatan Detail  Dihapus');
    }

    public function cetak($id)
    {
        $data["data"] = Pendapatan::with("perusahaan", "user", "debet")->findOrFail($id);
        $data["user"] = User::with("karyawan")->findOrFail(Auth::user()->id_user);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["akun"] = ACPerush::select("id_ac", "nama")->where('id_perush',Session("perusahaan")["id_perush"])->get();
        $data["detail"] = PendapatanDetail::with("akun")->where("id_pendapatan", $id)->get();
        
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
        return view('keuangan::pendapatan.cetak', $data);
    }

    public function cetakall(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $pendapatan = Pendapatan::with("perusahaan", "user", "debet")
        ->where("id_perush", $id_perush)
        ->where("tgl_masuk",">=", $dr_tgl)
        ->where("tgl_masuk","<=", $sp_tgl);
        
        $data["data"] = $pendapatan->get();
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
