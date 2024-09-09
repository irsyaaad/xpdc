<?php

namespace Modules\Keuangan\Entities;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class IndexPrestasi extends Model
{
    protected $fillable = [];

    public static function byGroupPelanggan($id_perush, $bulan, $tahun)
    {
        $sql = "
        select a.id_wil, a.nama_wil,
        b.id_plgn_group,b.nm_group,
        sum(b.c_total) as total,
        sum(b.n_koli) as koli
        from m_wilayah as a
        right join
            (
                select
                a.id_plgn_group,a.nm_group,
                b.*
                from m_plgn_group as a
                right join
                    (
                        select b.id_plgn_group as grup,
                        a.c_total,a.n_koli,
                        a.penerima_id_region
                        from t_order as a
                        join m_plgn as b on a.id_plgn = b.id_pelanggan
                        where id_perush = '" . $id_perush . "'
						and EXTRACT(YEAR FROM a.tgl_masuk) = '" . $tahun . "'
						and EXTRACT(MONTH FROM a.tgl_masuk) = '" . $bulan . "'
                    )as b on a.id_plgn_group = b.grup
            )as b on a.id_wil = b.penerima_id_region
        group by id_wil,b.id_plgn_group,b.nm_group
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function PrestasiMarketing($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        select a.id_marketing, a.nm_marketing,
        b.id_plgn_group,b.nm_group,
        b.omset,
		b.stt,
		b.bayar,
        b.koli
        from m_marketing as a
        left join
            (
                select
                a.id_plgn_group,a.nm_group,
                b.*,c.*
                from m_plgn_group as a
                left join
                    (
                        select b.id_plgn_group as grup, a.id_marketing,
                        SUM(a.c_total) as omset,SUM(a.n_koli) as koli, COUNT(a.id_stt) as stt
                        from t_order as a
                        join m_plgn as b on a.id_plgn = b.id_pelanggan
                        where id_perush = '" . $id_perush . "'
                        AND a.tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                        GROUP BY a.id_marketing, b.id_plgn_group
                    )as b on a.id_plgn_group = b.grup
                left join
                    (
                        select sum(a.n_bayar) as bayar , b.id_plgn_group as grup
                        from t_order_pay as a
                        join m_plgn as b on a.id_plgn = b.id_pelanggan
                        where a.id_perush = '" . $id_perush . "'
                        AND a.tgl BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                        GROUP BY b.id_plgn_group
                    )as c on a.id_plgn_group = c.grup

            )as b on a.id_marketing = b.id_marketing
            where a.id_perush = '" . $id_perush . "'
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function PrestasiMarketing2($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select o.id_marketing,sum(b.bayar) as bayar,u.kode_plgn_group,u.nm_group,sum(o.n_hrg_bruto-o.n_diskon) as omset,
        count(o.id_stt) as stt,sum(o.n_koli) as koli,COALESCE(m.nm_marketing,'Tanpa Marketing') as nm_marketing
        from t_order o left join (
            select sum(n_bayar) as bayar, id_stt from t_order_pay group by id_stt
        )  as b on b.id_stt=o.id_stt
        join m_plgn g on o.id_plgn=g.id_pelanggan
        join m_plgn_group u on u.id_plgn_group=g.id_plgn_group
        left join m_marketing m on o.id_marketing=m.id_marketing
        where o.tgl_masuk>='" . $dr_tgl . "' and o.tgl_masuk<='" . $sp_tgl . "' and o.id_perush_asal='" . $id_perush . "'
        group by m.nm_marketing,u.nm_group,u.kode_plgn_group,o.id_marketing ";

        $data = DB::select($sql);
        return $data;
    }

    public static function getDetail($id_marketing, $id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select DISTINCT(o.id_stt) as id_stt,o.kode_stt,o.n_koli,(o.n_hrg_bruto-o.n_diskon) as omset,o.tgl_masuk,o.penerima_nm,
        o.penerima_alm,o.pengirim_nm,dm.cabang,k.nm_tipe_kirim,o.id_marketing,g.nm_group,g.id_plgn_group,g.kode_plgn_group,g.nm_group
        from t_order o
        join d_tipe_kirim k on k.id_tipe_kirim=o.id_tipe_kirim
        join m_plgn p on p.id_pelanggan=o.id_plgn
        join m_plgn_group g on g.id_plgn_group=p.id_plgn_group
        left join t_order_dm r on o.id_stt=r.id_stt
        left join (
            select id_dm,COALESCE(s.nm_perush,v.nm_ven) as cabang,kode_dm from t_dm d
            left join m_vendor v on d.id_ven=v.id_ven
            left join s_perusahaan s on d.id_perush_tj=s.id_perush
            where d.id_perush_dr='" . $id_perush . "'
        ) as dm on r.id_dm=dm.id_dm
        where o.tgl_masuk>='" . $dr_tgl . "' and o.tgl_masuk<='" . $sp_tgl . "' and o.id_perush_asal='" . $id_perush . "' and o.id_marketing='" . $id_marketing . "' ";

        $data = DB::select($sql);
        return $data;
    }

    public static function AnalisaPelanggan($id_perush = null, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "
        SELECT a.id_marketing, a.nm_marketing, b.stt, b.koli, b.omset, c.id_pelanggan, c.total_stt, c.tgl
        FROM m_marketing as a
        JOIN (
            SELECT id_marketing, COUNT(id_stt) as stt, SUM(n_koli) as koli, SUM(c_total) as omset FROM t_order
            WHERE tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
			AND id_perush_asal = '" . $id_perush . "'
            GROUP BY id_marketing
        )as b on a.id_marketing = b.id_marketing
        JOIN (
            SELECT id_pelanggan, nm_pelanggan, id_marketing ,b.total_stt, b.tgl FROM m_plgn as a
            JOIN (
                SELECT id_plgn, id_marketing ,COUNT(id_stt) as total_stt ,max(tgl_masuk) as tgl FROM t_order
                WHERE tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
			    AND id_perush_asal = '" . $id_perush . "'
                GROUP BY id_plgn, id_marketing
            )as b on a.id_pelanggan = b.id_plgn
        )as c on c.id_marketing = a.id_marketing
        ORDER BY c.tgl ASC
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function AnalisaPelanggan2($id_perush, $dr_tgl, $sp_tgl)
    {
        $startDate = Carbon::parse($dr_tgl);
        $min_satu_tahun = $startDate->subYears(1);
        $min_tiga_tahun = $startDate->subYears(3);
        $sql = "
        SELECT
            m_marketing.nm_marketing,
            hasil.*
        FROM
            m_marketing
            JOIN (
            SELECT
                id_marketing,
                COUNT ( CASE WHEN tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "' THEN id_stt END ) AS total_stt,
                COUNT ( CASE WHEN tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "' THEN id_plgn END ) AS jumlah,
                (
                    SELECT SUM( CASE WHEN subquery.ROW_COUNT >= 2 THEN 1 ELSE 0 END )
                    FROM
                        ( SELECT COUNT ( * ) AS ROW_COUNT FROM t_order
                         WHERE id_marketing = root.id_marketing
                         AND tgl_masuk BETWEEN '" . $min_satu_tahun . "' AND '" . $sp_tgl . "'
                         GROUP BY id_plgn ) AS subquery
                    ) AS reorder,
                COUNT ( CASE WHEN tgl_masuk BETWEEN '" . $min_tiga_tahun . "' AND '" . $sp_tgl . "' THEN id_plgn END ) AS aktif,
                SUM ( CASE WHEN tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "' THEN c_total END ) AS omset,
                SUM ( CASE WHEN tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "' THEN n_koli END ) AS koli
                FROM
                    t_order AS root
                GROUP BY
                id_marketing
            ) AS hasil ON hasil.id_marketing = m_marketing.id_marketing
        WHERE m_marketing.id_perush = '" . $id_perush . "'";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function AnalisaPelanggan3($id_perush, $dr_tgl, $sp_tgl)
    {
        $startDate = Carbon::parse($dr_tgl);
        $min_satu_tahun = date('Y-m-d', strtotime("{$dr_tgl} -1 year"));
        $min_tiga_tahun = date('Y-m-d', strtotime("{$dr_tgl} -3 year"));
        $sql = "
        SELECT
            m_marketing.id_marketing, m_marketing.nm_marketing,
            A.total_stt,
            A.jumlah,
            A.koli,
            A.omset,
            B.baru,
            C.aktif,
            D.reorder
        FROM
            m_marketing
        LEFT JOIN (
            SELECT
                id_perush_asal,
                id_marketing,
                COUNT (id_stt) as total_stt,
                COUNT (DISTINCT id_plgn) as jumlah,
                SUM (n_koli) as koli,
                SUM (c_total) as omset
            FROM
                t_order
            WHERE
                tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            GROUP
                BY id_perush_asal, id_marketing
        ) as A ON m_marketing.id_marketing = A.id_marketing

        LEFT JOIN (
            SELECT
                id_marketing,
                COUNT(DISTINCT id_plgn) as baru
            FROM t_order
            WHERE
                id_plgn NOT IN (
                SELECT
                    id_plgn
                FROM t_order
                WHERE
                    tgl_masuk BETWEEN '" . $min_satu_tahun . "' AND '" . $dr_tgl . "'
                )
            AND
                tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            GROUP BY
            id_marketing
        ) AS B on m_marketing.id_marketing = B.id_marketing

        LEFT JOIN (
            SELECT
                id_marketing, COUNT(DISTINCT id_plgn) as aktif
            FROM
                t_order
            WHERE
                tgl_masuk BETWEEN '" . $min_tiga_tahun . "' AND '" . $sp_tgl . "'
            GROUP BY id_marketing
        ) AS C on m_marketing.id_marketing = C.id_marketing

        LEFT JOIN (
            SELECT id_marketing, COUNT(id_plgn) as reorder FROM (
                SELECT id_marketing, id_plgn, COUNT(id_plgn)
                FROM t_order
                WHERE id_perush_asal = '" . $id_perush . "'
                AND tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                GROUP BY id_plgn, id_marketing
                HAVING COUNT(id_plgn) > 1
            ) AS Q
            GROUP BY id_marketing
        ) AS D ON m_marketing.id_marketing = D.id_marketing
        WHERE m_marketing.id_perush = '" . $id_perush . "'";
        // dd($sql, $min_satu_tahun, $min_tiga_tahun);
        $data = DB::select($sql);
        return $data;
    }

    public static function AnalisaPelanggan4($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
            SELECT
                PP.id_marketing,
                MM.nm_marketing,
                SUM ( PP.jml_stt ) AS total_stt,
                SUM ( PP.jml_koli ) AS koli,
                SUM ( PP.omzet ) AS omset,
                COUNT ( PP.id_plgn ) AS jumlah,
                SUM ( PP.baru ) AS baru,
                SUM ( PP.omset_baru ) AS omset_baru,
                SUM ( PP.re_order ) AS reorder,
                SUM ( PP.omset_re_order ) AS omset_reorder,
                (
                    SELECT COUNT
                        ( AA.* )
                    FROM
                        (
                        SELECT
                            id_plgn
                        FROM
                            t_order OX
                        WHERE
                            OX.tgl_masuk BETWEEN ( TO_DATE( '" . $sp_tgl . "', 'YYYY-MM-DD' ) - INTERVAL '36 month' ) :: DATE
                            AND '" . $sp_tgl . "'
                            AND OX.id_perush_asal = '" . $id_perush . "'
                            AND OX.id_marketing = PP.id_marketing
                        GROUP BY
                            OX.id_plgn
                        ) AS AA
                ) AS aktif
            FROM
                (
                SELECT
                    Q.id_marketing,
                    Q.id_plgn,
                    Q.jml_stt,
                    Q.jml_koli,
                    Q.omzet,
                    Q.max_tgl,
                    ( Q.max_tgl - INTERVAL '12 month' ) :: DATE AS max_tgl_r,
                    Q.min_tgl,
                    ( Q.min_tgl - INTERVAL '12 month' ) :: DATE AS min_tgl_r,
                    (
                    SELECT
                    CASE
                        WHEN COUNT
                            ( * ) = 1 THEN
                                1 ELSE 0
                            END
                            FROM
                                t_order OX
                            WHERE
                                OX.tgl_masuk BETWEEN ( Q.min_tgl - INTERVAL '12 month' ) :: DATE
                                AND Q.min_tgl
                                AND OX.id_perush_asal = '" . $id_perush . "'
                                AND OX.id_plgn = Q.id_plgn
                            GROUP BY
                                OX.id_plgn
                            ) AS baru,
                            (
                                SELECT SUM (c_total) FROM t_order
                                WHERE id_plgn IN (
                                SELECT
                                CASE
                                    WHEN COUNT
                                        ( * ) = 1 THEN
                                            id_plgn ELSE 0
                                        END
                                        FROM
                                            t_order OX
                                        WHERE
                                            OX.tgl_masuk BETWEEN ( Q.min_tgl - INTERVAL '12 month' ) :: DATE
                                            AND Q.min_tgl
                                            AND OX.id_perush_asal = '" . $id_perush . "'
                                            AND OX.id_plgn = Q.id_plgn
                                        GROUP BY
                                            OX.id_plgn
                                        )
                                                    AND id_plgn != 0
                                                    AND t_order.id_marketing = Q.id_marketing
                                                    AND t_order.tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                                                ) AS omset_baru,
                            (
                            SELECT
                            CASE

                                WHEN COUNT
                                    ( * ) > 1 THEN
                                        1 ELSE 0
                                    END
                                    FROM
                                        t_order OX
                                    WHERE
                                        OX.tgl_masuk BETWEEN ( Q.min_tgl - INTERVAL '12 month' ) :: DATE
                                        AND Q.min_tgl
                                        AND OX.id_perush_asal = '" . $id_perush . "'
                                        AND OX.id_plgn = Q.id_plgn
                                    GROUP BY
                                        OX.id_plgn
                                    ) AS re_order,
                                    (
                                        SELECT SUM(c_total) FROM t_order
                                        WHERE id_plgn IN (
                                SELECT
                                CASE
                                    WHEN COUNT
                                        ( * ) > 1 THEN
                                            id_plgn ELSE 0
                                        END
                                        FROM
                                            t_order OX
                                        WHERE
                                            OX.tgl_masuk BETWEEN ( Q.min_tgl - INTERVAL '12 month' ) :: DATE
                                            AND Q.min_tgl
                                            AND OX.id_perush_asal = '" . $id_perush . "'
                                            AND OX.id_plgn = Q.id_plgn
                                        GROUP BY
                                            OX.id_plgn
                                        )
                                            AND id_plgn != 0
                                            AND t_order.id_marketing = Q.id_marketing
                                            AND t_order.tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                                        ) AS omset_re_order
                                FROM
                                    (
                                    SELECT
                                        O.id_marketing,
                                        O.id_plgn,
                                        COUNT ( O.id_stt ) AS jml_stt,
                                        SUM ( O.n_koli ) AS jml_koli,
                                        ( SUM ( O.c_total ) ) AS omzet,
                                        MAX ( O.tgl_masuk ) max_tgl,
                                        MIN ( O.tgl_masuk ) min_tgl
                                    FROM
                                        t_order O
                                    WHERE
                                        O.tgl_masuk BETWEEN '" . $dr_tgl . "'
                                        AND '" . $sp_tgl . "'
                                        AND O.id_perush_asal = '" . $id_perush . "'
                                    GROUP BY
                                        O.id_marketing,
                                        O.id_plgn
                                    ) AS Q
                                ORDER BY
                                    Q.id_marketing
                                ) AS PP
                                LEFT JOIN m_marketing MM ON MM.id_marketing = PP.id_marketing
                            GROUP BY
                            PP.id_marketing,
                MM.nm_marketing
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function getUnikPelanggan($id_perush, $dr_tgl, $sp_tgl)
    {
        $unikAktif = DB::table(DB::raw("
        (SELECT
            id_plgn
        FROM
            t_order
        WHERE
            tgl_masuk BETWEEN ( TO_DATE( '" . $sp_tgl . "', 'YYYY-MM-DD' ) - INTERVAL '36 month' ) :: DATE
            AND '" . $sp_tgl . "'
            AND id_perush_asal = '" . $id_perush . "'
        GROUP BY
            id_plgn) AS Q")
        )->count();

        $unik = DB::table(DB::raw("
            (SELECT
                id_plgn
            FROM
                t_order
            WHERE
                tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
                AND id_perush_asal = '" . $id_perush . "'
            GROUP BY
                id_plgn) AS Q")
        )->count();

        return $data = [
            'unik' => $unik,
            'unik_aktif' => $unikAktif,
        ];
    }

    public static function getDetailAnalisaPelanggan($id_marketing, $dr_tgl, $sp_tgl)
    {
        return DB::table('t_order')
            ->select(
                't_order.id_plgn',
                'm_plgn.nm_pelanggan',
                DB::raw('COUNT(t_order.id_stt) AS total_stt'),
                DB::raw('string_agg(t_order.kode_stt,\',\') AS kode_stt'),
                DB::raw('SUM(t_order.n_koli) AS total_koli'),
                DB::raw('SUM(t_order.c_total) AS total_omset')
            )
            ->join('m_plgn', 't_order.id_plgn', '=', 'm_plgn.id_pelanggan')
            ->where('id_marketing', '=', $id_marketing)
            ->whereBetween('tgl_masuk', [$dr_tgl, $sp_tgl])
            ->groupBy(['t_order.id_plgn', 'm_plgn.nm_pelanggan'])
            ->get();
    }

    public static function getDetailPelangganAktif($id_perush, $id_marketing, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            OX.id_marketing,
            OX.id_plgn,
            PG.nm_pelanggan,
            PG.alamat,
            PG.telp,
            MIN(OX.tgl_masuk) AS awal,
            MAX(OX.tgl_masuk) AS akhir,
            COUNT(OX.id_stt) AS stt,
            (
                SELECT
                CASE                            
                    WHEN COUNT
                        ( * ) = 1 THEN
                            1 ELSE 0 
                        END 
                        FROM
                            t_order OY 
                        WHERE
                            OY.tgl_masuk BETWEEN ( '" . $dr_tgl . "'::DATE - INTERVAL '12 month' ) :: DATE 
                            AND '" . $dr_tgl . "' 
                            AND OY.id_perush_asal = '" . $id_perush . "' 
                            AND OX.id_plgn = OY.id_plgn 
                        GROUP BY
                            OX.id_plgn 
                        ) AS baru
        FROM
            t_order OX
            JOIN m_plgn PG ON PG.id_pelanggan = OX.id_plgn
        WHERE
            OX.tgl_masuk BETWEEN ( TO_DATE( '" . $sp_tgl . "', 'YYYY-MM-DD' ) - INTERVAL '36 month' ) :: DATE
            AND '" . $sp_tgl . "'
            AND OX.id_perush_asal = '" . $id_perush . "'
            AND OX.id_marketing = '" . $id_marketing . "'
        GROUP BY
            OX.id_marketing,
            OX.id_plgn,
            PG.nm_pelanggan,
            PG.alamat,
            PG.telp
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function getDetailPelangganBaru($id_perush, $id_marketing, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            OX.id_marketing,
            OX.id_plgn,
            PG.nm_pelanggan,
            PG.alamat,
            PG.telp,
            MIN(OX.tgl_masuk) AS awal,
            MAX(OX.tgl_masuk) AS akhir,
            COUNT(OX.id_stt) AS stt
        FROM
            t_order OX
            JOIN m_plgn PG ON PG.id_pelanggan = OX.id_plgn
        WHERE
            OX.tgl_masuk BETWEEN ( TO_DATE( '" . $sp_tgl . "', 'YYYY-MM-DD' ) - INTERVAL '36 month' ) :: DATE
            AND '" . $sp_tgl . "'
            AND OX.id_perush_asal = '" . $id_perush . "'
            AND OX.id_marketing = '" . $id_marketing . "'
        GROUP BY
            OX.id_marketing,
            OX.id_plgn,
            PG.nm_pelanggan,
            PG.alamat,
            PG.telp
        ";
        $data = DB::select($sql);
        return $data;
    }
}
