<?php

namespace Modules\Operasional\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;

class SttKembali extends Model
{
    protected $table = "t_stt_kembali";
    public $incrementing = false;
    protected $primaryKey = 'id_stt_kembali';
    public $keyType = 'string';

    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }

    public static function getDokPengirim()
    {
        $data = self::with("perush_asal", "perush_tujuan", "user", "karyawan")
            ->where("id_perush", Session("perusahaan")["id_perush"]);

        return $data;
    }

    public static function getDokPenerima()
    {
        $data = self::with("perush_asal", "perush_tujuan", "user", "karyawan")
            ->where("id_perush_tj", Session("perusahaan")["id_perush"])
            ->where("status", ">", "1");

        return $data;
    }

    public static function generateId()
    {
        $data = self::where("id_perush", Session("perusahaan")["id_perush"])->orderBy("last_id", "desc")->get()->first();

        $a_data = [];
        if ($data == null) {
            $a_data["id_kembali"] = strtolower("STTKM" . Session("perusahaan")["id_perush"] . "001");
            $a_data["last_id"] = "1";
        } else {
            $id = (Int) $data->last_id + 1;
            $a_data["id_kembali"] = strtolower("STTKM" . Session("perusahaan")["id_perush"] . "00" . $id);
            $a_data["last_id"] = $id;
        }

        return $a_data;
    }

    public static function rekapitulasi_stt_kembali($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            Q.id_tujuan,
            B.nama_wil AS wilayah,
            provinsi.nama_wil AS provinsi,
            SUM ( total_stt ) AS total_stt,
            SUM ( t_stt_kembali ) AS stt_kembali,
            (SUM ( total_stt ) - SUM ( t_stt_kembali )) AS stt_belum,
            ROUND( SUM ( rata_rata ), 2 ) AS rata_rata_hari
        FROM
            (
            SELECT
                penerima_id_region AS id_tujuan,
                COUNT ( id_stt ) AS total_stt,
                0 AS t_stt_kembali,
                0 AS rata_rata
            FROM
                t_order
            WHERE
                id_perush_asal = " . $id_perush . "
                AND tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            GROUP BY
                penerima_id_region UNION
            SELECT C
                .penerima_id_region,
                0 AS total_stt,
                COUNT ( A.id_stt ) AS total_stt,
                AVG ( B.tgl - C.tgl_masuk ) AS rata_total_hari
            FROM
                t_stt_kembali_detail
                A JOIN t_stt_kembali AS B ON A.id_stt_kembali = B.id_stt_kembali
                JOIN t_order AS C ON C.id_stt = A.id_stt
            WHERE
                A.id_perush = " . $id_perush . "
                AND C.tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            GROUP BY
                C.penerima_id_region
            ) AS Q
            JOIN m_wilayah AS B ON Q.id_tujuan = B.id_wil
            JOIN m_wilayah AS provinsi ON B.prov_id = provinsi.id_wil
        GROUP BY
            Q.id_tujuan,
            B.nama_wil,
            provinsi.nama_wil
        ";

        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    public static function detail_rekapitulasi_stt_kembali($id_perush, $dr_tgl, $sp_tgl, $id_tujuan)
    {
        $sql = "
        SELECT 
            A.id_stt,
            A.kode_stt,
            A.pengirim_nm,
            A.tgl_masuk,
            C.tgl,
            ( C.tgl - A.tgl_masuk ) AS lama_hari 
        FROM
            t_order
            A LEFT JOIN t_stt_kembali_detail B ON A.id_stt = B.id_stt
            LEFT JOIN t_stt_kembali C ON B.id_stt_kembali = C.id_stt_kembali 
        WHERE
            A.id_perush_asal = " . $id_perush . " 
            AND A.penerima_id_region = '" . $id_tujuan . "' 
            AND A.tgl_masuk BETWEEN '" . $dr_tgl . "' 
            AND '" . $sp_tgl . "';
        ";
        $data = DB::select($sql);
        return $data;
    }

    public static function rekapitulasi_stt_kembali_by_dokumen($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            B.status,
            (
                SELECT COUNT ( id_stt ) FROM t_order
                WHERE id_perush_asal = " . $id_perush . "
                AND tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            ) total_stt_terbit,
            COUNT ( A.id_stt ) AS total_stt_kembali,
            ROUND(AVG ( B.tgl - C.tgl_masuk ), 2) AS rata_total_hari
        FROM
            t_stt_kembali_detail
            A JOIN t_stt_kembali AS B ON A.id_stt_kembali = B.id_stt_kembali
            JOIN t_order AS C ON C.id_stt = A.id_stt
        WHERE
            A.id_perush = " . $id_perush . "
            AND C.tgl_masuk BETWEEN '" . $dr_tgl . "'
            AND '" . $sp_tgl . "'
        GROUP BY
            B.status
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function rekapitulasi_stt_kembali_by_status_barang($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT
            A.kode_status,
            C.nm_ord_stt_stat AS status,
            (
                SELECT COUNT ( id_stt ) FROM t_order
                WHERE id_perush_asal = " . $id_perush . "
                AND tgl_masuk BETWEEN '" . $dr_tgl . "' AND '" . $sp_tgl . "'
            ) AS total_stt_terbit,
            COUNT ( DISTINCT A.id_stt ) AS total_stt_kembali,
            ROUND(AVG ( A.tgl_update - B.tgl_masuk ), 2) AS rata_total_hari
        FROM
            t_history_stt
            AS A JOIN t_order B ON A.id_stt = B.id_stt
            JOIN m_ord_stt_stat AS C ON C.kode_status = A.kode_status
        WHERE
            B.tgl_masuk BETWEEN '" . $dr_tgl . "'
            AND '" . $sp_tgl . "'
            AND B.id_perush_asal = " . $id_perush . "
            AND A.kode_status IS NOT NULL
        GROUP BY
            A.kode_status,
            C.nm_ord_stt_stat
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function detail_rekapitulasi_stt_kembali_by_status_barang($id_perush, $dr_tgl, $sp_tgl, $kode_status)
    {
        $sql = "
        SELECT
            DISTINCT B.*,
            A.kode_status,
            A.tgl_update,
            C.nm_ord_stt_stat AS nm_status,
            ( A.tgl_update - B.tgl_masuk ) AS selisih_hari
        FROM
            t_history_stt AS A 
            JOIN t_order B ON A.id_stt = B.id_stt
            JOIN m_ord_stt_stat AS C ON C.kode_status = A.kode_status
        WHERE
            B.tgl_masuk BETWEEN '" . $dr_tgl . "'
            AND '" . $sp_tgl . "'
            AND B.id_perush_asal = " . $id_perush . "
            AND A.kode_status IS NOT NULL
            AND A.kode_status = " . $kode_status . "
        ";

        $data = DB::select($sql);
        return $data;
    }

    public static function temp($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT COUNT
            ( id_stt ) AS id_stt,
            SUM ( total_status ) AS total_status,
            SUM ( lebih_dr ) AS lebih_dr,
            SUM ( kurang_dr ) AS kurang_dr,
            SUM ( pas_dr ) AS pas_dr 
        FROM
            (
            SELECT
                id_stt,
                COUNT ( id_history ) AS total_status,
                SUM ( lebih_dr ) AS lebih_dr,
                SUM ( kurang_dr ) AS kurang_dr,
                SUM ( pas_dr ) AS pas_dr 
            FROM
                (
                    WITH previous_values AS (
                    SELECT A
                        .id_history,
                        A.id_stt,
                        A.tgl_update,
                        LAG ( A.tgl_update ) OVER ( PARTITION BY A.id_stt ORDER BY A.id_history ) AS previous_value 
                    FROM
                        t_history_stt
                        A JOIN t_order B ON A.id_stt = B.id_stt 
                    WHERE
                        B.id_perush_asal = " . $id_perush . " 
                        AND B.tgl_masuk BETWEEN '" . $dr_tgl . "' 
                        AND '" . $sp_tgl . "' 
                    ) 
                    SELECT
                        id_history,
                        id_stt,
                        tgl_update,
                        tgl_update - previous_value AS selisih_value,
                        ( CASE WHEN tgl_update - previous_value > 3 THEN 1 ELSE 0 END ) AS lebih_dr,
                        ( CASE WHEN tgl_update - previous_value < 3 THEN 1 ELSE 0 END ) AS kurang_dr,
                        ( CASE WHEN tgl_update - previous_value = 3 THEN 1 ELSE 0 END ) AS pas_dr 
                    FROM
                        previous_values 
                    ORDER BY
                        id_stt,
                        id_history 
                ) AS Q 
            GROUP BY
            id_stt 
            ) AS Q1
        ";       
    }
}
