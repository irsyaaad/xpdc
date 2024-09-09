<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use DB;
use App\Models\Pelanggan;
use App\Models\Perusahaan;
use App\Models\Layanan;
use App\Models\GroupVendor;
use Modules\Operasional\Entities\CaraBayar;
use App\Models\Module;
use App\Models\Menu;
use App\Models\Karyawan;
use App\Models\Vendor;
use App\Models\Tarif;
use App\Models\User;
use App\Libraries\PHPGangsta_GoogleAuthenticator;
use App\Models\Role;
use Modules\Operasional\Entities\SttModel;
use Modules\Keuangan\Entities\Invoice;
use Modules\Keuangan\Entities\InvoiceHandling;
use Modules\Keuangan\Entities\Pengeluaran;
use Modules\Keuangan\Entities\Pendapatan;
use Modules\Keuangan\Entities\Memorial;
use Modules\Operasional\Entities\StatusStt;
use Modules\Operasional\Entities\Sopir;
use Modules\Operasional\Entities\DaftarMuat;
use Modules\Operasional\Entities\TarifAsuransi;
use Modules\Operasional\Entities\Armada;
use App\Models\PerushArmada;
use Modules\Keuangan\Entities\ACPerush;
use App\Models\RoleUser;
use App\Models\JenisKaryawan;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Tunjangan;
use Modules\Keuangan\Entities\GroupBiaya;
use Session;
use Modules\Operasional\Entities\Handling;
use Modules\Operasional\Entities\TandaTangan;
use Auth;
use Modules\Operasional\Entities\Kapal;

class ChainController extends Controller
{
    public function getwilayah(Request $request)
    {
        $term   = $request->term;
        $data = Wilayah::getKecamatan2($term);
        
        $results = [];
        foreach ($data as $query)
        {
            $results[] = ['kode' => $query->value, 'value' => strtoupper($query->label)];
        }
        
        return response()->json($results);
    }

    public function getRoleUser($id=null)
    {
        $data = RoleUser::getUserRole($id);
        
        return response()->json($data);
    }
    
    public function getKota(Request $request)
    {
        $term   = $request->term;
        $data = Wilayah::getWilayah($term, 2);

        $results = [];

        foreach ($data as $query)
        {
            if($query->kab_id!=null and $query->prov_id!=null and $query->kec_id==null){

                $region = $query->provinsi.", ".$query->kabupaten.", ".$query->nama_wil;

            }else if($query->kab_id==null and $query->prov_id!=null){

                $region = $query->provinsi.", ".$query->nama_wil;

            }else{

                $region  = $query->nama_wil;
            }

            $results[] = ['kode' => $query->id_wil, 'value' => strtoupper($region)];
        }

        return response()->json($results);
    }

    public function getKecamatan(Request $request)
    {
        $term   = $request->term;
        $data = Wilayah::getWilayah($term, 3);

        $results = [];
        foreach ($data as $query)
        {
            if($query->kab_id!=null and $query->prov_id!=null and $query->kec_id==null){

                $region = $query->provinsi.", ".$query->kabupaten.", ".$query->nama_wil;

            }else if($query->kab_id==null and $query->prov_id!=null){

                $region = $query->provinsi.", ".$query->nama_wil;

            }else{

                $region  = $query->nama_wil;
            }

            $results[] = ['kode' => $query->id_wil, 'value' => strtoupper($region)];
        }

        return response()->json($results);
    }

    public function getProvinsi(Request $request)
    {
        $term   = $request->term;
        $data = Wilayah::getWilayah($term, 1);

        $results = [];
        foreach ($data as $query)
        {
            if($query->kab_id!=null and $query->prov_id!=null and $query->kec_id==null){

                $region = $query->provinsi.", ".$query->kabupaten.", ".$query->nama_wil;

            }else if($query->kab_id==null and $query->prov_id!=null){

                $region = $query->provinsi.", ".$query->nama_wil;

            }else{

                $region  = $query->nama_wil;
            }

            $results[] = ['kode' => $query->id_wil, 'value' => strtoupper($region)];
        }

        return response()->json($results);
    }

    public function getPelanggan(Request $request)
    {
        if (is_numeric($request->term)) {
            $term   = $request->term;
        } else {
            $term   = strtolower($request->term);
        }
        // $data   = Pelanggan::select("id_pelanggan", "nm_pelanggan", "telp")->where("nm_pelanggan", 'ILIKE', '%' . $term . '%')->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data   = Pelanggan::select('id_pelanggan', 'nm_pelanggan','telp','id_perush')
                ->where(function ($query) use ($term) {
                     $query->whereRaw('lower(nm_pelanggan) LIKE ? ', ['%' . $term . '%'])
                            ->orWhere('telp','LIKE', '%' . $term . '%');
                    })
                ->where('id_perush', '=', Session("perusahaan")["id_perush"])
                ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_pelanggan, 'value' => strtoupper($value->nm_pelanggan." (".$value->telp.")")];
        }

        return response()->json($results);
    }

    public function getPenerima(Request $request)
    {
        if (is_numeric($request->term)) {
            $term   = $request->term;
        } else {
            $term   = strtolower($request->term);
        }
        // $data   = Pelanggan::select("id_pelanggan", "nm_pelanggan", "telp")->where("nm_pelanggan", 'ILIKE', '%' . $term . '%')->where("id_perush", Session("perusahaan")["id_perush"])->get();
        $data   = SttModel::select('id_stt', 'penerima_nm','penerima_telp')
                ->where(function ($query) use ($term) {
                     $query->whereRaw('lower(penerima_nm) LIKE ? ', ['%' . $term . '%'])
                            ->orWhere('penerima_telp','LIKE', '%' . $term . '%');
                    })
                ->where('id_perush_asal', '=', Session("perusahaan")["id_perush"])
                ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->penerima_nm, 'value' => strtoupper($value->penerima_nm." (".$value->penerima_telp.")")];
        }

        return response()->json($results);
    }

    public function getDetailPelanggan($id = null)
    {
        $data = Pelanggan::with("wilayah")->find($id);

        return response()->json($data);
    }

    public function getPerusahaan(Request $request)
    {
        $term   = $request->term;
        $data   = Perusahaan::select("id_perush", "nm_perush")->where("nm_perush", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_perush, 'value' => strtoupper($value->nm_perush)];
        }

        return response()->json($results);
    }

    public function getPerusahExcept(Request $request)
    {
        $term   = $request->term;
        $data   = Perusahaan::select("id_perush", "nm_perush")->where("nm_perush", 'ILIKE', '%' . $term . '%')->where("id_perush", "!=", Session("perusahaan")["id_perush"])->get();

        $results = [];

        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_perush, 'value' => strtoupper($value->nm_perush)];
        }

        return response()->json($results);
    }

    public function getLayanan(Request $request)
    {
        $term   = $request->term;
        $data   = Layanan::select("id_layanan", "nm_layanan")->where("nm_layanan", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_layanan, 'value' => strtoupper($value->nm_layanan)];
        }

        return response()->json($results);
    }

    public function getGroupVendor(Request $request)
    {
        $term   = $request->term;
        $data   = GroupVendor::select("id_grup_ven", "nm_grup_ven")->where("nm_grup_ven", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_grup_ven, 'value' => strtoupper($value->nm_grup_ven)];
        }

        return response()->json($results);
    }

    public function getCaraBayar(Request $request)
    {
        $term   = $request->term;
        $data   = CaraBayar::select("id_cr_byr_o", "nm_cr_byr_o")->where("nm_cr_byr_o", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_cr_byr_o, 'value' => strtoupper($value->nm_cr_byr_o)];
        }

        return response()->json($results);
    }

    public function getModule(Request $request)
    {
        $term   = $request->term;
        $data   = Module::select("id_module", "nm_module")->where("nm_module", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_module, 'value' => strtoupper($value->nm_module)];
        }

        return response()->json($results);
    }

    public function getMenu(Request $request)
    {
        $term   = $request->term;
        $data   = Menu::select("id_menu", "nm_menu", "level")->where("nm_menu", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_menu, 'value' => strtoupper($value->nm_menu)];
        }

        return response()->json($results);
    }

    public function getMarketing(Request $request)
    {
        $term   = $request->term;
        $data   = Karyawan::select("id_karyawan", "nm_karyawan")->where("id_jenis", 1)->
        where("nm_karyawan", 'ILIKE', '%' . $term . '%')->where("id_perush", Session("perusahaan")["id_perush"])->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_karyawan, 'value' => strtoupper($value->nm_karyawan)];
        }

        return response()->json($results);
    }

    public function getVendor(Request $request)
    {
        $term   = $request->term;
        $data   = Vendor::select("id_ven", "nm_ven")->where("nm_ven", 'ILIKE', '%' . $term . '%')->where("id_perush", Session("perusahaan")["id_perush"])->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ven, 'value' => strtoupper($value->nm_ven)];
        }

        return response()->json($results);
    }

    public function getUser(Request $request)
    {
        $term   = $request->term;
        $data   = User::select("id_user", "nm_user")->where("nm_user", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_user, 'value' => strtoupper($value->nm_user)];
        }

        return response()->json($results);
    }

    public function getRole(Request $request)
    {
        $term   = $request->term;
        $data   = Role::select("id_role", "nm_role")->where("nm_role", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_role, 'value' => strtoupper($value->nm_role)];
        }

        return response()->json($results);
    }

    public function mail()
    {

        return view("MailBorongan");
    }

    public function authenticator()
    {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = "GYSJD7PZ2THNQIUR";

        echo "Secret is: ".$secret."\n\n";

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('LSJ-Express', $secret);

        echo '<img src="'.$qrCodeUrl.'"> <br>';

    }

    public function getTarifAsalTj(Request $request)
    {
        $id_asal = null; $id_tujuan = null;

        if(isset($request->id_asal)){
            $id_asal = $request->id_asal;
        }

        if(isset($request->id_tujuan)){
            $id_tujuan = $request->id_tujuan;
        }

        $data = Tarif::getListTarif($id_asal, $id_tujuan, $request->id_layanan);

        return response()->json($data);
    }

    public function getTarifDetail($id)
    {
        $data = Tarif::findOrFail($id);

        return response()->json($data);
    }

    public function getArmadaGrup(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = DB::table('m_armada_grup')
                ->select('m_armada_grup.id_armd_grup', 'm_armada_grup.nm_armd_grup')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_armd_grup, 'value' => strtoupper($value->nm_armd_grup)];
        }

        return response()->json($results);
    }

    public function getGroupPelanggan(Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_plgn_group')
                ->select('m_plgn_group.id_plgn_group', 'm_plgn_group.nm_group')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_plgn_group, 'value' => strtoupper($value->nm_group)];
        }


        return response()->json($results);
    }

    public function getPerushArmada(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = PerushArmada::select("id_perush_armd", "nm_perush")
                ->where("id_perush", $id_perush)
                ->where("nm_perush", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_perush_armd, 'value' => strtoupper($value->nm_perush)];
        }

        return response()->json($results);
    }

    public function getArmada(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data = Armada::select("id_armada", "nm_armada", "no_plat")
                ->where("id_perush", $id_perush)
                ->where("nm_armada", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_armada, 'value' => strtoupper($value->nm_armada)." ( ".$value->no_plat." )"];
        }

        return response()->json($results);
    }

    public function getKapalPerush(Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_kapal_perush')
                ->select('m_kapal_perush.id_kapal_perush', 'm_kapal_perush.nm_kapal_perush')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_kapal_perush, 'value' => strtoupper($value->nm_kapal_perush)];
        }

        return response()->json($results);
    }

    public function getKelompok(Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_armada_grup')
                ->select('m_armada_grup.id_armd_grup', 'm_armada_grup.nm_armd_grup')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_armd_grup, 'value' => strtoupper($value->nm_armd_grup)];
        }

        return response()->json($results);
    }

    public function getAC1($id = null, Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',1)
                ->get();

        if(isset($id) and $id!=null){
            $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',1)
                ->where('jenis', $id)
                ->get();
        }

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function getAC2(Request $request, $id)
    {
        $term   = $request->term;
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',2)
                ->where('id_parent',$id)
                ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function ACLev2(Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',2)
                ->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function ACLev3(Request $request)
    {
        $term   = $request->term;
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',3)
                ->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function getAC3(Request $request, $id)
    {
        $term   = $request->term;
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',3)
                ->where('id_parent',$id)
                ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function getAC4($id)
    {
        $data   = DB::table('m_ac')
                ->select('m_ac.id_ac', 'm_ac.nama')
                ->where('level',4)
                ->where('id_parent', $id)
                ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function getSttPerush(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = SttModel::select("id_stt","no_awb", "kode_stt")->where("kode_stt", 'LIKE', '%' . strtoupper($term) . '%')->where("id_perush_asal", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->kode_stt)];
        }

        return response()->json($results);
    }

    public function getAwb(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = SttModel::select("no_awb")
        ->whereNotNull("no_awb")
        ->where("no_awb", 'ILIKE', '%' . $term . '%')
        ->where("id_perush_asal", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->no_awb, 'value' => strtoupper($value->no_awb)];
        }
        
        return response()->json($results);
    }

    public function getStt(Request $request)
    {
        $term   = $request->term;
        $data   = SttModel::select("id_stt","no_awb", "kode_stt","pengirim_nm")->where("id_stt", 'ILIKE', '%' . $term . '%')->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->kode_stt)];
        }

        return response()->json($results);
    }

    public function Invoice(Request $request)
    {
        $term   = $request->term;
        $data   = Invoice::select("id_invoice","kode_invoice")
                ->where("id_perush",Session("perusahaan")["id_perush"])
                ->where("kode_invoice", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_invoice, 'value' => strtoupper($value->kode_invoice)];
        }

        return response()->json($results);
    }

    public function getInvoice(Request $request,$id)
    {
        $term   = $request->term;
        $data   = Invoice::getAktif()->where("id_perush",$id);
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_invoice, 'value' => $value->nm_status, 'perush' => $value->id_perush];
        }

        return response()->json($results);
    }

    public function getPengeluaran(Request $request)
    {
        $term   = $request->term;
        $data   = Pengeluaran::select("id_pengeluaran","kode_pengeluaran")->where("kode_pengeluaran", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_pengeluaran, 'value' => strtoupper($value->kode_pengeluaran)];
        }

        return response()->json($results);
    }

    public function getPendapatan(Request $request)
    {
        $term   = $request->term;
        $data   = Pendapatan::select("id_pendapatan","kode_pendapatan")->where("kode_pendapatan", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_pendapatan, 'value' => strtoupper($value->kode_pendapatan)];
        }

        return response()->json($results);
    }

    public function getMemorial(Request $request)
    {
        $term   = $request->term;
        $data   = Memorial::select("id_memorial","kode_memorial")->where("kode_memorial", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_memorial, 'value' => strtoupper($value->kode_memorial)];
        }

        return response()->json($results);
    }

    public function getStatusStt(Request $request)
    {
        $data   = StatusStt::all();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ord_stt_stat, 'value' => strtoupper($value->nm_ord_stt_stat)];
        }

        return response()->json($results);
    }
    public function getInvoiceHandling(Request $request)
    {
        $data   = InvoiceHandling::getDataInvoiceHandling(Session("perusahaan")["id_perush"]);
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_invoice, 'value' => $value->kode_invoice];
        }

        return response()->json($results);
    }

    public function getInvoiceHandlingtj(Request $request)
    {
        $data   = InvoiceHandling::getDataInvoiceHandlingtj(Session("perusahaan")["id_perush"]);
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_invoice, 'value' => $value->kode_invoice];
        }

        return response()->json($results);
    }

    public function getACPerush(Request $request)
    {
        $term   = $request->term;
        $data   = ACPerush::select("id_ac","nama")->where("nama", 'ILIKE', '%' . $term . '%')->where("id_perush",Session("perusahaan")["id_perush"])->get();
        
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }

    public function getKasBank(Request $request)
    {
        $term   = $request->term;
        $data   = ACPerush::select("id_ac","nama")->where("nama", 'ILIKE', '%' . $term . '%')
        ->where("is_kas",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])
        ->orWhere("is_bank",true)
        ->where("id_perush",Session("perusahaan")["id_perush"])
        ->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_ac, 'value' => strtoupper($value->nama)];
        }

        return response()->json($results);
    }


    public function getKaryawan($id = null)
    {
        $data   = Karyawan::select("id_karyawan","nm_karyawan")->where("id_perush", $id)->orderBy("nm_karyawan", "asc")->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_karyawan, 'nama' => strtoupper($value->nm_karyawan)];
        }

        return response()->json($results);
    }

    public function getKapal(Request $request)
    {
        $term   = $request->term;
        $data   = Kapal::select("id_kapal", "nm_kapal")->where("nm_kapal", 'ILIKE', '%' . $term . '%')->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_kapal, 'value' => strtoupper($value->nm_kapal)];
        }

        return response()->json($results);
    }

    public function getOpsTerima($id = null)
    {
        $sql = "select u.id_user,u.nm_user from role r
        join role_user s on r.id_role=s.id_role
        join users u on s.id_user=u.id_user
        where s.id_perush='".$id."' and lower(r.nm_role)='operasional' order by s.id_ru asc limit 1;";

        $data = DB::select($sql);

        return response()->json($data);
    }

    public function getSopir(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = Sopir::select("id_sopir","nm_sopir")->where("nm_sopir", 'ILIKE', '%' . $term . '%')
                    ->where("id_perush", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_sopir, 'value' => strtoupper($value->nm_sopir)];
        }

        return response()->json($results);
    }

    public function getDM(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = DaftarMuat::select("id_dm")->where("id_dm", 'ILIKE', '%' . $term . '%')->where("id_perush_dr", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->id_dm)];
        }

        return response()->json($results);
    }

    public function getKaryawan1(Request $request)
    {
        $term   = $request->term;
        $data   = Karyawan::select("id_karyawan", "nm_karyawan")->where("nm_karyawan", 'ILIKE', '%' . $term . '%')->where("id_perush", Session("perusahaan")["id_perush"])->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_karyawan, 'value' => strtoupper($value->nm_karyawan)];
        }

        return response()->json($results);
    }
    public function getIjin(Request $request)
    {
        $term   = $request->term;
        $data   = JenisPerijinan::select("id_jenis", "nm_jenis")->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_jenis, 'value' => strtoupper($value->nm_jenis)];
        }

        return response()->json($results);
    }
    public function getJenisKaryawan(Request $request)
    {
        $term   = $request->term;
        $data   = JenisKaryawan::select("id_jenis", "nm_jenis")->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_jenis, 'value' => strtoupper($value->nm_jenis)];
        }

        return response()->json($results);
    }
    public function NominalTunjangan($id = null)
    {
        $data = Tunjangan::with("user")->find($id);

        return response()->json($data);
    }

    public function getKaryawanPerush($id = null)
    {
        $data   = Karyawan::select("id_karyawan","nm_karyawan")->where("id_perush", $id)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_karyawan, 'nama' => strtoupper($value->nm_karyawan)];
        }

        return response()->json($results);
    }

    public function getHargaAsuransi(Request $request)
    {
        $id_perush = null; $id_jenis = null;

        if(isset($request->id_perush)){
            $id_perush = $request->id_perush;
        }

        if(isset($request->id_jenis)){
            $id_jenis = $request->id_jenis;
        }

        $data = TarifAsuransi::all();
        $data = $data->where("id_perush_asuransi",$request->id_perush);
        $data = $data->where("jenis_asuransi",$request->id_jenis);

        $results = [];
        foreach ($data as $value) {
            $results[] = [
                'kode' => $value->id_tarif,
                'nama' => $value->id_perush_asuransi,
                'min_harga' => $value->min_harga_pertanggungan,
                'harga_jual' => $value->harga_jual,
                'harga_beli' => $value->harga_beli
            ];
        }

        return response()->json($results);
    }
    public function getKoli($id)
    {
        $data   = SttModel::getKoli($id)->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'id_koli' => $value->id_koli];
        }

        return response()->json($results);
    }

    public function getGroupBiaya(Request $request)
    {
        $term   = $request->term;
        $data   = GroupBiaya::select("id_biaya_grup", "nm_biaya_grup")->where("nm_biaya_grup", 'ILIKE', '%' . $term . '%')->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_biaya_grup, 'value' => strtoupper($value->nm_biaya_grup)];
        }

        return response()->json($results);
    }

    public function getSettingBiaya(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $term   = $request->term;
        $data   = DB::table('s_biaya_grup_ac_perush')
                ->join("m_ac_perush as a", "a.id_ac", "=", "s_biaya_grup_ac_perush.id_ac_hutang")
                ->join("m_biaya_grup as b", "b.id_biaya_grup", "=", "s_biaya_grup_ac_perush.id_biaya_grup")
                ->where("a.id_perush", $id_perush)
                ->where("b.nm_biaya_grup", 'ILIKE', '%' . $term . '%')
                ->groupBy("a.id_ac", "a.nama", "b.nm_biaya_grup", "b.id_biaya_grup")
                ->select("a.id_ac", "a.nama as nm_ac", "b.nm_biaya_grup", "b.id_biaya_grup")->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_biaya_grup, 'value' => strtoupper($value->nm_biaya_grup)];
        }

        return response()->json($results);
    }

    public function getDetailStt($id)
    {
        $data = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $results = '
        <table class="table table-sm">
                <tr>
                    <td>ID Stt</th>
                    <td>:</th>
                    <td>'.$data->id_stt.'</th>
                </tr>
                <tr>
                    <td>Kode Stt</th>
                    <td>:</th>
                    <td>'.$data->kode_stt.'</th>
                </tr>
                <tr>
                    <td>No Awb</th>
                    <td>:</th>
                    <td>'.$data->no_awb.'</th>
                </tr>
                <tr>
                    <td>Tgl Masuk</th>
                    <td>:</th>
                    <td>'.daydate($data->tgl_masuk).', '.dateindo($data->tgl_masuk).'</th>
                </tr>
                <tr>
                    <td>Tgl Keluar</th>
                    <td>:</th>
                    <td>'.daydate($data->tgl_keluar).', '.dateindo($data->tgl_keluar).'</th>
                </tr>
                <tr>
                    <td>Layanan</th>
                    <td>:</th>
                    <td>'.$data->layanan->nm_layanan.'</th>
                </tr>
                <tr>
                    <td>Asal</th>
                    <td>:</th>
                    <td>'.$data->asal->nama_wil.' - '.$data->pengirim_alm.'</th>
                </tr>
                <tr>
                    <td>Tujuan</th>
                    <td>:</th>
                    <td>'.$data->tujuan->nama_wil.' - '.$data->penerima_alm.'</th>
                </tr>
                <tr>
                    <td>Tipe Kirim</th>
                    <td>:</th>
                    <td>'.$data->tipekirim->nm_tipe_kirim.'</th>
                </tr>
        </table>
        ';
        return response()->json($results);
    }

    public function getSttterima(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $sql = "select o.id_stt, o.kode_stt from t_order o
        join t_order_dm d on o.id_stt = d.id_stt
        join t_dm m on d.id_dm = m.id_dm
        where m.id_perush_tj = '".$id_perush."' and lower(o.kode_stt ) like lower('%".$term."%');";

        $data = DB::select($sql);

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_stt, 'value' => strtoupper($value->kode_stt)];
        }

        return response()->json($results);
    }

    public function detailstt($id)
    {
        $data = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $nama_layanan = null;
        $status_bayar = null;
        if (isset($data->layanan->nm_layanan)) {
            $nama_layanan = $data->layanan->nm_layanan;
        }
        if ($data->is_bayar) {
            $status_bayar =  "Sudah Bayar";
        } else {
            $status_bayar =  "Belum Bayar";
        }

        $results = '
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-4">No Stt : </label>
                        <div class="col-8">'.$data->kode_stt.'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Tgl Masuk : </label>
                        <div class="col-8">'.dateindo($data->tgl_masuk).'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">No Stt : </label>
                        <div class="col-8">'.$nama_layanan.'</div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-xs-6">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-4">No AWB : </label>
                        <div class="col-8">'.$data->no_awb.'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Tgl Keluar : </label>
                        <div class="col-8">'.dateindo($data->tgl_keluar).'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Status Bayar : </label>
                        <div class="col-8">'.$status_bayar.'</div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-6 card">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-12" style="background-color: #eee; padding : 5px;"><b>Pengirim : </b></label>
                        <div class="col-8">'.$data->pengirim_alm.'</div>
                    </div>

                </form>
            </div>

            <div class="col-md-6 col-xs-6 card">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-12" style="background-color: #eee; padding : 5px;"><b>Penerima : </b></label>
                        <div class="col-8">'.$data->penerima_alm.'</div>
                    </div>

                </form>
            </div>
        </div>


        ';
        return response()->json($results);
    }

    public function detaildm($id)
    {
        $data = SttModel::with("layanan", "asal", "marketing", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "tujuan", "packing", "cara", "status")->where("id_stt", $id)->get()->first();
        $nama_layanan = null;
        $status_bayar = null;
        if (isset($data->layanan->nm_layanan)) {
            $nama_layanan = $data->layanan->nm_layanan;
        }
        if ($data->is_bayar) {
            $status_bayar =  "Sudah Bayar";
        } else {
            $status_bayar =  "Belum Bayar";
        }

        $results = '
        <div class="row">
            <div class="col-md-6 col-xs-6">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-4">No Stt : </label>
                        <div class="col-8">'.$data->kode_stt.'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Tgl Masuk : </label>
                        <div class="col-8">'.dateindo($data->tgl_masuk).'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">No Stt : </label>
                        <div class="col-8">'.$nama_layanan.'</div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-xs-6">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-4">No AWB : </label>
                        <div class="col-8">'.$data->no_awb.'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Tgl Keluar : </label>
                        <div class="col-8">'.dateindo($data->tgl_keluar).'</div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-4">Status Bayar : </label>
                        <div class="col-8">'.$status_bayar.'</div>
                    </div>
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-xs-6 card">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-12" style="background-color: #eee; padding : 5px;"><b>Penerima : </b></label>
                        <div class="col-8">'.$data->pengirim_alm.'</div>
                    </div>

                </form>
            </div>

            <div class="col-md-6 col-xs-6 card">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="control-label col-12" style="background-color: #eee; padding : 5px;"><b>Penerima : </b></label>
                        <div class="col-8">'.$data->penerima_alm.'</div>
                    </div>

                </form>
            </div>
        </div>


        ';
        return response()->json($results);
    }

    public function getDmHandling(Request $request)
    {
        $term   = $request->term;
        $id_perush = Session("perusahaan")["id_perush"];
        $data   = Handling::select("id_handling", "kode_handling")->where(DB::raw("lower(kode_handling)"), 'ILIKE', '%' . $term . '%')
                ->where("id_perush", $id_perush)->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_handling, 'value' => strtoupper($value->kode_handling)];
        }

        return response()->json($results);
    }

    public function getDaftarMuat(Request $request)
    {
        $term   = $request->term;
        $data   = DaftarMuat::select("id_dm","kode_dm")->where("kode_dm", 'ILIKE', '%' . $term . '%')->where("id_perush_dr",Session("perusahaan")["id_perush"])->get();

        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_dm, 'value' => strtoupper($value->kode_dm)];
        }

        return response()->json($results);
    }

    public function generatettd($id)
    {
        $ttd = TandaTangan::findOrFail($id);
        $id = $ttd->ttd;
        $base = "data:image/gif;base64,".$id;
        $imgstr = 'image/png;base64,'.$id;
        $new_data=explode(";",$imgstr);
        $type=$new_data[0];
        $data=explode(",",$new_data[1]);
        header("Content-type:".$type);
        echo base64_decode($data[1]);
    }

    public function savettd(Request $request)
    {
        try {
            DB::beginTransaction();

            $ttd                    = new TandaTangan();
            $ttd->id_ref            = $request->id_ref;
            $ttd->type_dok          = $request->type;
            $ttd->id_user           = Auth::user()->id_user;
            $ttd->ttd               = $request->img;
            $ttd->level             = $request->level;
            $ttd->id_perush         = Session("perusahaan")["id_perush"];

            // dd($ttd);
            $ttd->save();

            DB::commit();

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
        $data = [
            'status' => true,
            'data'   => $ttd,
            'url'    => $request->url,
        ];
        return response()->json($data);
    }

    public function createttd(Request $request)
    {
        $data['data'] = $request;
        // dd($data);
        return view('ttd',$data);
    }

    public function DataProvinsi(Request $request)
    {
        $data   = Wilayah::select("id_wil","nama_wil")->where('level_wil',1)->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_wil, 'value' => strtoupper($value->nama_wil)];
        }

        return response()->json($results);
    }

    public function DataKabupaten(Request $request)
    {
        $data   = Wilayah::select("id_wil","nama_wil")->where('level_wil',2)->get();
        $results = [];
        foreach ($data as $value) {
            $results[] = ['kode' => $value->id_wil, 'value' => strtoupper($value->nama_wil)];
        }

        return response()->json($results);
    }

    public function getTarifAsuransi($id)
    {
        $data = TarifAsuransi::where('id_perush_asuransi',$id)->get()->first();
        return response()->json($data);
    }

    public function GetGajiKaryawan($id)
    {
        $data = JenisKaryawan::findOrFail($id);
        return response()->json($data);
    }
}
