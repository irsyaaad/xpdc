<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use App\Models\Wilayah;

class HargaVendor extends Model
{
	protected $table = "harga";
	protected $primaryKey = 'id_harga';
	protected $fillable = ['wil_asal', 'wil_tujuan', 'id_ven', 'harga', 'id_user', 'min_kg', 'hrg_kubik', 'min_kubik', 'time', 'keterangan', 'type', 'rekomendasi', 'parent'];

	public static function getDataRelation($id)
	{
		$sql = "SELECT *
		FROM relationharga relat
		WHERE relat.child = $id::INTEGER";

		$data = DB::select($sql);
		$data = DB::select($sql);
		$collect = collect($data)->first();
		return $collect;
	}

	public static function get_data_harga($asal, $tujuan, $vendor, $hargakg, $minkg)
	{
		$hargakgrep = (double) str_replace(['Rp. ', ','], '', $hargakg);
		$sql = "SELECT H.id_harga, H.wil_asal, WA.nama_wil AS Asal, H.wil_tujuan, WT.nama_wil AS tujuan, H.harga, H.id_ven, V.nm_ven
					FROM harga H
					JOIN m_wilayah WA ON WA.id_wil::INTEGER = H.wil_asal
					JOIN m_wilayah WT ON WT.id_wil::INTEGER = H.wil_tujuan
					JOIN m_vendor V ON V.id_ven = H.id_ven
					WHERE WA.nama_wil like '%$asal%' 
					AND WT.nama_wil like '%$tujuan%'
					AND V.nm_ven like '%$vendor%'
					AND H.harga = '$hargakgrep'
					AND H.min_kg = $minkg";
		$data = DB::select($sql);
		$collect = collect($data);
		return $collect;
	}

	public static function deleteRelation($id)
	{
		return DB::table('relationharga')->where('child', $id)->delete();
	}

	public function get_datacetak($asal = null, $tujuan = null, $page, $perpage)
	{
		if ($asal != null && $tujuan != null) {
			// $sql = "SELECT kec_tujuan.id_Wil, kab_tujuan.nama_wil AS kab_tujuan, kec_tujuan.nama_wil AS kec_tujuan, harga_kec_tujuan.harga, harga_kec_tujuan.nm_ven
			// FROM m_wilayah kec_tujuan
			// JOIN m_wilayah kab_tujuan ON kab_tujuan.id_wil = kec_tujuan.kab_id

			// LEFT JOIN (
			// SELECT H.id_harga, H.wil_asal, WA.nama_wil AS Asal, H.wil_tujuan, WT.nama_wil AS tujuan, H.harga, H.id_ven, V.nm_ven

			// FROM harga H
			// JOIN m_wilayah WA ON WA.id_wil::INTEGER = H.wil_asal
			// JOIN m_wilayah WT ON WT.id_wil::INTEGER = H.wil_tujuan
			// JOIN m_vendor V ON V.id_ven = H.id_ven

			// WHERE H.wil_asal = $asal 
			// AND WT.prov_id = '" . $tujuan . "'
			// ) AS harga_kec_tujuan
			// ON kec_tujuan.id_wil::INTEGER = harga_kec_tujuan.wil_tujuan

			// WHERE kec_tujuan.prov_id= '" . $tujuan . "'

			// ORDER BY kec_tujuan.id_wil;";

			$sql = "SELECT kec_tujuan.id_Wil as id_wil_tujuan, 
               prov_tujuan.nama_wil AS prov_tujuan, 
               kab_tujuan.nama_wil AS kab_tujuan, 
               kec_tujuan.nama_wil AS kec_tujuan,  
               harga_kec_tujuan.harga, 
               harga_kec_tujuan.nm_ven
       		 FROM m_wilayah kec_tujuan
        	JOIN m_wilayah kab_tujuan ON kab_tujuan.id_wil = kec_tujuan.kab_id
        	JOIN m_wilayah prov_tujuan ON prov_tujuan.id_wil = kec_tujuan.prov_id

        LEFT JOIN (
            SELECT H.id_harga, H.wil_asal, WA.nama_wil AS Asal, H.wil_tujuan, WT.nama_wil AS tujuan, H.harga, H.id_ven, V.nm_ven
            FROM harga H
            JOIN m_wilayah WA ON WA.id_wil::INTEGER = H.wil_asal
            JOIN m_wilayah WT ON WT.id_wil::INTEGER = H.wil_tujuan
            JOIN m_vendor V ON V.id_ven = H.id_ven
            WHERE H.wil_asal = $asal 
            AND WT.prov_id = '" . $tujuan . "'
        ) AS harga_kec_tujuan ON kec_tujuan.id_wil::INTEGER = harga_kec_tujuan.wil_tujuan

        WHERE kec_tujuan.prov_id= '" . $tujuan . "'
        ORDER BY kec_tujuan.id_wil;";


			$data = DB::select($sql);
			$collect = collect($data);

			$data = new LengthAwarePaginator(
				$collect->forPage($page, $perpage),
				$collect->count(),
				$perpage,
				$page
			);
		} else {

			$data = [];

			$collect = collect($data);

			$data = new LengthAwarePaginator(
				$collect->forPage($page, $perpage),
				$collect->count(),
				$perpage,
				$page
			);
		}

		return $data;
	}


	public function getdatacetak_tidakdiimpor()
	{
		$sql = "SELECT DISTINCT wilayah.id_wil, provinsi.nama_wil AS provinsi, kabupaten.nama_wil AS kabupaten, wilayah.nama_wil AS kecamatan, harga.harga as harga_hpp, kecharga.wil_tujuan as id_diimport, harga.id_ven, vendor.nm_ven
		FROM m_wilayah AS wilayah
		JOIN m_wilayah AS provinsi ON wilayah.prov_id = provinsi.id_wil
		LEFT JOIN m_wilayah AS kabupaten ON wilayah.kab_id = kabupaten.id_wil
		LEFT JOIN harga AS harga ON harga.wil_tujuan = wilayah.id_wil::bigint
		LEFT JOIN harga AS kecharga ON kecharga.wil_tujuan = wilayah.id_wil::bigint
		LEFT JOIN m_vendor AS vendor ON harga.id_ven = vendor.id_ven::bigint
		WHERE wilayah.level_wil = '3'
		AND harga.harga IS NULL AND kecharga IS NULL
		ORDER BY wilayah.id_wil ASC;";

		$data = DB::select($sql);
		// $collect = collect($data);
		return $data;
	}

	public function getloggingpencarian($asal = null, $tujuan = null, $page, $perpage)
	{
		if ($asal != null && $tujuan != null) {
			$sql = "SELECT log.*, usr.nm_user, origin.nama_wil as origin, destination.nama_wil as destination, perush.nm_perush
            FROM m_logging as log
            LEFT JOIN users as usr ON usr.id_user::INTEGER = log.id_pengguna
            LEFT JOIN s_perusahaan as perush ON perush.id_perush::INTEGER = usr.id_perush::INTEGER
            LEFT JOIN m_wilayah as origin ON origin.id_wil::INTEGER = log.id_asal
            LEFT JOIN m_wilayah as destination ON destination.id_wil::INTEGER = log.id_tujuan
            WHERE origin.id_wil::INTEGER = " . $asal . " AND destination.id_wil::INTEGER = " . $tujuan . "
            ORDER BY log.created_at DESC";
		} else if ($asal != null && $tujuan == null) {
			$sql = "SELECT log.*, usr.nm_user, origin.nama_wil as origin, destination.nama_wil as destination, perush.nm_perush
            FROM m_logging as log
            LEFT JOIN users as usr ON usr.id_user::INTEGER = log.id_pengguna
            LEFT JOIN s_perusahaan as perush ON perush.id_perush::INTEGER = usr.id_perush::INTEGER
            LEFT JOIN m_wilayah as origin ON origin.id_wil::INTEGER = log.id_asal
            LEFT JOIN m_wilayah as destination ON destination.id_wil::INTEGER = log.id_tujuan
            WHERE origin.id_wil::INTEGER = " . $asal . "
            ORDER BY log.created_at DESC";
		} else if ($asal == null && $tujuan != null) {
			$sql = "SELECT log.*, usr.nm_user, origin.nama_wil as origin, destination.nama_wil as destination, perush.nm_perush
            FROM m_logging as log
            LEFT JOIN users as usr ON usr.id_user::INTEGER = log.id_pengguna
            LEFT JOIN s_perusahaan as perush ON perush.id_perush::INTEGER = usr.id_perush::INTEGER
            LEFT JOIN m_wilayah as origin ON origin.id_wil::INTEGER = log.id_asal
            LEFT JOIN m_wilayah as destination ON destination.id_wil::INTEGER = log.id_tujuan
            WHERE destination.id_wil::INTEGER = " . $tujuan . "
            ORDER BY log.created_at DESC";
		} else {
			$sql = "SELECT log.*, usr.nm_user, origin.nama_wil as origin, destination.nama_wil as destination, perush.nm_perush
            FROM m_logging as log
            LEFT JOIN users as usr ON usr.id_user::INTEGER = log.id_pengguna
            LEFT JOIN s_perusahaan as perush ON perush.id_perush::INTEGER = usr.id_perush::INTEGER
            LEFT JOIN m_wilayah as origin ON origin.id_wil::INTEGER = log.id_asal
            LEFT JOIN m_wilayah as destination ON destination.id_wil::INTEGER = log.id_tujuan
            ORDER BY log.created_at DESC";
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

	public static function getData($page = null, $perpage = null, $id_asal = null, $id_tujuan = null, $id_ven = null, $updated = null, $range = null, $type = null)
	{
		$sql = "select distinct(h.id_harga),h.created_at,h.rekomendasi,h.updated_at, h.same_balik,
		case when(h.type ='1') then h.min_kg when(h.type='2') then par.min_kg end as min_kg,
		case when(h.type ='1') then h.min_kubik when(h.type='2') then par.min_kubik end as min_kubik,
		case when(h.type ='1') then h.time when(h.type='2') then par.time end as time,
		case when(h.type ='1') then h.harga when(h.type='2') then par.harga end as harga,
		case when(h.type ='1') then h.hrg_kubik when(h.type='2') then par.hrg_kubik end as hrg_kubik,
		a.nama_wil as wil_asal,t.nama_wil as wil_tujuan,h.id_user,c.tven,h.type,
		h.keterangan,v.nm_ven,i.nm_karyawan as insert_user,u.nm_karyawan as update_user
		from harga h
		left join relationharga o on o.child = h.id_harga
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as i on h.id_user = i.id_user
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as u on h.updated_user = u.id_user
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		) 
		as t on h.wil_tujuan=t.id_wil::INTEGER
		left join m_vendor v on v.id_ven = h.id_ven
		left join 
		(
			select COALESCE(count(h.id_ven),0) as tven,r.parent
			from harga h 
			join relationharga r on h.id_harga=r.child group by r.parent
		) as c on c.parent = h.id_harga
		left join (
				select r.parent,sum(h.harga) as harga,sum(h.hrg_kubik) as hrg_kubik,
				sum(h.time) as time,max(h.min_kg) as min_kg,max(h.min_kubik) as min_kubik 
				from harga h 
				join relationharga r on h.id_harga=r.child group by r.parent
		) as par on par.parent = h.id_harga
		where h.type is not null";

		if ($id_asal != null) {
			$sql .= " and  h.wil_asal = '" . $id_asal . "' ";
		}

		if ($id_tujuan != null) {
			if ($range != null) {
				$tujuan = Wilayah::findOrFail($id_tujuan);
				$level = $tujuan->level_wil + 1;

				if ($tujuan->level_wil == "1") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where prov_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} elseif ($tujuan->level_wil == "2") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where level_wil='" . $level . "'  and kab_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} else {
					$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
				}

			} else {
				$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
			}
		}

		if ($id_ven != null) {
			$sql .= " and h.id_ven = '" . $id_ven . "' ";
		}
		if ($updated != null) {
			$sql .= " and h.updated_at >= '" . $updated . "' ";
		}
		if ($type != null) {
			$sql .= " and h.type = '" . $type . "' ";
		}

		$sql .= " order by h.id_harga desc,h.created_at desc,h.updated_at desc";

		$data = DB::select($sql);
		$a_data = [];

		$collect = collect($data);

		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		);

		return $data;
	}
	public static function getDataMarketing($page = null, $perpage = null, $id_asal = null, $id_tujuan = null, $id_ven = null, $updated = null, $range = null, $type = null, $is_aktif = true)
	{
		$sql = "SELECT DISTINCT(h.id_harga), h.created_at, h.rekomendasi, h.updated_at, h.same_balik,
        CASE WHEN (h.type ='1') THEN h.min_kg WHEN (h.type='2') THEN par.min_kg END AS min_kg,
        CASE WHEN (h.type ='1') THEN h.min_kubik WHEN (h.type='2') THEN par.min_kubik END AS min_kubik,
        CASE WHEN (h.type ='1') THEN h.time WHEN (h.type='2') THEN par.time END AS time,
        CASE WHEN (h.type ='1') THEN h.harga WHEN (h.type='2') THEN par.harga END AS harga,
        CASE WHEN (h.type ='1') THEN h.hrg_kubik WHEN (h.type='2') THEN par.hrg_kubik END AS hrg_kubik,
        a.nama_wil AS wil_asal, t.nama_wil AS wil_tujuan, h.id_user, c.tven, h.type,
        h.keterangan, v.nm_ven, i.nm_karyawan AS insert_user, u.nm_karyawan AS update_user
        FROM harga h
        LEFT JOIN relationharga o ON o.child = h.id_harga
        LEFT JOIN (
            SELECT k.nm_karyawan, u.id_user FROM m_karyawan k JOIN users u ON k.id_karyawan = u.id_karyawan
        ) AS i ON h.id_user = i.id_user
        LEFT JOIN (
            SELECT k.nm_karyawan, u.id_user FROM m_karyawan k JOIN users u ON k.id_karyawan = u.id_karyawan
        ) AS u ON h.updated_user = u.id_user
        JOIN (
            SELECT 
                CASE
                    WHEN (kec.level_wil='3') THEN CONCAT('Kec. ', kec.nama_wil, ', ', kab.nama_wil, ', Prov. ', prov.nama_wil)
                    WHEN (kec.level_wil ='2') THEN CONCAT(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
                    WHEN (kec.level_wil='1') THEN CONCAT('Prov. ', kec.nama_wil)
                END AS nama_wil, kec.id_wil, kec.level_wil
            FROM m_wilayah kec 
            LEFT JOIN m_wilayah kab ON kec.kab_id = kab.id_wil
            LEFT JOIN m_wilayah prov ON prov.id_wil = kec.prov_id
        ) AS a ON h.wil_asal = a.id_wil::INTEGER
        JOIN (
            SELECT 
                CASE
                    WHEN (kec.level_wil='3') THEN CONCAT('Kec. ', kec.nama_wil, ', ', kab.nama_wil, ', Prov. ', prov.nama_wil)
                    WHEN (kec.level_wil ='2') THEN CONCAT(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
                    WHEN (kec.level_wil='1') THEN CONCAT('Prov. ', kec.nama_wil)
                END AS nama_wil, kec.id_wil, kec.level_wil
            FROM m_wilayah kec 
            LEFT JOIN m_wilayah kab ON kec.kab_id = kab.id_wil
            LEFT JOIN m_wilayah prov ON prov.id_wil = kec.prov_id
        ) AS t ON h.wil_tujuan = t.id_wil::INTEGER
        LEFT JOIN m_vendor v ON v.id_ven = h.id_ven AND v.is_aktif = 'true'
        LEFT JOIN (
            SELECT COALESCE(COUNT(h.id_ven), 0) AS tven, r.parent
            FROM harga h 
            JOIN relationharga r ON h.id_harga = r.child GROUP BY r.parent
        ) AS c ON c.parent = h.id_harga
        LEFT JOIN (
            SELECT r.parent, SUM(h.harga) AS harga, SUM(h.hrg_kubik) AS hrg_kubik,
                SUM(h.time) AS time, MAX(h.min_kg) AS min_kg, MAX(h.min_kubik) AS min_kubik 
            FROM harga h 
            JOIN relationharga r ON h.id_harga = r.child GROUP BY r.parent
        ) AS par ON par.parent = h.id_harga
        WHERE h.type IS NOT NULL";

		if ($id_asal != null) {
			$sql .= " and  h.wil_asal = '" . $id_asal . "' ";
		}

		if ($id_tujuan != null) {
			if ($range != null) {
				$tujuan = Wilayah::findOrFail($id_tujuan);
				$level = $tujuan->level_wil + 1;

				if ($tujuan->level_wil == "1") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where prov_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} elseif ($tujuan->level_wil == "2") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where level_wil='" . $level . "'  and kab_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} else {
					$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
				}

			} else {
				$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
			}
		}

		if ($id_ven != null && $is_aktif) {
			$sql .= " AND v.id_ven = '" . $id_ven . "'";
		}
		if ($updated != null) {
			$sql .= " and h.updated_at >= '" . $updated . "' ";
		}
		if ($type != null) {
			$sql .= " and h.type = '" . $type . "' ";
		}

		$sql .= " order by h.id_harga desc,h.created_at desc,h.updated_at desc";

		$data = DB::select($sql);
		$a_data = [];

		$collect = collect($data);

		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		);

		return $data;
	}

	// new function getdatamarketing
	public static function getDataMarketingnew($page = null, $perpage = null, $id_asal = null, $id_tujuan = null, $id_ven = null, $updated = null, $range = null, $type = null, $is_aktif = true)
	{
		$sql = "SELECT DISTINCT(h.id_harga), h.created_at, h.rekomendasi, h.updated_at, h.same_balik,
    CASE WHEN (h.type ='1') THEN h.min_kg WHEN (h.type='2') THEN par.min_kg END AS min_kg,
    CASE WHEN (h.type ='1') THEN h.min_kubik WHEN (h.type='2') THEN par.min_kubik END AS min_kubik,
    CASE WHEN (h.type ='1') THEN h.time WHEN (h.type='2') THEN par.time END AS time,
    CASE WHEN (h.type ='1') THEN h.harga WHEN (h.type='2') THEN par.harga END AS harga,
    CASE WHEN (h.type ='1') THEN h.hrg_kubik WHEN (h.type='2') THEN par.hrg_kubik END AS hrg_kubik,
    a.nama_wil AS wil_asal, t.nama_wil AS wil_tujuan, h.id_user, c.tven, h.type,
    h.keterangan, v.nm_ven, i.nm_karyawan AS insert_user, u.nm_karyawan AS update_user
    FROM harga h
    LEFT JOIN relationharga o ON o.child = h.id_harga
    LEFT JOIN (
        SELECT k.nm_karyawan, u.id_user FROM m_karyawan k JOIN users u ON k.id_karyawan = u.id_karyawan
    ) AS i ON h.id_user = i.id_user
    LEFT JOIN (
        SELECT k.nm_karyawan, u.id_user FROM m_karyawan k JOIN users u ON k.id_karyawan = u.id_karyawan
    ) AS u ON h.updated_user = u.id_user
    JOIN (
        SELECT 
            CASE
                WHEN (kec.level_wil='3') THEN CONCAT('Kec. ', kec.nama_wil, ', ', kab.nama_wil, ', Prov. ', prov.nama_wil)
                WHEN (kec.level_wil ='2') THEN CONCAT(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
                WHEN (kec.level_wil='1') THEN CONCAT('Prov. ', kec.nama_wil)
            END AS nama_wil, kec.id_wil, kec.level_wil
        FROM m_wilayah kec 
        LEFT JOIN m_wilayah kab ON kec.kab_id = kab.id_wil
        LEFT JOIN m_wilayah prov ON prov.id_wil = kec.prov_id
    ) AS a ON h.wil_asal = a.id_wil::INTEGER
    JOIN (
        SELECT 
            CASE
                WHEN (kec.level_wil='3') THEN CONCAT('Kec. ', kec.nama_wil, ', ', kab.nama_wil, ', Prov. ', prov.nama_wil)
                WHEN (kec.level_wil ='2') THEN CONCAT(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
                WHEN (kec.level_wil='1') THEN CONCAT('Prov. ', kec.nama_wil)
            END AS nama_wil, kec.id_wil, kec.level_wil
        FROM m_wilayah kec 
        LEFT JOIN m_wilayah kab ON kec.kab_id = kab.id_wil
        LEFT JOIN m_wilayah prov ON prov.id_wil = kec.prov_id
    ) AS t ON h.wil_tujuan = t.id_wil::INTEGER
    LEFT JOIN m_vendor v ON v.id_ven = h.id_ven
    LEFT JOIN (
        SELECT COALESCE(COUNT(h.id_ven), 0) AS tven, r.parent
        FROM harga h 
        JOIN relationharga r ON h.id_harga = r.child GROUP BY r.parent
    ) AS c ON c.parent = h.id_harga
    LEFT JOIN (
        SELECT r.parent, SUM(h.harga) AS harga, SUM(h.hrg_kubik) AS hrg_kubik,
            SUM(h.time) AS time, MAX(h.min_kg) AS min_kg, MAX(h.min_kubik) AS min_kubik 
        FROM harga h 
        JOIN relationharga r ON h.id_harga = r.child GROUP BY r.parent
    ) AS par ON par.parent = h.id_harga
    WHERE h.type IS NOT NULL AND v.is_aktif = 'true'";

		if ($id_asal != null) {
			$sql .= " and  h.wil_asal = '" . $id_asal . "' ";
		}

		if ($id_tujuan != null) {
			if ($range != null) {
				$tujuan = Wilayah::findOrFail($id_tujuan);
				$level = $tujuan->level_wil + 1;

				if ($tujuan->level_wil == "1") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where prov_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} elseif ($tujuan->level_wil == "2") {
					$select = " select id_wil::INTEGER as wil_tujuan from m_wilayah where level_wil='" . $level . "'  and kab_id='" . $id_tujuan . "' ";
					$sql .= " and h.wil_tujuan::INTEGER in (" . $select . ") ";
				} else {
					$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
				}

			} else {
				$sql .= " and  h.wil_tujuan = '" . $id_tujuan . "' ";
			}
		}

		if ($id_ven != null) {
			$sql .= " AND v.id_ven = '" . $id_ven . "'";
		}
		if ($updated != null) {
			$sql .= " and h.updated_at >= '" . $updated . "' ";
		}
		if ($type != null) {
			$sql .= " and h.type = '" . $type . "' ";
		}

		$sql .= " order by h.id_harga desc,h.created_at desc,h.updated_at desc";

		$data = DB::select($sql);
		$a_data = [];

		$collect = collect($data);

		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		);

		return $data;
	}


	public static function getHarga($id)
	{
		$sql = "select h.id_harga,h.rekomendasi,h.harga,h.min_kg,h.min_kubik,h.is_aktif,a.nama_wil as wil_asal,t.nama_wil as wil_tujuan,h.id_user,u.nm_user,h.keterangan,v.nm_ven
		from harga h
		join 
		(
			select concat(kab.nama_wil,', ', prov.nama_wil) as nama_wil,kab.id_wil from m_wilayah kab
			left join m_wilayah prov  on prov.id_wil = kab.prov_id
			where kab.level_wil = '2'
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select concat(kab.nama_wil,', ', prov.nama_wil) as nama_wil,kab.id_wil from m_wilayah kab
			left join m_wilayah prov  on prov.id_wil = kab.prov_id
			where kab.level_wil = '2'
		)
		as t on h.wil_tujuan=t.id_wil::INTEGER
		join m_vendor v on v.id_ven = h.id_ven::INTEGER
		join users u on h.id_user = u.id_user
		where id_harga = '" . $id . "' ";

		$data = DB::select($sql);

		$a_data = [];
		if (count($data) > 0) {
			$a_data["id_harga"] = $data[0]->id_harga;
			$a_data["vendor"] = $data[0]->nm_ven;
			$a_data["asal"] = $data[0]->wil_asal;
			$a_data["wil_tujuan"] = $data[0]->wil_tujuan;
			$a_data["harga"] = $data[0]->harga;
		}

		return $a_data;
	}

	public function vendor()
	{
		return $this->belongsTo('Modules\Busdev\Entities\VendorBusdev', 'id_ven', 'id_ven');
	}

	public static function getDataShow($id_ven = null, $id_asal = null, $id_tujuan = null)
	{
		$sql = "select h.created_at,h.rekomendasi,h.updated_at,h.min_kg,h.min_kubik, h.same_balik, h.id_harga,h.type,h.hrg_kubik,h.time,h.type,h.harga,a.nama_wil as wil_asal,t.nama_wil as wil_tujuan,h.id_user,c.tven,
		h.keterangan,v.nm_ven,i.nm_karyawan as insert_user,u.nm_karyawan as update_user
		from harga h
		join relationharga o on o.child = h.id_harga
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as i on h.id_user = i.id_user
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as u on h.updated_user = u.id_user
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		) 
		as t on h.wil_tujuan=t.id_wil::INTEGER
		left join m_vendor v on v.id_ven = h.id_ven
		left join 
		(
			select COALESCE(count(id_ven), 0) as tven , parent from harga group by parent
		) as c on c.parent = h.id_harga
		where h.type is not null";

		if ($id_asal != null) {
			$sql .= " and  h.wil_asal = '" . $id_asal . "' ";
		}

		if ($id_tujuan != null) {
			$sql .= " and h.wil_tujuan = '" . $id_tujuan . "' ";
		}

		if ($id_ven != null) {
			$sql .= " and h.id_ven = '" . $id_ven . "' ";
		}

		$data = DB::select($sql);

		return $data;
	}

	public static function getDetail($id)
	{
		$sql = "select h.created_at,h.rekomendasi,h.updated_at,h.min_kg,h.min_kubik, h.id_harga,h.type,h.hrg_kubik,h.time,h.type,h.same_balik, h.harga,upper(a.nama_wil) as wil_asal,upper(t.nama_wil) as wil_tujuan,h.id_user,c.tven,
		h.keterangan,v.nm_ven,i.nm_karyawan as insert_user,u.nm_karyawan as update_user,h.wil_asal as id_asal,h.wil_tujuan as id_tujuan,h.id_ven
		from harga h
		join relationharga o on o.child = h.id_harga
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as i on h.id_user = i.id_user
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as u on h.updated_user = u.id_user
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		) 
		as t on h.wil_tujuan=t.id_wil::INTEGER
		left join m_vendor v on v.id_ven = h.id_ven
		left join 
		(
			select COALESCE(count(id_ven), 0) as tven , parent from harga group by parent
		) as c on c.parent = h.id_harga
		where o.parent='" . $id . "' order by h.updated_at desc  ";

		return DB::select($sql);
	}

	public static function getOnes($asal, $tujuan)
	{
		$sql = "select h.created_at,h.rekomendasi,h.updated_at,h.min_kg,h.min_kubik, h.id_harga,h.type,h.hrg_kubik,h.time,h.type,h.harga,a.nama_wil as wil_asal,t.nama_wil as wil_tujuan,h.id_user,c.tven,
		h.keterangan,v.nm_ven,i.nm_karyawan as insert_user,u.nm_karyawan as update_user
		from harga h
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as i on h.id_user = i.id_user
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as u on h.updated_user = u.id_user
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		) 
		as t on h.wil_tujuan=t.id_wil::INTEGER
		left join m_vendor v on v.id_ven = h.id_ven
		left join 
		(
			select COALESCE(count(id_ven), 0) as tven , parent from harga group by parent
		) as c on c.parent = h.id_harga
		where h.wil_asal='" . $asal . "' and h.wil_tujuan='" . $tujuan . "' and h.type='1' ";

		$data = DB::select($sql);

		return $data;
	}

	public static function getParent($id)
	{
		$sql = "select distinct(h.id_harga),h.created_at,h.rekomendasi,h.updated_at,
		case when(h.type ='1') then h.min_kg when(h.type='2') then par.min_kg end as min_kg,
		case when(h.type ='1') then h.min_kubik when(h.type='2') then par.min_kubik end as min_kubik,
		case when(h.type ='1') then h.time when(h.type='2') then par.time end as time,
		case when(h.type ='1') then h.harga when(h.type='2') then par.harga end as harga,
		case when(h.type ='1') then h.hrg_kubik when(h.type='2') then par.hrg_kubik end as hrg_kubik,
		a.nama_wil as wil_asal,t.nama_wil as wil_tujuan,h.id_user,c.tven,h.type,
		h.keterangan,v.nm_ven,i.nm_karyawan as insert_user,u.nm_karyawan as update_user
		from harga h
		left join relationharga o on o.child = h.id_harga
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as i on h.id_user = i.id_user
		left join (
			select k.nm_karyawan, u.id_user from m_karyawan k join users u on k.id_karyawan = u.id_karyawan
		) as u on h.updated_user = u.id_user
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		)
		as a on h.wil_asal=a.id_wil::INTEGER
		join 
		(
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id
		) 
		as t on h.wil_tujuan=t.id_wil::INTEGER
		left join m_vendor v on v.id_ven = h.id_ven
		left join 
		(
			select COALESCE(count(h.id_ven),0) as tven,r.parent
			from harga h 
			join relationharga r on h.id_harga=r.child group by r.parent
		) as c on c.parent = h.id_harga
		left join (
				select r.parent,sum(h.harga) as harga,sum(h.hrg_kubik) as hrg_kubik,
				sum(h.time) as time,max(h.min_kg) as min_kg,max(h.min_kubik) as min_kubik 
				from harga h 
				join relationharga r on h.id_harga=r.child group by r.parent
		) as par on par.parent = h.id_harga
		where h.id_harga='" . $id . "' ";

		$data = DB::select($sql);
		$a_data = [];
		if (count($data) > 0) {
			$a_data = $data[0];
		}

		return $a_data;
	}

}