<?php

namespace Modules\Keuangan\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Piutang extends Model
{
    protected $fillable = [];

    public static function getData($perpage, $page, $id_perush, $id_pelanggan = null, $id_group = null, $f_start = null, $f_end = null, $tipe_data = null)
    {
        $sql = "
        SELECT A
        .id_pelanggan,
        A.nm_pelanggan,
        A.alamat,
        A.telp,
        A.id_plgn_group,
        g.kode_plgn_group, g.nm_group,
        COALESCE ( b.total, 0 ) AS total,
        COALESCE ( b.jml_stt, 0 ) AS total_stt,
        COALESCE ( lunas.stt_lunas, 0 ) AS total_stt_byr,
        COALESCE ( ng.bayar, 0 ) AS bayar,
        (COALESCE ( b.total, 0 ) - COALESCE ( ng.bayar, 0 )) AS kurang,
        COALESCE ( A.n_limit_piutang, 0 ) AS limit
        FROM
        m_plgn
        AS A
        JOIN m_plgn_group as g on a.id_plgn_group=g.id_plgn_group ";

        $sql .= " LEFT JOIN ( SELECT id_plgn, SUM ( c_total ) AS total, COUNT ( id_stt ) AS jml_stt FROM t_order
        where t_order.tgl_masuk between '" . $f_start . "' and '" . $f_end . "'
        GROUP BY id_plgn ) AS b ON A.id_pelanggan = b.id_plgn ";

        $sql .= " LEFT JOIN ( SELECT t_order.id_plgn, SUM ( n_bayar ) AS bayar FROM t_order_pay
        JOIN t_order ON t_order_pay.id_stt = t_order.id_stt
        where t_order.tgl_masuk between '" . $f_start . "' and '" . $f_end . "'
        GROUP BY t_order.id_plgn ) AS ng ON A.id_pelanggan = ng.id_plgn ";

        $sql .= " LEFT JOIN (SELECT id_plgn, COUNT (is_lunas) as stt_lunas FROM t_order
        where t_order.tgl_masuk between '" . $f_start . "' and '" . $f_end . "' GROUP BY id_plgn) as lunas on a.id_pelanggan = lunas.id_plgn ";

        $sql .= " WHERE
        A.id_perush = '" . $id_perush . "'
        ";

        if (isset($id_pelanggan) and $id_pelanggan != null) {
            $sql = $sql . "AND A.id_pelanggan = '" . $id_pelanggan . "'";
        }

        if (isset($id_group) and $id_group != null) {
            $sql = $sql . "AND A.id_plgn_group = '" . $id_group . "'";
        }

        if (isset($tipe_data) and $tipe_data != null) {
            if ($tipe_data == 'BELUM LUNAS') {
                $sql = $sql . " AND ( COALESCE ( b.total, 0 ) - COALESCE ( ng.bayar, 0 ) ) > 0 ";
            } else {
                $sql = $sql . " AND ( COALESCE ( b.total, 0 ) - COALESCE ( ng.bayar, 0 ) ) = 0 ";
            }
        }
        // dd($sql);
        $data = DB::select($sql);

        $collect = collect($data);
        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getPiutangCabang($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT A
        .id_perush AS id_perush,
        b.id_perush AS id_perush_asal,
        b.id_perush_tj,
        A.nm_perush AS nama,
        b.total AS total,
        b.dibayar AS dibayar,
        COALESCE (
            COALESCE ( b.total, 0 ) - COALESCE ( b.dibayar, 0 )) AS kurang
            FROM
            s_perusahaan
            AS A LEFT JOIN (
                SELECT
                id_perush,
                id_perush_tj,
                SUM ( nominal ) AS total,
                SUM ( dibayar ) AS dibayar
                FROM
                keu_invoice_handling_pendapatan
                WHERE
                created_at BETWEEN '" . $dr_tgl . "'
                AND '" . $sp_tgl . "'
                GROUP BY
                id_perush,
                id_perush_tj
                ) AS b ON A.id_perush = b.id_perush_tj
                WHERE
                A.id_perush != '" . $id_perush . "'
                ORDER BY
                A.id_perush
                ";
        $data = DB::select($sql);
        return $data;
    }

    public static function getDetail($id, $paginate, $status = null, $dr_tgl = null, $sp_tgl = null)
    {
        $data = DB::table('t_order')
            ->select(
                'm_plgn.id_pelanggan',
                'm_plgn.nm_pelanggan',
                't_order.id_stt',
                't_order.kode_stt',
                't_order.tgl_masuk',
                't_order.n_koli',
                't_order.n_berat',
                't_order.n_volume',
                't_order.n_kubik',
                DB::raw('COALESCE(t_order.c_total,0) AS piutang'),
                DB::raw('COALESCE(bayar.total,0) AS bayar'),
                DB::raw('COALESCE ( t_order.c_total, 0 ) - COALESCE ( bayar.total, 0 ) AS kurang'))
            ->leftJoin(DB::raw('(SELECT id_stt, SUM(n_bayar) as total FROM t_order_pay
        GROUP BY id_stt) AS bayar'), 't_order.id_stt', '=', 'bayar.id_stt')
            ->join('m_plgn', 't_order.id_plgn', '=', 'm_plgn.id_pelanggan')
            ->where('m_plgn.id_pelanggan', '=', $id)
            ->where('t_order.id_perush_asal', '=', Session("perusahaan")["id_perush"]);

        if ($status == 'BELUM LUNAS') {
            $data->whereRaw('(COALESCE ( t_order.c_total, 0 ) - COALESCE ( bayar.total, 0 )) > ? ', [0]);
        }
        if ($status == 'LUNAS') {
            $data->whereRaw('(COALESCE ( t_order.c_total, 0 ) - COALESCE ( bayar.total, 0 )) = ? ', [0]);
        }
        if (isset($dr_tgl)) {
            $data->where('t_order.tgl_masuk', '>=', $dr_tgl);
        }
        if (isset($sp_tgl)) {
            $data->where('t_order.tgl_masuk', '<=', $sp_tgl);
        }
        return $data->paginate($paginate);
    }

    public static function cetakDetail($id)
    {
        $sql = "
                    SELECT A
                    .id_pelanggan,
                    A.nm_pelanggan,
                    b.id_stt,
                    b.no_awb,
                    inv_p.kode_invoice,
                    b.kode_stt,
                    b.penerima_nm,
                    wil.nama_wil as tujuan,
                    b.tgl_masuk,
                    b.n_koli,
                    b.n_berat,
                    b.n_volume,
                    b.n_kubik,
                    COALESCE ( b.c_total, 0 ) AS piutang,
                    COALESCE ( C.total, 0 ) AS bayar,
                    COALESCE ( b.c_total, 0 ) - COALESCE ( c.total, 0 ) AS kurang
                    FROM
                    t_order b
                    INNER JOIN m_plgn A ON ( b.id_plgn = A.id_pelanggan )
                    LEFT OUTER JOIN ( SELECT id_stt, SUM ( n_bayar ) AS total FROM t_order_pay GROUP BY id_stt ) C ON (
                        b.id_stt = C.id_stt)
                        LEFT JOIN keu_draft_invoice as inv on b.id_stt = inv.id_stt
                        LEFT JOIN keu_invoice_pelanggan as inv_p on inv.id_invoice = inv_p.id_invoice
                        LEFT JOIN m_wilayah as wil on b.penerima_id_region = wil.id_wil
                        WHERE a.id_pelanggan = '" . $id . "'
                        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function getDetailDate($perpage, $page, $id, $dr_tgl, $sp_tgl)
    {
        $sql = "
                        SELECT A
                        .id_pelanggan,
                        A.nm_pelanggan,
                        b.id_stt,
                        b.kode_stt,
                        b.tgl_masuk,
                        b.n_koli,
                        b.n_berat,
                        b.n_volume,
                        b.n_kubik,
                        COALESCE ( b.c_total, 0 ) AS piutang,
                        COALESCE ( C.total, 0 ) AS bayar,
                        COALESCE ( b.c_total, 0 ) - COALESCE ( C.total, 0 ) AS kurang
                        FROM
                        t_order b
                        INNER JOIN m_plgn A ON ( b.id_plgn = A.id_pelanggan )
                        LEFT OUTER JOIN ( SELECT id_stt, SUM ( n_bayar ) AS total FROM t_order_pay GROUP BY id_stt ) C ON ( b.id_stt = C.id_stt )
                        WHERE
                        A.id_pelanggan = '" . $id . "'
                        AND B.tgl_masuk BETWEEN '" . $dr_tgl . "'
                        AND '" . $sp_tgl . "'
                        ";
        $data = DB::select($sql);
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );
        return $data;
    }

    public static function cetakAll($id_perush, $id_pelanggan, $id_group, $f_start, $f_end)
    {
        $sql = "
                        SELECT A
                        .id_pelanggan,
                        A.alamat,
                        A.telp,
                        A.id_plgn_group,
                        A.nm_pelanggan,
                        COALESCE ( b.total, 0 ) AS total,
                        COALESCE ( b.jml_stt, 0 ) AS total_stt,
                        COALESCE ( lunas.stt_lunas, 0 ) AS total_stt_byr,
                        COALESCE ( ng.bayar, 0 ) AS bayar,
                        (
                            COALESCE ( b.total, 0 ) - COALESCE ( ng.bayar, 0 )) AS kurang
                            FROM
                            m_plgn A ";

        $sql .= " LEFT JOIN ( SELECT id_plgn, SUM ( c_total ) AS total, COUNT ( id_stt ) AS jml_stt FROM t_order
                            where t_order.tgl_masuk between '" . $f_start . "' and '" . $f_end . "'
                            GROUP BY id_plgn ) AS b ON A.id_pelanggan = b.id_plgn ";

        $sql .= " LEFT JOIN ( SELECT id_plgn, SUM ( n_bayar ) AS bayar FROM t_order_pay
                            where tgl between '" . $f_start . "' and '" . $f_end . "'
                            GROUP BY id_plgn ) AS ng ON A.id_pelanggan = ng.id_plgn ";

        $sql .= " LEFT JOIN (SELECT id_plgn, COUNT (is_lunas) as stt_lunas FROM t_order
                            where t_order.tgl_masuk between '" . $f_start . "' and '" . $f_end . "' GROUP BY id_plgn) as lunas on a.id_pelanggan = lunas.id_plgn ";

        $sql .= " WHERE
                            A.id_perush = '" . $id_perush . "' ";

        if (isset($id_pelanggan) and $id_pelanggan != null) {
            $sql = $sql . "AND A.id_pelanggan = '" . $id_pelanggan . "'";
        }

        if (isset($id_group) and $id_group != null) {
            $sql = $sql . "AND A.id_plgn_group = '" . $id_group . "'";
        }

        $data = DB::select($sql);

        return $data;
    }

    public static function getAllPiutang($id_perush, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "
        SELECT SUM(omset) AS omset,
            SUM(bayar) AS bayar,
            (SUM(omset)-SUM(bayar)) AS piutang
        FROM
            (
            SELECT SUM(c_total) AS omset,
                0 AS bayar
            FROM
                t_order
            WHERE
                id_perush_asal = {$id_perush} ";

        if (isset($dr_tgl)) {
            $sql = $sql . " AND tgl_masuk >= '{$dr_tgl}'";
        }

        if (isset($sp_tgl)) {
            $sql = $sql . " AND tgl_masuk <= '{$sp_tgl}'";
        }
        $sql = $sql . "
        UNION
            SELECT
                0 AS omset,
                SUM( n_bayar ) AS bayar
            FROM
                t_order_pay
            WHERE
                id_perush = {$id_perush} ";
        if (isset($dr_tgl)) {
            $sql = $sql . " AND tgl >= '{$dr_tgl}'";
        }

        if (isset($sp_tgl)) {
            $sql = $sql . " AND tgl <= '{$sp_tgl}'";
        }

        $sql = $sql . "
        ) AS Q
        ";

        $data = DB::select($sql)[0];
        return $data;
    }

    public static function newGetData($perpage, $page, $id_perush, $id_pelanggan = null, $id_group = null, $dr_tgl = null, $sp_tgl = null, $tipe_data = null)
    {
        $sql = "
        SELECT
            id_plgn AS id_pelanggan,
            m_plgn_group.id_plgn_group,
            m_plgn_group.nm_group,
            m_plgn_group.kode_plgn_group,
            m_plgn.nm_pelanggan,
            m_plgn.n_limit_piutang AS limit_piutang,
            m_plgn.alamat,
            m_plgn.telp,
            SUM(omset) AS total,
            SUM(kiriman) AS kiriman,
            SUM(lunas) AS lunas,
            SUM(bayar) AS bayar,
            (SUM(omset)-SUM(bayar)) AS kurang
        FROM
            (
            SELECT
                id_plgn,
                SUM(c_total) AS omset,
                COUNT(id_stt) AS kiriman,
                SUM(CASE WHEN is_lunas = TRUE THEN 1 ELSE 0 END) AS lunas,
                0 AS bayar
            FROM
                t_order
            WHERE
                tgl_masuk BETWEEN '{$dr_tgl}'
                AND '{$sp_tgl}'
                AND id_perush_asal = {$id_perush}
            GROUP BY
                id_plgn UNION
            SELECT
                t_order.id_plgn,
                0,
                0,
                0,
                SUM(n_bayar) AS bayar
            FROM
                t_order_pay
                JOIN t_order ON t_order_pay.id_stt = t_order.id_stt
            WHERE
                t_order.tgl_masuk BETWEEN '{$dr_tgl}'
                AND '{$sp_tgl}'
                AND t_order.id_perush_asal = {$id_perush}
            GROUP BY
                t_order.id_plgn
            ) AS Q
            JOIN m_plgn ON Q.id_plgn = m_plgn.id_pelanggan
            JOIN m_plgn_group ON m_plgn.id_plgn_group = m_plgn_group.id_plgn_group
            WHERE Q.id_plgn IS NOT NULL";

        if (isset($id_pelanggan) && $id_pelanggan != null) {
            $sql = $sql . " AND m_plgn.id_pelanggan = {$id_pelanggan}";
        }

        if (isset($id_group) && $id_group != null) {
            $sql = $sql . " AND m_plgn_group.id_plgn_group = '" . $id_group . "'";
        }

        $sql = $sql . " GROUP BY
                id_plgn, m_plgn_group.id_plgn_group,
                m_plgn_group.nm_group,
                m_plgn_group.kode_plgn_group,
                m_plgn.nm_pelanggan,
                m_plgn.n_limit_piutang,
                m_plgn.alamat,
                m_plgn.telp ";

        if (isset($tipe_data) and $tipe_data != null) {
            if ($tipe_data == 'BELUM LUNAS') {
                $sql = $sql . " HAVING SUM(omset)-SUM(bayar)>0";
            } else {
                $sql = $sql . " HAVING SUM(omset)-SUM(bayar)=0";
            }
        }

        $data = DB::select($sql);

        $collect = collect($data);
        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }
}
