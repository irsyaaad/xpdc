<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\Kapal;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Http\Requests\DaftarMuatRequest;
use Auth;
use DB;
use Exception;
use App\Models\Layanan;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\OpOrderKoli;
use Modules\Operasional\Entities\DmKoli;
use Modules\Operasional\Entities\SttDm;
use Modules\Operasional\Entities\DetailProyeksi;
use App\Models\Proyeksi;
use Modules\Operasional\Entities\ProyeksiDm;
use Modules\Operasional\Entities\Armada;
use Modules\Operasional\Entities\StatusDM;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\CaraBayar;

class DMContainerController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $page = 1;
        $perpage = 50;

        if(isset($request->page)){
            $page = $request->page;
        }

        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "kontainer")->get()->first();
        $id_dm = $request->id_dm;
        $id_perush_tj = $request->id_perush_tj;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $tglberangkat = $request->tglberangkat;
        $tgltiba = $request->tglsampai;
        $id_status = $request->id_status;
        $is_kota = 0;

        $data["layanan"] = $layanan;
        $data["data"] = DaftarMuat::getFilter($page, $perpage, $id_perush, $id_dm, 0, $layanan->id_layanan, $id_perush_tj, $id_sopir, $id_armada, $tglberangkat, $tgltiba, $id_status, $is_kota);
        $data["status"] = StatusDM::getList(1);
        $data["perusahaan"] = Perusahaan::getDataExept();
        $data["sopir"] = Sopir::getData($id_perush);
        $data["armada"] = Armada::getData($id_perush);

        return view('operasional::daftarmuat.dmcontainer', $data);
    }

    public function getdm(Request $request)
    {
        $term   = strtolower($request->term);
        $id_perush = Session("perusahaan")["id_perush"];
        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "kontainer")->get()->first();
        $data   = DaftarMuat::select("id_dm", "kode_dm")->where("id_layanan", $layanan->id_layanan)->where(DB::raw("lower(kode_dm)"),'LIKE','%'.$term.'%');

        if(!get_admin()){
            $data = $data->where("id_perush_dr", $id_perush);
        }

        $data = $data->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    public function filter(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $page = 1;
        $perpage = 50;

        if(isset($request->page)){
            $page = $request->page;
        }

        if(isset($request->shareselect)){
            $perpage = $request->shareselect;
        }

        if(isset($request->filterperushasal)){
            $id_perush = $request->id_perush;
        }

        $layanan = Layanan::where(DB::raw("lower(nm_layanan)"), "kontainer")->get()->first();
        $id_layanan = $layanan->id_layanan;
        $id_dm = $request->id_dm;
        $id_perush_tj = $request->id_perush_tj;
        $id_sopir = $request->id_sopir;
        $id_armada = $request->id_armada;
        $tglberangkat = $request->tglberangkat;
        $tglsampai = $request->tglsampai;
        $id_status = $request->id_status;
        $is_tiba = $request->is_tiba;

        $data["data"] = DaftarMuat::getFilter($page, $perpage, $id_perush, $id_dm, null, $id_layanan, $id_perush_tj, $id_sopir, $id_armada, $tglberangkat, $tglsampai, $id_status, null, $is_tiba);
        $data["status"] = StatusDM::getList(1);
        $data["perusahaan"] = Perusahaan::getDataExept();
        $data["sopir"] = Sopir::getData($id_perush);
        $data["armada"] = Armada::getData($id_perush);

        // for filter
        $id_dm = DaftarMuat::select("id_dm", "kode_dm")->where("id_dm", $id_dm)->get()->first();
        $filter = array("page"=> $perpage, "id_dm" => $id_dm, "id_perush" => $id_perush, "id_perush_tj"=> $id_perush_tj, "id_sopir"=> $id_sopir, "id_armada" => $id_armada, "id_status" => $id_status, "tglberangkat" => $tglberangkat, "tglsampai"=>$tglsampai, "is_tiba" => $is_tiba);
        $data["filter"] = $filter;

        return view('operasional::daftarmuat.dmcontainer', $data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data["data"] = [];
        $layanan =  Layanan::where(DB::raw("lower(nm_layanan)"), "kontainer")->Orwhere(DB::raw("lower(nm_layanan)"), "container")->get();
        $id_perush = Session("perusahaan")["id_perush"];
        if($layanan==null){
            return redirect()->back()->with('error', 'Layanan Belum ada');
        }

        $data["layanan"] = $layanan;
        $data["perusahaan"] = Perusahaan::where("id_perush", $id_perush)->get()->first();
        $data["perush_tj"] = Perusahaan::getDataExept();
        $data["kapal"] = Kapal::select("id_kapal", "nm_kapal")->get();
        $data["armada"] = Armada::select("id_armada", "nm_armada")->where("id_perush", $id_perush)->get();
        $data["sopir"] = Sopir::getSopirInActive($id_perush);

        return view('operasional::daftarmuat.dmcontainer', $data);
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(DaftarMuatRequest $request)
    {
        try {

            // save to user
            DB::beginTransaction();
            $perush = Perusahaan::findorFail(Session("perusahaan")["id_perush"]);
            $gen                   = $this->generate($request->id_layanan);
            $daftar                = new DaftarMuat();
            // $daftar->id_dm          = $gen["id_dm"];
            $daftar->kode_dm        = $gen["kode_dm"];
            $daftar->id_perush_dr   = $perush->id_perush;
            $daftar->id_perush_dr   = Session("perusahaan")["id_perush"];
            $daftar->id_layanan   = $request->id_layanan;
            $daftar->id_perush_tj   = $request->id_perush_tj;
            $daftar->id_kapal       = $request->id_kapal;
            $daftar->id_sopir       = $request->id_sopir;
            $daftar->id_armada       = $request->id_armada;
            $daftar->tgl_berangkat       = $request->tgl_berangkat;
            $daftar->tgl_sampai       = $request->tgl_sampai;
            $daftar->nm_dari       = $request->nm_dari;
            $daftar->nm_tuju       = $request->nm_tuju;
            $daftar->nm_pj_dr       = $request->nm_pj_dr;
            $daftar->nm_pj_tuju       = $request->nm_pj_tuju;
            $daftar->id_user        = Auth::user()->id_user;
            $daftar->no_container       = $request->no_container;
            $daftar->no_seal       = $request->no_seal;
            $daftar->id_status       = 1;

            $perusahaan = Perusahaan::findOrFail($request->id_perush_tj);
            $daftar->id_wil_asal         = $perush->id_region;
            $daftar->id_wil_tujuan   = $perusahaan->id_region;

            $daftar->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Daftar Muat Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect()."/".$daftar->id_dm."/show")->with('success', 'Data Daftar Muat Disimpan');
    }

    public function generate($id_layanan)
    {
        $id_perush = Session("perusahaan")["id_perush"];

        $time = substr(time(), 3,10);
        $data = [];
        $data["kode_dm"] = "DM".$id_perush.$id_layanan.$time;
        $data["id_dm"] = $id_perush.$id_layanan.$time;

        return $data;
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */

    public function detailstt($id, $id_stt)
    {
        $data["data"] = SttModel::findOrFail($id_stt);
        $data["koli"] = DmKoli::where("id_dm", $id)->where("id_stt", $id_stt)->get();

        return view('operasional::daftarmuat.showkoli', $data);
    }

    public function show($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;
        $data["status"] = StatusDM::getList();
        $data["detail"] = SttModel::getSttDM($id);
        $data["sttstat"] = StatusStt::getList();

        return view('operasional::daftarmuat.showdm', $data);
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $dm = DaftarMuat::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada")->findOrFail($id);
        $data["data"] = $dm;

        $layanan =  Layanan::where(DB::raw("lower(nm_layanan)"), "kontainer")->Orwhere(DB::raw("lower(nm_layanan)"), "container")->get();
        if($layanan==null){
            return redirect()->back()->with('error', 'Layanan Belum ada');
        }

        $data["layanan"] = $layanan;
        $data["perusahaan"] = Perusahaan::where("id_perush", Auth::user()->id_perush)->get()->first();
        $data["kapal"] = Kapal::all();
        $data["sopir"] = Sopir::all();
        $data["armada"] = Armada::all();
        $data["perush_tj"] = Perusahaan::getDataExept();

        return view('operasional::daftarmuat.dmcontainer', $data);
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(DaftarMuatRequest $request, $id)
    {
        try {

            // save to user
            DB::beginTransaction();
            $daftar                = DaftarMuat::findOrFail($id);
            $daftar->id_layanan   = $request->id_layanan;
            $daftar->id_perush_tj   = $request->id_perush_tj;
            $daftar->id_kapal       = $request->id_kapal;
            $daftar->tgl_berangkat       = $request->tgl_berangkat;
            $daftar->tgl_sampai       = $request->tgl_sampai;
            $daftar->nm_dari       = $request->nm_dari;
            $daftar->nm_tuju       = $request->nm_tuju;
            $daftar->nm_pj_dr       = $request->nm_pj_dr;
            $daftar->nm_pj_tuju       = $request->nm_pj_tuju;
            $daftar->id_user        = Auth::user()->id_user;
            $daftar->no_container       = $request->no_container;
            $daftar->no_seal       = $request->no_seal;
            //dd($daftar);
            $daftar->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Daftar Muat Gagal Disimpan');
        }

        return redirect(route_redirect()."/".$daftar->id_dm."/show")->with('success', 'Data Daftar Muat Disimpan');
    }

    public function getstt(Request $request)
    {
        $data = SttModel::where("pengirim_id_region", $request->id_asal)
        ->where("penerima_id_region", $request->id_tujuan)
        ->where("id_layanan", $request->id_layanan)->get();

        $a_data = [];
        foreach ($data as $key => $value) {
            $a_data[$key] = '<tr><td>'.strtoupper($value->id_stt).'</td><td>'.date_format(date_create($value->tgl_masuk), "d-m-Y").'</td><td>'.strtoupper($value->pengirim_nm).'</td><td class="text-center"><button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button></td></tr>';
        }

        return response()->json($a_data);
    }

    public function detail($id = null, Request $request)
    {
        $dm = DaftarMuat::findOrFail($id);
        if($dm->id_status!="1"){
            return redirect()->back()->with('error', 'Access Terbatas');
        }

        $data["stt"] = SttModel::getSttKoli($id, $dm->id_perush_dr, $dm->id_perush_tj, $dm->id_layanan,1);

        if(isset($request->id_stt)){
            $data["data"] = SttModel::getIdSttKoli($request->id_stt);
            $data["koli"] = OpOrderKoli::getKoliStt($request->id_stt, 1);

            if(count($data["koli"])<1){
                return redirect()->back()->with('error', 'Data Stt Tidak Ditemukan');
            }
        }

        return view('operasional::daftarmuat.detaildm', $data);
    }
    public function cetakDM($id)
    {
        $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"]            = SttModel::getDM($id)->get();
        $stt                    = SttModel::getDM($id)->get();
        $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"]             = $id;
        $temp                   = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"]           = $temp;
        $data["carabayar"]      = $cara_bayar;

        return view('operasional::daftarmuat.cetakdm', $data);
    }

    public function cetakDMNoTarif($id)
    {
        $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"]            = SttModel::getDM($id)->get();
        $stt                    = SttModel::getDM($id)->get();
        $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"]             = $id;
        $temp                   = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"]           = $temp;
        $data["carabayar"]      = $cara_bayar;

        return view('operasional::daftarmuat.cetakdm', $data);
    }
    public function cetakDMBarcode($id)
    {
        $cara_bayar             = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->get();
        $data["dm"]             = DaftarMuat::with("kapal","armada","sopir","perush_tujuan")->where("id_dm",$id)->get()->first();
        $data["stt"]            = SttModel::getDM($id)->get();
        $stt                    = SttModel::getDM($id)->get();
        $data["perusahaan"]     = Perusahaan::where("id_perush",Session("perusahaan")["id_perush"])->get()->first();
        $data["id"]             = $id;
        $temp                   = [];

        foreach ($stt as $key => $value) {
            $temp[$value->id_cr_byr_o][$key] = $value;
        }

        $data["data"]           = $temp;
        $data["carabayar"]      = $cara_bayar;

        return view('operasional::daftarmuat.cetakdmbarcode', $data);
    }
}
