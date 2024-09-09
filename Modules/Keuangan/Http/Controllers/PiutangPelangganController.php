<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\Piutang;
use Modules\Keuangan\Entities\Pembayaran;
use App\Models\Grouppelanggan;
use Modules\Operasional\Entities\SttModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Pelanggan;
use App\Models\Perusahaan;

class PiutangPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $perpage            = $request->shareselect!=null?$request->shareselect:50;
        $page               = $request->page!=null?$request->page:1;
        $f_id_pelanggan     = $request->f_id_pelanggan;
        $f_start            = $request->f_start!=null?$request->f_start:date("Y-01-01");
        $f_end              = $request->f_end!=null?$request->f_end:date("Y-12-31");
        $id_perush          = Session("perusahaan")["id_perush"];
        $f_id_group         = $request->f_id_group;
        $tipe_data          = isset($request->tipe_data) ? $request->tipe_data : NULL;

        $data["data"]           = Piutang::newgetData($perpage, $page, $id_perush,$f_id_pelanggan, $f_id_group, $f_start, $f_end, $tipe_data);
        $data["total_piutang"]  = Piutang::getAllPiutang($id_perush, $f_start, $f_end);
        $data["group"]          = Grouppelanggan::get();
        $data["page"]           = $perpage;
        $data["pelanggan"]      = Pelanggan::select("id_pelanggan", "nm_pelanggan")->where("id_perush",$id_perush)->get();
        $urls                   = "?f_id_group=".$f_id_group."&f_id_pelanggan=".$f_id_pelanggan."&f_start=".$f_start."&f_end=".$f_end."";
        $data["filter"]         = array("f_id_pelanggan"=> $f_id_pelanggan, "f_id_group"=> $f_id_group, "f_start" => $f_start, "f_end" => $f_end, "tipe_data" => $tipe_data, "urls" => $urls);
        
        return view('keuangan::pelanggan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id, Request $request)
    {

        $dr_tgl = date("Y-m-01");
        $sp_tgl = date("Y-m-t");

        if (isset($request->dr_tgl)) {
            $dr_tgl             = date($request->dr_tgl);
        }

        if (isset($request->sp_tgl)) {
            $sp_tgl             = date($request->sp_tgl);
        }

        $pelanggan = Pelanggan::findOrFail($id);
        $data["data"] = Piutang::getDetail($id, 10);
        $data["lunas"] = Piutang::getDetail($id, 10, 'LUNAS', $dr_tgl, $sp_tgl);
        $data["belum"] = Piutang::getDetail($id, 10, 'BELUM LUNAS', $dr_tgl, $sp_tgl);
        $data["pelanggan"] = $pelanggan;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];

        // dd($data);
        return view('keuangan::pelanggan.show',$data);
    }

    public function filtershow(Request $request)
    {
        // dd($request->dr_tgl);
        $page = 1;
        $perpage = 20;
        $dr_tgl = date("Y-m-d");
        $sp_tgl = date("Y-m-d");

        if ($request->method()=="POST") {
            if (isset($request->dr_tgl)) {
                $dr_tgl             = date($request->dr_tgl);
            }

            if (isset($request->sp_tgl)) {
                $sp_tgl             = date($request->sp_tgl);
            }
        }

        $datax = Piutang::getDetailDate($perpage, $page, $request->id_pelanggan, $dr_tgl, $sp_tgl);
        dd($request->id_pelanggan, $dr_tgl, $sp_tgl,$datax);
        $lunas = []; $belum = [];
        foreach ($datax as $key => $value) {
            if ($value->kurang == 0) {
                $lunas[$key] = $value;
            }else{
                $belum[$key] = $value;
            }
        }
        $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);
        $data["data"] = $datax;
        $data["lunas"] = $lunas;
        $data["belum"] = $belum;

        // $data["data"] = $this->paginate($datax,$page)->setPath('http://localhost:8000/piutangpelanggan/'.$request->id_pelanggan.'/show');
        // $data["lunas"] = $this->paginate($lunas,$page)->setPath('http://localhost:8000/piutangpelanggan/'.$request->id_pelanggan.'/show');
        // $data["belum"] = $this->paginate($belum,$page)->setPath('http://localhost:8000/piutangpelanggan/'.$request->id_pelanggan.'/show');
        $data["pelanggan"] = $pelanggan;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl
        ];

        // dd($data);
        return view('keuangan::pelanggan.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function getData()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = Piutang::getData($id_perush);
        // dd($newdata);
        $array = [];
        $group = Grouppelanggan::get();
        foreach ($newdata as $key => $value) {
            $array[$value->id_plgn_group][$value->id_pelanggan] = $value;
        }
        // foreach ($group as $key => $value) {
        //     echo $value->id_plgn_group."<br>";
        //     if (isset($array[$value->id_plgn_group])) {
        //         foreach ($array[$value->id_plgn_group] as $key2 => $value2) {
        //             echo $value2->nm_pelanggan."<br>";
        //         }
        //     }
        // }
        return $array;
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function cetak($id)
    {
        $page = 1;
        $perpage = 20;

        $datax = Piutang::cetakDetail($id);
        $lunas = []; $belum = [];
        foreach ($datax as $key => $value) {
            if ($value->kurang == 0) {
                $lunas[$key] = $value;
            }else{
                $belum[$key] = $value;
            }
        }
        $pelanggan = Pelanggan::findOrFail($id);
        $data["data"] = $datax;
        $data["lunas"] = $lunas;
        $data["belum"] = $belum;
        // $data["data"] = $this->paginate($datax,$page)->setPath('http://localhost:8000/piutangpelanggan/'.$id.'/show');
        $data["pelanggan"] = $pelanggan;
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        // dd($data);
        return view('keuangan::pelanggan.cetak',$data);
    }

    public function cetaksemua(Request $request)
    {
        $f_id_pelanggan = $request->f_id_pelanggan;
        $f_start = $request->f_start!=null?$request->f_start:date("Y-01-31");
        $f_end = $request->f_end!=null?$request->f_end:date("Y-12-31");
        $id_perush          = Session("perusahaan")["id_perush"];
        $f_id_group = $request->f_id_group;

        $newdata = Piutang::cetakAll($id_perush,$f_id_pelanggan, $f_id_group, $f_start, $f_end);
        $group = Grouppelanggan::orderBy('id_plgn_group','ASC')->get();
        $array = [];
        foreach ($group as $key => $value) {
            foreach ($newdata as $key2 => $value2) {
                if ($value2->id_plgn_group == $value->id_plgn_group) {
                    $array[$value->id_plgn_group][$value2->id_pelanggan] = $value2;
                }
            }
        }
        $data["data"] = $array;
        $data["group"] = $group;
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $urls = "?f_id_group=".$f_id_group."&f_id_pelanggan=".$f_id_pelanggan."&f_start=".$f_start."&f_end=".$f_end."";
        $data["urls"] = $urls;

        return view('keuangan::pelanggan.cetakalldata',$data);
    }

    public function cetaklunas(Request $request)
    {
        $f_id_pelanggan = $request->f_id_pelanggan;
        $f_start = $request->f_start!=null?$request->f_start:date("Y-01-31");
        $f_end = $request->f_end!=null?$request->f_end:date("Y-12-31");
        $id_perush          = Session("perusahaan")["id_perush"];
        $f_id_group = $request->f_id_group;

        $newdata = Piutang::cetakAll($id_perush,$f_id_pelanggan, $f_id_group, $f_start, $f_end);
        $belum = [];
        foreach ($newdata as $key => $value) {
            if ($value->kurang == 0) {
                $belum[$key] = $value;
            }
        }
        $group = Grouppelanggan::orderBy('id_plgn_group','ASC')->get();
        $array = [];
        foreach ($group as $key => $value) {
            foreach ($belum as $key2 => $value2) {
                if ($value2->id_plgn_group == $value->id_plgn_group) {
                    $array[$value->id_plgn_group][$value2->id_pelanggan] = $value2;
                }
            }
        }
        $data["data"] = $array;
        $data["group"] = $group;
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $urls = "?f_id_group=".$f_id_group."&f_id_pelanggan=".$f_id_pelanggan."&f_start=".$f_start."&f_end=".$f_end."";
        $data["urls"] = $urls;

        $pdf = \PDF::loadview("keuangan::pelanggan.cetak-piutang",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
        // return view('keuangan::pelanggan.cetakalldata',$data);
    }

    public function cetakbelumlunas(Request $request)
    {
        $f_id_pelanggan = $request->f_id_pelanggan;
        $f_start = $request->f_start!=null?$request->f_start:date("Y-01-31");
        $f_end = $request->f_end!=null?$request->f_end:date("Y-12-31");
        $id_perush          = Session("perusahaan")["id_perush"];
        $f_id_group = $request->f_id_group;

        $newdata = Piutang::cetakAll($id_perush,$f_id_pelanggan, $f_id_group, $f_start, $f_end);
        $belum = [];
        foreach ($newdata as $key => $value) {
            if ($value->kurang > 0) {
                $belum[$key] = $value;
            }
        }
        $group = Grouppelanggan::orderBy('id_plgn_group','ASC')->get();
        $array = [];
        foreach ($group as $key => $value) {
            foreach ($belum as $key2 => $value2) {
                if ($value2->id_plgn_group == $value->id_plgn_group) {
                    $array[$value->id_plgn_group][$value2->id_pelanggan] = $value2;
                }
            }
        }
        $data["data"] = $array;
        $data["group"] = $group;
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        $urls = "?f_id_group=".$f_id_group."&f_id_pelanggan=".$f_id_pelanggan."&f_start=".$f_start."&f_end=".$f_end."";
        $data["urls"] = $urls;

        $pdf = \PDF::loadview("keuangan::pelanggan.cetak-piutang",$data)
        ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
        // return view('keuangan::pelanggan.cetakalldata',$data);
    }


}
