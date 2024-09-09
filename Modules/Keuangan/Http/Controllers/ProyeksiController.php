<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\Proyeksi;
use Modules\Keuangan\Entities\ProyeksiTahun;
use Modules\Keuangan\Entities\Neraca;
use DB;
use Auth;

class ProyeksiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = $request->f_bulan!=null?$request->f_bulan:date('m');
        $tahun = $request->f_tahun!=null?$request->f_tahun:date('Y');

        $proyeksi = Proyeksi::whereMonth('tgl', $bulan)
                            ->whereYear('tgl', $tahun)
                            ->where('id_perush', $id_perush)
                            ->get();
        $nilai=[];
        foreach ($proyeksi as $key => $value) {
            $nilai[$value->ac4]     =   $value;
        }

        $data["data"]           =  ACPerush::where("id_perush",$id_perush)->where('id_ac', '>=', '4000')->orderBy("id_ac")->get();
        $data["nilai"]          = $nilai;
        $data["filter"]         = array("f_bulan"=>$bulan, "f_tahun" =>$tahun);

        return view('keuangan::proyeksi.indeksproyeksi', $data);
    }

    public function SettingByTahun(Request $request)
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $tahun      = isset($request->tahun) ? $request->tahun : date('Y');
        $proyeksi   = ProyeksiTahun::where('id_perush', Session("perusahaan")["id_perush"])
                                    ->where('tahun', $tahun)
                                    ->get();
        $level2     = MasterAC::where("level",2)->where('id_ac', '>=', 40)->get(); 
        $level3     = MasterAC::where("level",3)->where('id_ac', '>=', 401)->get(); 
        $ac_perush  = ACPerush::where("id_perush",$id_perush)->where('id_ac', '>=', '4000')->orderBy("id_ac")->get();
        $nilai      = [];
        $data3      = [];
        $data_ac    = [];
        foreach ($proyeksi as $key => $value) {
            $nilai[$value->ac4]     =   $value;
        }
        foreach ($level3 as $key => $value) {
            $data3[$value->id_parent][] = $value;
        }
        foreach ($ac_perush as $key => $value) {
            $data_ac[$value->parent][] = $value;
        }
        $data["data"]           = $data_ac;
        $data["data2"]          = $level2;
        $data["data3"]          = $data3;
        $data["nilai"]          = $this->get_data($tahun);
        $data["proyeksi"]       = $nilai;
        $data["filter"] = [
            'tahun' => $tahun
        ];

        return view('keuangan::proyeksi_tahun.index', $data);
    }

    public function saveSettingByTahun(Request $request)
    {
        $tahun = isset($request->tahun) ? $request->tahun : date('Y');
        try {
            ProyeksiTahun::where('tahun', $tahun)
                        ->where("id_perush", Session("perusahaan")["id_perush"])
                        ->delete();

            $proyeksi   = $request->n_proyeksi;
            $prosentase = $request->prosentase_proyeksi;
            $data = [];
            foreach ($proyeksi as $key => $value) {
                $data[] = [
                    'id_perush' => Session("perusahaan")["id_perush"],
                    'ac4' => $key,
                    'proyeksi' => !empty($value) ? $this->extract($value) : 0,
                    'prosentase' => isset($prosentase[$key]) ? $prosentase[$key] : 0,
                    'tahun' => $tahun,
                    'id_user' => Auth::user()->id_user,
                ];
            }
            DB::beginTransaction();
            ProyeksiTahun::insert($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Proyeksi Gagal Disimpan '.$e->getMessage());
        }
        return redirect(url('proyeksi-by-tahun'))->with('success', 'Data Berhasil Disimpan');
    }

    public function get_data($tahun)
    {
        $tahun      = $tahun-1;
        $id_perush  = Session("perusahaan")["id_perush"];
        $dr_tgl     = date($tahun . '-01-01');
        $sp_tgl     = date($tahun . '-12-31');
        $ac         = ACPerush::where("id_perush",$id_perush)
                                ->where("id_ac", ">", 4000)
                                ->orderBy("id_ac")->get();
        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);

        $temp               = [];
        
        $total_pendapatan = 0;
        foreach ($ac as $key => $ac_perush) {
            $total = 0;
            foreach ($newdata as $key => $value) {
                if($value->id_debet == $ac_perush->id_ac){
                    if($value->pos_d == "D"){
                        $total+=$value->total_debet;
                    }else{
                        $total-=$value->total_kredit;
                    }
                }elseif($value->id_kredit == $ac_perush->id_ac){
                    if($value->pos_k == "K"){
                        $total+=$value->total_kredit;
                    }else{
                        $total-=$value->total_debet;
                    }
                }

            }
            $temp[$ac_perush->id_ac] = $total;

            if ($ac_perush->id_ac >= 4010 && $ac_perush->id_ac < 5000) {
                if ($ac_perush->def_pos == 'K') {
                    $total_pendapatan += $total;
                } else {
                    $total_pendapatan -= $total;
                }
                
            }

            // if (in_array($ac_perush->id_ac, [4110, 4111])) {
            //     $total_pendapatan -= $total;
            // }
        }    
        return $data = [
            'nilai' => $temp,
            'total_pendapatan' => $total_pendapatan
        ];
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["data"] = Proyeksi::all();

        return view('keuangan::proyeksi.createproyeksi',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $proyeksi                     = new Proyeksi();
            $proyeksi->id_perush          = Session("perusahaan")["id_perush"];
            $proyeksi->ac4                = $request->id_ac;
            $proyeksi->id_user            = Auth::user()->id_user;
            $proyeksi->tgl                = date($request->tahun.'-'.$request->bulan.'-01');
            $proyeksi->proyeksi           = $request->nominal;
            $proyeksi->save();
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Account Gagal Disimpan '.$e->getMessage());
        }
        $urls = "proyeksi?f_bulan=".$request->bulan."&f_tahun=".$request->tahun;

        return redirect(url($urls))->with('success', 'Data Account  Disimpan');
    }

    public function editsaldo(Request $request)
    {
        $id                          = $request->id_pro;
        $proyeksi                    = $request->nominal_e;

        try {

            DB::beginTransaction();
            $data = [
                "proyeksi"   =>  $proyeksi,
            ];

            Proyeksi::where("id",$id)->update(
                $data
            );

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Proyeksi Gagal Disimpan');
        }

        $urls = "proyeksi?f_bulan=".$request->bulan_e."&f_tahun=".$request->tahun_e;

        return redirect(url($urls))->with('success', 'Data Proyeks Disimpan');
    }

    public function generate()
    {
        $proyeksi = Proyeksi::whereYear('tgl', date('Y'))->where('id_perush', Session("perusahaan")["id_perush"])->get();
        if (count($proyeksi) > 0) {
            return redirect()->back()->with('error', 'Data Proyeksi Sudah di Setting, Mohon untuk Hapus jika ingin melanjutkan proses');
        }

        $proyeksi_tahun = ProyeksiTahun::where('tahun', date('Y'))->where('id_perush', Session("perusahaan")["id_perush"])->get();
        if (count($proyeksi_tahun) == 0) {
            return redirect()->back()->with('error', 'Data Proyeksi Tahunan Belum di Setting');
        } else {
            try {
                DB::beginTransaction();
                $data = [];
                for ($i=1; $i <= 12; $i++) { 
                    foreach ($proyeksi_tahun as $key => $value) {
                        $bulan = $i;
                        if ($i < 10) {
                            $bulan = '0' . $i;
                        }
                        $data[] = [
                            'id_perush' => Session("perusahaan")["id_perush"],
                            'ac4' => $value->ac4,
                            'proyeksi' => round($value->proyeksi/12),
                            'tgl' => date('Y-' . $bulan . '-01'),
                            'id_user' => Auth::user()->id_user,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }

                Proyeksi::insert($data);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Proyeksi Gagal Disimpan');
            }            
        }

        return redirect(url('proyeksi'))->with('success', 'Data Berhasil Disimpan'); 
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
    public function update(Request $request, $id)
    {
        //
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

    public function extract($c)
    {
        return preg_replace("/[^0-9]/", "",$c);
    }
}
