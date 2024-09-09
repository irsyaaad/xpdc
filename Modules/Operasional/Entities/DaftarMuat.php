<?php

namespace Modules\Operasional\Entities;

use App\Models\Layanan;
use App\Models\Perusahaan;
use App\Models\RoleUser;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Operasional\Entities\GenerateDm;

class DaftarMuat extends Model
{
    protected $fillable = [];
    protected $table = "t_dm";
    // public $incrementing = false;
    protected $primaryKey = 'id_dm';
    //public $keyType = 'string';

    public function perush_asal()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_dr', 'id_perush');
    }

    public function perush_tujuan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tj', 'id_perush');
    }

    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
    }

    public function kapal()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Kapal', 'id_kapal', 'id_kapal');
    }

    public function sopir()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Sopir', 'id_sopir', 'id_sopir');
    }

    public function armada()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Armada', 'id_armada', 'id_armada');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Operasional\Entities\StatusDM', 'id_status', 'id_status');
    }

    public function dmtiba()
    {
        return $this->belongsTo('Modules\Operasional\Entities\DMTiba', 'id_dm', 'id_dm');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'id_ven', 'id_ven');
    }

    public function wilayah()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_wil_asal', 'id_wil');
    }

    public function wilayah_tujuan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_wil_tujuan', 'id_wil');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public static function getNoConatiner()
    {
        $sql = DB::raw("select no_container from t_dm where no_container is not null");

        return DB::select($sql);
    }

    public static function generateIdVendor($kode_perush, $id_ven)
    {
        $date = date("ym");
        $dm = GenerateDm::where("id_ven", $id_ven)
            ->where("date_origin", $date)
            ->where("kode_perush", $kode_perush)
            ->get()->first();

        $id_dm = "";
        if ($dm == null) {

            $id_dm = "DMV" . strtoupper($id_ven) . date("ym") . "001";

            $dm = new GenerateDm();
            $dm->id_dm = $id_dm;
            $dm->id_ven = $id_ven;
            $dm->kode_perush = $kode_perush;
            $dm->date_origin = date("ym");
            $dm->last_id = "001";
            $dm->save();

        } else {

            $special = substr_count($dm->last_id, "0");
            $count = ($dm->last_id + 1);

            if ($special == 2) {
                $dm->last_id = "00" . $count;
            } elseif ($special == 1) {
                $dm->last_id = "0" . $count;
            } else {
                $dm->last_id = $count;
            }

            $id_dm = "DMV" . strtoupper($id_ven) . date("ym") . $dm->last_id;

            $a_dm = [];
            $a_dm["id_dm"] = $id_dm;
            $a_dm["last_id"] = $dm->last_id;

            //dd($a_dm);
            $dam = GenerateDm::where("id_gen", $dm->id_gen)->update($a_dm);
        }

        return $id_dm;
    }

    public static function generateId($id_layanan, $kode_perush)
    {
        $role = RoleUser::where("id_user", Auth::user()->id_user)->get()->first();
        $perush = Perusahaan::findorFail($role->id_perush);
        $layanan = Layanan::findorFail($id_layanan);
        $date = date("ym");
        $id = strtoupper($perush->id_perush . $layanan->kode_layanan . $date);

        $dm = GenerateDm::select("id_dm", "last_id", "id_gen")
            ->where("kode_layanan", $layanan->kode_layanan)
            ->where("date_origin", $date)
            ->where("kode_perush", $kode_perush)
            ->get()->first();

        $id_dm = "";
        if ($dm == null) {
            $id_dm = "DM" . $layanan->kode_layanan . strtoupper($kode_perush) . date("ym") . "001";

            $gen = new GenerateDm();
            $gen->id_dm = $id_dm;
            $gen->kode_perush = $kode_perush;
            $gen->date_origin = date("ym");
            $gen->kode_layanan = $layanan->kode_layanan;
            $gen->last_id = "001";
            $gen->save();

        } else {
            $special = substr_count($dm->last_id, "0");
            $count = ($dm->last_id + 1);

            if ($special == 2) {
                $dm->last_id = "00" . $count;
            } elseif ($special == 1) {
                $dm->last_id = "0" . $count;
            } else {
                $dm->last_id = $count;
            }
            //
            $id_dm = "DM" . $layanan->kode_layanan . strtoupper($kode_perush) . date("ym") . $dm->last_id;

            $a_dm = [];
            $a_dm["id_dm"] = $id_dm;
            $a_dm["last_id"] = $dm->last_id;

            //dd($a_dm);
            $dam = GenerateDm::where("id_gen", $dm->id_gen)->update($a_dm);
        }

        return $id_dm;
    }

    public static function getDm()
    {
        $data = [];
        if (get_admin()) {

            $data = self::with("dmtiba", "perush_tujuan", "perush_asal", "layanan", "status")->whereNull("id_ven");
        } else {

            $data = self::with("dmtiba", "perush_tujuan", "perush_asal", "layanan", "status")->whereNull("id_ven")->where("id_perush_tj", Session("perusahaan")["id_perush"]);
        }

        return $data;
    }

    public static function getDmTiba($page, $perpage, $id_perush, $id_perush_tj = null, $id_dm = null, $id_layanan = null, $tgl_awal = null, $tgl_akhir = null, $id_status = null, $action = null)
    {
        $sql = "select d.id_dm, d.kode_dm,k.stt,d.is_vendor, d.id_ven, l.id_layanan,l.nm_layanan,d.tgl_berangkat, d.ata, d.atd, d.tgl_sampai,r.nm_sopir, a.nm_armada, d.id_status, s.nm_status, p.nm_perush as perush_asal,
        n.nm_perush as perush_tujuan, i.total_berat, i.total_volume, i.total_kubik, i.total_koli, i.total_stt
        from t_dm d
        join (
            SELECT 
                    A.id_dm,
                    SUM( b.n_berat ) AS total_berat,
                    SUM( b.n_volume ) AS total_volume,
                    SUM( b.n_kubik ) AS total_kubik,
                    SUM( b.n_koli ) AS total_koli,
                    COUNT( b.id_stt ) AS total_stt
            FROM
                    t_order_dm
                    AS A LEFT JOIN t_order AS b ON A.id_stt = b.id_stt
                    join t_dm as c on A.id_dm = c.id_dm
            GROUP BY
                    A.id_dm
        ) as i on i.id_dm = d.id_dm
        left join (
            select count(id_stt) as stt,id_dm from t_order_dm group by id_dm
        ) as k on d.id_dm = k.id_dm
        join m_layanan l on d.id_layanan = l.id_layanan
        join s_perusahaan p on p.id_perush = d.id_perush_dr
        join s_perusahaan n on n.id_perush = d.id_perush_tj
        join m_status_dm s on s.id_status = d.id_status
        left join m_armada a on d.id_armada = a.id_armada
        left join m_sopir r on r.id_sopir = d.id_sopir where d.id_perush_tj = '" . $id_perush . "' ";

        if ($id_perush_tj != null) {
            $sql .= " and d.id_perush_dr = '" . $id_perush_tj . "' ";
        }

        if ($id_dm != null) {
            $sql .= " and d.id_dm = '" . $id_dm . "' ";
        }

        if ($id_layanan != null) {
            $sql .= " and d.id_layanan = '" . $id_layanan . "' ";
        }

        if ($id_status != null) {
            $sql .= " and d.id_status = '" . $id_status . "' ";
        }

        if ($action != null) {
            if ($action == 1) {
                $sql .= " and d.id_status < '4' ";
            }
            if ($action == 2) {
                $sql .= " and d.id_status > '3' ";
            }

        }

        if ($tgl_awal != null) {
            $sql .= " and d.ata >= '" . $tgl_awal . "' ";
        }

        if ($tgl_akhir != null) {
            $sql .= " and d.ata <= '" . $tgl_akhir . "' ";
        }

        $sql .= " order by d.tgl_berangkat desc ";

        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getTotalKoli($id_dm)
    {
        $sql = "select d.id_dm,o.id_stt,o.n_koli,k.koli, t.no_awb,t.id_status,d.id_status as status_dm,d.id_kapal, t.pengirim_nm from t_dm d
        join t_order_dm o on d.id_dm=o.id_dm join
        ( select count(id_koli) as koli,id_stt from t_dm_koli group by id_stt)
        as k on o.id_stt=k.id_stt join t_order t on o.id_stt=t.id_stt where d.id_dm='" . $id_dm . "'";

        $data = DB::select($sql);

        $a_data = [];
        foreach ($data as $key => $value) {
            $a_data[$key] = $value;
        }

        return $a_data;
    }

    public static function getList($id_layanan = null)
    {
        $data = [];
        $data = self::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada", "status", "layanan")
            ->where("is_vendor", null)
            ->where("id_layanan", $id_layanan)
            ->where("id_perush_dr", Session("perusahaan")["id_perush"])
            ->where("id_perush_tj", "!=", null)
            ->orderBy("id_dm", "asc");

        return $data;
    }

    public static function getDmVendor($id_perush, $is_vendor = null, $id_dm = null, $id_layanan = null, $id_perush_tj = null, $id_ven = null, $id_asal = null, $id_tujuan = null, $tgl_berangkat = null, $tgl_sampai = null, $id_status = null, $id_stt = null)
    {
        $data = self::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada", "status", "layanan")
            ->select("t_dm.*", "s.nm_status", "r.stt")
            ->join("m_status_dm as s", "t_dm.id_status", "s.id_status")
            ->leftjoin(DB::raw("( select count(id_stt) as stt,id_dm from t_order_dm group by id_dm ) as r"), "t_dm.id_dm", "r.id_dm")
            ->leftjoin("t_order_dm as odm", "t_dm.id_dm", "odm.id_dm")
            ->where("t_dm.is_vendor", $is_vendor)
            ->where("t_dm.id_perush_dr", $id_perush);

        if ($id_dm != null) {
            $data = $data->where("t_dm.id_dm", $id_dm);
        }

        if ($id_layanan != null) {
            $data = $data->where("t_dm.id_layanan", $id_layanan);
        }

        if ($id_perush_tj != null) {
            $data = $data->where("t_dm.id_perush_tj", $id_perush_tj);
        }

        if ($id_ven != null) {
            $data = $data->where("t_dm.id_ven", $id_ven);
        }

        if ($id_asal != null) {
            $data = $data->where("t_dm.id_wil_asal", $id_asal);
        }

        if ($id_tujuan != null) {
            $data = $data->where("t_dm.id_wil_tujuan", $id_tujuan);
        }

        if ($tgl_berangkat != null) {
            $data = $data->where("t_dm.tgl_berangkat", '>=', $tgl_berangkat);
        }

        if ($tgl_sampai != null) {
            $data = $data->where("t_dm.tgl_sampai", '<=', $tgl_sampai);
        }

        if ($id_status != null) {
            $data = $data->where("t_dm.id_status", $id_status);
        }

        if ($id_stt != null) {
            $data = $data->where("odm.id_stt", $id_stt);
        }

        $data = $data->groupBy('t_dm.id_dm', 's.nm_status', 'r.stt')
            ->orderBy("t_dm.tgl_berangkat", "desc");

        return $data;
    }

    public static function getFilter($page, $perpage, $id_perush, $id_dm = null, $is_vendor = null, $id_layanan = null, $id_perush_tj = null, $id_sopir = null, $id_armada = null, $tgl_berangkat = null, $tgl_sampai = null, $id_status = null, $is_kota = null, $action = null, $id_stt = null)
    {
        $sql = "select d.id_dm, d.kode_dm,a.nm_armada,d.id_status,r.stt, p.nm_sopir,s.nm_status,wa.nama_wil as wil_asal, wt.nama_wil as wil_tujuan,pa.nm_perush as perush_asal, pt.nm_perush as perush_tj,
        d.tgl_berangkat,d.created_at,d.tgl_sampai,d.ata,d.atd,d.nm_pj_dr, d.nm_pj_tuju,d.no_container, d.no_seal
        from t_dm d
        join s_perusahaan pa on pa.id_perush = d.id_perush_dr
        join m_status_dm s on s.id_status = d.id_status
        join m_wilayah wa on d.id_wil_asal = wa.id_wil
				left join (
					select count(id_stt) as stt,id_dm from t_order_dm group by id_dm
				) as r on d.id_dm = r.id_dm
        left join m_wilayah wt on d.id_wil_tujuan = wt.id_wil
        left join s_perusahaan pt on pt.id_perush = d.id_perush_tj
        left join m_armada a on a.id_armada = d.id_armada
        left join m_sopir p on p.id_sopir = d.id_sopir
        left join t_order_dm as odm on d.id_dm = odm.id_dm
        where d.id_perush_dr = '" . $id_perush . "' and d.is_vendor ='" . $is_vendor . "' ";

        if ($id_dm != null) {
            $sql .= " and d.id_dm = '" . $id_dm . "' ";
        }

        if ($id_perush_tj != null) {
            $sql .= " and d.id_perush_tj = '" . $id_perush_tj . "' ";
        }

        if ($id_layanan != null) {
            $sql .= " and d.id_layanan = '" . $id_layanan . "' ";
        }

        if ($id_sopir != null) {
            $sql .= " and d.id_sopir = '" . $id_sopir . "' ";
        }

        if ($id_armada != null) {
            $sql .= " and d.id_armada = '" . $id_armada . "' ";
        }

        if ($tgl_berangkat != null) {
            $sql .= " and CAST(d.atd AS Date) = '" . $tgl_berangkat . "' ";
        }

        if ($tgl_sampai != null) {
            $sql .= " and CAST(d.ata AS Date) = '" . $tgl_sampai . "' ";
        }

        if ($id_status != null) {
            $sql .= " and d.id_status = '" . $id_status . "' ";
        }

        if ($id_stt != null) {
            $sql .= " and odm.id_stt = '" . $id_stt . "' ";
        }

        if ($action != null) {
            if ($action == 1) {
                $sql .= " and d.id_status < '4' ";
            }
            if ($action == 2) {
                $sql .= " and d.id_status > '3' ";
            }

        }
        $sql .= " group by
        d.id_dm, a.nm_armada, r.stt, p.nm_sopir, s.nm_status, wa.nama_wil, wt.nama_wil, pa.nm_perush, pt.nm_perush
        order by d.tgl_berangkat desc";
        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getListBiaya()
    {
        $data = [];
        if (get_admin()) {
            $data = self::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada", "status", "layanan", "vendor")
                ->orderBy("created_at", "DESC");

        } else {
            $data = self::with("perush_asal", "perush_tujuan", "kapal", "sopir", "armada", "status", "layanan", "vendor")
                ->where("id_perush_dr", Session("perusahaan")["id_perush"])
                ->orderBy("t_dm.created_at", "DESC");
        }

        return $data;
    }

    public static function getBiayaHpp($page, $perpage, $id_dm = null, $is_ven = null, $id_stt = null, $id_perush = null, $f_perushtj = null, $f_id_ven = null, $dr_tgl = null, $sp_tgl = null, $f_no = null)
    {
        $sql = "select distinct y.n_bayar,b.nominal,d.c_total,b.nominal as c_pro,
        y.n_bayar as bayar,d.id_dm,d.kode_dm,d.tgl_berangkat,p.nm_perush,v.nm_ven,l.nm_layanan from t_dm d
        left join (
            select COALESCE(sum(nominal), 0) as nominal,id_dm from t_dm_biaya group by id_dm
        ) as b on d.id_dm = b.id_dm
        left join (
            select COALESCE(sum(n_bayar), 0) as n_bayar,id_dm from t_dm_biaya_bayar group by id_dm
        ) as y on y.id_dm = d.id_dm
        left join (
            select id_stt,id_dm from t_order_dm
        ) t on t.id_dm = d.id_dm
        join m_layanan l on l.id_layanan=d.id_layanan
        join s_perusahaan p on p.id_perush = d.id_perush_dr
        join m_vendor v on v.id_ven = d.id_ven
        where d.created_at is not null ";

        if ($id_dm != null) {
            $sql .= " and d.id_dm ='" . $id_dm . "' ";
        }

        if ($id_stt != null) {
            $sql .= " and t.id_stt ='" . $id_stt . "' ";
        }

        if ($id_perush != null) {
            $sql .= " and d.id_perush_dr ='" . $id_perush . "' ";
        }

        if ($f_perushtj != null) {
            $sql .= " and d.id_perush_tj ='" . $f_perushtj . "' ";
        }

        if ($f_perushtj != null) {
            $sql .= " and t.f_perushtj ='" . $f_perushtj . "' ";
        }

        if ($is_ven != null) {
            $sql .= " and d.is_vendor ='" . $is_ven . "' ";
        }

        if ($f_id_ven != null) {
            $sql .= " and d.id_ven ='" . $f_id_ven . "' ";
        }

        if ($dr_tgl != null) {
            $sql .= " and d.tgl_berangkat >= '" . $dr_tgl . "' ";
        }

        if ($sp_tgl != null) {
            $sql .= " and d.tgl_berangkat <= '" . $sp_tgl . "' ";
        }

        if ($f_no != null) {
            $sql .= " and d.no_container = '" . $f_no . "' ";
        }

        $sql .= " ORDER BY d.id_dm asc";

        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getDmKota($page, $perpage, $id_perush, $id_dm = null, $id_wil_tujuan = null, $id_sopir = null, $id_armada = null, $dr_tgl = null, $sp_tgl = null, $id_status = null)
    {
        $sql = "select d.id_dm, d.kode_dm,a.nm_armada,r.stt, d.id_status, p.nm_sopir,s.nm_status,w.nama_wil,d.tgl_berangkat,d.created_at from t_dm d
        join m_wilayah w on d.id_wil_tujuan = w.id_wil
        join m_status_dm s on s.id_status = d.id_status
        join m_armada a on a.id_armada = d.id_armada
        join m_sopir p on p.id_sopir = d.id_sopir
        left join (
            select count(id_stt) as stt,id_dm from t_order_dm group by id_dm
        ) as r on d.id_dm = r.id_dm
        where d.id_perush_dr = '" . $id_perush . "' and
        d.id_perush_tj is null and d.id_ven is null";

        if ($id_dm != null) {
            $sql .= " and  d.id_dm = '" . $id_dm . "' ";
        }

        if ($id_wil_tujuan != null) {
            $sql .= " and  d.id_wil_tujuan = '" . $id_wil_tujuan . "' ";
        }

        if ($id_sopir != null) {
            $sql .= " and  d.id_sopir = '" . $id_sopir . "' ";
        }

        if ($id_armada != null) {
            $sql .= " and  d.id_armada = '" . $id_armada . "' ";
        }

        if ($dr_tgl != null) {
            $sql .= " and CAST(d.atd AS Date) >= '" . $dr_tgl . "' ";
        }

        if ($sp_tgl != null) {
            $sql .= " and CAST(d.atd AS Date) <= '" . $sp_tgl . "' ";
        }

        if ($id_status != null) {
            $sql .= " and  d.id_status = '" . $id_status . "' ";
        }

        $sql .= " order by d.tgl_berangkat desc";

        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getDmOmzet($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select d.tgl_berangkat,d.id_dm,d.kode_dm,v.nm_ven,d.cara,d.c_total,c_pro from t_dm d
        join m_vendor v on v.id_ven=d.id_ven
        where d.id_perush_dr='" . $id_perush . "' and d.tgl_berangkat>='" . $dr_tgl . "' and d.tgl_berangkat<='" . $sp_tgl . "'
        and d.is_vendor=true
        order by d.tgl_berangkat asc";

        return DB::select($sql);
    }

    public static function getOmzetCount($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select d.cara,d.c_pro,d.c_total,d.n_bayar,d.n_harga,od.n_koli,od.stt,d.id_dm
        from t_dm d
        left join (
            select count(id_stt) as stt,sum(n_koli) as n_koli,id_dm from t_order_dm GROUP BY id_dm
        ) as od on od.id_dm=d.id_dm
        where d.is_vendor=TRUE and d.id_perush_dr='" . $id_perush . "'
        and d.tgl_berangkat>='" . $dr_tgl . "' and d.tgl_berangkat<='" . $sp_tgl . "'
        order by d.tgl_berangkat asc";

        $data = DB::select($sql);
        $a_data = [];

        foreach ($data as $key => $value) {
            $a_data[$value->id_dm] = $value;
        }

        return $a_data;
    }

    public static function getOmzetBiaya($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select m.id_dm,d.id_jenis,d.bvendor,d.bumum,d.bstt from t_dm m
        left join (
            select
                CASE
                    WHEN b.id_jenis='0' THEN SUM(b.nominal)
                    ELSE 0
                 END AS bstt,
                 CASE
                    WHEN b.id_jenis='1' THEN SUM(b.nominal)
                    ELSE 0
                 END AS bumum,
                    CASE
                    WHEN b.id_jenis='2' THEN SUM(b.nominal)
                    ELSE 0
                 END AS bvendor,
                 b.id_jenis,
                 b.id_dm
                 from t_dm_biaya b
                 GROUP BY b.id_dm,b.id_jenis
        ) as d on d.id_dm=m.id_dm
        where m.id_perush_dr='" . $id_perush . "'
        and m.tgl_berangkat>='" . $dr_tgl . "'
        and m.tgl_berangkat<='" . $sp_tgl . "'";

        $data = DB::select($sql);
        $a_data = [];

        foreach ($data as $key => $value) {
            $a_data[$value->id_dm] = $value;
        }

        return $a_data;
    }

    public static function getSatuanDM($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "	select sum(o.n_volume) as v, sum(o.n_berat) as k,sum(o.n_kubik) as m,t.id_dm from
        t_dm t
        join t_order_dm d on d.id_dm =t.id_dm
        join (
            select n_volume,n_berat,n_kubik,id_stt from t_order
        ) as o on o.id_stt=d.id_stt
        where t.id_perush_dr='" . $id_perush . "' and t.tgl_berangkat>='" . $dr_tgl . "' and t.tgl_berangkat<='" . $sp_tgl . "'
        GROUP BY t.id_dm";

        $data = DB::select($sql);
        $a_data = [];

        foreach ($data as $key => $value) {
            $a_data[$value->id_dm] = $value;
        }

        return $a_data;
    }

    public static function get_stt_dm_tiba($id_perush_tj, $kode_stt)
    {
        return self::select(
            't_dm.kode_dm',
            't_dm.id_perush_tj',
            'D.nm_perush AS perusahaan_asal',
            'E.nm_perush AS perusahaan_tujuan',
            'F.nama_wil AS asal',
            'G.nama_wil AS tujuan',
            'C.*'
        )
            ->leftjoin('t_order_dm AS B', 't_dm.id_dm', '=', 'B.id_dm')
            ->leftjoin('t_order AS C', 'B.id_stt', '=', 'C.id_stt')
            ->leftjoin('s_perusahaan AS D', 'D.id_perush', '=', 't_dm.id_perush_dr')
            ->leftjoin('s_perusahaan AS E', 'E.id_perush', '=', 't_dm.id_perush_tj')
            ->leftjoin('m_wilayah AS F', 'F.id_wil', '=', 'C.pengirim_id_region')
            ->leftjoin('m_wilayah AS G', 'G.id_wil', '=', 'C.penerima_id_region')
            ->where(function ($q) use ($id_perush_tj) {
                $q->where('t_dm.id_perush_tj', $id_perush_tj)
                    ->orWhere('C.id_perush_asal', '=', $id_perush_tj);
            })
        // ->where('t_dm.id_perush_tj', $id_perush_tj)
            ->where('C.kode_stt', strtoupper($kode_stt))
            ->get();
    }
}
