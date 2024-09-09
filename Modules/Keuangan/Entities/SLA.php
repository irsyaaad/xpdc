<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SLA extends Model
{
    protected $fillable = [];

    public static function SLAStt($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT 
            COALESCE ( kabupaten, tujuan ) AS wilayah,
            COUNT ( id_stt ) AS stt,
            SUM ( n_koli ) AS koli,
            AVG ( selisih_dm ) AS rata_muat_dm,
            AVG ( selisih_sampai ) AS rata_sampai,
            AVG ( rata_update ) AS rata_update 
        FROM
            (
            SELECT A
                .id_stt,
                A.n_koli,
                A.kode_stt,
                perush.nm_perush,
                C.nm_pelanggan,
                tujuan.nama_wil AS tujuan,
                kab.nama_wil AS kabupaten,
                prov.nama_wil AS provinsi,
                A.tgl_masuk,
                COALESCE ( DATE ( B.created_at ), CURRENT_DATE ) AS tgl_dm,
                ( COALESCE ( DATE ( B.created_at ), CURRENT_DATE ) - A.tgl_masuk ) AS selisih_dm,
                COALESCE (
                    (
                    SELECT DATE
                        ( created_at ) 
                    FROM
                        t_history_stt 
                    WHERE
                        t_history_stt.id_stt = A.id_stt 
                        AND t_history_stt.id_status = '7' 
                    ORDER BY
                        created_at DESC 
                        LIMIT 1 
                    ),
                CURRENT_DATE 
                ) AS tgl_sampai,
                (
                    COALESCE (
                        (
                        SELECT DATE
                            ( created_at ) 
                        FROM
                            t_history_stt 
                        WHERE
                            t_history_stt.id_stt = A.id_stt 
                            AND t_history_stt.id_status = '7' 
                        ORDER BY
                            created_at DESC 
                            LIMIT 1 
                        ),
                    CURRENT_DATE 
                    ) - COALESCE ( DATE ( B.created_at ), CURRENT_DATE ) 
                ) AS selisih_sampai,
                (
                SELECT
                    ( MAX ( DATE ( created_at ) ) - MIN ( DATE ( created_at ) ) ) / NULLIF ( COUNT ( * ) - 1, 0 ) AS diff_date 
                FROM
                    t_history_stt 
                WHERE
                    t_history_stt.id_stt = A.id_stt 
                GROUP BY
                    t_history_stt.id_stt 
                ) AS rata_update 
            FROM
                t_order
                A LEFT JOIN t_order_dm B ON A.id_stt = B.id_stt
                LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
                LEFT JOIN m_wilayah AS tujuan ON A.penerima_id_region = tujuan.id_wil
                LEFT JOIN m_wilayah AS kab ON tujuan.kab_id = kab.id_wil
                LEFT JOIN m_wilayah AS prov ON kab.prov_id = prov.id_wil
                LEFT JOIN s_perusahaan AS perush ON A.id_perush_asal = perush.id_perush 
            WHERE
                A.id_perush_asal = {$id_perush} 
                AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                AND '{$sp_tgl}' 
            ORDER BY
                A.tgl_masuk 
            ) Q 
        GROUP BY
            COALESCE ( kabupaten, tujuan )
        ";

        $data = DB::select($sql);
		return $data;
    }

    public static function SLADMTrucking($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            nm_perush,
            nm_ven,
            COALESCE ( kabupaten, tujuan ) AS wilayah,
            COALESCE ( COUNT ( id_stt ), 0 ) AS stt,
            COALESCE ( SUM ( n_koli ), 0 ) AS koli,
            COALESCE ( ROUND( AVG ( dibuat_ke_berangkat ) ), 0 ) AS dibuat_ke_berangkat,
            COALESCE ( SUM ( minggu_dibuat_ke_berangkat ), 0 ) AS minggu_dibuat_ke_berangkat,
            COALESCE ( SUM ( dibuat_ke_berangkat_ok ), 0 ) AS dibuat_ke_berangkat_ok,
            COALESCE ( ROUND( AVG ( berangkat_ke_tiba ) ), 0 ) AS berangkat_ke_tiba,
            COALESCE ( SUM ( minggu_berangkat_ke_tiba ), 0 ) AS minggu_berangkat_ke_tiba,
            COALESCE ( SUM ( berangkat_ke_tiba_ok ), 0 ) AS berangkat_ke_tiba_ok,
            COALESCE ( ROUND( AVG ( tiba_ke_dooring ) ), 0 ) AS tiba_ke_dooring,
            COALESCE ( SUM ( minggu_tiba_ke_dooring ), 0 ) AS minggu_tiba_ke_dooring,
            COALESCE ( SUM ( tiba_ke_dooring_ok ), 0 ) AS tiba_ke_dooring_ok,
            COALESCE ( ROUND( AVG ( dooring_ke_sampai ) ), 0 ) AS dooring_ke_sampai,
            COALESCE ( SUM ( minggu_dooring_ke_sampai ), 0 ) AS minggu_dooring_ke_sampai,
            COALESCE ( SUM ( dooring_ke_sampai_ok ), 0 ) AS dooring_ke_sampai_ok 
        FROM
            (
            SELECT A
                .id_stt,
                A.n_koli,
                A.kode_stt,
                perush.nm_perush,
                C.nm_pelanggan,
                ven.nm_ven,
                tujuan.nama_wil AS tujuan,
                kab.nama_wil AS kabupaten,
                prov.nama_wil AS provinsi,
                A.tgl_masuk,
                COALESCE ( DATE ( E.tgl_berangkat ), NULL ) AS tgl_dm_berangkat,
                ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) AS dibuat_ke_berangkat,
                (
                SELECT COUNT
                    ( * ) 
                FROM
                    generate_series ( DATE ( A.tgl_masuk ), DATE ( E.tgl_berangkat ), '1 day' :: INTERVAL ) s 
                WHERE
                    EXTRACT ( DOW FROM s ) IN ( 0 ) 
                ) AS minggu_dibuat_ke_berangkat,
                ( CASE WHEN ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) <= 3 THEN 1 ELSE 0 END ) AS dibuat_ke_berangkat_ok,
                COALESCE ( DATE ( E.tgl_sampai ), NULL ) AS tgl_tiba,
                ( COALESCE ( DATE ( E.tgl_sampai ), NULL ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) ) AS berangkat_ke_tiba,
                (
                SELECT COUNT
                    ( * ) 
                FROM
                    generate_series ( DATE ( E.tgl_berangkat ), DATE ( E.tgl_sampai ), '1 day' :: INTERVAL ) s 
                WHERE
                    EXTRACT ( DOW FROM s ) IN ( 0 ) 
                ) AS minggu_berangkat_ke_tiba,
                (
                CASE
                        
                        WHEN ( COALESCE ( DATE ( E.tgl_sampai ), NULL ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) ) <= 3 THEN
                        1 ELSE 0 
                    END 
                    ) AS berangkat_ke_tiba_ok,
                    COALESCE (
                        (
                        SELECT DATE
                            ( tgl_update ) 
                        FROM
                            t_history_stt 
                        WHERE
                            t_history_stt.id_stt = A.id_stt 
                            AND t_history_stt.id_status = '6' 
                        ORDER BY
                            created_at DESC 
                            LIMIT 1 
                        ),
                    NULL 
                    ) AS tgl_dooring,
                    (
                        COALESCE (
                            (
                            SELECT DATE
                                ( tgl_update ) 
                            FROM
                                t_history_stt 
                            WHERE
                                t_history_stt.id_stt = A.id_stt 
                                AND t_history_stt.id_status = '6' 
                            ORDER BY
                                created_at DESC 
                                LIMIT 1 
                            ),
                        NULL 
                        ) - COALESCE ( DATE ( E.tgl_sampai ), NULL ) 
                    ) AS tiba_ke_dooring,
                    (
                    SELECT COUNT
                        ( * ) 
                    FROM
                        generate_series (
                            DATE ( E.tgl_sampai ),
                            (
                            SELECT DATE
                                ( tgl_update ) 
                            FROM
                                t_history_stt 
                            WHERE
                                t_history_stt.id_stt = A.id_stt 
                                AND t_history_stt.id_status = '6' 
                            ORDER BY
                                created_at DESC 
                                LIMIT 1 
                            ),
                            '1 day' :: INTERVAL 
                        ) s 
                    WHERE
                        EXTRACT ( DOW FROM s ) IN ( 0 ) 
                    ) AS minggu_tiba_ke_dooring,
                    (
                    CASE
                            
                            WHEN (
                                COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                ) - COALESCE ( DATE ( E.tgl_sampai ), NULL ) 
                                ) <= 3 THEN
                                1 ELSE 0 
                            END 
                            ) AS tiba_ke_dooring_ok,
                            COALESCE (
                                (
                                SELECT DATE
                                    ( tgl_update ) 
                                FROM
                                    t_history_stt 
                                WHERE
                                    t_history_stt.id_stt = A.id_stt 
                                    AND t_history_stt.id_status = '7' 
                                ORDER BY
                                    created_at DESC 
                                    LIMIT 1 
                                ),
                            NULL 
                            ) AS tgl_sampai_tujuan,
                            (
                                COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '7' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                    ) - COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                ) 
                            ) AS dooring_ke_sampai,
                            (
                            SELECT COUNT
                                ( * ) 
                            FROM
                                generate_series (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '7' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                    '1 day' :: INTERVAL 
                                ) s 
                            WHERE
                                EXTRACT ( DOW FROM s ) IN ( 0 ) 
                            ) AS minggu_dooring_ke_sampai,
                            (
                            CASE
                                    
                                    WHEN (
                                        COALESCE (
                                            (
                                            SELECT DATE
                                                ( tgl_update ) 
                                            FROM
                                                t_history_stt 
                                            WHERE
                                                t_history_stt.id_stt = A.id_stt 
                                                AND t_history_stt.id_status = '7' 
                                            ORDER BY
                                                created_at DESC 
                                                LIMIT 1 
                                            ),
                                        NULL 
                                            ) - COALESCE (
                                            (
                                            SELECT DATE
                                                ( tgl_update ) 
                                            FROM
                                                t_history_stt 
                                            WHERE
                                                t_history_stt.id_stt = A.id_stt 
                                                AND t_history_stt.id_status = '6' 
                                            ORDER BY
                                                created_at DESC 
                                                LIMIT 1 
                                            ),
                                        NULL 
                                        ) 
                                        ) <= 3 THEN
                                        1 ELSE 0 
                                    END 
                                    ) AS dooring_ke_sampai_ok,
                                    (
                                    SELECT
                                        ( MAX ( DATE ( created_at ) ) - MIN ( DATE ( created_at ) ) ) / NULLIF ( COUNT ( * ) - 1, 0 ) AS diff_date 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                    GROUP BY
                                        t_history_stt.id_stt 
                                    ) AS rata_update 
                                FROM
                                    t_order
                                    A LEFT JOIN t_order_dm B ON A.id_stt = B.id_stt
                                    LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
                                    LEFT JOIN m_wilayah AS tujuan ON A.penerima_id_region = tujuan.id_wil
                                    LEFT JOIN m_wilayah AS kab ON tujuan.kab_id = kab.id_wil
                                    LEFT JOIN m_wilayah AS prov ON kab.prov_id = prov.id_wil
                                    LEFT JOIN s_perusahaan AS perush ON A.id_perush_asal = perush.id_perush
                                    JOIN t_dm AS E ON B.id_dm = E.id_dm
                                    LEFT JOIN m_vendor AS ven ON E.id_ven = ven.id_ven 
                                WHERE
                                    A.id_perush_asal = {$id_perush} 
                                    AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                                    AND '{$sp_tgl}' 
                                    AND E.is_vendor = FALSE 
                                ORDER BY
                                    A.tgl_masuk 
                                ) Q 
                            GROUP BY
                                nm_perush,
                            nm_ven,
            COALESCE ( kabupaten, tujuan )
        ";
        // dd($sql);
        $data = DB::select($sql);
		return $data;
    }

    public static function detailSLADMTrucking($id_perush, $dr_tgl, $sp_tgl, $wilayah)
    {
        $sql = "
        SELECT
            * 
        FROM
            (
            SELECT A
                .id_stt,
                A.n_koli,
                A.kode_stt,
                E.kode_dm,
                perush.nm_perush,
                C.nm_pelanggan,
                ven.nm_ven,
                tujuan.nama_wil AS tujuan,
                kab.nama_wil AS kabupaten,
                prov.nama_wil AS provinsi,
                A.tgl_masuk,
                COALESCE ( DATE ( E.tgl_berangkat ), NULL ) AS tgl_dm_berangkat,
                ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) AS dibuat_ke_berangkat,
                (
                SELECT COUNT
                    ( * ) 
                FROM
                    generate_series ( DATE ( A.tgl_masuk ), DATE ( E.tgl_berangkat ), '1 day' :: INTERVAL ) s 
                WHERE
                    EXTRACT ( DOW FROM s ) IN ( 0 ) 
                ) AS minggu_dibuat_ke_berangkat,
                ( CASE WHEN ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) <= 3 THEN 1 ELSE 0 END ) AS dibuat_ke_berangkat_ok,
                COALESCE ( DATE ( E.tgl_sampai ), NULL ) AS tgl_tiba,
                ( COALESCE ( DATE ( E.tgl_sampai ), NULL ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) ) AS berangkat_ke_tiba,
                (
                SELECT COUNT
                    ( * ) 
                FROM
                    generate_series ( DATE ( E.tgl_berangkat ), DATE ( E.tgl_sampai ), '1 day' :: INTERVAL ) s 
                WHERE
                    EXTRACT ( DOW FROM s ) IN ( 0 ) 
                ) AS minggu_berangkat_ke_tiba,
                (
                CASE
                        
                        WHEN ( COALESCE ( DATE ( E.tgl_sampai ), NULL ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) ) <= 3 THEN
                        1 ELSE 0 
                    END 
                    ) AS berangkat_ke_tiba_ok,
                    COALESCE (
                        (
                        SELECT DATE
                            ( tgl_update ) 
                        FROM
                            t_history_stt 
                        WHERE
                            t_history_stt.id_stt = A.id_stt 
                            AND t_history_stt.id_status = '6' 
                        ORDER BY
                            created_at DESC 
                            LIMIT 1 
                        ),
                    NULL 
                    ) AS tgl_dooring,
                    (
                        COALESCE (
                            (
                            SELECT DATE
                                ( tgl_update ) 
                            FROM
                                t_history_stt 
                            WHERE
                                t_history_stt.id_stt = A.id_stt 
                                AND t_history_stt.id_status = '6' 
                            ORDER BY
                                created_at DESC 
                                LIMIT 1 
                            ),
                        NULL 
                        ) - COALESCE ( DATE ( E.tgl_sampai ), NULL ) 
                    ) AS tiba_ke_dooring,
                    (
                    SELECT COUNT
                        ( * ) 
                    FROM
                        generate_series (
                            DATE ( E.tgl_sampai ),
                            (
                            SELECT DATE
                                ( tgl_update ) 
                            FROM
                                t_history_stt 
                            WHERE
                                t_history_stt.id_stt = A.id_stt 
                                AND t_history_stt.id_status = '6' 
                            ORDER BY
                                created_at DESC 
                                LIMIT 1 
                            ),
                            '1 day' :: INTERVAL 
                        ) s 
                    WHERE
                        EXTRACT ( DOW FROM s ) IN ( 0 ) 
                    ) AS minggu_tiba_ke_dooring,
                    (
                    CASE
                            
                            WHEN (
                                COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                ) - COALESCE ( DATE ( E.tgl_sampai ), NULL ) 
                                ) <= 3 THEN
                                1 ELSE 0 
                            END 
                            ) AS tiba_ke_dooring_ok,
                            COALESCE (
                                (
                                SELECT DATE
                                    ( tgl_update ) 
                                FROM
                                    t_history_stt 
                                WHERE
                                    t_history_stt.id_stt = A.id_stt 
                                    AND t_history_stt.id_status = '7' 
                                ORDER BY
                                    created_at DESC 
                                    LIMIT 1 
                                ),
                            NULL 
                            ) AS tgl_sampai_tujuan,
                            (
                                COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '7' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                    ) - COALESCE (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                NULL 
                                ) 
                            ) AS dooring_ke_sampai,
                            (
                            SELECT COUNT
                                ( * ) 
                            FROM
                                generate_series (
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '6' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                    (
                                    SELECT DATE
                                        ( tgl_update ) 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                        AND t_history_stt.id_status = '7' 
                                    ORDER BY
                                        created_at DESC 
                                        LIMIT 1 
                                    ),
                                    '1 day' :: INTERVAL 
                                ) s 
                            WHERE
                                EXTRACT ( DOW FROM s ) IN ( 0 ) 
                            ) AS minggu_dooring_ke_sampai,
                            (
                            CASE
                                    
                                    WHEN (
                                        COALESCE (
                                            (
                                            SELECT DATE
                                                ( tgl_update ) 
                                            FROM
                                                t_history_stt 
                                            WHERE
                                                t_history_stt.id_stt = A.id_stt 
                                                AND t_history_stt.id_status = '7' 
                                            ORDER BY
                                                created_at DESC 
                                                LIMIT 1 
                                            ),
                                        NULL 
                                            ) - COALESCE (
                                            (
                                            SELECT DATE
                                                ( tgl_update ) 
                                            FROM
                                                t_history_stt 
                                            WHERE
                                                t_history_stt.id_stt = A.id_stt 
                                                AND t_history_stt.id_status = '6' 
                                            ORDER BY
                                                created_at DESC 
                                                LIMIT 1 
                                            ),
                                        NULL 
                                        ) 
                                        ) <= 3 THEN
                                        1 ELSE 0 
                                    END 
                                    ) AS dooring_ke_sampai_ok,
                                    (
                                    SELECT
                                        ( MAX ( DATE ( created_at ) ) - MIN ( DATE ( created_at ) ) ) / NULLIF ( COUNT ( * ) - 1, 0 ) AS diff_date 
                                    FROM
                                        t_history_stt 
                                    WHERE
                                        t_history_stt.id_stt = A.id_stt 
                                    GROUP BY
                                        t_history_stt.id_stt 
                                    ) AS rata_update 
                                FROM
                                    t_order
                                    A LEFT JOIN t_order_dm B ON A.id_stt = B.id_stt
                                    LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
                                    LEFT JOIN m_wilayah AS tujuan ON A.penerima_id_region = tujuan.id_wil
                                    LEFT JOIN m_wilayah AS kab ON tujuan.kab_id = kab.id_wil
                                    LEFT JOIN m_wilayah AS prov ON kab.prov_id = prov.id_wil
                                    LEFT JOIN s_perusahaan AS perush ON A.id_perush_asal = perush.id_perush
                                    JOIN t_dm AS E ON B.id_dm = E.id_dm
                                    LEFT JOIN m_vendor AS ven ON E.id_ven = ven.id_ven  
                                WHERE
                                    A.id_perush_asal = {$id_perush} 
                                    AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                                    AND '{$sp_tgl}' 
                                    AND E.is_vendor = FALSE 
                                ORDER BY
                                    A.tgl_masuk 
                                ) Q 
                            WHERE
                            Q.kabupaten = '{$wilayah}' 
            OR Q.tujuan = '{$wilayah}' 
        ";

        $data = DB::select($sql);
		return $data;
    }

    public static function SLADMVendor($id_perush, $dr_tgl, $sp_tgl){
        $sql = "
        SELECT
            nm_perush,
            id_ven,
            nm_ven,
            COALESCE ( kabupaten, tujuan ) AS wilayah,
            COALESCE ( COUNT ( id_dm ), 0 ) AS id_dm,
            COALESCE ( COUNT ( id_stt ), 0 ) AS stt,
            COALESCE ( SUM ( n_koli ), 0 ) AS koli,
            COALESCE ( ROUND( AVG ( dibuat_ke_berangkat ) ), 0 ) AS dibuat_ke_berangkat,
            COALESCE ( SUM ( dibuat_ke_berangkat_ok ), 0 ) AS dibuat_ke_berangkat_ok,
            COALESCE ( ROUND( AVG ( berangkat_sampai ) ), 0 ) AS berangkat_sampai,
            COALESCE ( SUM ( berangkat_selesai_ok ), 0 ) AS berangkat_selesai_ok 
        FROM
            (
            SELECT A
                .id_stt,
                E.id_dm,
                A.n_koli,
                A.kode_stt,
                E.id_ven,
                perush.nm_perush,
                C.nm_pelanggan,
                ven.nm_ven,
                tujuan.nama_wil AS tujuan,
                kab.nama_wil AS kabupaten,
                prov.nama_wil AS provinsi,
                A.tgl_masuk,
                COALESCE ( DATE ( E.tgl_berangkat ), NULL ) AS tgl_dm_berangkat,
                ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) AS dibuat_ke_berangkat,
                ( CASE WHEN ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) <= 3 THEN 1 ELSE 0 END ) AS dibuat_ke_berangkat_ok,
                COALESCE (
                    (
                    SELECT DATE
                        ( tgl_update ) 
                    FROM
                        t_history_stt 
                    WHERE
                        t_history_stt.id_stt = A.id_stt 
                        AND t_history_stt.id_status = '7' 
                    ORDER BY
                        created_at DESC 
                        LIMIT 1 
                    ),
                NULL 
                ) AS tgl_sampai_tujuan,
                (
                    COALESCE (
                        (
                        SELECT DATE
                            ( tgl_update ) 
                        FROM
                            t_history_stt 
                        WHERE
                            t_history_stt.id_stt = A.id_stt 
                            AND t_history_stt.id_status = '7' 
                        ORDER BY
                            created_at DESC 
                            LIMIT 1 
                        ),
                    NULL 
                    ) - E.tgl_berangkat 
                ) AS berangkat_sampai,
                (
                CASE
                        
                        WHEN (
                            COALESCE (
                                (
                                SELECT DATE
                                    ( tgl_update ) 
                                FROM
                                    t_history_stt 
                                WHERE
                                    t_history_stt.id_stt = A.id_stt 
                                    AND t_history_stt.id_status = '7' 
                                ORDER BY
                                    created_at DESC 
                                    LIMIT 1 
                                ),
                            NULL 
                            ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) 
                            ) <= 3 THEN
                            1 ELSE 0 
                        END 
                        ) AS berangkat_selesai_ok 
                    FROM
                        t_order
                        A LEFT JOIN t_order_dm B ON A.id_stt = B.id_stt
                        LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
                        LEFT JOIN m_wilayah AS tujuan ON A.penerima_id_region = tujuan.id_wil
                        LEFT JOIN m_wilayah AS kab ON tujuan.kab_id = kab.id_wil
                        LEFT JOIN m_wilayah AS prov ON kab.prov_id = prov.id_wil
                        LEFT JOIN s_perusahaan AS perush ON A.id_perush_asal = perush.id_perush
                        JOIN t_dm AS E ON B.id_dm = E.id_dm
                        LEFT JOIN m_vendor AS ven ON E.id_ven = ven.id_ven 
                    WHERE
                        A.id_perush_asal = {$id_perush} 
                        AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                        AND '{$sp_tgl}' 
                        AND E.is_vendor = TRUE 
                    ORDER BY
                        A.tgl_masuk 
                    ) Q 
                GROUP BY
                    nm_perush,
                    id_ven,
                nm_ven,
            COALESCE ( kabupaten, tujuan )
        ";

        $data = DB::select($sql);
		return $data;
    }

    public static function detailSLADMVendor($id_perush, $dr_tgl, $sp_tgl, $id_ven, $wilayah){
        $sql = "
        SELECT
            * 
        FROM
            (
            SELECT A
                .id_stt,
                A.n_koli,
                E.id_dm,
                E.kode_dm,
                A.kode_stt,
                E.id_ven,
                perush.nm_perush,
                C.nm_pelanggan,
                ven.nm_ven,
                tujuan.nama_wil AS tujuan,
                kab.nama_wil AS kabupaten,
                prov.nama_wil AS provinsi,
                A.tgl_masuk,
                COALESCE ( DATE ( E.tgl_berangkat ), NULL ) AS tgl_dm_berangkat,
                ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) AS dibuat_ke_berangkat,
                ( CASE WHEN ( COALESCE ( DATE ( E.tgl_berangkat ), NULL ) - A.tgl_masuk ) <= 3 THEN 1 ELSE 0 END ) AS dibuat_ke_berangkat_ok,
                COALESCE (
                    (
                    SELECT DATE
                        ( tgl_update ) 
                    FROM
                        t_history_stt 
                    WHERE
                        t_history_stt.id_stt = A.id_stt 
                        AND t_history_stt.id_status = '7' 
                    ORDER BY
                        created_at DESC 
                        LIMIT 1 
                    ),
                NULL 
                ) AS tgl_sampai_tujuan,
                (
                    COALESCE (
                        (
                        SELECT DATE
                            ( tgl_update ) 
                        FROM
                            t_history_stt 
                        WHERE
                            t_history_stt.id_stt = A.id_stt 
                            AND t_history_stt.id_status = '7' 
                        ORDER BY
                            created_at DESC 
                            LIMIT 1 
                        ),
                    NULL 
                    ) - E.tgl_berangkat 
                ) AS berangkat_sampai,
                (
                CASE
                        
                        WHEN (
                            COALESCE (
                                (
                                SELECT DATE
                                    ( tgl_update ) 
                                FROM
                                    t_history_stt 
                                WHERE
                                    t_history_stt.id_stt = A.id_stt 
                                    AND t_history_stt.id_status = '7' 
                                ORDER BY
                                    created_at DESC 
                                    LIMIT 1 
                                ),
                            NULL 
                            ) - COALESCE ( DATE ( E.tgl_berangkat ), NULL ) 
                            ) <= 3 THEN
                            1 ELSE 0 
                        END 
                        ) AS berangkat_selesai_ok 
                    FROM
                        t_order
                        A LEFT JOIN t_order_dm B ON A.id_stt = B.id_stt
                        LEFT JOIN m_plgn AS C ON A.id_plgn = C.id_pelanggan
                        LEFT JOIN m_wilayah AS tujuan ON A.penerima_id_region = tujuan.id_wil
                        LEFT JOIN m_wilayah AS kab ON tujuan.kab_id = kab.id_wil
                        LEFT JOIN m_wilayah AS prov ON kab.prov_id = prov.id_wil
                        LEFT JOIN s_perusahaan AS perush ON A.id_perush_asal = perush.id_perush
                        JOIN t_dm AS E ON B.id_dm = E.id_dm
                        LEFT JOIN m_vendor AS ven ON E.id_ven = ven.id_ven 
                    WHERE
                        A.id_perush_asal = {$id_perush} 
                        AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                        AND '{$sp_tgl}' 
                        AND E.is_vendor = TRUE 
                    ORDER BY
                        A.tgl_masuk 
                    ) Q 
                WHERE
                    id_ven = {$id_ven} 
                AND tujuan = '{$wilayah}' 
            OR kabupaten = '{$wilayah}'
        ";

        $data = DB::select($sql);
		return $data;
    }
}
