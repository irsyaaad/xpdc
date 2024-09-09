<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\DaftarMuat;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {   
        session()->forget('bulan');
        session()->forget('tahun');
        session()->forget('layanan');
        session()->forget('status');
        session()->forget('bulandm');
        session()->forget('tahundm');
        session()->forget('layanandm');

        $bulan = date('m');
        $tahun = date('Y');
        $layanan = "0";
        $status = "0";
        $sttstatistik = $this->StatistikStt($bulan,$tahun);
        $dmstatistik = $this->StatistikDm($bulan,$tahun);
        $stt = $this->DataStt($bulan,$tahun,$layanan,$status);
        $dm = $this->DataDM($bulan,$tahun,$layanan);

        $data["stt"] = $stt;
        $data["dm"] = $dm;
        $data["data"] = $sttstatistik; 
        $data["datadm"] = $dmstatistik;    
        return view('operasional::dashboard', $data);
    }

    public function filter(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');
        $layanan = "0";
        $status = "0";
        if (Session('bulandm') !== null) {
            $bulan = Session('bulandm');
        }
        if (Session('tahundm') !== null) {
            $tahun = Session('tahundm');
        }
        if (Session('layanandm') !== null) {
            $layanan = Session('layanandm');
        }
        $dm = $this->DataDM($bulan,$tahun,$layanan);
        $dmstatistik = $this->StatistikDm($bulan,$tahun);
        $data["datadm"] = $dmstatistik;

        if(isset($request->filter) and $request->filter != "0"){
            $bulan = $request->filter;
            $session = [];
            $session['bulan'] = $request->filter;
            Session($session);
        }

        if (isset($request->tahun) and $request->tahun != "0") {
            $tahun = $request->tahun;
            $session = [];
            $session['tahun'] = $request->tahun;
            Session($session);
        }

        if (isset($request->layanan) and $request->layanan != "0") {
            $layanan = $request->layanan;
            $session = [];
            $session['layanan'] = $request->layanan;
            Session($session);
        }

        if (isset($request->status) and $request->status != "0") {
            $status = $request->status;
            $session = [];
            $session['status'] = $request->status;
            Session($session);
        }

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        if (Session('layanan') !== null) {
            $layanan = Session('layanan');
        }

        if (Session('status') !== null) {
            $status = Session('status');
        }

        $sttstatistik = $this->StatistikStt($bulan,$tahun);
        $data["data"] = $sttstatistik;
        $stt = $this->DataStt($bulan,$tahun,$layanan,$status);
        $data["stt"] = $stt;       
        $data["dm"] = $dm;
        return view('operasional::dashboard', $data);
    }

    public function filterdm(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');
        $layanan = "0";
        $status = "0";

        if (Session('bulan') !== null) {
            $bulan = Session('bulan');
        }
        if (Session('tahun') !== null) {
            $tahun = Session('tahun');
        }
        if (Session('layanan') !== null) {
            $layanan = Session('layanan');
        }
        if (Session('status') !== null) {
            $status = Session('status');
        }

        $stt = $this->DataStt($bulan,$tahun,$layanan,$status);
        $sttstatistik = $this->StatistikStt($bulan,$tahun);
        $data["data"] = $sttstatistik;

        if(isset($request->filterdm) and $request->filterdm != "0"){
            $bulan = $request->filterdm;
            $session = [];
            $session['bulandm'] = $request->filterdm;
            Session($session);
        }

        if (isset($request->tahundm) and $request->tahundm != "0") {
            $tahun = $request->tahundm;
            $session = [];
            $session['tahundm'] = $request->tahundm;
            Session($session);
        }

        if (isset($request->layanandm) and $request->layanandm != "0") {
            $layanan = $request->layanandm;
            $session = [];
            $session['layanandm'] = $request->layanandm;
            Session($session);
        }

        if (Session('bulandm') !== null) {
            $bulan = Session('bulandm');
        }
        if (Session('tahundm') !== null) {
            $tahun = Session('tahundm');
        }
        if (Session('layanandm') !== null) {
            $layanan = Session('layanandm');
        }
        $dmstatistik = $this->StatistikDm($bulan,$tahun);
        $data["datadm"] = $dmstatistik;
        $dm = $this->DataDM($bulan,$tahun,$layanan);
        $data["stt"] = $stt;       
        $data["dm"] = $dm;
        return view('operasional::dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["data"] = [];
        
        return view('operasional::stt', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('operasional::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('operasional::edit');
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

    public function DataStt($tgl,$tahun,$layanan,$status)
    {
        $stt = [];
        $stt = SttModel::all()->where("id_perush_asal",Session("perusahaan")["id_perush"]);
        if ($layanan != "0") {
            $stt = SttModel::all()->where("id_layanan",$layanan);
        }
        if ($status != "0") {
            $stt = SttModel::all()->where("id_status",$status);
        }
        $newdata = [];
        foreach ($stt as $key => $value) {
            $timestamp = strtotime($value->tgl_masuk);
            $bln = date('m', $timestamp);
            $thn = date('Y',$timestamp);
            if ($bln == $tgl and $thn == $tahun) {
                $timestamp = strtotime($value->tgl_masuk);
                $hr = date('d',$timestamp);
                $newdata[$value->id_stt]=$hr;
            }
        }
        $temp = range(1, 31);
        $datanya = [];
        for ($i=1; $i <=31 ; $i++) { 
            $count = 0;
            foreach ($newdata as $key => $value) {
                if($i == (int)$value){
                    $count+=1;
                }
            }
            $datanya[$i]=$count;
        }
        return $datanya;
    }

    public function DataDM($tgl,$tahun,$layanan)
    {
        $dm = [];
        $dm = DaftarMuat::all()->where("id_perush_dr",Session("perusahaan")["id_perush"]);
        if ($layanan != "0") {
            $dm = DaftarMuat::all()->where("id_layanan",$layanan);
        }
        $newdata = [];
        foreach ($dm as $key => $value) {
            $timestamp = strtotime($value->tgl_berangkat);
            $bln = date('m', $timestamp);
            $thn = date('Y',$timestamp);
            if ($bln == $tgl and $thn == $tahun) {
                $timestamp = strtotime($value->tgl_berangkat);
                $hr = date('d',$timestamp);
                $newdata[$value->id_dm]=$hr;
            }
        }
        $temp = range(1, 31);
        $datanya = [];
        for ($i=1; $i <=31 ; $i++) { 
            $count = 0;
            foreach ($newdata as $key => $value) {
                if($i == (int)$value){
                    $count+=1;
                }
            }
            $datanya[$i]=$count;
        }
        return $datanya;
    }
    public function StatistikStt($tgl,$tahun)
    {
        $stt = SttModel::all()->where("id_perush_asal",Session("perusahaan")["id_perush"]);
        $trucking = 0;
        $kontainer = 0;
        $kapal     = 0;
        $pendapatan = 0;
        $total = 0;
        $sttditerima = 0;
        foreach ($stt as $key => $value) {
            $timestamp = strtotime($value->tgl_masuk);
            $bln = date('m', $timestamp);
            $thn = date('Y',$timestamp);
            if ($bln == $tgl and $thn == $tahun) {
                $pendapatan = $pendapatan+(int)$value->c_total;
                $total+=1;
                if ($value->id_layanan == 1) {
                    $trucking+=1;
                }elseif ($value->id_layanan == 2) {
                    $kontainer+=1;
                }elseif ($value->id_layanan == 3) {
                    $kapal+=1;
                }

                if ($value->id_status == 7) {
                    $sttditerima+=1;
                }
                
            }
        }
        $data["sttditerima"] = $sttditerima;
        $data["trucking"] = $trucking;
        $data["kontainer"] = $kontainer;
        $data["kapal"] = $kapal;
        $data["pendapatan"] = $pendapatan;
        $data["total"] = $total;

        return $data;
    }

    public function StatistikDm($tgl,$tahun)
    {
        $dm = DaftarMuat::all()->where("id_perush_dr",Session("perusahaan")["id_perush"]);;
        $trucking = 0;
        $kontainer = 0;
        $kapal     = 0;
        $pendapatan = 0;
        $total = 0;
        $dmsampai = 0;
        foreach ($dm as $key => $value) {
            $timestamp = strtotime($value->tgl_berangkat);
            $bln = date('m', $timestamp);
            $thn = date('Y',$timestamp);
            if ($bln == $tgl and $thn == $tahun) {
                $pendapatan = $pendapatan+(int)$value->c_total;
                $dmsampai+=1;
                $total+=1;
                if ($value->id_layanan == 1) {
                    $trucking+=1;
                }elseif ($value->id_layanan == 2) {
                    $kontainer+=1;
                }elseif ($value->id_layanan == 3) {
                    $kapal+=1;
                }                
            }
        }
        $data["trucking"] = $trucking;
        $data["kontainer"] = $kontainer;
        $data["kapal"] = $kapal;
        $data["pendapatan"] = $pendapatan;
        $data["total"] = $total;
        $data["dmsampai"] = $dmsampai;

        return $data;
    }
}
