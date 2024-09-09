<?php

namespace Modules\Keuangan\Entities;
use DB;

use Illuminate\Database\Eloquent\Model;
use Modules\Keuangan\Entities\PengeluraranDetail;
use Modules\Keuangan\Entities\PendapatanDetail;

class Neraca extends Model
{
    protected $fillable = [];

    public static function Master($id_perush,$dr_tgl,$sp_tgl, $sa = null, $id_ac = null)
    {
        $tahun = date("Y", strtotime($dr_tgl));
        $sql = "
        SELECT * FROM (
            -- Pendapatan
            SELECT DISTINCT(A.id_pendapatan) as id, A.id_ac AS id_debet,
                C.nama AS nama_debet,
                C.def_pos AS pos_d,
                C.parent AS parent_d,
                b.total AS total_debet,
                b.info AS info_debet,
                A.tgl_masuk as tgl_masuk,
                b.id_ac AS id_kredit,
                d.nama AS nama_kredit,
                d.def_pos AS pos_k,
                d.parent AS parent_k,
                b.total AS total_kredit,
                b.info AS info_kredit,
                concat ( 'JM' ) AS reff,
                concat ( A.kode_pendapatan, '-',b.id_detail ) AS id_detail
            FROM
                keu_pendapatan
                AS A JOIN m_ac_perush AS C ON A.id_ac = C.id_ac
                JOIN keu_pendapatan_det AS b ON A.id_pendapatan = b.id_pendapatan
                JOIN m_ac_perush AS d ON b.id_ac = d.id_ac
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND d.id_perush = '".$id_perush."'
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."' UNION

                -- Pengeluaran
            SELECT DISTINCT(A.id_pengeluaran) as id,
                d.id_ac AS id_debet,
                d.nama AS nama_debet,
                d.def_pos AS pos_d,
                d.parent AS parent_k,
                b.total AS total_debet,
                b.info AS info_debet,
                A.tgl_keluar,
                A.id_ac AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                b.total AS total_kredit,
                b.info AS info_kredit,
                concat ( 'JK' ) AS reff,
                concat ( A.kode_pengeluaran, '-',b.id_detail ) AS id_detail
            FROM
                keu_pengeluaran
                AS A JOIN m_ac_perush AS C ON A.id_ac = C.id_ac
                JOIN keu_pengeluaran_det AS b ON A.id_pengeluaran = b.id_pengeluaran
                JOIN m_ac_perush AS d ON b.id_ac = d.id_ac
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND d.id_perush = '".$id_perush."'
                AND A.tgl_keluar BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
                -- Pembayaran
            SELECT DISTINCT(A.id_order_pay) as id, A
                .ac4_d AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_bayar,
                A.info,
                A.tgl,
                A.ac4_k AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_bayar,
                A.info,
                concat ( 'BYRSTT' ) AS reff,
                concat ( A.no_kwitansi, '-' ) AS id_detail
            FROM
                t_order_pay
                AS A JOIN m_ac_perush AS b ON A.ac4_d = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_k = C.id_ac
                JOIN t_order AS st ON A.id_stt = st.id_stt
                JOIN m_ac_perush AS d ON d.id_ac = st.c_ac4_pend
            WHERE
                A.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND A.tgl BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
                -- =============STT=====================--
                -- Piutang >< Pendapatan
            SELECT DISTINCT(A.id_stt) as id, A
                .c_ac4_piut AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_hrg_bruto AS piutang,
                concat ( 'Piutang STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_pend AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_hrg_bruto AS pendapatan,
                concat ( 'Pendapatan STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_piut = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_pend = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

            -- ASURANSI
            SELECT DISTINCT(A.id_stt) as id, A
                .c_ac4_piut AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_asuransi AS piutang,
                concat ( 'Piutang STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_asur AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_asuransi AS pendapatan,
                concat ( 'Pendapatan Asuransi ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_piut = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_asur = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.n_asuransi != 0
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

                -- DISKON
            SELECT DISTINCT(A.id_stt) as id,A
                .c_ac4_disc AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_diskon AS piutang,
                concat ( 'Diskon ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_piut AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_diskon AS diskon,
                concat ( 'Diskon ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_disc = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_piut = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.n_diskon != 0
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

                -- PPN
            SELECT DISTINCT(A.id_stt) as id, A
                .c_ac4_piut AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_ppn AS piutang,
                concat ( 'Piutang STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_ppn AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_ppn,
                concat ( 'Hutang Pajak (PPN)', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_piut = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_ppn = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.n_ppn != 0
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

                -- MATERAI
            SELECT DISTINCT(A.id_stt) as id, A
                .c_ac4_piut AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_materai AS piutang,
                concat ( 'Piutang STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_mat AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_materai,
                concat ( 'Materai ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_piut = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_mat = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.n_materai != 0
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
            
            -- PACKING
            SELECT DISTINCT(A.id_stt) as id, A
                .c_ac4_piut AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_packing AS piutang,
                concat ( 'Piutang Packing STT ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info,
                A.tgl_masuk,
                A.c_ac4_packing AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_packing,
                concat ( 'Pendapatan Packing STT NO ', A.kode_stt, ' a/n ', m_plgn.nm_pelanggan ) AS info_k,
                concat ( 'STT' ) AS reff,
                concat ( A.kode_stt, '-' ) AS id_detail
            FROM
                t_order
                AS A JOIN m_ac_perush AS b ON A.c_ac4_piut = b.id_ac
                JOIN m_ac_perush AS C ON A.c_ac4_packing = C.id_ac
                JOIN m_plgn ON m_plgn.id_pelanggan = A.id_plgn
            WHERE
                A.id_perush_asal = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.n_packing != 0
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

            -- ====================( DM BIAYA )=================
            SELECT DISTINCT(A.id_pro_bi) as id,  A
                .ac4_kredit AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.nominal AS piutang,
                concat ( 'HPP DM ', A.kode_dm, ' (',D.nm_biaya_grup,')' ) AS info,
                A.tgl_posting,
                A.ac4_debit AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.nominal,
                concat ( 'HPP DM ', A.kode_dm, ' (',D.nm_biaya_grup,')' ) AS info_k,
                concat ( 'HPP' ) AS reff,
                concat ( A.kode_dm, '-' ) AS id_detail
            FROM
                t_dm_biaya
                AS A JOIN m_ac_perush AS b ON A.ac4_kredit = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_debit = C.id_ac
                JOIN m_biaya_grup AS D ON A.id_biaya_grup = D.id_biaya_grup
            WHERE
                A.id_perush_dr = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND A.tgl_posting BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
            -- ==============(DM BIAYA BAYAR)=====================
            SELECT DISTINCT(A.id_biaya) as id,  A
                .ac4_debit AS id_debit,
                b.nama AS nama_debit,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.n_bayar AS bayar,
                A.info AS info,
                A.tgl_bayar,
                A.ac4_kredit AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.n_bayar,
                A.info AS info_k,
                concat ( 'BYHPP' ) AS reff,
                concat ( A.kode_dm, '-',A.id_biaya ) AS id_detail
            FROM
                t_dm_biaya_bayar
                AS A JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                JOIN m_biaya_grup AS D ON A.id_biaya_grup = D.id_biaya_grup
            WHERE
                A.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND A.tgl_bayar BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION

            -- ================( Memorial )===============================
            SELECT DISTINCT(A.id_memorial) as id,
                A.id_ac_debet AS id_debet,
                b.nama AS nama_debet,
                b.def_pos AS pos_d,
                b.parent AS parent_d,
                A.nominal AS total,
                A.info,
                CAST( A.tgl AS DATE),
                A.id_ac_kredit AS id_kredit,
                C.nama AS nama_kredit,
                C.def_pos AS pos_k,
                C.parent AS parent_k,
                A.nominal,
                A.info,
                concat ( 'MEMO' ) AS reff,
                concat ( A.kode_memorial, '-' ) AS id_detail
            FROM
                keu_memorial
                AS A LEFT JOIN m_ac_perush AS b ON A.id_ac_debet = b.id_ac
                LEFT JOIN m_ac_perush AS C ON A.id_ac_kredit = C.id_ac
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.tgl BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
            ";


            //dd($sa);
        if (isset($sa) and $sa != null) {
            if($sa != '01-01'){
                $sql = $sql. "
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
                    A.id_perush = '".$id_perush."'
                    AND b.id_perush = '".$id_perush."'
                    AND A.tgl = '".$dr_tgl."'
                    OR
                    C.id_perush = '".$id_perush."'
                    AND A.id_perush = '".$id_perush."'
                    AND A.tgl = '".$dr_tgl."'
                ";
            }else{
                $sql = $sql."
                ";
            }
        } else {
            $sql = $sql ."
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
                    A.id_perush = '".$id_perush."'
                    AND b.id_perush = '".$id_perush."'
                    AND A.tgl = '".$dr_tgl."'
                    OR
                    C.id_perush = '".$id_perush."'
                    AND A.id_perush = '".$id_perush."'
                    AND A.tgl = '".$dr_tgl."'
            ";
        }
        $sql = $sql." ORDER BY tgl_masuk, id_debet, id_kredit) as master";

        if(isset($id_ac) and $id_ac != 0){
            $sql = $sql." WHERE id_debet = '".$id_aC."' OR id_kredit = '".$id_aC."'";
        }
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        return $data;
    }

    public static function SaldoAwal($id_perush,$tahun,$id)
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
            A.id_perush = '".$id_perush."'
            AND b.id_perush = '".$id_perush."'
            AND A.ac4_debit = '".$id."'
            AND A.tahun = '".$tahun."'
            OR
            C.id_perush = '".$id_perush."'
            AND A.id_perush = '".$id_perush."'
            AND A.ac4_kredit = '".$id."'
            AND A.tahun = '".$tahun."'
        ";
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        return $data;
    }

    public static function CashFlow($id_perush, $dr_tgl, $sp_tgl, $id_ac = null, $id_user = null)
    {
        $sql = "
        SELECT * FROM (
                -- Bayar STT --
            SELECT DISTINCT
                ( A.id_order_pay ) AS ID,
                A.id_perush,
                A.tgl AS tgl,
                A.id_stt,
                A.n_bayar AS nominal,
                A.ac4_d AS id_debet,
                C.def_pos AS pos_d,
                C.parent as parent_d,
                C.nama AS nama_ac_d,
                T.c_ac4_pend AS id_kredit,
                D.def_pos AS pos_k,
                D.parent as parent_k,
                D.nama as nama_ac_k,
                A.info AS keterangan,
                concat ( 'BYRSTT' ) AS reff,
                concat ( A.no_kwitansi, '-' ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                t_order_pay
                AS A JOIN t_order AS T ON A.id_stt = T.id_stt
                JOIN m_ac_perush AS C ON A.ac4_d = C.id_ac
                JOIN m_ac_perush AS D ON T.c_ac4_pend = D.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND D.id_perush = '".$id_perush."'
                AND A.tgl BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
                -- Bayar HPP DM --
            SELECT DISTINCT
                ( A.id_biaya ) AS ID,
                A.id_perush,
                A.tgl_bayar,
                A.id_dm,
                A.n_bayar AS bayar,
                A.ac4_debit,
                B.def_pos,
                B.parent,
                B.nama,
                A.ac4_kredit AS id_kredit,
                C.def_pos AS pos_k,
                C.parent,
                C.nama,
                concat ( 'Bayar HPP DM ', A.kode_dm ) AS info_k,
                concat ( 'BYHPP' ) AS reff,
                concat ( A.kode_dm, '-',A.id_biaya ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                t_dm_biaya_bayar
                AS A JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND A.tgl_bayar BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."' 
                UNION
                -- Bayar Handling --
            SELECT DISTINCT
                ( A.id_bayar ) AS ID,
                A.id_perush,
                A.created_at,
                A.id_handling,
                A.nominal,
                A.ac4_debit,
                B.def_pos,
                B.parent,
                B.nama,
                A.ac4_kredit AS id_kredit,
                C.def_pos AS pos_k,
                C.parent,
                C.nama,
                A.keterangan,
                concat ( 'BYRHAND' ) AS reff,
                concat ( A.id_bayar, '-' ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                t_handling_biaya_bayar
                AS A JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND A.created_at BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."' 
                UNION
                -- Keu Pengeluaran
            SELECT DISTINCT
                ( A.id_pengeluaran ) AS ID,
                A.id_perush,
                A.tgl_keluar,
                b.id_detail,
                b.total,
                d.id_ac AS id_debet,
                d.def_pos AS pos_d,
                d.parent,
                D.nama,
                A.id_ac AS id_kredit,
                C.def_pos AS pos_k,
                C.parent,
                C.nama,
                b.info AS info_kredit,
                concat ( 'JK' ) AS reff,
                concat ( A.kode_pengeluaran, '-',b.id_detail ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                keu_pengeluaran
                AS A JOIN m_ac_perush AS C ON A.id_ac = C.id_ac
                JOIN keu_pengeluaran_det AS b ON A.id_pengeluaran = b.id_pengeluaran
                JOIN m_ac_perush AS d ON b.id_ac = d.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND D.id_perush = '".$id_perush."'
                AND A.tgl_keluar BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."' UNION
                -- Pendapatan
            SELECT DISTINCT
                ( A.id_pendapatan ) AS ID,
                A.id_perush,
                A.tgl_masuk,
                b.id_detail,
                b.total,
                A.id_ac AS id_debet,
                d.def_pos AS pos_d,
                C.parent,
                C.nama,
                b.id_ac AS id_kredit,
                C.def_pos AS pos_k,
                d.parent,
                d.nama,
                b.info,
                concat ( 'JM' ) AS reff,
                concat ( A.kode_pendapatan, '-',b.id_detail ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                keu_pendapatan
                AS A JOIN m_ac_perush AS C ON A.id_ac = C.id_ac
                JOIN keu_pendapatan_det AS b ON A.id_pendapatan = b.id_pendapatan
                JOIN m_ac_perush AS d ON b.id_ac = d.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND D.id_perush = '".$id_perush."'
                AND A.tgl_masuk BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."' UNION
                -- Bayar INVOICE --
            SELECT DISTINCT
                ( A.id_bayar ) AS ID,
                A.id_perush,
                A.created_at,
                A.id_invoice,
                A.nominal,
                A.ac4_debit AS id_debit,
                b.def_pos AS pos_d,
                b.parent,
                b.nama,
                A.ac4_kredit AS id_kredit,
                C.def_pos AS pos_k,
                C.parent,
                C.nama,
                A.keterangan,
                concat ( 'BYRINV' ) AS reff,
                concat ( A.id_bayar, '-' ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                keu_invoice_pendapatan_bayar
                AS A JOIN m_ac_perush AS b ON A.ac4_debit = b.id_ac
                JOIN m_ac_perush AS C ON A.ac4_kredit = C.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.created_at BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
                UNION
            -- Memorial --
            SELECT DISTINCT
                ( A.id_memorial ) AS ID,
                A.id_perush,
                A.created_at,
                A.id_memorial,
                A.nominal,
                A.id_ac_debet AS id_debit,
                b.def_pos AS pos_d,
                b.parent,
                b.nama,
                A.id_ac_kredit AS id_kredit,
                C.def_pos AS pos_k,
                C.parent,
                C.nama,
                A.info,
                concat ( 'MEMO' ) AS reff,
                concat ( A.kode_memorial, '-' ) AS id_detail,
                U.nm_user,
                U.id_user
            FROM
                keu_memorial
                AS A JOIN m_ac_perush AS b ON A.id_ac_debet = b.id_ac
                JOIN m_ac_perush AS C ON A.id_ac_kredit = C.id_ac
                LEFT JOIN users AS U ON A.id_user = U.id_user
            WHERE
                A.id_perush = '".$id_perush."'
                AND C.id_perush = '".$id_perush."'
                AND b.id_perush = '".$id_perush."'
                AND A.created_at BETWEEN '".$dr_tgl."'
                AND '".$sp_tgl."'
        ) AS MASTER WHERE tgl IS NOT NULL ";

        if (isset($id_ac)) {
            $sql = $sql . " AND id_debet = {$id_ac} OR id_kredit = {$id_ac}";
        }

        if (isset($id_user)) {
            $sql = $sql . " AND id_user = {$id_user}";
        }

        $sql = $sql . " ORDER BY tgl ";
// dd($sql);
        $data = DB::select(DB::raw($sql));
        return $data;
    }
    
    public static function getSQL($builder) {
        $sql = $builder->toSql();
        foreach ( $builder->getBindings() as $binding ) {
          $value = is_numeric($binding) ? $binding : "'".$binding."'";
          $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }
}
