<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class ManagerialStt extends Model
{
    protected $fillable = [];

    public static function getDataManagerialStt($id_perush, $dr_tgl, $sp_tgl, $id_stt = null, $id_dm = null, $id_pelanggan = null, $mode)
    {
        $sql = "
        SELECT 
            A.tgl_masuk,
            A.id_stt,
            A.kode_stt,
            H.kode_plgn_group,
            A.pengirim_nm,
            A.n_volume,
            A.n_berat,
            A.n_kubik,
            A.n_koli,
            A.c_total,
            (
                CASE   
                    WHEN A.c_tarif = '1' THEN
                    A.c_total::INTEGER / A.n_berat::INTEGER
                    WHEN A.c_tarif = '2' THEN			
                    A.c_total::INTEGER / A.n_volume::INTEGER
                    WHEN A.c_tarif = '3' THEN
                    A.c_total
                    WHEN A.c_tarif = '4' THEN
                    CASE				
                        WHEN A.n_kubik :: INTEGER < 1 THEN
                        A.c_total :: INTEGER / 1 ELSE A.c_total :: INTEGER / A.n_kubik :: INTEGER 
                    END 
                END 
            ) AS n_tarif_koli,
            ( SELECT SUM(n_bayar) FROM t_order_pay WHERE t_order_pay.id_stt = A.id_stt GROUP BY id_stt) AS bayar,
            A.id_marketing,
            B.nm_marketing,
            D.id_dm,
            D.kode_dm,
            D.created_at AS dm_dibuat,
            D.tgl_berangkat,
            (SELECT tgl_update FROM t_history_stt WHERE t_history_stt.id_stt = A.id_stt AND id_status = '4' ORDER BY id_history DESC LIMIT 1) AS tgl_sampai,
            E.tgl_update AS tgl_tiba,
            ( SELECT nm_status FROM t_history_stt WHERE t_history_stt.id_stt = A.id_stt ORDER BY id_history DESC LIMIT 1 ) AS nama_status,
            A.id_cr_byr_o,
            F.nm_cr_byr_o,
            A.n_hrg_bruto,
            A.n_diskon,
            A.n_ppn,
            A.n_materai,
            A.n_asuransi,
            A.no_awb,
            (
            CASE
                    
                    WHEN A.c_tarif = '1' THEN
                    'BERAT' 
                    WHEN A.c_tarif = '2' THEN
                    'VOLUME' 
                    WHEN A.c_tarif = '3' THEN
                    'BORONGAN' 
                    WHEN A.c_tarif = '4' THEN
                    'KUBIK' 
                END 
                ) AS tarif 
            FROM
                t_order
                AS A LEFT JOIN m_marketing AS B ON A.id_marketing = B.id_marketing
                LEFT JOIN t_order_dm AS C ON A.id_stt = C.id_stt
                LEFT JOIN t_dm AS D ON C.id_dm = D.id_dm
                LEFT JOIN ( SELECT DISTINCT id_stt, tgl_update FROM t_history_stt WHERE id_status = '7' ) AS E ON A.id_stt = E.id_stt
                LEFT JOIN m_cr_bayar_order AS F ON A.id_cr_byr_o = F.id_cr_byr_o 
                LEFT JOIN m_plgn AS G ON A.id_plgn = G.id_pelanggan
                LEFT JOIN m_plgn_group AS H ON G.id_plgn_group = H.id_plgn_group
            WHERE
                A.id_perush_asal = {$id_perush} 
                AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}'";
        
        if (isset($id_stt)) {
            $sql = $sql . " AND A.id_stt = {$id_stt}";
        }

        if (isset($id_dm)) {
            $sql = $sql . " AND D.id_dm = {$id_dm}";
        }

        if (isset($id_pelanggan)) {
            $sql = $sql . " AND A.id_plgn = {$id_pelanggan}";
        }

        switch ($mode) {
            case 'SEMUA-STT':
                break;
            case 'SUDAH-SAMPAI':
                $sql = $sql . " AND A.id_status = '7' ";
                break;
            case 'BELUM-SAMPAI':
                $sql = $sql . " AND A.id_status < '7' ";
                break;            
            default:
                # code...
                break;
        }

        $sql = $sql . " ORDER BY A.tgl_masuk ";
        // dd($sql);
        $data = DB::select($sql);
		return $data;
    }
}
