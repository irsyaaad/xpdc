<?php

namespace Modules\Operasional\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class SttModel extends Model
{
    protected $fillable = [];
    protected $table = "t_order";
    protected $primaryKey = 'id_stt';

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'id_plgn', 'id_pelanggan');
    }

    public function perush_asal()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_asal', 'id_perush');
    }

    public function perush_tujuan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tujuan', 'id_perush');
    }

    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
    }

    public function tipekirim()
    {
        return $this->belongsTo('Modules\Operasional\Entities\TipeKirim', 'id_tipe_kirim', 'id_tipe_kirim');
    }

    public function asal()
    {
        return $this->belongsTo('App\Models\Wilayah', 'pengirim_id_region', 'id_wil');
    }

    public function tujuan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'penerima_id_region', 'id_wil');
    }

    public function tarif()
    {
        return $this->belongsTo('App\Models\Tarif', 'id_tarif', 'id_tarif');
    }

    public function marketing()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }

    public function packing()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Packing', 'id_packing', 'id_packing');
    }

    public function cara()
    {
        return $this->belongsTo('Modules\Operasional\Entities\CaraBayar', 'id_cr_byr_o', 'id_cr_byr_o');
    }

    public function koli()
    {
        return $this->hasMany('Modules\Operasional\Entities\OpOrderKoli', 'id_stt', 'id_stt');
    }

    public function koli2()
    {
        return $this->hasMany('Modules\Operasional\Entities\OpOrderKoli', 'id_stt', 'id_stt');
    }

    public function sttdm()
    {
        return $this->hasMany('Modules\Operasional\Entities\SttDm', 'id_stt', 'id_stt');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Operasional\Entities\StatusStt', 'id_status', 'id_ord_stt_stat');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public static function getApi($id)
    {
        $sql = "SELECT
		o.no_awb,
        o.kode_stt,
		o.id_marketing,
        o.id_stt,
        o.tgl_masuk,
        o.tgl_keluar,
        o.id_status,
        G.nm_pelanggan,
        l.nm_layanan,
        l.id_layanan,
        wa.nama_wil AS asal,
        wt.nama_wil AS tujuan,
        s.nm_ord_stt_stat AS nm_status,
        o.pengirim_nm,
        o.pengirim_perush,
        o.penerima_nm,
        o.penerima_perush,
        o.id_perush_asal,
        P.nm_perush,
        dm.kode_dm,
        odm.id_dm
    FROM
        t_order o
        JOIN s_perusahaan P ON P.id_perush = o.id_perush_asal
        JOIN m_plgn G ON G.id_pelanggan = o.id_plgn
        JOIN m_layanan l ON o.id_layanan = l.id_layanan
        JOIN m_wilayah wa ON o.pengirim_id_region = wa.id_wil
        JOIN m_wilayah wt ON o.penerima_id_region = wt.id_wil
        JOIN m_ord_stt_stat s ON o.id_status = s.id_ord_stt_stat
        LEFT JOIN m_marketing i ON i.id_marketing = o.id_marketing
        LEFT JOIN t_order_dm odm ON o.id_stt = odm.id_stt
        LEFT JOIN t_dm as dm on odm.id_dm = dm.id_dm
		where o.kode_stt = '" . $id . "'";

        return collect(\DB::select($sql))->first();
    }

    public static function getDataStt($perpage, $page, $id_perush, $id_stt = null, $asal = null, $tujuan = null, $status = null, $layanan = null, $dr_tgl = null, $sp_tgl = null, $cara = null, $no_awb = null, $pelanggan = null, $f_penerima = null)
    {
        $sql = " 
        SELECT
            o.no_awb,
            o.c_total,
            o.x_n_piut,
            o.x_n_bayar,
            o.kode_stt,
            o.id_marketing,
            o.id_stt,
            o.tgl_masuk,
            o.tgl_keluar,
            o.is_lunas,
            o.id_status,
            G.nm_pelanggan,
            l.nm_layanan,
            o.id_layanan,
            wa.nama_wil AS asal,
            wt.nama_wil AS tujuan,
            (SELECT nm_status FROM t_history_stt WHERE t_history_stt.id_stt = o.id_stt ORDER BY id_status DESC LIMIT 1) as nm_status,
            o.pengirim_nm,
            o.pengirim_perush,
            o.penerima_nm,
            o.penerima_perush,
            o.id_perush_asal,
            P.nm_perush,
            dm.kode_dm,
            i.nm_marketing,
            o.created_at,
            dm.id_dm,
            (SELECT tgl_update FROM t_history_stt WHERE t_history_stt.id_stt = o.id_stt ORDER BY id_status DESC LIMIT 1) as tgl_update,
            COALESCE (( SELECT SUM( n_bayar ) FROM t_order_pay WHERE t_order_pay.id_stt = o.id_stt GROUP BY t_order_pay.id_stt ), 0 ) AS tot_bayar
        FROM
            t_order o
            JOIN s_perusahaan P ON P.id_perush = o.id_perush_asal
            JOIN m_plgn G ON G.id_pelanggan = o.id_plgn
            JOIN m_layanan l ON o.id_layanan = l.id_layanan
            JOIN m_wilayah wa ON o.pengirim_id_region = wa.id_wil
            JOIN m_wilayah wt ON o.penerima_id_region = wt.id_wil
            LEFT JOIN m_marketing i ON i.id_marketing = o.id_marketing
            LEFT JOIN t_order_dm odm ON o.id_stt = odm.id_stt
            LEFT JOIN t_dm AS dm ON odm.id_dm = dm.id_dm
		where o.id_perush_asal = '" . $id_perush . "' ";

        if ($status != null) {
            $sql .= " and  o.id_status ='" . $status . "' ";
        }

        if ($layanan != null) {
            $sql .= " and  o.id_layanan ='" . $layanan . "' ";
        }

        if ($asal != null) {
            $sql .= " and  wa.id_wil ='" . $asal . "' ";
        }

        if ($tujuan != null) {
            $sql .= " and  wt.id_wil ='" . $tujuan . "' ";
        }

        if ($dr_tgl != null) {
            $sql .= " and  o.tgl_masuk >= '" . $dr_tgl . "' ";
        }

        if ($sp_tgl != null) {
            $sql .= " and o.tgl_masuk <= '''" . $sp_tgl . "' ";
        }

        if ($cara != null) {
            $sql .= " and  o.id_cr_byr_o ='" . $cara . "' ";
        }

        if ($id_stt != null) {
            $sql .= " and  o.id_stt ='" . $id_stt . "' ";
        }

        if ($no_awb != null) {
            $sql .= " and  o.no_awb ='" . $no_awb . "' ";
        }

        if ($pelanggan != null) {
            $sql .= " and  o.id_plgn ='" . $pelanggan . "' ";
        }

        if ($f_penerima != null) {
            $nama = strtolower($f_penerima);
            $sql .= " and  lower(o.penerima_nm) LIKE '%{$nama}%' ";
        }

        $sql .= " GROUP BY
                o.id_stt,
                G.nm_pelanggan,
                l.nm_layanan,
                wa.nama_wil,
                wt.nama_wil,
                P.nm_perush,
                dm.kode_dm,
                dm.id_dm,
                i.nm_marketing 
            ORDER BY
                o.tgl_masuk DESC
            ";

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

    public static function getSttBelumLunas($id_perush)
    {
        $sql = DB::select("select id_stt, kode_stt from t_order where id_perush_asal = '" . $id_perush . "' and is_lunas is not true ");
        return $sql;
    }

    public static function getOrder($id_perush)
    {
        $data = self::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")
            ->where("id_perush_asal", $id_perush)
            ->where(function ($q) use ($id_perush) {
                $q->where('is_lunas', false)
                    ->orWhere('is_lunas', '=', null);
            });

        return $data;
    }

    public static function getSttKoli($id_dm = null, $id_perush_asal = null, $id_perush_tujuan = null, $id_layanan = null, $status = null)
    {
        $sql = "select t.id_stt,t.kode_stt,t.tgl_masuk,t.n_hrg_bruto, t.pengirim_nm, t.pengirim_alm, t.pengirim_telp, count(koli.id_stt) as n_koli,
		t.id_status,t.n_diskon, t.n_tarif_koli * count(koli.id_stt) as c_total,
		t.penerima_nm, t.penerima_alm, t.penerima_telp
		from t_order t
		join ( select id_stt from t_order_koli where status='" . $status . "')
		as koli on t.id_stt=koli.id_stt
		left join t_order_dm as d on t.id_stt=d.id_stt
		where t.id_perush_asal='" . $id_perush_asal . "' ";

        $sql .= " GROUP BY t.id_stt";

        $data = DB::select($sql);
        return $data;
    }

    public static function getSelectAwb($no_awb, $id_perush = null)
    {
        $sql = "select o.id_stt,o.kode_stt, o.no_awb, a.id_stt as id_awb from t_order o
		join t_order a on o.no_awb = a.kode_stt
		where o.id_perush_asal = '" . $id_perush . "' and cast(o.no_awb as varchar) like '%" . $no_awb . "%'";
        $data = DB::select($sql);

        return $data;
    }

    public static function getAwb($no_awb, $id_perush = null)
    {
        $sql = "select o.id_stt,o.kode_stt, o.no_awb, a.id_stt as id_awb from t_order o
		join t_order a on o.no_awb = a.kode_stt
		where o.id_perush_asal = '" . $id_perush . "' and cast(a.id_stt as varchar) like '%" . $no_awb . "%'";
        $data = DB::select($sql);

        return $data;
    }

    public static function getSttAwb($page, $perpage, $id_perush, $id_perush_asal = null, $id_dm = null, $no_awb = null, $id_asal = null, $id_tujuan = null, $id_status = null, $id_layanan = null, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "select a.id_stt as id_stt, a.kode_stt,o.id_stt as id_asal,o.kode_stt as kode_asal,a.no_awb, a.id_perush_asal,p.nm_perush,l.nm_layanan,wa.nama_wil as asal,wt.nama_wil as tujuan,
		s.nm_ord_stt_stat as status_asal, o.id_status as id_status_asal, r.nm_ord_stt_stat as nm_status, a.id_status,a.tgl_masuk,m.id_dm, m.kode_dm
		from t_order o
		join t_order a on a.no_awb = o.kode_stt
		join t_order_dm d on d.id_stt = o.id_stt
		join t_dm m on m.id_dm = d.id_dm
		join s_perusahaan p on o.id_perush_asal= p.id_perush
		join m_layanan l on a.id_layanan = l.id_layanan
		join m_wilayah wa on a.pengirim_id_region=wa.id_wil
		join m_wilayah wt on a.penerima_id_region=wt.id_wil
		join m_ord_stt_stat s on o.id_status=s.id_ord_stt_stat
		join m_ord_stt_stat r on a.id_status=r.id_ord_stt_stat
		where a.id_perush_asal = '" . $id_perush . "' ";

        if ($id_perush_asal != null) {
            $sql .= " and  o.id_perush_asal ='" . $id_perush_asal . "' ";
        }

        if ($dr_tgl != null) {
            $sql .= " and  a.tgl_masuk >= '" . $dr_tgl . "' ";
        }

        if ($sp_tgl != null) {
            $sql .= " and  a.tgl_masuk <= '" . $sp_tgl . "' ";
        }

        if ($id_layanan != null) {
            $sql .= " and  l.id_layanan  ='" . $id_layanan . "' ";
        }

        if ($id_status != null) {
            $sql .= " and  o.id_status  ='" . $id_status . "' ";
        }

        if ($id_dm != null) {
            $sql .= " and  d.id_dm  ='" . $id_dm . "' ";
        }

        if ($no_awb != null) {
            $sql .= " and  o.id_stt  ='" . $no_awb . "' ";
        }

        if ($id_asal != null) {
            $sql .= " and  a.pengirim_id_region = '" . $id_asal . "' ";
        }

        if ($id_tujuan != null) {
            $sql .= " and  a.penerima_id_region = '" . $id_tujuan . "' ";
        }

        $sql .= " order by o.tgl_masuk desc";

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

    public static function getSttDM($id)
    {
        $sql = "select t.*,ap.nama_wil as pengirim_alm, ag.nama_wil as penerima_alm, (SELECT COUNT(*) FROM t_dm_koli WHERE t.id_stt = t_dm_koli.id_stt) as muat
		from t_order t
		join t_order_dm m on m.id_stt = t.id_stt
		join (
			select kec.id_wil,concat(kec.nama_wil ,' ', kab.nama_wil,' ', prov.nama_wil) as nama_wil from m_wilayah kec
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah  prov on kec.prov_id = prov.id_wil
		) as ap on ap.id_wil=t.pengirim_id_region
		join (
			select kec.id_wil,concat(kec.nama_wil ,' ', kab.nama_wil,' ', prov.nama_wil) as nama_wil from m_wilayah kec
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah  prov on kec.prov_id = prov.id_wil
		) as ag on ag.id_wil=t.penerima_id_region
		join (select id_koli,id_dm,id_stt from t_dm_koli where id_dm = '" . $id . "') as d
		on t.id_stt=d.id_stt
		where d.id_dm='" . $id . "' GROUP BY t.id_stt,ap.nama_wil,ag.nama_wil";

        $data = DB::select($sql);

        return $data;
    }

    public static function getUpdateStt($id)
    {
        $sql = "select o.id_stt,o.kode_stt,o.pengirim_telp,o.penerima_telp,d.ata, d.atd, o.n_koli,o.id_layanan,o.tgl_masuk,o.pengirim_nm,o.pengirim_alm,
		o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, h.nm_status, b.tgl as tgl_tiba
		from t_order_dm t join
						(
			 SELECT DISTINCT ON (id_stt) *
			 FROM t_history_stt
			 ORDER BY id_stt, id_history DESC
		) as h on h.id_stt = t.id_stt join
		t_dm d on t.id_dm= d.id_dm left join
		t_dm_tiba b on t.id_dm=b.id_dm join
		t_order o on t.id_stt=o.id_stt join
		s_perusahaan p on o.id_perush_asal=p.id_perush join
		m_layanan l on o.id_layanan=l.id_layanan
		where t.id_dm='" . $id . "' GROUP BY o.id_stt,o.n_koli,o.id_layanan,o.tgl_masuk, o.tgl_keluar,o.pengirim_nm,o.pengirim_alm,
		o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, t.id_dm, d.ata, d.atd, b.tgl,h.nm_status;";

        $data = DB::select($sql);

        return $data;
    }

    public static function getSttDetail($id)
    {
        $sql = "select t.id_stt, t.tgl_masuk,t.c_total,t.x_n_bayar,t.x_n_piut,t.n_berat,t.n_volume,t.n_kubik, t.pengirim_nm, t.is_import,t.n_koli,t.kode_stt,t.pengirim_alm,t.pengirim_telp, t.penerima_nm, t.penerima_alm, t.penerima_telp,
		t.id_status, t.n_tarif_koli,count(d.id_koli) as muat ,t.tgl_update, t.is_penerusan
		from t_order t
		join t_order_dm m on m.id_stt = t.id_stt
		join (select id_koli,id_dm,id_stt from t_dm_koli where id_dm = '" . $id . "') as d
		on t.id_stt=d.id_stt
		where d.id_dm='" . $id . "' GROUP BY t.id_stt";

        $data = DB::select($sql);

        return $data;
    }

    public static function getIdSttKoli($id)
    {
        $data = Self::with("perush_asal")->with(["koli" => function ($query) {
            $query->where("status", "1");
        }, "koli2" => function ($query) {
            $query->where("status", "2");
        }])->findOrFail($id);

        return $data;
    }

    public static function getSttVendor($id_perush, $id_wil = null, $id_layanan = null)
    {
        $sql = "select t.id_stt, t.tgl_masuk,t.c_total,t.is_import, t.pengirim_nm,t.n_koli,t.kode_stt,t.pengirim_alm,t.pengirim_telp, t.penerima_nm, t.penerima_alm, t.penerima_telp,  t.id_status from t_order t
		join (select id_stt from t_order_koli where status='1')
		as koli on t.id_stt=koli.id_stt where t.id_perush_asal='" . $id_perush . "' ";

        if ($id_wil != null) {
            $sql .= " and t.id_wil='" . $id_wil . "' ";
        }

        if ($id_layanan != null) {
            $sql .= " and t.id_layanan='" . $id_layanan . "' ";
        }

        $sql .= " GROUP BY t.id_stt order by t.tgl_masuk asc";

        $data = DB::select($sql);

        return $data;
    }

    public static function forInvoice_old($id, $id_plgn)
    {
        $sql = "SELECT t_order.id_stt,t_order.kode_stt,t_order.pengirim_nm,m_wilayah.nama_wil,
		t_order.penerima_nm,m_layanan.nm_layanan, t_order.pengirim_telp,t_order.c_total
		FROM t_order
		JOIN m_wilayah on t_order.penerima_id_region = m_wilayah.id_wil
		JOIN m_layanan on t_order.id_layanan = m_layanan.id_layanan
		WHERE t_order.is_lunas IS NOT TRUE
		and t_order.id_plgn='" . $id_plgn . "'
		and t_order.id_stt not in (
			select id_stt from keu_draft_invoice where id_perush='" . $id . "')";

        $data = DB::select($sql);

        return $data;
    }

    public static function forInvoice($id_perush, $id_plgn)
    {
        return DB::table('t_order')
            ->select('t_order.*', 'm_wilayah.nama_wil', 'm_layanan.nm_layanan')
            ->join('m_wilayah', 't_order.penerima_id_region', '=', 'm_wilayah.id_wil')
            ->join('m_layanan', 't_order.id_layanan', '=', 'm_layanan.id_layanan')
            ->where('id_perush_asal', '=', $id_perush)
            ->where('id_plgn', '=', $id_plgn)
            ->whereRaw('id_stt NOT IN (SELECT id_stt FROM keu_draft_invoice)')
            ->get();
    }

    public static function forDP($id)
    {
        $sql = "SELECT t_order.id_stt,t_order.pengirim_nm,m_wilayah.nama_wil,
		t_order.penerima_nm,m_layanan.nm_layanan, t_order.pengirim_telp, t_order.c_total
		FROM t_order
		JOIN m_wilayah on t_order.penerima_id_region = m_wilayah.id_wil
		JOIN m_layanan on t_order.id_layanan = m_layanan.id_layanan
		LEFT OUTER JOIN t_downpay
		ON t_order.id_stt = t_downpay.id_stt
		LEFT OUTER JOIN t_order_pay
		ON t_order.id_stt = t_order_pay.id_stt
		WHERE t_downpay.id_stt IS NULL
		AND t_order_pay.id_stt IS NULL
		AND t_order.id_perush_asal='" . $id . "'
		AND t_order.id_status < '7'
		";

        $data = DB::select($sql);

        return $data;
    }

    public static function filterDP($id, $id_stt)
    {
        $sql = "SELECT t_order.id_stt,t_order.pengirim_nm,m_wilayah.nama_wil,
		t_order.penerima_nm,m_layanan.nm_layanan, t_order.pengirim_telp, t_order.c_total
		FROM t_order
		JOIN m_wilayah on t_order.penerima_id_region = m_wilayah.id_wil
		JOIN m_layanan on t_order.id_layanan = m_layanan.id_layanan
		LEFT OUTER JOIN t_downpay
		ON t_order.id_stt = t_downpay.id_stt
		WHERE t_downpay.id_stt IS NULL
		AND t_order.id_perush_asal='" . $id . "'
		AND t_order.id_stt='" . $id_stt . "'
		AND t_order.id_status < '7'
		";

        $data = DB::select($sql);

        return $data;
    }

    public static function getData($id_perush)
    {
        $data = DB::table('t_order_pay')
            ->join('t_order', 't_order.id_stt', '=', 't_order_pay.id_stt')
            ->join('t_order_dm', 't_order_dm.id_stt', '=', 't_order.id_stt')
            ->join('t_dm', 't_dm.id_dm', '=', 't_order_dm.id_dm')
            ->join('s_perusahaan', 's_perusahaan.id_perush', '=', 't_dm.id_perush_dr')
            ->select('t_order.*')
            ->where('t_dm.id_perush_tj', $id_perush);

        return $data;
    }

    public static function getSttterima($page, $perpage, $id_perush = null, $id_perush_asal = null, $asal = null, $tujuan = null, $id_status = null, $id_layanan = null, $tgl_berangkat = null, $tgl_tiba = null, $id_stt = null)
    {
        $sql = "select
		o.id_stt,p.nm_perush,l.nm_layanan,o.tgl_masuk,o.kode_stt,
		o.pengirim_nm, w.nama_wil as kota_tujuan, o.penerima_nm,
		d.tgl_sampai, h.nama_wil as kota_asal,
		t.nm_ord_stt_stat as nm_status, d.tgl_berangkat, d.id_perush_tj, o.id_status
		from t_order o
		join s_perusahaan p on o.id_perush_asal=p.id_perush
		join m_layanan l on l.id_layanan = o.id_layanan
		join m_ord_stt_stat t on o.id_status=t.id_ord_stt_stat
		join m_plgn n on o.id_plgn=n.id_pelanggan
		join m_wilayah w on o.penerima_id_region=w.id_wil
		join t_order_dm r on o.id_stt=r.id_stt
		join t_dm as d on d.id_dm=r.id_dm
		join m_wilayah h on o.pengirim_id_region = h.id_wil
		where d.id_perush_tj ='" . $id_perush . "' ";

        if ($id_perush_asal != null) {
            $sql .= " and  d.id_perush_tj ='" . $id_perush_asal . "' ";
        }

        if ($id_status != null) {
            $sql .= " and  o.id_status ='" . $id_status . "' ";
        }

        if ($id_layanan != null) {
            $sql .= " and  d.id_layanan ='" . $id_layanan . "' ";
        }

        if ($asal != null) {
            $sql .= " and  w.id_wil ='" . $asal . "' ";
        }

        if ($tujuan != null) {
            $sql .= " and  h.id_wil ='" . $tujuan . "' ";
        }

        if ($tgl_berangkat != null) {
            $sql .= " and  d.tgl_berangkat ='" . $tgl_berangkat . "' ";
        }

        if ($tgl_tiba != null) {
            $sql .= " and  d.tgl_sampai ='" . $tgl_tiba . "' ";
        }

        if ($id_stt != null) {
            $sql .= " and  o.id_stt ='" . $id_stt . "' ";
        }

        $sql = $sql . ' GROUP BY(o.id_stt)';
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

    public static function getKoli($id_stt)
    {
        $data = DB::table("t_order_koli as k")
            ->join("t_order as o", "k.id_stt", "=", "o.id_stt")
            ->join("s_perusahaan as p", "o.id_perush_asal", "=", "p.id_perush")
            ->join("m_layanan as l", "o.id_layanan", "=", "l.id_layanan")
            ->join("m_plgn as n", "o.id_plgn", "=", "n.id_pelanggan")
            ->join("m_ord_stt_stat as t", "o.id_status", "=", "t.id_ord_stt_stat")
            ->join("m_wilayah as w", "o.penerima_id_region", "=", "w.id_wil")
            ->select("o.id_stt", "o.kode_stt", "p.nm_perush", "l.nm_layanan", "o.tgl_masuk", "o.pengirim_nm", "w.nama_wil as kota_tujuan", "o.penerima_nm", "t.nm_ord_stt_stat as nm_status", "o.id_status", "k.id_koli")
            ->where("o.id_stt", $id_stt);

        return $data;
    }

    public static function getSttPacking($page, $id_perush = null, $tgl_masuk = null, $status = null)
    {
        $sql = "select o.id_stt, o.kode_stt,o.tgl_masuk, o.n_koli, o.n_berat, o.n_volume, o.n_kubik,g.nm_pelanggan,o.pengirim_nm, p.nm_perush from t_order o
		left join m_plgn g on o.id_plgn=g.id_pelanggan
		left join s_perusahaan p on o.id_perush_asal = p.id_perush
		where o.is_packing='1' and NOT EXISTS (SELECT id_stt FROM t_order_packing i where i.id_stt=o.id_stt) ";

        if (isset($id_perush) and $id_perush != null) {
            $sql = $sql . " and o.id_perush_asal = '" . $id_perush . "' ";
        }

        if (isset($tgl_masuk) and $tgl_masuk != null) {
            $sql = $sql . " and o.tgl_masuk = '" . $tgl_masuk . "' ";
        }

        $data = DB::select(DB::raw($sql));

        $data = new Paginator($data, $page);

        return $data;
    }

    public static function getDM($id)
    {
        $data = self::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")
            ->join("t_order_dm as od", "od.id_stt", "=", "t_order.id_stt")
            ->select("t_order.*", "od.id_dm")
            ->where("od.id_dm", $id);

        return $data;

    }

    public static function SttNoDM($id_perush)
    {
        $sql = "
		SELECT A
		.id_stt,
		A.kode_stt,
		A.tgl_masuk,
		b.id_dm
		FROM
		t_order
		AS A LEFT JOIN t_order_dm AS b ON A.id_stt = b.id_stt
		WHERE
		b.id_stt IS NULL
		AND A.id_perush_asal = '" . $id_perush . "'
		ORDER BY
		A.tgl_masuk
		";

        $data = DB::select(DB::raw($sql));

        // $data = new Paginator($data, $page);

        return $data;
    }

    public static function get_stt_handling($id_sopir)
    {
        return self::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")
            ->select('t_order.*')
            ->join('t_handling_stt', 't_order.id_stt', '=', 't_handling_stt.id_stt')
            ->join('t_handling', 't_handling.id_handling', '=', 't_handling_stt.id_handling')
            ->where('t_handling.id_sopir', $id_sopir);
    }

    public static function SttNoDM2()
	{
		return self::with("layanan", "perush_asal", "perush_tujuan", "pelanggan", "tipekirim", "asal", "tujuan", "status")
		->select('t_order.*')
		->leftJoin('t_order_dm','t_order.id_stt','=','t_order_dm.id_stt')
		->where('t_order.id_perush_asal','=', Session("perusahaan")["id_perush"])
		->whereNull('t_order_dm.id_dm')
		->orderBy('tgl_masuk', 'ASC')
		->get();
	}

}
