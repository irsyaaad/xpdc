<?php

namespace Modules\Keuangan\Entities;

use DB;

use Illuminate\Database\Eloquent\Model;

class Omset extends Model
{
    protected $fillable = [];

    public static function byCaraBayar($id_perush, $dr_tgl, $sp_tgl, $cara_bayar, $status_lunas)
    {
        $sql = "
            SELECT A
                .id_cr_byr_o,
                A.id_stt,
                A.kode_stt,
                A.c_total,
                A.tgl_masuk,
                A.id_plgn,
                A.penerima_nm,
                C.nm_pelanggan,
                B.bukti AS id_order_pay,
                B.bayar,
                ( COALESCE ( A.c_total, 0 ) - COALESCE ( B.bayar, 0 ) ) AS piutang 
            FROM
                t_order
                AS A LEFT JOIN (
                SELECT
                    id_stt,
                    string_agg ( no_kwitansi, '<br>' ) AS bukti,
                    SUM ( n_bayar ) AS bayar 
                FROM
                    t_order_pay 
                WHERE
                    id_perush = {$id_perush} 
                    AND tgl BETWEEN '{$dr_tgl}' 
                    AND '{$sp_tgl}' 
                GROUP BY
                    id_stt 
                ) AS B ON A.id_stt = B.id_stt
                LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan 
            WHERE
                A.id_perush_asal = {$id_perush}
                AND A.tgl_masuk BETWEEN '{$dr_tgl}'
                AND '{$sp_tgl}'
        ";

        if ($cara_bayar != 0) {
            $sql = $sql . " AND A.id_cr_byr_o = {$cara_bayar}";
        }

        if ($status_lunas != 0) {
            if ($status_lunas == 1) {
                $sql = $sql . " AND ( COALESCE ( A.c_total, 0 ) - COALESCE ( B.bayar, 0 ) ) = 0 ";
            } else if ($status_lunas == 2) {
                $sql = $sql . " AND ( COALESCE ( A.c_total, 0 ) - COALESCE ( B.bayar, 0 ) ) != 0 ";
            }
        }

        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function byUsers($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT A
            .id_user,
            A.id_marketing,
            A.id_stt,
            A.kode_stt,
            A.c_total,
            A.tgl_masuk,
            A.id_plgn,
            A.penerima_nm,
            C.nm_pelanggan,
            B.bukti AS id_order_pay,
            B.bayar,
            D.nm_tipe_kirim,
            E.nm_user,
            F.nm_marketing,
            ( COALESCE ( A.c_total, 0 ) - COALESCE ( B.bayar, 0 ) ) AS piutang 
        FROM
            t_order
            AS A LEFT JOIN (
            SELECT
                id_stt,
                string_agg ( no_kwitansi, '<br>' ) AS bukti,
                SUM ( n_bayar ) AS bayar 
            FROM
                t_order_pay 
            WHERE
                id_perush = {$id_perush} 
                AND tgl BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            GROUP BY
                id_stt 
            ) AS B ON A.id_stt = B.id_stt
            LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
            LEFT JOIN d_tipe_kirim AS D on A.id_tipe_kirim = D.id_tipe_kirim
            LEFT JOIN users AS E ON A.id_user = E.id_user
            LEFT JOIN m_marketing AS F ON A.id_marketing = F.id_marketing 
        WHERE
            A.id_perush_asal = {$id_perush}
            AND A.tgl_masuk BETWEEN '{$dr_tgl}'
            AND '{$sp_tgl}' 
        ORDER BY
            A.id_user
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function byDM($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT A
            .id_user,
            A.id_marketing,
            A.id_stt,
            A.kode_stt,
            A.c_total,
            A.tgl_masuk,
            A.id_plgn,
            A.penerima_nm,
            A.n_berat,
            A.n_volume,
            A.n_kubik,
            A.n_koli,
            A.no_awb,
            C.nm_pelanggan,
            CB.nm_cr_byr_o,
            B.bukti AS id_order_pay,
            B.bayar,
            D.nm_tipe_kirim,
            E.nm_user,
            F.nm_marketing,
            ( COALESCE ( A.c_total, 0 ) - COALESCE ( B.bayar, 0 ) ) AS piutang,
            DM.id_dm,
            DM.kode_dm,
            DM.tgl_berangkat,
            PRSH.nm_perush,
            VEN.nm_ven,
            TUJ.nama_wil AS tujuan
        FROM
            t_order
            AS A LEFT JOIN (
            SELECT
                id_stt,
                string_agg ( no_kwitansi, ',' ) AS bukti,
                SUM ( n_bayar ) AS bayar 
            FROM
                t_order_pay 
            WHERE
                id_perush = {$id_perush} 
                AND tgl BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            GROUP BY
                id_stt 
            ) AS B ON A.id_stt = B.id_stt
        LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan 
        LEFT JOIN d_tipe_kirim AS D on A.id_tipe_kirim = D.id_tipe_kirim
        LEFT JOIN users AS E ON A.id_user = E.id_user
        LEFT JOIN m_marketing AS F ON A.id_marketing = F.id_marketing
        LEFT JOIN m_cr_bayar_order AS CB ON CB.id_cr_byr_o = A.id_cr_byr_o
        JOIN t_order_dm AS OD ON OD.id_stt = A.id_stt
        JOIN t_dm AS DM ON OD.id_dm = DM.id_dm
        LEFT JOIN s_perusahaan AS PRSH ON PRSH.id_perush = DM.id_perush_tj
        LEFT JOIN m_vendor AS VEN ON VEN.id_ven = DM.id_ven
        LEFT JOIN m_wilayah AS TUJ ON DM.id_wil_tujuan = TUJ.id_wil
        WHERE
            A.id_perush_asal = {$id_perush} 
            AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
        ORDER BY
            A.tgl_masuk
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function byTarif($id_perush, $dr_tgl, $sp_tgl, $id_tarif = 0)
    {
        $sql = "
        SELECT A
            .id_stt,
            A.kode_stt,
            A.tgl_masuk,
            A.c_tarif,
            A.id_plgn,
            PG.nm_pelanggan,
            A.id_tipe_kirim,
            TK.nm_tipe_kirim,
            A.n_koli,
            A.n_berat,
            A.n_volume,
            A.c_total 
        FROM
            t_order
            AS A JOIN m_plgn AS PG ON PG.id_pelanggan = A.id_plgn
            JOIN d_tipe_kirim AS TK ON TK.id_tipe_kirim = A.id_tipe_kirim 
        WHERE
            A.tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
            AND A.id_perush_asal = {$id_perush}
        ";

        if (isset($id_tarif) && $id_tarif != 0) {
            $sql = $sql .= " AND A.c_tarif = '{$id_tarif}'";
        }

        $sql = $sql .= " ORDER BY A.c_tarif";

        $data = DB::select($sql);
        return $data;
    }

    public static function rekapByTarif($id_perush, $dr_tgl, $sp_tgl, $id_tarif = 0)
    {
        $sql = "
        SELECT
            c_tarif,
            COUNT ( id_stt ) AS total_stt,
            SUM ( n_koli ) AS total_koli,
            SUM ( n_berat ) AS total_berat,
            SUM ( n_volume ) AS total_volume,
            SUM ( c_total ) AS total_omset,
            AVG ( c_total ) AS rata_rata_omset 
        FROM
            t_order 
        WHERE
            tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
            AND id_perush_asal = {$id_perush} 
        ";

        if (isset($id_tarif) && $id_tarif != 0) {
            $sql = $sql .= " AND c_tarif = '{$id_tarif}'";
        }

        $sql = $sql .= " 
        GROUP BY
            c_tarif 
        ORDER BY 
            c_tarif
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function byPelanggan($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            id_pelanggan,
            nm_pelanggan,
            id_plgn_group,
            Q.* 
        FROM
            m_plgn
            AS A JOIN (
            SELECT
                id_plgn,
                EXTRACT ( 'MONTH' FROM tgl_masuk ) AS bulan,
                SUM ( c_total ) AS omset 
            FROM
                t_order 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            GROUP BY
                id_plgn,
                EXTRACT ( 'MONTH' FROM tgl_masuk ) 
            ) AS Q ON Q.id_plgn = A.id_pelanggan
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function byGroupPelanggan($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            B.nm_group,
            Q.id_plgn_group,
            SUM ( koli_keseluruhan ) AS koli_keseluruhan,
            SUM ( koli_sudah_tiba ) AS koli_sudah_tiba,
            SUM ( koli_perjalanan ) AS koli_perjalanan,
            SUM ( keseluruhan ) AS keseluruhan,
            SUM ( sudah_tiba ) AS sudah_tiba,
            SUM ( perjalanan ) AS perjalanan 
        FROM
            (
            SELECT
                B.id_plgn_group,
                COUNT ( A.id_plgn ),
                SUM ( n_koli ) AS koli_keseluruhan,
                0 AS koli_sudah_tiba,
                0 AS koli_perjalanan,
                SUM ( A.c_total ) AS keseluruhan,
                0 AS sudah_tiba,
                0 AS perjalanan 
            FROM
                t_order
                A JOIN m_plgn B ON A.id_plgn = B.id_pelanggan 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '$sp_tgl' 
            GROUP BY
                B.id_plgn_group UNION
            SELECT
                B.id_plgn_group,
                COUNT ( A.id_plgn ),
                0 AS koli_keseluruhan,
                SUM ( n_koli ) AS koli_sudah_tiba,
                0 AS koli_perjalanan,
                0 AS keseluruhan,
                SUM ( A.c_total ) AS sudah_tiba,
                0 AS perjalanan 
            FROM
                t_order
                A JOIN m_plgn B ON A.id_plgn = B.id_pelanggan 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '$sp_tgl' 
                AND id_status = '7' 
            GROUP BY
                B.id_plgn_group UNION
            SELECT
                B.id_plgn_group,
                COUNT ( A.id_plgn ),
                0 AS koli_keseluruhan,
                0 AS koli_sudah_tiba,
                SUM ( n_koli ) AS koli_perjalanan,
                0 AS keseluruhan,
                0 AS sudah_tiba,
                SUM ( A.c_total ) AS perjalanan 
            FROM
                t_order
                A JOIN m_plgn B ON A.id_plgn = B.id_pelanggan 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '$sp_tgl' 
                AND id_status != '7' 
            GROUP BY
                B.id_plgn_group 
            ) AS Q
            JOIN m_plgn_group AS B ON Q.id_plgn_group = B.id_plgn_group 
        GROUP BY
            B.nm_group,
            Q.id_plgn_group
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function OmsetbyCaraBayar($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            bulan,
            tahun,
            SUM ( omset ) AS omset,
            SUM ( bayar ) AS bayar,
            SUM ( omset - bayar ) AS piutang,
            SUM ( cash ) AS cash,
            SUM ( trasfer ) AS transfer 
        FROM
            (
            SELECT
                date_part( 'month', tgl_masuk ) AS bulan,
                date_part( 'year', tgl_masuk ) AS tahun,
                SUM ( c_total ) AS omset,
                0 AS bayar,
                0 AS cash,
                0 AS trasfer 
            FROM
                t_order 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            GROUP BY
                date_part( 'month', tgl_masuk ),
                date_part( 'year', tgl_masuk ) UNION
            SELECT
                date_part( 'month', tgl_masuk ) AS bulan,
                date_part( 'year', tgl_masuk ) AS tahun,
                0 AS omset,
                SUM ( A.n_bayar ),
                0 AS cash,
                0 AS trasfer 
            FROM
                t_order_pay
                A JOIN t_order B ON A.id_stt = B.id_stt 
            WHERE
                B.id_perush_asal = {$id_perush} 
                AND B.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            GROUP BY
                date_part( 'month', tgl_masuk ),
                date_part( 'year', tgl_masuk ) UNION
            SELECT
                date_part( 'month', tgl_masuk ) AS bulan,
                date_part( 'year', tgl_masuk ) AS tahun,
                0 AS omset,
                0 AS cash,
                SUM ( A.n_bayar ),
                0 AS trasfer 
            FROM
                t_order_pay
                A JOIN t_order B ON A.id_stt = B.id_stt 
            WHERE
                B.id_perush_asal = {$id_perush} 
                AND B.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
                AND A.id_cr_byr = '1' 
            GROUP BY
                date_part( 'month', tgl_masuk ),
                date_part( 'year', tgl_masuk ) UNION
            SELECT
                date_part( 'month', tgl_masuk ) AS bulan,
                date_part( 'year', tgl_masuk ) AS tahun,
                0 AS omset,
                0 AS bayar,
                0 AS cash,
                SUM ( A.n_bayar ) 
            FROM
                t_order_pay
                A JOIN t_order B ON A.id_stt = B.id_stt 
            WHERE
                B.id_perush_asal = {$id_perush} 
                AND B.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
                AND A.id_cr_byr = '2' 
            GROUP BY
                date_part( 'month', tgl_masuk ),
                date_part( 'year', tgl_masuk ) 
            ) AS Q 
        WHERE
            Q.bulan IS NOT NULL 
        GROUP BY
            bulan,
            tahun
        ORDER BY
            bulan,
            tahun
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function ProyeksiBiayaVsOmset($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = " SELECT a.*, b.total_biaya, c.total_omset, (c.total_omset-b.total_biaya) as total_laba, ven.nm_ven,p.nm_perush as p_perush FROM t_dm as a
            LEFT JOIN (
                SELECT id_dm, SUM(nominal) as total_biaya FROM t_dm_biaya GROUP BY id_dm
            )as b on a.id_dm = b.id_dm
            LEFT JOIN (
                SELECT a.id_dm, SUM(b.c_total)as total_omset FROM t_order_dm as a
                JOIN t_order as b on a.id_stt = b.id_stt
                GROUP BY a.id_dm
            )as c on a.id_dm = c.id_dm
            LEFT JOIN m_vendor as ven on a.id_ven = ven.id_ven
                        LEFT JOIN s_perusahaan as p on a.id_perush_tj= p.id_perush

            WHERE a.id_perush_dr = '" . $id_perush . "'
            AND a.tgl_berangkat BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            ORDER BY a.tgl_berangkat";

        $data = DB::select($sql);
        return $data;
    }

    public static function BiayaByDM($id_perush, $bulan, $tahun)
    {
        $sql = "
        SELECT
        a.id_dm, a.tgl_berangkat,a.kode_dm, u.nm_user,
		p.nm_perush, ven.nm_ven,
		k.nm_kapal_perush, s.nm_sopir, ar.no_plat,
		a.created_at, a.id_perush_dr,
        b.*, c.*
        from t_dm as a
        left join s_perusahaan as p on a.id_perush_tj = p.id_perush
        left join m_kapal_perush as k on a.id_kapal = k.id_kapal_perush
        left join m_sopir as s on a.id_sopir = s.id_sopir
        left join m_armada as ar on a.id_armada = ar.id_armada
		left join m_vendor as ven on a.id_ven = ven.id_ven
		left join users as u on a.id_user = u.id_user
        left join
            (
                select id_dm as dm_biaya, sum(nominal) as biaya from t_dm_biaya
                group by id_dm
            )as b on a.id_dm = b.dm_biaya
            left join
            (
                select id_dm as dm_bayar, sum(n_bayar) as bayar from t_dm_biaya_bayar
                group by id_dm
            )as c on a.id_dm = c.dm_bayar
        where a.id_perush_dr = '" . $id_perush . "'
		and EXTRACT(YEAR FROM a.tgl_berangkat) = '" . $tahun . "'
		and EXTRACT(MONTH FROM a.tgl_berangkat) = '" . $bulan . "'
        order by a.id_dm ASC
        ";
        $data = DB::select($sql);
        dd($sql);
        return $data;
    }

    public static function BiayaByDMTrucking($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
            SELECT A
                .id_dm,
                A.tgl_berangkat,
                A.kode_dm,
                u.nm_user,
                P.nm_perush,
                ven.nm_ven,
                K.nm_kapal_perush,
                s.nm_sopir,
                ar.no_plat,
                A.created_at,
                A.id_perush_dr,
                b.*,
                C.*,
                (B.biaya - C.bayar) as sisa 
            FROM
                t_dm
                AS A LEFT JOIN s_perusahaan AS P ON A.id_perush_tj = P.id_perush
                LEFT JOIN m_kapal_perush AS K ON A.id_kapal = K.id_kapal_perush
                LEFT JOIN m_sopir AS s ON A.id_sopir = s.id_sopir
                LEFT JOIN m_armada AS ar ON A.id_armada = ar.id_armada
                LEFT JOIN m_vendor AS ven ON A.id_ven = ven.id_ven
                LEFT JOIN users AS u ON A.id_user = u.id_user
                LEFT JOIN ( SELECT id_dm AS dm_biaya, SUM ( nominal ) AS biaya FROM t_dm_biaya GROUP BY id_dm ) AS b ON A.id_dm = b.dm_biaya
                LEFT JOIN ( SELECT id_dm AS dm_bayar, SUM ( n_bayar ) AS bayar FROM t_dm_biaya_bayar GROUP BY id_dm ) AS C ON A.id_dm = C.dm_bayar 
            WHERE
                A.id_perush_dr = {$id_perush} 
                AND A.tgl_berangkat BETWEEN '{$dr_tgl}' AND '{$sp_tgl}'
                AND (A.is_vendor IS FALSE OR A.is_vendor IS NULL)
            ORDER BY
                A.id_dm ASC;
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function showBiayaByDmTrucking($id)
    {
        $sql = "
        SELECT A
            .id_dm,
            A.id_pro_bi,
            C.nm_biaya_grup,
            A.id_biaya_grup,
            A.keterangan,
            A.tgl_posting,
            COALESCE(SUM ( A.nominal ), 0) AS biaya,
            COALESCE(SUM ( B.n_bayar ), 0) AS bayar,
            (COALESCE(SUM ( A.nominal ), 0) - COALESCE(SUM ( B.n_bayar ), 0)) AS sisa
        FROM
            t_dm_biaya AS A 
            LEFT JOIN t_dm_biaya_bayar AS B ON A.id_pro_bi = B.id_proyeksi 
            LEFT JOIN m_biaya_grup AS C ON A.id_biaya_grup = C.id_biaya_grup
        WHERE
            A.id_dm = {$id} 
        GROUP BY
            A.id_dm,
            A.id_pro_bi,
            A.id_biaya_grup,
            C.nm_biaya_grup,
            A.keterangan       
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function BiayaByDMVendor($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
            SELECT A
                .id_dm,
                A.tgl_berangkat,
                A.kode_dm,
                u.nm_user,
                P.nm_perush,
                ven.nm_ven,
                K.nm_kapal_perush,
                s.nm_sopir,
                ar.no_plat,
                A.created_at,
                A.id_perush_dr,
                b.*,
                C.*,
                (B.biaya - C.bayar) as sisa 
            FROM
                t_dm
                AS A LEFT JOIN s_perusahaan AS P ON A.id_perush_tj = P.id_perush
                LEFT JOIN m_kapal_perush AS K ON A.id_kapal = K.id_kapal_perush
                LEFT JOIN m_sopir AS s ON A.id_sopir = s.id_sopir
                LEFT JOIN m_armada AS ar ON A.id_armada = ar.id_armada
                LEFT JOIN m_vendor AS ven ON A.id_ven = ven.id_ven
                LEFT JOIN users AS u ON A.id_user = u.id_user
                LEFT JOIN ( SELECT id_dm AS dm_biaya, SUM ( nominal ) AS biaya FROM t_dm_biaya GROUP BY id_dm ) AS b ON A.id_dm = b.dm_biaya
                LEFT JOIN ( SELECT id_dm AS dm_bayar, SUM ( n_bayar ) AS bayar FROM t_dm_biaya_bayar GROUP BY id_dm ) AS C ON A.id_dm = C.dm_bayar 
            WHERE
                A.id_perush_dr = {$id_perush} 
                AND A.tgl_berangkat BETWEEN '{$dr_tgl}' AND '{$sp_tgl}'
                AND A.is_vendor IS TRUE
            ORDER BY
                A.id_dm ASC;
        ";

        $data = DB::select($sql);
        return $data;
    }


    public static function OmsetByBulan($id_perush, $tahun)
    {
        $sql = "
            select EXTRACT(MONTH FROM t_order.tgl_masuk) as month, sum(c_total) as TotalAmount
            from t_order
            where EXTRACT(YEAR FROM t_order.tgl_masuk) = '" . $tahun . "'
            group by EXTRACT(MONTH FROM t_order.tgl_masuk)
            order by EXTRACT(MONTH FROM t_order.tgl_masuk) ASC
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function BayarByBulan($id_perush, $tahun)
    {
        $sql = "
        select EXTRACT(MONTH FROM b.tgl_masuk) as masuk, EXTRACT(MONTH FROM a.tgl) as month, sum(a.n_bayar) as TotalAmount
        from t_order_pay as a
        join t_order as b on a.id_stt = b.id_stt
        where EXTRACT(YEAR FROM a.tgl) = '" . $tahun . "'
        group by EXTRACT(MONTH FROM a.tgl),EXTRACT(MONTH FROM b.tgl_masuk)
        order by EXTRACT(MONTH FROM a.tgl) ASC
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function PrestasiPenagihan($id_perush, $dr_tgl, $sp_tgl)
    {
        $omset = "
        SELECT 
            EXTRACT ( MONTH FROM tgl_masuk ) AS bulan_stt,
            EXTRACT ( YEAR FROM tgl_masuk ) AS tahun_stt,
            COUNT ( id_stt ) AS total_stt,
            SUM ( c_total ) AS omset	
        FROM
            t_order 
            WHERE tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
            AND id_perush_asal = {$id_perush} 
        GROUP BY
            EXTRACT ( MONTH FROM tgl_masuk ),
            EXTRACT ( YEAR FROM tgl_masuk )
            ORDER BY
            EXTRACT ( MONTH FROM tgl_masuk ),
            EXTRACT ( YEAR FROM tgl_masuk )
        ";

        $bayar = "
        SELECT 
            EXTRACT ( MONTH FROM A.tgl_masuk ) AS bulan_stt,
            EXTRACT ( YEAR FROM A.tgl_masuk ) AS tahun_stt,
            COUNT ( A.id_stt ) AS total_stt,
            SUM ( A.c_total ) AS omset,
            EXTRACT ( MONTH FROM B.tgl ) AS bulan_bayar,
            EXTRACT ( YEAR FROM B.tgl ) AS tahun_bayar,
            COUNT ( B.id_order_pay ) AS total_bayar,
            SUM ( B.n_bayar ) AS bayar 
        FROM
            t_order
            A LEFT JOIN t_order_pay B ON A.id_stt = B.id_stt 
        WHERE
            A.tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
            AND A.id_perush_asal = {$id_perush} 
        GROUP BY
            EXTRACT ( MONTH FROM A.tgl_masuk ),
            EXTRACT ( YEAR FROM A.tgl_masuk ),
            EXTRACT ( MONTH FROM B.tgl ),
            EXTRACT ( YEAR FROM B.tgl ) 
        ORDER BY
            EXTRACT ( MONTH FROM A.tgl_masuk ),
            EXTRACT ( YEAR FROM A.tgl_masuk )
        ";

        $omsetData = DB::select($omset);
        $bayarData = DB::select($bayar);
        $data = (object)[
            'omset_data' => $omsetData,
            'bayar_data' => $bayarData,
        ];
        return $data;
    }

    public static function LamaHariSTT($id_perush, $tahun, $bulan)
    {
        $sql = "
        select a.id_dm, s.nm_sopir,k.nm_kapal_perush, p.nm_perush, a.tgl_berangkat,
        b.*
        from t_dm as a
        right join
            (
                select a.*,b.* from t_order_dm as a
                left join
                (
                    select a.id_stt,a.c_total,
                    b.id_order_pay,b.n_bayar,b.tgl
                    from t_order as a
                    left join t_order_pay as b
                    on a.id_stt = b.id_stt
                )as b on a.id_stt = b.id_stt
            )as b on a.id_dm = b.id_dm
        left join m_sopir as s on a.id_sopir = s.id_sopir
        left join s_perusahaan as p on a.id_perush_tj = p.id_perush
        left join m_kapal_perush as k on a.id_kapal = k.id_kapal_perush
		where a.id_perush_dr = '" . $id_perush . "'
		and EXTRACT(YEAR FROM a.tgl_berangkat) = '" . $tahun . "'
		and EXTRACT(MONTH FROM a.tgl_berangkat) = '" . $bulan . "'
        ";
        dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function newLamaHariStt($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT A
            .id_dm,
            A.tgl_berangkat,
            A.tgl_sampai,
            A.kode_dm,
            A.id_sopir,
            A.id_perush_tj,
            A.id_kapal,
            A.id_ven,
            H.nm_perush,
            I.nm_sopir,
            J.nm_ven,
            K.nm_kapal,
            C.id_stt,
            C.kode_stt,
            C.tgl_masuk,
            C.c_total,
            D.tgl_update,
            M.tgl AS tgl_kembali,
            ( M.tgl - C.tgl_masuk ) AS stt_kembali,
            ( D.tgl_update - C.tgl_masuk ) AS sampai,
            F.tgl,
            F.kode_invoice,
            ( F.tgl - D.tgl_update ) AS inv,
            G.tgl_bayar,
            G.no_kwitansi,
            G.total, 
            G.n_bayar,
            (COALESCE(C.c_total, 0) - COALESCE(G.total, 0)) AS piutang
        FROM
            t_dm
            A JOIN t_order_dm B ON A.id_dm = B.id_dm
            JOIN t_order C ON B.id_stt = C.id_stt
            LEFT JOIN ( SELECT * FROM t_history_stt WHERE id_status = '7' ) AS D ON C.id_stt = D.id_stt
            LEFT JOIN keu_draft_invoice E ON E.id_stt = C.id_stt
            LEFT JOIN keu_invoice_pelanggan F ON E.id_invoice = F.id_invoice
            LEFT JOIN (
            SELECT
                id_stt,
                SUM ( n_bayar ) AS total,
                string_agg ( no_kwitansi, ',' ) AS no_kwitansi,
                string_agg ( tgl :: TEXT, ',' ) AS tgl_bayar, 
                string_agg ( n_bayar :: TEXT, ',' ) AS n_bayar 
            FROM
                t_order_pay 
            GROUP BY
                id_stt 
            ) AS G ON G.id_stt = C.id_stt
            LEFT JOIN s_perusahaan H ON H.id_perush = A.id_perush_tj
            LEFT JOIN m_sopir I ON I.id_sopir = A.id_sopir
            LEFT JOIN m_vendor J ON J.id_ven = A.id_ven
            LEFT JOIN m_kapal K ON K.id_kapal = A.id_kapal
            LEFT JOIN t_stt_kembali_detail AS L ON C.id_stt = L.id_stt
            LEFT JOIN t_stt_kembali AS M ON M.id_stt_kembali = L.id_stt_kembali 
        WHERE
            A.id_perush_dr = {$id_perush} 
            AND A.tgl_berangkat BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}';
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function LamaHariSTTbyGroup($id_perush, $tahun)
    {
        $sql = "
        select
        EXTRACT(MONTH FROM a.tgl_berangkat) as month,
        count(b.id_dm) as total_dm,
        count(b.id_stt) as total_stt,
        count(b.stt_bayar) as stt_bayar,
        round(avg(b.hari_bayar) , 2) as rata_bayar,
        sum(b.c_total) as total_pend,
        sum(b.n_bayar) as total_bayar

        from t_dm as a
        right join
            (
                select
                a.id_dm,b.id_stt,b.c_total, b.n_bayar, b.stt_bayar,b.hari_bayar
                from t_order_dm as a
                left join
                (
                    select a.id_stt,a.c_total,
                    b.id_order_pay,b.n_bayar,b.tgl,b.id_stt as stt_bayar,
                    (a.tgl_masuk - b.tgl) as hari_bayar
                    from t_order as a
                    left join t_order_pay as b
                    on a.id_stt = b.id_stt
                )as b on a.id_stt = b.id_stt
            )as b on a.id_dm = b.id_dm
			where a.id_perush_dr = '" . $id_perush . "'
			and EXTRACT(YEAR FROM a.tgl_berangkat) = '" . $tahun . "'
        group by EXTRACT(MONTH FROM a.tgl_berangkat)
        order by month ASC
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function OmsetByTipeKirim($id_perush)
    {
        $sql = "
        select a.id_tipe_kirim,a.nm_tipe_kirim,
        count(b.id_stt) as stt,
        sum(b.n_berat) as berat,
        sum(b.n_volume) as volume,
        sum(b.n_koli) as koli,
        sum(b.c_total)as omset,
				w.nama_wil
				from d_tipe_kirim as a
        join t_order as b on a.id_tipe_kirim = b.id_tipe_kirim
				join m_wilayah as w on b.penerima_id_region = w.id_wil
        where b.id_perush_asal = '" . $id_perush . "'
        group by a.id_tipe_kirim,w.nama_wil
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function showOmsetByTipeKirim($id, $id_perush)
    {
        $sql = "
        SELECT
            d.nm_tipe_kirim,
            A.id_stt,
            A.tgl_masuk,
            A.pengirim_nm,
            A.n_berat,
            A.n_volume,
            A.n_koli,
            A.c_total,
            w.nama_wil
        FROM
            t_order
            AS A JOIN d_tipe_kirim AS b ON A.id_tipe_kirim = b.id_tipe_kirim
            JOIN m_wilayah AS w ON A.penerima_id_region = w.id_wil
            JOIN d_tipe_kirim AS d ON A.id_tipe_kirim = d.id_tipe_kirim
        WHERE
            A.id_perush_asal = '" . $id_perush . "'
            AND A.id_tipe_kirim = '" . $id . "';
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function OmsetByLayanan($id_perush, $f_layanan = null, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "
        SELECT A
            .id_layanan,
            A.nm_layanan,
            COUNT ( b.id_stt ) AS stt,
            SUM ( b.n_berat ) AS berat,
            SUM ( b.n_volume ) AS volume,
            SUM ( b.n_koli ) AS koli,
            SUM ( b.c_total ) AS omset,
            w.nama_wil
        FROM
            m_layanan
            AS A JOIN t_order AS b ON A.id_layanan = b.id_layanan
            JOIN m_wilayah AS w ON b.penerima_id_region = w.id_wil
        WHERE
            b.id_perush_asal = '" . $id_perush . "'
        GROUP BY
            A.id_layanan,
            w.nama_wil
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function OmsetByLayanan2($id_perush, $f_layanan = null, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = " SELECT A.id_layanan,A.nm_layanan, COUNT ( b.id_stt ) AS stt, SUM ( b.n_berat ) AS berat, 
        SUM ( b.n_volume ) AS volume,SUM ( b.n_koli ) AS koli,SUM ( b.c_total ) AS omset, b.tgl_masuk
        FROM m_layanan AS A 
        JOIN t_order AS b ON A.id_layanan = b.id_layanan
        WHERE b.id_perush_asal = '32' and b.tgl_masuk BETWEEN '" . $dr_tgl . "' and '" . $sp_tgl . "' ";

        if ($f_layanan != null) {
            $sql .= "and a.id_layanan='" . $f_layanan . "' ";
        }

        $sql .= " GROUP BY A.id_layanan,b.tgl_masuk order by b.tgl_masuk asc  ";

        $data = DB::select($sql);

        return $data;
    }

    public static function OmsetByLayanan3($id_perush, $dr_tgl, $sp_tgl, $id_layanan = null)
    {
        $data = DB::table('t_order')
            ->select(
                't_order.id_layanan',
                'm_layanan.nm_layanan',
                DB::raw('SUM(t_order.c_total) AS omset'),
                DB::raw('COUNT(t_order.id_stt) AS stt'),
                DB::raw('SUM(t_order.n_berat) AS berat'),
                DB::raw('SUM(t_order.n_volume) AS volume'),
                DB::raw('SUM(t_order.n_koli) AS koli')
            )
            ->join('m_layanan', 't_order.id_layanan', '=', 'm_layanan.id_layanan')
            ->where('t_order.id_perush_asal', '=', $id_perush)
            ->whereBetween('t_order.tgl_masuk', [$dr_tgl, $sp_tgl]);

        if ($id_layanan != null) {
            $data = $data->where('t_order.id_layanan', '=', $id_layanan);
        }

        $data = $data->groupBy(['t_order.id_layanan', 'm_layanan.nm_layanan'])
            ->orderBy('t_order.id_layanan', 'ASC')
            ->get();

        return $data;
    }

    public static function showOmsetByLayanan($id, $id_perush)
    {
        $sql = "
        SELECT
            b.nm_layanan,
            A.id_stt,
            A.tgl_masuk,
            A.pengirim_nm,
            A.n_berat,
            A.n_volume,
            A.n_koli,
            A.c_total,
            w.nama_wil
        FROM
            t_order
            AS A JOIN m_layanan AS b ON A.id_layanan = b.id_layanan
            JOIN m_wilayah AS w ON A.penerima_id_region = w.id_wil
        WHERE
            A.id_perush_asal = '" . $id_perush . "'
            AND A.id_layanan = '" . $id . "';
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function SttByOrigin($id_perush, $start = null, $end = null)
    {
        $sql = "
        SELECT A.pengirim_id_region,A.penerima_id_region,w.nama_wil as Asal,z.nama_wil as Tujuan,
        COUNT ( A.id_stt ) AS jumlah_stt,SUM ( A.n_koli ) AS jumlah_koli,SUM ( A.n_berat ) AS berat,
        SUM ( A.n_volume ) AS volume,
        SUM ( A.c_total ) AS total
        FROM t_order AS A left JOIN m_wilayah AS w ON A.pengirim_id_region = w.id_wil 
        left JOIN m_wilayah AS z ON A.penerima_id_region = z.id_wil WHERE a.id_perush_asal = '" . $id_perush . "' ";

        if ($start != null) {
            $sql .= " and a.tgl_masuk >= '" . $start . "' ";
        }

        if ($end != null) {
            $sql .= " and a.tgl_masuk <= '" . $end . "' ";
        }

        $sql .= " GROUP BY A.pengirim_id_region,A.penerima_id_region,w.nama_wil,z.nama_wil";
        //dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public function showSttByOrigin($id_perush, $id)
    {
        $sql = "
        SELECT
            A.pengirim_id_region,
            A.penerima_id_region,
            w.nama_wil AS Asal,
            z.nama_wil AS Tujuan,
            COUNT ( A.id_stt ) AS jumlah_stt,
            SUM ( A.n_koli ) AS jumlah_koli,
            SUM ( A.n_berat ) AS berat,
            SUM ( A.n_volume ) AS volume,
            SUM ( A.c_total ) AS total
        FROM
            t_order
            AS A JOIN m_wilayah AS w ON A.pengirim_id_region = w.id_wil
            JOIN m_wilayah AS z ON A.penerima_id_region = z.id_wil
        WHERE
            A.id_perush_asal = '" . $id_perush . "'
            AND A.pengirim_id_region = '" . $id . "'
        GROUP BY
            A.pengirim_id_region,
            A.penerima_id_region,
            w.nama_wil,
            z.nama_wil
        ";
    }

    public static function OmsetByRegion($id_perush, $jns_region = 'tujuan', $start = null, $end = null)
    {
        $where = "";
        $join = "";

        if ($start != null && $end != null) {
            $where .= "AND O.tgl_masuk BETWEEN '{$start}' AND '{$end}'";
        }

        if ($jns_region == 'asal') {
            $join = "JOIN m_wilayah W ON O.pengirim_id_region = W.id_wil";
            $field_select = "O.pengirim_id_region AS id_region";
        } else {
            $join = "JOIN m_wilayah W ON O.penerima_id_region = W.id_wil";
            $field_select = "O.penerima_id_region AS id_region";
        }

        $sql = "
        SELECT
    Q.id_region,
	CONCAT ( Q.prov, ' - ', Q.kab ) AS kab,
	Q.nama_wil,
	Q.jml_stt,
	Q.jml_koli,
	Q.jml_berat,
	Q.jml_volume,
	Q.jml_kubik,
	Q.jml_omset
FROM
	(
	SELECT
		O.id_perush_asal,
		{$field_select},
		COALESCE (PROV.nama_wil, W.nama_wil) AS Prov,
		COALESCE ( KAB.nama_wil, W.nama_wil ) AS Kab,
		W.nama_wil,
		COUNT ( O.id_stt ) AS jml_stt ,
		SUM(O.n_koli) AS jml_koli,
		SUM(O.n_berat) AS jml_berat,
		SUM(O.n_kubik) AS jml_kubik,
		SUM(O.n_volume) AS jml_volume,
		SUM(O.c_total) AS jml_omset
	FROM
		t_order O
		{$join}
		LEFT JOIN m_wilayah KAB ON W.kab_id = KAB.id_wil
		LEFT JOIN m_wilayah PROV ON W.prov_id = PROV.id_wil 
	WHERE
        O.id_perush_asal = {$id_perush}
        {$where}
		
	GROUP BY
		O.id_perush_asal,
		id_region,
		PROV.nama_wil,
		KAB.nama_wil,
		W.nama_wil 
	ORDER BY
		id_region 
	) Q -- GROUP BY WIL
	
ORDER BY
	Q.jml_omset DESC
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function detailOmsetByRegion($id_perush, $dr_tgl, $sp_tgl, $id_wil, $tipe = 'tujuan')
    {
        $sql = "
        SELECT 
            A.id_layanan,
            A.kode_stt,
            A.tgl_masuk,
            A.id_plgn,
            A.n_berat,
            A.n_volume,
            A.n_kubik,
            A.n_koli,
            A.c_total,
            B.nm_pelanggan 
        FROM
            t_order
            A JOIN m_plgn AS B ON A.id_plgn = B.id_pelanggan 
        WHERE
            A.tgl_masuk BETWEEN '" . $dr_tgl . "' 
            AND '" . $sp_tgl . "' 
            AND A.id_perush_asal = " . $id_perush . " 
        ";
        if (isset($tipe) && $tipe == 'asal') {
            $sql = $sql . " AND A.pengirim_id_region = '" . $id_wil . "' ";
        } else {
            $sql = $sql . " AND A.penerima_id_region = '" . $id_wil . "' ";
        }
        $data = DB::select($sql);
        return $data;
    }


    public static function HutangVendor($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            Q.id_ven,
            VEN.nm_ven,
            COALESCE ( SUM ( total_sblm ) - SUM ( bayar_sblm ), 0 ) AS saldo_awal,
            SUM ( stt ) AS stt,
            SUM ( vendor ) AS vendor,
            COALESCE ( SUM ( stt ) + SUM ( vendor ), 0 ) AS total,
            SUM ( bayar ) AS bayar,
            string_agg ( kode_dm, ',' ) AS kode_dm 
        FROM
            (
            SELECT
                DM.id_ven,
                DM.kode_dm,
                nominal AS total_sblm,
                0 AS bayar_sblm,
                0 AS stt,
                0 AS vendor,
                0 AS bayar 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_posting <= '{$dr_tgl}' UNION
                
            SELECT
                DM.id_ven,
                DM.kode_dm,
                0 AS total_sblm,
                A.n_bayar AS bayar_sblm,
                0 AS stt,
                0 AS vendor,
                0 AS bayar 
            FROM
                t_dm_biaya_bayar
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_bayar <= '{$dr_tgl}' UNION
                
            SELECT
                DM.id_ven,
                DM.kode_dm,
                0 AS total_sblm,
                0 AS byr_sblm,
                nominal AS stt,
                0 as vendor,
                0 AS bayar 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis = '0' 
                AND A.tgl_posting BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION
                
                SELECT
                DM.id_ven,
                DM.kode_dm,
                0 AS total_sblm,
                0 AS byr_sblm,
                0 as stt,
                nominal AS vendor,
                0 AS bayar 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis = '2' 
                AND A.tgl_posting BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION
                
            SELECT
                DM.id_ven,
                DM.kode_dm,
                0 AS total_sblm,
                0 AS byr_sblm,
                0 AS stt,
                0 AS vendor,
                A.n_bayar 
            FROM
                t_dm_biaya_bayar
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_bayar BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            ) AS Q
            JOIN m_vendor AS VEN ON Q.id_ven = VEN.id_ven 
        GROUP BY
            Q.id_ven,
            VEN.nm_ven
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function detailHutangVendor($id_perush, $id_ven, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            id_ven,
            kode_dm,
            tgl_posting,
            tgl_bayar,
            keterangan,
            SUM ( stt ) AS stt,
            SUM ( vendor ) AS vendor,
            COALESCE ( SUM ( stt ) + SUM ( vendor ), 0 ) AS hutang,
            SUM ( bayar ) AS bayar 
        FROM
            (
            SELECT
                DM.id_ven,
                DM.kode_dm,
                A.tgl_posting :: TEXT,
                '' tgl_bayar,
                A.keterangan,
                nominal AS stt,
                0 AS vendor,
                0 AS bayar 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis = '0' 
                AND A.tgl_posting BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION
            SELECT
                DM.id_ven,
                DM.kode_dm,
                A.tgl_posting :: TEXT,
                '' tgl_bayar,
                A.keterangan,
                0 AS stt,
                nominal AS vendor,
                0 AS bayar 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis = '2' 
                AND A.tgl_posting BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION
            SELECT
                DM.id_ven,
                DM.kode_dm,
                '' AS tgl_posting,
                A.tgl_bayar :: TEXT,
                A.info,
                0 AS stt,
                0 AS vendor,
                A.n_bayar 
            FROM
                t_dm_biaya_bayar
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_bayar BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            ) AS Q 
        WHERE
            id_ven = {$id_ven} 
        GROUP BY
            id_ven,
            kode_dm,
            tgl_posting,
            tgl_bayar,
            keterangan
        ORDER BY tgl_bayar, tgl_posting
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function saldoAwalHutangVendor($id_perush, $id_ven, $dr_tgl)
    {
        $sql = "
        SELECT
            id_ven,
            COALESCE ( SUM ( total_sblm ) - SUM ( bayar_sblm ), 0 ) AS saldo_awal 
        FROM
            (
            SELECT
                DM.id_ven,
                DM.kode_dm,
                nominal AS total_sblm,
                0 AS bayar_sblm 
            FROM
                t_dm_biaya
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_posting <= '{$dr_tgl}' UNION
            SELECT
                DM.id_ven,
                DM.kode_dm,
                0 AS total_sblm,
                A.n_bayar AS bayar_sblm 
            FROM
                t_dm_biaya_bayar
                AS A JOIN t_dm AS DM ON DM.id_dm = A.id_dm 
            WHERE
                DM.id_perush_dr = {$id_perush} 
                AND DM.is_vendor = TRUE 
                AND A.id_jenis != '1' 
                AND A.tgl_bayar <= '{$dr_tgl}' 
            ) AS Q 
        WHERE
            id_ven = {$id_ven} 
        GROUP BY
            id_ven
        ";

        $data = DB::select($sql)[0];
        return $data;
    }
}
