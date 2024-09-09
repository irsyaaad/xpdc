<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Modules\Keuangan\Entities\Budgeting;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\Neraca;
use DB;
use Auth;
use App\Models\Perusahaan;

class BudgetingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $bulan      = date('m');
        $tahun      = date('Y'); 

        if (isset($request->bulan)) {
            $bulan             = $request->bulan;
        }

        if (isset($request->tahun)) {
            $tahun             = $request->tahun;
        }
        $data["akun"] = ACPerush::select('m_ac_perush.id_ac','m_ac_perush.nama','parent.nama as parent_3')
                        ->join('m_ac AS parent','parent.id_ac','=','m_ac_perush.parent')
                        ->where('m_ac_perush.id_perush','=',Session("perusahaan")["id_perush"])
                        ->orderBy('m_ac_perush.id_ac','ASC')
                        ->get();
        $data["filter"] = [
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
        return view('keuangan::budgeting.index', $data);
    }

    public function data(Request $request)
    {
        $tgl = date($request->tahun.'-'.$request->bulan.'-01');
        $id_perush = Session("perusahaan")["id_perush"];
        $data = Budgeting::select('m_budgeting.*','m_ac_perush.nama')
                ->join('m_ac_perush','m_ac_perush.id_ac','=','m_budgeting.ac4')
                ->where([
                    ['m_budgeting.id_perush', $id_perush],
                    ['m_ac_perush.id_perush', $id_perush]
                    ])
                ->where('tgl', $tgl)
                ->get();
        $key = 0;
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('nominal', function ($user) {
            return number_format($user->nominal,0,',','.');
        })
        ->addColumn('bulan', function ($user) {
            return date('m', strtotime($user->tgl));
        })
        ->addColumn('tahun', function ($user) {
            return date('Y', strtotime($user->tgl));
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('keuangan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data = [];
        $data['status'] = false;

        $cek = Budgeting::where('ac4', $request->id_ac)
                        ->where('id_perush', $id_perush)
                        ->where('tgl', date($request->tahun.'-'.$request->bulan.'-01'))
                        ->get();

        if (count($cek) > 0) {
            $data['status'] = false;
            $data['message'] = 'Account Sudah Ada, Silahkan pilih akun yang lain';
            return response()->json($data);
        }

        try {
            DB::beginTransaction();
            $budgeting                     = new Budgeting();
            $budgeting->id_perush          = $id_perush;
            $budgeting->ac4                = $request->id_ac;
            $budgeting->id_user            = Auth::user()->id_user;
            $budgeting->tgl                = date($request->tahun.'-'.$request->bulan.'-01');
            $budgeting->nominal            = $request->nominal;
            $budgeting->keterangan         = $request->keterangan;
            $budgeting->save();

            DB::commit();
            $data['status'] = true;
        } catch (Exception $e) {
            DB::rollback();
            // return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
            $data['status'] = false;
            $data['message'] = 'Data Account Gagal Disimpan '.$e->getMessage();
        }

        return response()->json($data);
    }

    public function LaporanBudgeting(Request $request)
    {
        $data = [];
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');

        if (isset($request->bulan)) {
            $bulan             = $request->bulan;
        }
        if (isset($request->tahun)) {
            $tahun             = $request->tahun;
        }

        $data = $this->process_data($id_perush, $bulan, $tahun);

        return view('keuangan::budgeting.laporan', $data);
    }

    public function cetakLaporanBudgeting(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date('m');
        $tahun = date('Y');

        if (isset($request->bulan)) {
            $bulan             = $request->bulan;
        }
        if (isset($request->tahun)) {
            $tahun             = $request->tahun;
        }

        $data               = $this->process_data($id_perush, $bulan, $tahun);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        // return view('keuangan::budgeting.laporan', $data);   

        $pdf = \PDF::loadview("keuangan::budgeting.cetak", $data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function process_data($id_perush, $bulan, $tahun)
    {
        $dr_tgl = date("{$tahun}-{$bulan}-01");
        $sp_tgl = date('Y-m-t', strtotime(date("{$tahun}-{$bulan}-t")));

        $dr_tgl_bulan_kemarin   = date('Y-m-d', strtotime("{$dr_tgl} -1 month"));
        $sp_tgl_bulan_kemarin   = date('Y-m-t', strtotime("{$sp_tgl} -1 month"));
        $dr_tgl_bulan_depan     = date('Y-m-d', strtotime("{$dr_tgl} +1 month"));
        $sp_tgl_bulan_depan     = date('Y-m-t', strtotime("{$sp_tgl} +1 month"));

        $budgeting                  = Budgeting::select('ac4', 'nominal')
                                        ->where('id_perush', $id_perush)
                                        ->where('tgl', '>=', $dr_tgl)
                                        ->where('tgl', '<=', $sp_tgl)->get();
        $budgeting_bulan_kemarin    = Budgeting::select('ac4', 'nominal')
                                        ->where('id_perush', $id_perush)
                                        ->where('tgl', '>=', $dr_tgl_bulan_kemarin)
                                        ->where('tgl', '<=', $sp_tgl_bulan_kemarin)->get();
        $budgeting_bulan_depan      = Budgeting::select('ac4', 'nominal')
                                        ->where('id_perush', $id_perush)
                                        ->where('tgl', '>=', $dr_tgl_bulan_depan)
                                        ->where('tgl', '<=', $sp_tgl_bulan_depan)->get();

        $acBudgeting    = array_column($budgeting->toArray(), 'ac4');
        $ac             = ACPerush::whereIn("id_ac", $acBudgeting)->where("id_perush",$id_perush)->get();  

        $bulanIni               = $this->get_data($id_perush, $dr_tgl, $sp_tgl, $ac);        
        $bulanKemarin           = $this->get_data($id_perush, $dr_tgl_bulan_kemarin, $sp_tgl_bulan_kemarin, $ac); 
        $bulanDepan             = $this->get_data($id_perush, $dr_tgl_bulan_depan, $sp_tgl_bulan_depan, $ac);

        $data["ac"]                         = $ac;
        $data["budgeting_bulan_ini"]        = array_column($budgeting->toArray(), 'nominal', 'ac4');
        $data["budgeting_bulan_kemarin"]    = array_column($budgeting_bulan_kemarin->toArray(), 'nominal', 'ac4');
        $data["budgeting_bulan_depan"]      = array_column($budgeting_bulan_depan->toArray(), 'nominal', 'ac4');
        $data["bulanIni"]                   = $bulanIni;
        $data["bulanKemarin"]               = $bulanKemarin;
        $data["bulanDepan"]                 = $bulanDepan;
        $data["filter"] = [
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'dr_tgl'    => $dr_tgl,
            'sp_tgl'    => $sp_tgl,
            'bulan_ini' => date('Y-m', strtotime($dr_tgl)),
            'bulan_kemarin' => date('Y-m', strtotime($dr_tgl_bulan_kemarin)),
            'bulan_depan' => date('Y-m', strtotime($dr_tgl_bulan_depan)),
        ];

        return $data;
    }

    public function get_data($id_perush, $dr_tgl, $sp_tgl, $ac)
    {
        $newdata        = Neraca::Master($id_perush, $dr_tgl, $sp_tgl);
        $debit      = [];
        $kredit     = [];

        foreach ($ac as $key => $value) {
            $total_deb = 0;
            $total_kre = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_debet == $value->id_ac) {
                    $total_deb+=$value2->total_debet;
                }elseif ($value2->id_kredit == $value->id_ac) {
                    $total_kre+=$value2->total_kredit;
                }
            }
            $debit[$value->id_ac]   = $total_deb;
            $kredit[$value->id_ac]  = $total_kre;
        }

        $data["ac"]         = $ac;
        $data["debit"]      = $debit;
        $data["kredit"]     = $kredit;
        
        return $data;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('keuangan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('keuangan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateBudgeting(Request $request)
    {
        // return response()->json($request->all());

        $data = $request->all();
        
        $status = false;
        try {

            DB::beginTransaction();
            $data = [
                "ac4"       =>  $request->id_ac,
                "tgl"       =>  date($request->tahun.'-'.$request->bulan.'-01'),
                "nominal"   =>  $request->nominal,
                "keterangan" => $request->keterangan
            ];

            Budgeting::where("id",$request->id)->update(
                $data
            );
            DB::commit();
            $status = true;

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Budgeting Gagal Disimpan');
        }

        return response()->json($status);
    }

    public function deleteBudgeting(Request $request)
    {
        $data = $request->all();

        try {

            DB::beginTransaction();
            $budgeting = Budgeting::findOrFail($request->id);
            $budgeting->delete();
            DB::commit();

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Budgeting Gagal Dihapus');
        }

        return redirect(route_redirect())->with('success', 'Budget Berhasil Di Hapus');
    }

    public function copyBudgeting(Request $request)
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $tgl_from       = date($request->dari_tahun . '-' . $request->dari_bulan . '-' . '01');
        $budgeting      = Budgeting::where('id_perush', $id_perush)->where('tgl', $tgl_from)->get();
        $budgetingNext  = array_column(Budgeting::join('m_ac_perush','m_ac_perush.id_ac','=','m_budgeting.ac4')
                                        ->where('m_budgeting.id_perush', $id_perush)
                                        ->where('tgl', date($request->tahun . '-' . $request->bulan . '-' . '01'))
                                        ->get()->toArray(), 'nama', 'ac4');
        
        try {
            DB::beginTransaction();

            foreach ($budgeting as $key => $value) {
                if (isset($budgetingNext[$value->ac4])) {
                    return redirect()->back()->with('error', "Setting untuk {$budgetingNext[$value->ac4]} sudah dibuat. Silahkan hapus terlabih dahulu");
                } else {
                    $budgeting                     = new Budgeting();
                    $budgeting->id_perush          = Session("perusahaan")["id_perush"];
                    $budgeting->ac4                = $value->ac4;
                    $budgeting->id_user            = Auth::user()->id_user;
                    $budgeting->tgl                = date($request->tahun . '-' . $request->bulan . '-' . '01');
                    $budgeting->nominal            = $value->nominal;
                    $budgeting->keterangan         = $value->keterangan;
                    $budgeting->save();            
                }    
            }
            DB::commit();
            $status = true;
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Budget Berhasil Di Copy');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
