<?php

namespace Modules\Kepegawaian\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;

class GajiKaryawan extends Model
{
    protected $table = "kep_gaji_karyawan";
    protected $primaryKey = 'id_gk';

    // format jenis ijin
    // 1 / Hituang Jam {Datang Terlambat, Pulang Cepat, Keluar Kantor}
    // 2 / Hitung Harian {Cuti, Sakit, Izin Tidak Masuk}

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }
    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }
    public function jabatan()
    {
        return $this->belongsTo('Modules\Kepegawaian\Entities\Jabatan', 'id_jabatan', 'id_jabatan');
    }
    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public static function getGaji($bulan, $tahun, $id_perush = null)
    {
        $sql = "
        SELECT DISTINCT
            K.nm_karyawan,
            K.golongan,
            K.pangkat,
            G.*,
            jk.nm_jenis,
            jb.nm_jabatan
        FROM
            kep_gaji_karyawan
            G JOIN m_karyawan K ON G.id_karyawan = K.id_karyawan
            LEFT JOIN m_jenis_karyawan jk ON K.id_jenis = jk.id_jenis
            LEFT JOIN m_jabatan jb ON jb.id_jabatan = K.id_jabatan
        WHERE
            G.bulan = '" . $bulan . "'
            AND G.tahun = '" . $tahun . "'
        ";

        if (isset($id_perush) and $id_perush != null) {
            $sql .= " AND G.id_perush IN (" . $id_perush . ") ";
        }

        $sql .= " order by k.nm_karyawan asc ";
        $data = DB::select($sql);

        return $data;
    }

    public static function getDetailGaji($bulan, $tahun, $id_perush = null)
    {
        $sql = "select g.n_pajak,g.is_approve,g.id_gk, g.n_bpjs, g.n_piutang, g.n_kehadiran, g.n_tunjangan, k.id_karyawan,k.nm_karyawan, p.id_perush, p.nm_perush, g.bulan, g.tahun, k.nm_jenis,k.golongan, k.pangkat, k.nm_jabatan,k.n_gaji,g.n_denda from kep_gaji_karyawan g
		left join(
		select k.id_karyawan,k.nm_karyawan,k.golongan, k.pangkat,k.n_gaji,j.nm_jenis,b.nm_jabatan,k.is_aktif,k.id_perush from m_karyawan k
		left join m_jenis_karyawan j on k.id_jenis = j.id_jenis
		left join m_jabatan b on k.id_jabatan = b.id_jabatan where k.id_perush ='" . $id_perush . "' GROUP BY k.id_karyawan,j.nm_jenis,b.nm_jabatan
		) as k on k.id_karyawan = g.id_karyawan
		join s_perusahaan p on k.id_perush = p.id_perush
			where g.bulan='" . $bulan . "' and g.tahun ='" . $tahun . "' and k.is_aktif=true ";

        if (isset($id_perush) and $id_perush != null) {
            $sql = $sql . " and k.id_perush = '" . $id_perush . "' ";
        }

        $data = DB::select($sql);

        return $data;
    }

    public static function getDetail($id_gaji)
    {
        $data = DB::table('m_karyawan as a')
            ->join('s_perusahaan as p', 'a.id_perush', '=', 'p.id_perush')
            ->leftjoin("m_jenis_karyawan as b", "b.id_jenis", "=", "a.id_jenis")
            ->leftjoin("m_jabatan as j", "j.id_jabatan", "=", "a.id_jabatan")
            ->leftjoin('kep_gaji_karyawan as g', 'a.id_karyawan', '=', 'g.id_karyawan')
            ->select(
                "a.id_karyawan",
                "g.*",
                "a.nm_karyawan",
                "a.pangkat",
                "a.golongan",
                "b.nm_jenis",
                "j.nm_jabatan",
                "g.is_approve",
                "p.nm_perush"
            )
            ->groupBy('a.id_karyawan', "g.id_gk", "g.dr_tgl", "g.sp_tgl", "p.nm_perush", "g.bulan", "g.tahun", "a.pangkat", "a.golongan", "b.nm_jenis", "j.nm_jabatan", "g.n_pajak", "g.n_bpjs", "g.n_piutang", )
            ->orderBy('a.nm_karyawan')
            ->where("g.id_gk", $id_gaji);

        return $data;
    }

    public static function getData($id_perush = null)
    {
        $data = DB::table('m_karyawan as a')
            ->leftjoin('m_karyawan_gaji as b', 'a.id_karyawan', '=', 'b.id_karyawan')
            ->where('a.id_perush', $id_perush)
            ->where('a.is_aktif', true)
            ->select([
                "a.id_karyawan AS karyawan_id",
                "a.nm_karyawan",
                "b.*",
            ])
            ->groupBy('a.id_karyawan', 'b.id')
            ->orderBy('a.nm_karyawan');

        return $data;
    }

    public static function getRekapGaji($id_perush, $from_year, $to_year)
    {
        $sql = "
        SELECT
            tahun,
            bulan,
            SUM ( COALESCE ( n_gaji, 0 ) ) AS gaji,
            SUM ( COALESCE ( n_denda, 0 ) ) AS n_denda,
            SUM ( COALESCE ( n_tunjangan_jabatan, 0 ) ) AS n_tunjangan_jabatan,
            SUM ( COALESCE ( n_tunjangan_kinerja, 0 ) ) AS n_tunjangan_kinerja,
            SUM ( COALESCE ( n_tunjangan_kpi, 0 ) ) AS n_tunjangan_kpi,
            SUM ( COALESCE ( n_tunjangan_kesehatan, 0 ) ) AS n_tunjangan_kesehatan,
            SUM ( COALESCE ( n_tunjangan_jht, 0 ) ) AS n_tunjangan_jht,
            SUM ( COALESCE ( n_tunjangan_jkk, 0 ) ) AS n_tunjangan_jkk,
            SUM ( COALESCE ( n_tunjangan_jkm, 0 ) ) AS n_tunjangan_jkm,
            SUM ( COALESCE ( n_tunjangan_jp, 0 ) ) AS n_tunjangan_jp,
            SUM ( COALESCE ( n_potongan_kehadiran, 0 ) ) AS n_potongan_kehadiran,
            SUM ( COALESCE ( n_potongan_kasbon, 0 ) ) AS n_potongan_kasbon,
            SUM ( COALESCE ( n_potongan_pph, 0 ) ) AS n_potongan_pph,
            SUM ( COALESCE ( n_potongan_kesehatan, 0 ) ) AS n_potongan_kesehatan,
            SUM ( COALESCE ( n_potongan_jht, 0 ) ) AS n_potongan_jht,
            SUM ( COALESCE ( n_potongan_jp, 0 ) ) AS n_potongan_jp
        FROM
            kep_gaji_karyawan
        WHERE
            id_perush = " . $id_perush . "
        AND tahun BETWEEN '" . $from_year . "' AND '" . $to_year . "'
        GROUP BY
            tahun,
            bulan
        ORDER BY
            tahun,
            bulan
        ";
        
        $data = DB::select($sql);
        return $data;
    }
}
