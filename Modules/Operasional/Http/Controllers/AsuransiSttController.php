<?php

namespace Modules\Operasional\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Operasional\Entities\SttModel;
use Modules\Operasional\Entities\PerusahaanAsuransi;
use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Operasional\Entities\Asuransi;
use DB;
use Auth;
use Modules\Keuangan\Entities\Pengeluaran;
use Modules\Keuangan\Entities\GenIdPengeluaran;
use Modules\Keuangan\Entities\MasterAC;
use Modules\Keuangan\Entities\PengeluaranDetail;
use Modules\Keuangan\Http\Controllers\PengeluaranController;
use Modules\Keuangan\Entities\PendapatanDetail;
use Modules\Keuangan\Http\Controllers\PendapatanController;
use Modules\Keuangan\Entities\Pendapatan;
use Modules\Keuangan\Entities\SettingLayananPerush;
use Modules\Keuangan\Entities\ACPerush;
use App\Models\Wilayah;
use App\Models\Perusahaan;
use Modules\Operasional\Entities\TipeKirim;

class AsuransiSttController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    protected $pengeluaran;
    protected $pendapatan;

    public function __construct(PengeluaranController $PengeluaranController, PendapatanController $PendapatanController)
    {
        $this->pengeluaran = $PengeluaranController;
        $this->pendapatan = $PendapatanController;
    }

    public function index()
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $newdata        = Asuransi::with("stt","pelanggan","perusahaan","user")->where("id_perush",$id_perush)->paginate(100);
        $data['perush'] = PerusahaanAsuransi::where('id_perush',$id_perush)->get();
        $data["data"]   = $newdata;
        //dd($data);
        return view('operasional::index',$data);
    }

    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $data['perush_asuransi'] = PerusahaanAsuransi::all();
        return view('operasional::create-v2',$data);
    }

    function getAsuransiStt (Request $request) {
        $term   = $request->term;
        $data   = SttModel::select("id_stt","no_awb", "kode_stt")
                    ->where("kode_stt", 'ILIKE', '%' . $term . '%')
                    ->where("n_asuransi", ">", 0)
                    ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->kode_stt)];
        }

        return response()->json($results);
    }

    function getDataSttAsuransi($id) {
        $data["stt"] = SttModel::findOrfail($id);
        $data["asal"] = Wilayah::findOrfail($data["stt"]->pengirim_id_region);
        $data["tujuan"] = Wilayah::findOrfail($data["stt"]->penerima_id_region);
        $data["perush"] = Perusahaan::select("id_perush","nm_perush")->where("id_perush",$data["stt"]->id_perush_asal)->get()->first();
        $data["tipe"] = TipeKirim::findOrFail($data["stt"]->id_tipe_kirim);

        return response()->json($data);
    }

    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        // dd($request->request);

        try {

            // save to user
            DB::beginTransaction();

            $id_perush  = Session("perusahaan")["id_perush"];
            if(count($request->id)<1){
                return redirect()->back()->with('error', 'Tidak ada STT yang dipilih');
            }
            $tarif  = TarifAsuransi::where("id_perush",$id_perush)->get()->first();

            $group = DB::table('m_layanan as a')
            ->join('s_grup_layanan_ac_perush as b','a.id_layanan','=','b.id_layanan')
            ->select('b.*')
            ->where("a.kode_layanan", "A")
            ->where("b.id_perush",Session("perusahaan")["id_perush"])->get()->first();
            if($group==null){
                DB::rollback();
                return redirect()->back()->with('error', "Setting Group Layanan Asuransi Belum Ada");
            }

            $datanya = [];
            foreach ($request->id as $key => $value) {
                $temp = SttModel::findOrFail($value);
                $batas = $tarif->min_harga_pertanggungan;
                $nominal = 0;
                if ($temp->n_harga_pertanggungan < $batas) {
                    $nominal = $tarif->harga_pertanggungan;
                } else {
                    $nominal = $temp->n_harga_pertanggungan*$tarif->harga_jual;
                }

                $datanya[$key] = [
                    'id_stt'                => $value,
                    'awb'                   => $temp->kode_stt,
                    'id_pelanggan'          => $temp->id_perush_asal,
                    'ac4_d'                 => $group->ac_piutang,
                    'ac4_k'                 => $group->ac_pendapatan,
                    'id_tipe_barang'        => $temp->id_tipe_kirim,
                    'id_perush'             => $id_perush,
                    'id_user'               => Auth::user()->id_user,
                    'harga_pertanggungan'   => $temp->n_harga_pertanggungan,
                    'nominal'               => $nominal,
                    'keterangan'            => 'Asuransi STT No. '.$temp->kode_stt,
                    'id_status'             => 1,
                    'created_at'            => date("Y-m-d H:i:s"),
                    'updated_at'            => date("Y-m-d H:i:s"),
                ];
            }
            //dd($datanya);
            $asuransi = Asuransi::insert($datanya);
            //$asuransi->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan'.$e->getMessage());
        }

        return redirect("asuransistt")->with('success', 'Data Asuransi Disimpan');
    }

    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $data['data'] = Asuransi::findOrFail($id);
        $data['perush_asuransi'] = PerusahaanAsuransi::all();
        // dd($datanya);
        return view('operasional::show',$data);
    }

    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        $data['data'] = Asuransi::findOrFail($id);
        $data['perush_asuransi'] = PerusahaanAsuransi::all();
        // dd($datanya);
        return view('operasional::edit',$data);
    }

    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        // dd($request->all(),$id);
        $id_perush = Session("perusahaan")["id_perush"];
        $tarif  = TarifAsuransi::where('id_perush_asuransi',$request->broker)->get()->first();

        // $group = DB::table('m_layanan as a')
        // ->join('s_grup_layanan_ac_perush as b','a.id_layanan','=','b.id_layanan')
        // ->select('b.*')
        // ->where("a.kode_layanan", "A")
        // ->where("b.id_perush",Session("perusahaan")["id_perush"])->get()->first();
        // if($group==null){
        //     DB::rollback();
        //     return redirect()->back()->with('error', "Setting Group Layanan Asuransi Belum Ada");
        // }

        $ac = ACPerush::where('id_perush',$id_perush);

        if($ac==null){
            DB::rollback();
            return redirect()->back()->with('error', "Master AC Belum Ada");
        }
        $cek_biaya = ACPerush::where('id_perush',$id_perush)->where('id_ac',5091);
        if($cek_biaya==null){
            DB::rollback();
            return redirect()->back()->with('error', "Account Biaya Broker Belum diSetting");
        }
        $cek_hutang = ACPerush::where('id_perush',$id_perush)->where('id_ac',2042);
        if($cek_hutang==null){
            DB::rollback();
            return redirect()->back()->with('error', "Account Hutang Broker Belum diSetting");
        }

        try {
            DB::beginTransaction();

            $asuransi = Asuransi::findOrfail($id);

            $asuransi->id_perush            = $id_perush;
            $asuransi->id_stt               = $request->id_stt;
            $asuransi->no_dm                = $request->no_dm;
            $asuransi->id_pelanggan         = $request->id_pelanggan;
            $asuransi->nm_pengirim          = $request->nm_pengirim;
            $asuransi->id_asal              = $request->id_asal;
            $asuransi->id_tujuan            = $request->id_tujuan;
            $asuransi->tgl_berangkat        = $request->tgl_berangkat;
            $asuransi->tgl_sampai           = $request->tgl_sampai;
            $asuransi->nm_kapal             = $request->nm_kapal;
            $asuransi->no_identity          = $request->no_identity;
            $asuransi->id_tipe_barang       = $request->id_tipe_barang;
            $asuransi->qty                  = $request->qty;
            $asuransi->keterangan           = $request->keterangan;
            $asuransi->broker               = $request->broker;

            // Harga
            $asuransi->harga_pertanggungan  = $request->n_pertanggungan;
            $harga_jual                     = $tarif->harga_jual*$request->n_pertanggungan/100;
            $harga_beli                     = $tarif->harga_beli*$request->n_pertanggungan/100;

            $asuransi->nominal              = $harga_jual;
            $asuransi->premi                = $harga_beli;

            // Account
            $asuransi->ac4_d                = 1020;
            $asuransi->ac4_k                = 4050;
            $asuransi->ac4_h                = 2042;
            $asuransi->ac4_b                = 5091;

            $asuransi->id_user              = Auth::user()->id_user;
            $asuransi->id_status            = 1;

            // dd($asuransi);
            $asuransi->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Gagal Disimpan '.$e->getMessage());
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
        //
    }

    public function handle($id)
    {

        $data["data"] = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $data["ac_perush"] = ACPerush::where("id_perush",Session("perusahaan")["id_perush"])
        ->where(function($q) {
            $q->where('is_bank', true)
            ->orWhere('is_kas', true);
        })->get();
        $data["perusahaan_asuransi"] = TarifAsuransi::with("perusahaan_asuransi")->get();
        $data["perush_asuransi"] = PerusahaanAsuransi::all();
        //dd($data["ac_perush"]);
        if($data["data"]==null){
            return redirect()->back()->with('error', 'Data STT tidak ada');
        }

        return view('operasional::handle',$data);
    }

    public function import()
    {
        $data["data"] = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")->where("is_asuransi",1)->whereNotIn('id_stt',function($query) {

            $query->select('id_stt')->from('t_asuransi');

        })->get();

        //dd($data);
        return view('operasional::import',$data);
    }

    public function updateStatus($id)
    {
        $datanya = Asuransi::findOrFail($id);
        //dd($datanya);

        try {
            DB::beginTransaction();
            $ganti["id_status"] = $datanya->id_status+1;
            Asuransi::where("id_asuransi",$id)->update($ganti);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Asuransi Gagal Disimpan'.$e->getMessage());
        }

        return redirect("asuransistt")->with('success', 'Data Asuransi Diupdate');
    }


    public function list()
    {
        $id_perush      = Session("perusahaan")["id_perush"];
        $data["data"]   = SttModel::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")
        ->where("is_asuransi",1)
        ->where("id_perush_asal",$id_perush)
        ->get();

        return view('operasional::list',$data);
    }


    public function save(Request $request)
    {
        // dd($request->all());
        $id_perush = Session("perusahaan")["id_perush"];
        $tarif  = TarifAsuransi::where('id_perush_asuransi',$request->broker)->get()->first();

        // $group = DB::table('m_layanan as a')
        // ->join('s_grup_layanan_ac_perush as b','a.id_layanan','=','b.id_layanan')
        // ->select('b.*')
        // ->where("a.kode_layanan", "A")
        // ->where("b.id_perush",Session("perusahaan")["id_perush"])->get()->first();
        // if($group==null){
        //     DB::rollback();
        //     return redirect()->back()->with('error', "Setting Group Layanan Asuransi Belum Ada");
        // }

        $ac = ACPerush::where('id_perush',$id_perush);

        if($ac==null){
            DB::rollback();
            return redirect()->back()->with('error', "Master AC Belum Ada");
        }
        $cek_biaya = ACPerush::where('id_perush',$id_perush)->where('id_ac',5091);
        if($cek_biaya==null){
            DB::rollback();
            return redirect()->back()->with('error', "Account Biaya Broker Belum diSetting");
        }
        $cek_hutang = ACPerush::where('id_perush',$id_perush)->where('id_ac',2042);
        if($cek_hutang==null){
            DB::rollback();
            return redirect()->back()->with('error', "Account Hutang Broker Belum diSetting");
        }

        try {
            DB::beginTransaction();

            $asuransi = new Asuransi();
            $asuransi->id_perush            = $id_perush;
            $asuransi->id_stt               = $request->id_stt;
            $asuransi->no_dm                = $request->no_dm;
            $asuransi->id_pelanggan         = $request->id_pelanggan;
            $asuransi->nm_pengirim          = $request->nm_pengirim;
            $asuransi->id_asal              = $request->id_asal;
            $asuransi->id_tujuan            = $request->id_tujuan;
            $asuransi->tgl_berangkat        = $request->tgl_berangkat;
            $asuransi->tgl_sampai           = $request->tgl_sampai;
            $asuransi->nm_kapal             = $request->nm_kapal;
            $asuransi->no_identity          = $request->no_identity;
            $asuransi->id_tipe_barang       = $request->id_tipe_barang;
            $asuransi->qty                  = $request->qty;
            $asuransi->keterangan           = $request->keterangan;
            $asuransi->broker               = $request->broker;

            // Harga
            $asuransi->harga_pertanggungan  = $request->n_pertanggungan;
            $harga_jual                     = $tarif->harga_jual*$request->n_pertanggungan/100;
            $harga_beli                     = $tarif->harga_beli*$request->n_pertanggungan/100;

            $asuransi->nominal              = $harga_jual;
            $asuransi->premi                = $harga_beli;

            // Account
            $asuransi->ac4_d                = 1020;
            $asuransi->ac4_k                = 4050;
            $asuransi->ac4_h                = 2042;
            $asuransi->ac4_b                = 5091;

            $asuransi->id_user              = Auth::user()->id_user;
            $asuransi->id_status            = 1;

            // dd($asuransi);
            $asuransi->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pendapatan Gagal Disimpan '.$e->getMessage());
        }

        return redirect("asuransistt")->with('success', 'Data Asuransi Disimpan');
    }

    public function cetak(Request $request)
    {
        // dd($request->all());
        $id_perush = Session("perusahaan")["id_perush"];

        $datax = Asuransi::where('id_perush',$id_perush);
        if (isset($request->broker) and $request->broker != '') {
            $datax = $datax->where('broker',$request->broker);
        }
        if (isset($request->dr_tgl) and $request->dr_tgl != '') {
            $dr_tgl    = date($request->dr_tgl.' 00:00:00');
            $datax = $datax->where('created_at','>=',$dr_tgl);
        }
        if (isset($request->sp_tgl) and $request->sp_tgl != '') {
            $sp_tgl    = date($request->sp_tgl.' 23:59:59');
            $datax = $datax->where('created_at','<=',$sp_tgl);
            // dd($datax->get(),$request->all());
        }
        $data["data"] = $datax->get();
        return view('operasional::cetakexcel',$data);
    }


}
