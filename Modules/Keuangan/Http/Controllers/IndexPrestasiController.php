<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\IndexPrestasi;
use Modules\Kepegawaian\Entities\Marketing;
use App\Models\Perusahaan;

class IndexPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('keuangan::index');
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
        //
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

    public function PrestasiMarketing(Request $request)
    {
        $dr_tgl = $request->dr_tgl!=null?date('Y-m-d', strtotime($request->dr_tgl)):Date('Y-m-01');
        $sp_tgl = $request->sp_tgl!=null?date('Y-m-d', strtotime($request->sp_tgl)):Date('Y-m-t');
        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = IndexPrestasi::PrestasiMarketing2($id_perush,$dr_tgl,$sp_tgl);

        $array = []; $mar = [];
        foreach ($newdata as $key => $value) {
            $array[$value->nm_marketing][$value->kode_plgn_group] = $value;
            $mar[$value->id_marketing] = $value->nm_marketing;
        }
        $data["marketing"] = $mar;
        $data["data"] = $array;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            '_token' => $request->_token
        ];

        return view('keuangan::indexprestasi.prestasimarketing', $data);
    }

    public function detailprestasimarketing(Request $request){
        $dr_tgl = $request->dr_tgl!=null?date('Y-m-d', strtotime($request->dr_tgl)):Date('Y-m-01');
        $sp_tgl = $request->sp_tgl!=null?date('Y-m-d', strtotime($request->sp_tgl)):Date('Y-m-t');
        $id_marketing = $request->id_marketing;

        $id_perush = Session("perusahaan")["id_perush"];
        $prestasi = IndexPrestasi::getDetail($id_marketing, $id_perush, $dr_tgl, $sp_tgl);
        $group = []; $arr = [];
        foreach ($prestasi as $key => $value) {
            $arr[$value->id_plgn_group][$value->id_stt] = $value;
            $group[$value->id_plgn_group] = $value->nm_group." (".$value->kode_plgn_group.")";
        }
        $data["marketing"] = $id_marketing != 0 ? Marketing::findOrfail($id_marketing) : null;
        $data["data"] = $arr;
        $data["group"] = $group;
        $data["id_marketing"] = $id_marketing;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'back' => url("prestasimarketing?_token").$request->_token."&dr_tgl=".$dr_tgl."&sp_tgl=".$sp_tgl
        ];

        return view('keuangan::indexprestasi.detailprestasimarketing', $data);
    }

    public function AnalisaPelangganOld(Request $request)
    {
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $marketing = Marketing::where('id_perush',$id_perush)->get();
        $newdata = IndexPrestasi::AnalisaPelanggan($id_perush,$dr_tgl,$sp_tgl);
        $arr = [];
        $mar = [];

        foreach ($newdata as $key => $value) {
            $mar[$value->id_marketing][$key] = $value;
            
        }

        foreach ($marketing as $key2 => $value2) {
            $total_aktif    = 0;
            $total_reorder  = 0;
            $total_baru     = 0;
            $jml_pelanggan  = 0;

            if (isset($mar[$value2->id_marketing])) {
                foreach ($mar[$value2->id_marketing] as $key => $value) {                
                    $arr[$value->id_marketing]['total_stt'] = $value->stt;
                    $arr[$value->id_marketing]['total_koli'] = $value->koli;
                    $arr[$value->id_marketing]['total_omset'] = $value->omset;
                    if ($this->month_diff($value->tgl,$sp_tgl) <= 36) {
                        $total_aktif += 1;
                        $arr[$value->id_marketing]['aktif'] = $total_aktif;
                    }
    
                    if ($this->month_diff($value->tgl,$sp_tgl) <= 12 and $value->total_stt > 1) {
                        $total_reorder += 1;
                        $arr[$value->id_marketing]['reorder'] = $total_reorder;
                    }
    
                    if ($this->month_diff($value->tgl,$sp_tgl) <= 12) {
                        $total_baru += 1;
                        $arr[$value->id_marketing]['baru'] = $total_baru;
                    }
    
                    $jml_pelanggan+=1;
                    $arr[$value->id_marketing]['jml_pelanggan'] = $jml_pelanggan;
                }
            }
        }

        $data["marketing"] = Marketing::where('id_perush',$id_perush)->get();
        $data["data"] = $arr;
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($arr);
        return view('keuangan::indexprestasi.analisapelanggan', $data);
    }

    public function month_diff($tgl,$sp_tgl)
    {
        $datetime1 = date_create($tgl);
        $datetime2 = date_create($sp_tgl);
        $interval = date_diff($datetime1, $datetime2);

        return $interval->format('%m months');
    }

    public function AnalisaPelanggan(Request $request) {
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = IndexPrestasi::AnalisaPelanggan4($id_perush,$dr_tgl,$sp_tgl);
        // dd($newdata);
        $data["data"] = $newdata;
        $data["pelanggan_unik"] = IndexPrestasi::getUnikPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);
        return view('keuangan::indexprestasi.analisa_pelanggan_v2', $data);
    }

    public function AnalisaPelangganAktif(Request $request)
    {
        $id_perush = Session('perusahaan')['id_perush'];
        $id_marketing = 0;
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }
        if (isset($request->id_marketing)) {
            $id_marketing = $request->id_marketing;
        }

        $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
        // dd($newdata);
        switch ($request->type) {
            case 'aktif':
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;

            case 'baru':
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;
            
            default:
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;
        }
        $data["data"] = $newdata;
        $data["marketing"] = Marketing::findOrfail($id_marketing);
        $data["filter"] = [
            'id_marketing' => $id_marketing,
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'back' => url("analisapelanggan")."?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl ,
        ];
        // dd($data);
        return view('keuangan::indexprestasi.detail-pelanggan-aktif', $data);
    }

    public function CetakAnalisaPelangganAktif(Request $request)
    {
        $id_perush = Session('perusahaan')['id_perush'];
        $id_marketing = 0;
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }
        if (isset($request->id_marketing)) {
            $id_marketing = $request->id_marketing;
        }

        $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
        switch ($request->type) {
            case 'aktif':
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;

            case 'baru':
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;
            
            default:
                $newdata = IndexPrestasi::getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl);
                break;
        }
        $data["data"] = $newdata;
        $data["marketing"] = Marketing::findOrfail($id_marketing);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"] = [
            'id_marketing' => $id_marketing,
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'back' => url("analisapelanggan")."?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl ,
        ];
        // dd($data);
        // return view('keuangan::indexprestasi.detail-pelanggan-aktif', $data);

        $pdf = \PDF::loadview("keuangan::indexprestasi.cetak.pelanggan-aktif", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function DetailAnalisaPelanggan(Request $request)
    {
        $dr_tgl         = Date('Y-m-01');
        $sp_tgl         = Date('Y-m-t');
        $id_marketing   = 0;

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }
        if (isset($request->id_marketing)) {
            $id_marketing = $request->id_marketing;
        }
        
        $data["data"] = IndexPrestasi::getDetailAnalisaPelanggan($id_marketing, $dr_tgl, $sp_tgl);
        $data["marketing"] = Marketing::findOrfail($id_marketing);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
            'back' => url("analisapelanggan")."?_token=" . $request->_token . "&dr_tgl=" . $dr_tgl . "&sp_tgl=" . $sp_tgl ,
        ];
        // dd($data);
        return view('keuangan::indexprestasi.detail-analisa-pelanggan', $data);
    }

    public function CetakAnalisaPelanggan(Request $request)
    {
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = IndexPrestasi::AnalisaPelanggan4($id_perush,$dr_tgl,$sp_tgl);
        // dd($newdata);
        $data["data"] = $newdata;
        $data["pelanggan_unik"] = IndexPrestasi::getUnikPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        // dd($data);

        $pdf = \PDF::loadview("keuangan::indexprestasi.cetak.analisa-pelanggan", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function ExcelAnalisaPelanggan(Request $request)
    {
        $dr_tgl = Date('Y-m-01');
        $sp_tgl = Date('Y-m-t');

        if (isset($request->dr_tgl)) {
            $dr_tgl = $request->dr_tgl;
        }
        if (isset($request->sp_tgl)) {
            $sp_tgl = $request->sp_tgl;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $newdata = IndexPrestasi::AnalisaPelanggan4($id_perush,$dr_tgl,$sp_tgl);
        // dd($newdata);
        $data["data"] = $newdata;
        $data["pelanggan_unik"] = IndexPrestasi::getUnikPelanggan($id_perush, $dr_tgl, $sp_tgl);
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        $data["filter"] = [
            'dr_tgl' => $dr_tgl,
            'sp_tgl' => $sp_tgl,
        ];
        return view('keuangan::indexprestasi.cetak.excel-analisa-pelanggan', $data);
    }

}
