<?php

namespace Modules\Keuangan\Entities;
use DB;

use Illuminate\Database\Eloquent\Model;

class LaporanOmset extends Model
{
    protected $fillable = [];

    public static function OmsetVsCashin($id_perush, $dr_tgl, $sp_tgl) {
        $sql = "
        SELECT 
            EXTRACT(MONTH FROM hasil.tgl_masuk) as bulan, 
            EXTRACT(YEAR FROM hasil.tgl_masuk) as tahun, 
            SUM(hasil.total) as total,
            SUM(hasil.omset - hasil.diskon) as omset,
            SUM(hasil.omset) as pend_kirim, 
            SUM(hasil.diskon) as diskon, 
            SUM(hasil.asuransi) as asuransi, 
            SUM(hasil.packing) as packing, 
            SUM(hasil.pembayaran) as bayar, 
            SUM (hasil.tunai) as tunai, 
            SUM (hasil.invoice) as invoice
        FROM (
            SELECT 
                id_stt, 
                tgl_masuk, 
                id_perush_asal, 
                c_total as total, 
                n_hrg_bruto as omset, 
                n_diskon as diskon, 
                n_asuransi as asuransi, 
                n_packing as packing, 
                0 as pembayaran, 
                0 as tunai,
                0 as invoice
            FROM 
                t_order
            WHERE tgl_masuk BETWEEN '" . $dr_tgl ."' AND '" . $sp_tgl ."'
            UNION 
            SELECT 
                id_order_pay, 
                tgl, 
                id_perush, 
                0 as total, 
                0 as omset, 
                0 as diskon, 
                0 as asuransi, 
                0 as packing, 
                n_bayar as pembayaran, 
                0 as tunai,
                0 as invoice 
            FROM 
                t_order_pay
            WHERE t_order_pay.tgl BETWEEN '" . $dr_tgl ."' AND '" . $sp_tgl ."'
            UNION
            SELECT 
                t_order.id_stt,
                t_order_pay.tgl,
                t_order_pay.id_perush,
                0 as total, 
                0 as omset, 
                0 as diskon, 
                0 as asuransi, 
                0 as packing, 
                0 as pembayaran, 
                CASE WHEN t_order.tgl_masuk = t_order_pay.tgl THEN t_order_pay.n_bayar ELSE 0 END AS tunai,
                CASE WHEN t_order.tgl_masuk <> t_order_pay.tgl THEN t_order_pay.n_bayar ELSE 0 END AS invoice
            FROM t_order
            JOIN t_order_pay ON t_order.id_stt = t_order_pay.id_stt
                    WHERE t_order_pay.tgl BETWEEN '" . $dr_tgl ."' AND '" . $sp_tgl ."'
            ) as hasil
            WHERE hasil.id_perush_asal = '" . $id_perush ."'
            GROUP BY bulan, tahun
            ORDER BY bulan, tahun
            ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function SaldoAwalCashIn($id_perush, $sp_tgl) {
        $sql = "
        SELECT COALESCE
            ( SUM ( omset ) - SUM ( bayar ), 0 ) AS total 
        FROM
            (
            SELECT
                id_perush_asal AS id_perush,
                tgl_masuk AS tgl,
                id_plgn AS id_pelanggan,
                c_total AS omset,
                0 AS bayar 
            FROM
                t_order 
            WHERE
                id_perush_asal = '" . $id_perush . "' 
                AND tgl_masuk < '" . $sp_tgl . "' 
            UNION
            SELECT
                id_perush,
                tgl,
                id_plgn,
                0 AS omset,
                n_bayar AS bayar 
            FROM
                t_order_pay 
            WHERE
                id_perush = '" . $id_perush . "' 
            AND tgl < '" . $sp_tgl . "' 
            ) AS query
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function DetailSaldoAwal($id_perush, $sp_tgl) {
        $sql = "
        SELECT
            query.id_pelanggan,
            m_plgn.nm_pelanggan AS nm_pelanggan,
            m_plgn.alamat,
            m_plgn.telp,
            SUM ( omset ) AS omset,
            SUM ( bayar ) AS bayar,
            COALESCE ( SUM ( omset ) - SUM ( bayar ), 0 ) AS piutang,
            SUM ( bayar_sekarang ) AS bayar_hingga_sekarang 
        FROM
            (
            SELECT
                id_perush_asal AS id_perush,
                tgl_masuk AS tgl,
                id_plgn AS id_pelanggan,
                c_total AS omset,
                0 AS bayar,
                0 AS bayar_sekarang 
            FROM
                t_order 
            WHERE
                id_perush_asal = '" . $id_perush . "' 
                AND tgl_masuk < '" . $sp_tgl . "' 
            UNION
            SELECT
                id_perush,
                tgl,
                id_plgn,
                0 AS omset,
                n_bayar AS bayar,
                0 AS bayar_sekarang 
            FROM
                t_order_pay 
            WHERE
                id_perush = '" . $id_perush . "' 
                AND tgl < '" . $sp_tgl . "'  
            UNION
            SELECT
                id_perush,
                tgl,
                t_order_pay.id_plgn,
                0 AS omset,
                0 AS bayar,
                n_bayar AS bayar 
            FROM
                t_order_pay
                JOIN t_order ON t_order.id_stt = t_order_pay.id_stt 
            WHERE
                id_perush_asal = '" . $id_perush . "' 
                AND t_order.tgl_masuk < '" . $sp_tgl . "' 
            ) AS query
            JOIN m_plgn ON m_plgn.id_pelanggan = query.id_pelanggan 
        GROUP BY
            query.id_pelanggan,
            m_plgn.nm_pelanggan,
            m_plgn.telp,
            m_plgn.alamat
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function DetailSaldoAwal2($id_perush, $sp_tgl) {
        $sql = "
        SELECT
            m_plgn.id_pelanggan,
            m_plgn.nm_pelanggan,
            m_plgn.alamat,
            m_plgn.telp,
            SUM ( Q.omset ) AS omset,
            SUM ( Q.bayar ) AS bayar,
            ( SUM ( Q.omset ) - SUM ( Q.bayar ) ) AS piutang,
            SUM ( Q.bayar_hingga_sekarang ) AS bayar_hingga_sekarang 
        FROM
            (
            SELECT
                id_plgn,
                SUM ( c_total ) AS omset,
                0 AS bayar,
                0 AS bayar_hingga_sekarang 
            FROM
                t_order 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk <= '{$sp_tgl}' 
            GROUP BY
                id_plgn UNION
            SELECT
                id_plgn,
                0 AS omset,
                SUM ( n_bayar ),
                0 AS bayar_hingga_sekarang 
            FROM
                t_order_pay 
            WHERE
                id_perush = {$id_perush} 
                AND tgl <= '{$sp_tgl}' 
            GROUP BY
                id_plgn UNION
            SELECT
                t_order_pay.id_plgn,
                0 AS omset,
                0 AS bayar,
                SUM ( n_bayar ) 
            FROM
                t_order_pay
                JOIN t_order ON t_order.id_stt = t_order_pay.id_stt 
            WHERE
                t_order_pay.id_perush = {$id_perush} 
                AND tgl <= CURRENT_DATE 
                AND t_order.tgl_masuk <= '{$sp_tgl}' 
            GROUP BY
                t_order_pay.id_plgn 
            ) Q
            LEFT JOIN m_plgn ON Q.id_plgn = m_plgn.id_pelanggan 
        GROUP BY
            m_plgn.id_pelanggan,
            m_plgn.nm_pelanggan,
            m_plgn.telp
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function temp() : Returntype {
        $sql = "
        SELECT 
            IDP,
            Q.ID_PLGN,
            B.nm_pelanggan,
            B.ALAMAT,
            B.TELP,
            SUM(TOTAL) TOTAL,
            SUM(BAYAR) BAYAR,
            SUM(Q.TOTAL) - SUM(Q.BAYAR) AS PIUTANG,
            SUM(UDAH_BAYAR) UDAH_BAYAR,
            SUM(TOTAL) - SUM(UDAH_BAYAR) AS PIUT_NOW
            FROM
            (
            SELECT 
            A.ID_PERUSH_ASAL AS IDP,
            A.ID_STT NO_BUKTI,
            A.ID_PLGN,
            A.tgl_masuk TGL,
            A.C_TOTAL TOTAL,
            0 AS BAYAR,
            A.X_N_BAYAR UDAH_BAYAR
            FROM
            T_ORDER A
            WHERE A.ID_PERUSH_ASAL = '{$id_perush}'
            AND A.tgl_masuk < '2023-08-01'

            UNION ALL

            SELECT
            A.ID_PERUSH,
            A.id_order_pay,
            B.id_plgn,
            A.TGL,
            0 AS TOTAL,
            A.N_BAYAR,
            0.0 AS UDAH_BAYAR
            FROM
            T_ORDER_PAY A
            LEFT OUTER JOIN T_ORDER B ON (A.ID_STT = B.ID_STT)
            WHERE A.ID_PERUSH = '{$id_perush}'
            AND A.TGL < '2023-08-01'
            ) Q
            LEFT OUTER JOIN M_PLGN B ON (Q.ID_PLGN = B.id_pelanggan)
            GROUP BY
            Q.IDP,
            Q.ID_PLGN,
            B.nm_pelanggan,
            B.ALAMAT,
            B.TELP
            HAVING SUM(Q.TOTAL) - SUM(Q.BAYAR) <> 0
        ";
    }

    public static function DetailTotalOmset($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            Q.id_stt,
            Q.kode,
            Q.tgl_masuk,
            m_plgn.nm_pelanggan,
            m_marketing.nm_marketing,
            SUM ( omset ) AS omset,
            SUM ( bayar ) AS bayar,
            ( SUM ( omset ) - SUM ( bayar ) ) AS piutang,
            SUM ( bayar_sekarang ) AS bayar_sekarang 
        FROM
            (
            SELECT
                id_perush_asal AS id_perush,
                tgl_masuk,
                id_stt AS id_stt,
                kode_stt AS kode,
                id_plgn,
                id_marketing,
                c_total AS omset,
                0 AS bayar,
                0 bayar_sekarang 
            FROM
                t_order 
            WHERE
                id_perush_asal = {$id_perush} 
                AND tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION ALL
            SELECT
                id_perush,
                t_order.tgl_masuk,
                t_order_pay.id_stt,
                t_order.kode_stt,
                t_order_pay.id_plgn,
                t_order.id_marketing,
                0 AS omset,
                n_bayar AS bayar,
                0 bayar_sekarang 
            FROM
                t_order_pay
                JOIN t_order ON t_order_pay.id_stt = t_order.id_stt 
            WHERE
                id_perush = {$id_perush} 
                AND t_order.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' UNION ALL
            SELECT
                id_perush,
                t_order.tgl_masuk,
                t_order_pay.id_stt,
                t_order.kode_stt,
                t_order_pay.id_plgn,
                t_order.id_marketing,
                0 AS omset,
                0 AS bayar,
                n_bayar AS bayar_sekarang 
            FROM
                t_order_pay
                JOIN t_order ON t_order_pay.id_stt = t_order.id_stt 
            WHERE
                id_perush = {$id_perush} 
                AND t_order.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
                AND tgl <= CURRENT_DATE  
            ) AS Q
            LEFT JOIN m_plgn ON Q.id_plgn = m_plgn.id_pelanggan 
            LEFT JOIN m_marketing ON Q.id_marketing = m_marketing.id_marketing 
        GROUP BY
            id_stt,
            kode,
            m_plgn.nm_pelanggan,
            Q.tgl_masuk,
            m_marketing.nm_marketing
        ORDER BY 
            Q.tgl_masuk
        ";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }
}
