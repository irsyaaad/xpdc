<?php

namespace Modules\Asuransi\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;

class JurnalAsuransi extends Model
{
    protected $table = "keuangan_jurnal";
    protected $primaryKey = 'id_jurnal';
    protected $fillable = [];

    public static function Master($id_perush, $dr_tgl, $sp_tgl, $sa = null, $id_ac = null)
    {
        $tahun = date("Y", strtotime($dr_tgl));
        $sql = "
        SELECT * FROM (
            SELECT DISTINCT
            ( A.id_jurnal ) AS ID,
            A.id_debet AS id_debet,
            C.nama AS nama_debet,
            C.def_pos AS pos_d,
            C.parent AS parent_d,
            A.nominal AS total_debet,
            A.info_debet AS info_debet,
            A.tgl_transaksi AS tgl_transaksi,
            A.id_kredit AS id_kredit,
            D.nama AS nama_kredit,
            D.def_pos AS pos_k,
            D.parent AS parent_k,
            A.nominal AS total_kredit,
            A.info_kredit AS info_kredit,
            concat ( A.kode_referance ) AS reff,
            concat ( A.id_referance ) AS id_detail
        FROM
            keuangan_jurnal
            AS A JOIN m_ac_perush AS C ON C.id_ac = A.id_debet
            JOIN m_ac_perush AS D ON D.id_ac = A.id_kredit
        WHERE
            A.tgl_transaksi BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            AND C.id_perush = 17
            AND D.id_perush = 17
            ";

        if (isset($sa) and $sa != null) {
            if ($sa != '01-01') {
                $sql = $sql . "
                    -- ================( TUTUP BUKU )===============================
                    UNION
                    SELECT DISTINCT(A.ID) as id,
                        A.ac4_debit AS id_debet,
                        b.nama AS nama_debet,
                        b.def_pos AS pos_d,
                        b.parent AS parent_d,
                        A.total AS total,
                        concat ( 'Saldo Awal ', b.nama ) AS info,
                        CAST( A.tgl AS DATE) AS tanggal,
                        A.ac4_kredit AS id_kredit,
                        C.nama AS nama_kredit,
                        C.def_pos AS pos_k,
                        C.parent AS parent_k,
                        A.total,
                        concat ( 'Saldo Awal ', C.nama ) AS info_k,
                        concat ( 'SALDO' ) AS reff,
                        concat ( A.ID, '-' ) AS id_detail
                    FROM
                        tutup_buku
                        AS A LEFT JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                        LEFT JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                    WHERE
                        A.id_perush = '" . $id_perush . "'
                        AND b.id_perush = '" . $id_perush . "'
                        AND A.tgl = '" . $dr_tgl . "'
                        OR
                        C.id_perush = '" . $id_perush . "'
                        AND A.id_perush = '" . $id_perush . "'
                        AND A.tgl = '" . $dr_tgl . "'
                    ";
            } else {
                $sql = $sql . "
                    ";
            }
        } else {
            $sql = $sql . "
                -- ================( TUTUP BUKU )===============================
                    UNION
                    SELECT DISTINCT(A.ID) as id,
                        A.ac4_debit AS id_debet,
                        b.nama AS nama_debet,
                        b.def_pos AS pos_d,
                        b.parent AS parent_d,
                        A.total AS total,
                        concat ( 'Saldo Awal ', b.nama ) AS info,
                        CAST( A.tgl AS DATE) AS tanggal,
                        A.ac4_kredit AS id_kredit,
                        C.nama AS nama_kredit,
                        C.def_pos AS pos_k,
                        C.parent AS parent_k,
                        A.total,
                        concat ( 'Saldo Awal ', C.nama ) AS info_k,
                        concat ( 'SALDO' ) AS reff,
                        concat ( A.ID, '-' ) AS id_detail
                    FROM
                        tutup_buku
                        AS A LEFT JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                        LEFT JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                    WHERE
                        A.id_perush = '" . $id_perush . "'
                        AND b.id_perush = '" . $id_perush . "'
                        AND A.tgl = '" . $dr_tgl . "'
                        OR
                        C.id_perush = '" . $id_perush . "'
                        AND A.id_perush = '" . $id_perush . "'
                        AND A.tgl = '" . $dr_tgl . "'
                ";
        }

        $sql = $sql . " ORDER BY tgl_transaksi, id_debet, id_kredit) as master";

        if (isset($id_ac) and $id_ac != 0) {
            $sql = $sql . " WHERE id_debet = '" . $id_ac . "' OR id_kredit = '" . $id_ac . "'";
        }
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        return $data;
    }

    public static function SaldoAwal($id_perush, $tahun, $id)
    {
        $sql = "
        SELECT DISTINCT(A.ID) as id,
            A.ac4_debit AS id_debet,
            b.nama AS nama_debet,
            b.def_pos AS pos_d,
            b.parent AS parent_d,
            A.total AS total,
            concat ( 'Saldo Awal ', b.nama ) AS info,
            A.tgl AS tanggal,
            A.ac4_kredit AS id_kredit,
            C.nama AS nama_kredit,
            C.def_pos AS pos_k,
            C.parent AS parent_k,
            A.total,
            concat ( 'Saldo Awal ', C.nama ) AS info_k,
            A.ID
        FROM
            tutup_buku
            AS A LEFT JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
            LEFT JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
        WHERE
            A.id_perush = '" . $id_perush . "'
            AND b.id_perush = '" . $id_perush . "'
            AND A.ac4_debit = '" . $id . "'
            AND A.tahun = '" . $tahun . "'
            OR
            C.id_perush = '" . $id_perush . "'
            AND A.id_perush = '" . $id_perush . "'
            AND A.ac4_kredit = '" . $id . "'
            AND A.tahun = '" . $tahun . "'
        ";
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        return $data;
    }

}
