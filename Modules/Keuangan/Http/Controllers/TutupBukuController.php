<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\TutupBuku;
use Modules\Keuangan\Entities\Neraca;
use Modules\Keuangan\Entities\ACPerush;
use Illuminate\Support\Arr;
use Modules\Keuangan\Entities\MasterAC;
use DB;
use Auth;

class TutupBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush          = Session('perusahaan')['id_perush'];
        $tgl                = date("Y-m-d");
        $getTanggal         = date('Y-m-d', strtotime('-1 year', strtotime($tgl)));
        
        $dr_tgl             = date('Y-01-01', strtotime($getTanggal));
        $sp_tgl             = date('Y-12-t', strtotime($getTanggal));
        $ac                 = ACPerush::where("id_perush",$id_perush)->where("id_ac", "<", 4000)->orderBy("id_ac")->get();
        $newdata            = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $tutup_buku         = TutupBuku::where("id_perush",Session("perusahaan")["id_perush"])->where("tahun",date("Y"))->get();

        $temp               = [];
        $ttp                = [];
        
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
        }        

        foreach ($tutup_buku as $key => $value) {
            if (isset($value->ac4_debit)) {
                $ttp[$value->ac4_debit] = $value;
            }else{
                $ttp[$value->ac4_kredit] = $value;
            }
        }

        //dd($ttp);
        $data["ac"]                 = $ac;
        $data["data"]               = $temp;
        $data["periode"]            = date('Y', strtotime($getTanggal));
        $data["lababerjalan"]       = $this->laba($id_perush,$dr_tgl,$sp_tgl);
        $data["tutup"]              = $ttp;
        $data["filter"]             = [];

        //dd($data);
        return view('keuangan::laporan.tutupbuku',$data);

    }

    public function filter(Request $request)
    {
        //dd($request->request);
        $tahun              = $request->tahun;
        $tahun_p            = $tahun+1;
        $id_perush          = Session('perusahaan')['id_perush'];
        $dr_tgl             = date($tahun.'-01-01');
        $sp_tgl             = date($tahun.'-12-t');
        $ac                 = ACPerush::where("id_perush",$id_perush)->orderBy("id_ac")->get();
        $newdata            = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $tutup_buku         = TutupBuku::where("id_perush",Session("perusahaan")["id_perush"])->where("tahun",date($tahun_p))->get();

        $temp               = [];
        $ttp                = [];
        
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
        }        

        foreach ($tutup_buku as $key => $value) {
            if (isset($value->ac4_debit)) {
                $ttp[$value->ac4_debit] = $value;
            }else{
                $ttp[$value->ac4_kredit] = $value;
            }
        }

        //dd($ttp);
        $data["ac"]                 = $ac;
        $data["data"]               = $temp;
        $data["periode"]            = $tahun;
        $data["lababerjalan"]       = $this->laba($id_perush,$dr_tgl,$sp_tgl);
        $data["tutup"]              = $ttp;
        $data["filter"]             = [];

        // dd($data);
        return view('keuangan::laporan.tutupbuku',$data);
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

        $nilai = $this->neraca();
        $laba  = $this->laba();

        //dd($nilai);

        TutupBuku::insert($nilai);
        TutupBuku::insert($laba);
        return redirect(url(route_redirect()))->with('success', 'Tutup Buku  Disimpan');
    }

    public function savesaldo(Request $request)
    {
        //dd($request->request);
        $temp                        = $request->tgl+1;
        $tanggal                     = $temp."-01-01";
        $id_perush                   = Session("perusahaan")["id_perush"];


        $cek        = TutupBuku::where("id_perush",$id_perush)
                                ->where("ac4_debit",$request->id_ac)
                                ->where("tgl",$tanggal)
                                ->orWhere("id_perush",$id_perush)
                                ->where("ac4_kredit",$request->id_ac)
                                ->where("tgl",$tanggal)->get()->first();
        
        if ($cek != null) {
            return redirect()->back()->with('error', 'Saldo Awal Gagal Disimpan (Saldo Awal sudah ADA)');

        }else {
            try {

                DB::beginTransaction();
                $tutup                       = new TutupBuku();
                $tutup->id_perush            = $id_perush;
    
                if ($request->def_pos == "D") {
                    $tutup->ac4_debit        = $request->id_ac;
                } else {
                    $tutup->ac4_kredit       = $request->id_ac;
                }
                $tutup->tgl                  = $tanggal;
                $tutup->tahun                = $temp;   
                
                $tutup->total                = $request->nominal;
                $tutup->created_at           = date("Y-m-d H:i:s");
                $tutup->updated_at           = date("Y-m-d H:i:s");
                $tutup->id_user              = Auth::user()->id_user;
    
                //dd($tutup);
                $tutup->save();
                
                DB::commit();
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Saldo Awal Gagal Disimpan');
            }

            return redirect(route_redirect())->with('success', 'Saldo Awal Berhasil Disimpan');

        }
        
    }

    public function editsaldo(Request $request)
    {
        //dd($request->request);
        $id                          = $request->id_t;
        $temp                        = $request->tgl_e+1;
        $tanggal                     = $temp."-01-01";
        $nominal                     = $request->nominal_e;   

        try {

            DB::beginTransaction();
            $data = [
                "total"   =>  $nominal,
                "tgl"     =>  $tanggal,
                "tahun"   =>  $temp,
            ];

            TutupBuku::where("id",$id)->update(
                $data
            );
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Saldo Awal Gagal Disimpan');
        }

        return redirect(route_redirect())->with('success', 'Saldo Awal Berhasil DIUpdate');
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

    public function neraca()
    {
        $id_perush  = Session("perusahaan")["id_perush"];
        $getTanggal = date("Y-m-d");
        $dr_tgl     = date('Y-01-01', strtotime($getTanggal));
        $sp_tgl     = date('Y-m-t', strtotime($getTanggal));
        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $ac         = ACPerush::where("id_perush",$id_perush)->orderBy("id_ac")->get();
        $nilai      = [];
        $sampai     = date('Y-m-d', strtotime('+1 year', strtotime(date("Y-01-01"))));

        foreach ($ac as $key2 => $value2) {
            if($value2->id_ac < 3000){
                $saldo = 0;
                foreach ($newdata as $key => $value) {
                    if($value->id_debet == $value2->id_ac){
                        if($value->pos_d == "D"){
                            $saldo+=$value->total_debet;
                        }else{
                            $saldo-=$value->total_kredit;
                        }
                    }elseif($value->id_kredit == $value2->id_ac){
                        if($value->pos_k == "K"){                            
                            $saldo+=$value->total_kredit;
                        }else{
                            $saldo-=$value->total_debet;
                        }
                    }
                }
                if($saldo > 0){
                    $ac4_debit = null;
                    $ac4_kredit = null;

                    if ($value2->def_pos == "D") {
                        $ac4_debit = $value2->id_ac;
                    }else {
                        $ac4_kredit = $value2->id_ac;
                    }
                    
                    $nilai[$value2->id_ac] = [
                        'id_perush'     => $id_perush,
                        'ac4_debit'     => $ac4_debit,
                        'ac4_kredit'    => $ac4_kredit,
                        'tgl'           => $sampai,
                        'total'         => $saldo,
                        
                    ];
                }
                
            }
        }

        return $nilai;
    }

    public function laba($id_perush,$dr_tgl,$sp_tgl)
    {
        $level3     = MasterAC::where("level",3)->get();
        $newdata    = Neraca::Master($id_perush,$dr_tgl,$sp_tgl);
        $nilai      = [];
        $sampai     = date('Y-m-d', strtotime('+1 year', strtotime(date("Y-01-01"))));

        foreach ($level3 as $key => $value) {
            $total = 0;
            $pengurang = 0;
            foreach ($newdata as $key2 => $value2) {
                if ($value2->parent_d == $value->id_ac) {
                    if ($value2->pos_d == "D") {
                        $total+=$value2->total_debet;
                    }else{
                        $total-=$value2->total_debet;
                    }
                    
                }
                if ($value2->parent_k == $value->id_ac) {
                    if ($value2->pos_k == "K") {
                        $total+=$value2->total_kredit;
                    }else {
                        $total-=$value2->total_kredit;
                    }
                }
            }
            $nilai[$value->id_ac]=$total;
        }        
        
        $lababerjalan = 0;
        foreach ($nilai as $key => $value) {
            if ($key > 400) {
                if ($key < 410 || $key == 531) {
                    $lababerjalan+=$value;
                } else {
                    $lababerjalan-=$value;
                }
            }
            
        }
        $hasil = $lababerjalan;
        return $hasil;
    }
    

    public function AllData()
    {
        $tahun = date('Y');
        $id_perush = Session("perusahaan")["id_perush"];

        $arr_bulan = ["00","01","02","03","04","05","06","07","08","09","10","11","12"];
        $all_data = [];
        for ($i=1; $i <= 12; $i++) { 
            $all_data[$i] = Neraca::getData($id_perush,$arr_bulan[$i],$tahun);
        }

        $lev1 = []; $lev2=[]; $lev3=[]; $lev4 = [];
        $nilai1 =[]; $jumlah=[]; $debit = []; $kredit=[];

        for ($i=1; $i <= 12; $i++) { 
            foreach ($all_data[$i] as $key => $value) {
                if ($value->level == 1) {
                    $lev1[$value->id_ac] = $value;
                }
                elseif ($value->level == 2) {
                    $lev2[$value->id_parent][$value->id_ac] = $value;
                }
                elseif ($value->level == 3) {
                    $lev3[$i][$value->id_parent][$value->id_ac] = $value;
                    if ($value->parent != null) {
                        $lev4[$i][$value->parent][$value->ac_perush] = $value;
                    }
                }
            }
        }

        for ($i=1; $i <= 12; $i++) {
            //echo "Bulan ".$i."<br>";
            foreach ($lev1 as $key => $value) {
                if(isset($lev2[$value->id_ac])){
                    foreach($lev2[$value->id_ac] as $key2 => $value2){
                        $subtotal2 = 0;
                        if (isset($lev3[$i][$value2->id_ac])) {
                            foreach ($lev3[$i][$value2->id_ac] as $key3 => $value3) {
                                //echo $value3->nama."<br>";
                                if (isset($lev4[$i][$value3->id_ac])) {
                                    $total = 0;$deb = 0; $kre=0;
                                    foreach ($lev4[$i][$value3->id_ac] as $key4 => $value4) {
                                        $total+=$value4->total;
                                        $deb+=$value4->debit;
                                        $kre+=$value4->kredit;
                                        $subtotal2+=$value4->total;
                                    }
                                }
                                $jumlah[$i][$value3->id_ac]=$total;
                                $debit[$i][$value3->id_ac]=$deb;
                                $kredit[$i][$value3->id_ac]=$kre;
                                //echo $total."<br>";
                            }
                        }
                    $nilai1[$value2->id_ac] = $subtotal2;
                    }
                }
            }
        }
        $data["lev1"] = $lev1;
        $data["lev2"] = $lev2;
        $data["lev3"] = $lev3;
        $data["lev4"] = $lev4;
        $data["kredit"] = $kredit;
        $data["debit"] = $debit;
        $data["total"] = $jumlah;
        $data["nilai"] = $nilai1;
        $data["bulan"] = $arr_bulan;
    }
}
