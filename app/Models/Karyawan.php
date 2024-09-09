<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Karyawan extends Model
{
    protected $table = "m_karyawan";
    protected $primaryKey = 'id_karyawan';
 	
 	public function perusahaan()
 	{	
 	   	return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}
	
	public function jenis()
 	{	
 	   	return $this->belongsTo('App\Models\JenisKaryawan', 'id_jenis', 'id_jenis');
	}

	public function shift()
 	{	
 	   	return $this->belongsTo('Modules\Kepegawaian\Entities\SettingJam', 'id_jam_kerja', 'id_setting');
	}
	
	public function mesin()
 	{	
 	   	return $this->belongsTo('Modules\Kepegawaian\Entities\MesinFinger', 'id_mesin', 'id_mesin');
	}

	public function jabatan()
 	{	
 	   	return $this->belongsTo('Modules\Kepegawaian\Entities\Jabatan', 'id_jabatan', 'id_jabatan');
	}
	public function status_karyawan()
 	{	
 	   	return $this->belongsTo('Modules\Kepegawaian\Entities\StatusKaryawan', 'id_status_karyawan', 'id_status_karyawan');
	}
	public function penggajian()
 	{	
 	   	return $this->belongsTo('Modules\Kepegawaian\Entities\GajiKaryawan', 'id_karyawan', 'id_karyawan');
	}

	public static function getKaryawanAll(){
		$sql = "select k.nm_karyawan,p.nm_perush,k.id_karyawan from m_karyawan k 
		join s_perusahaan p on k.id_perush=p.id_perush";

		return DB::select($sql);
	}
	
	public static function getKaryawanShift($id_perush)
	{
		$sql = "select j.jam_masuk, j.jam_istirahat, j.jam_pulang,j.jam_istirahat_masuk, j.jam_toleransi,  k.nm_karyawan, k.id_karyawan from m_karyawan k
		join s_jam_kerja j on k.id_jam_kerja = j.id_setting where k.id_perush ='".$id_perush."'";
		
		return DB::select($sql);
	}

	public static function getData($id_perush = null)
	{
		$sql = "select k.nm_karyawan, k.jenis_kelamin,k.id_karyawan, j.nm_jenis, p.nm_perush  from m_karyawan k
		left join m_jenis_karyawan j on k.id_jenis=j.id_jenis
		join s_perusahaan p on k.id_perush = p.id_perush
		where k.is_aktif = true ";

		if($id_perush != null){
			$sql = $sql." and k.id_perush ='".$id_perush."' ";
		}

		$sql = $sql." order by k.nm_karyawan asc ";

		return DB::select($sql);
	}
	
	public static function getKaryawanCabang($id_role = null, $id_perush = null)
	{
		$sql = "select k.id_karyawan,k.nm_karyawan,k.id_perush from role_user r
		join m_karyawan k on r.id_perush = k.id_perush 
		where r.id_role = '".$id_role."' ";

		if($id_perush != null){
			$sql = $sql." and r.id_perush = '".$id_perush."'";
		}

		$sql .= " order by k.nm_karyawan asc ";
		
		$data = DB::select($sql);

		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_perush][$value->id_karyawan] = $value;
		}

		return $a_data;
	}

	public static function getKaryawanNotUser($id_perush = null)
	{
		$sql = "select k.nm_karyawan,k.id_karyawan from m_karyawan k
		where k.id_karyawan not in (select id_karyawan from users) ";

		if($id_perush != null){
			$sql .= " and k.id_perush = '".$id_perush."' ";
		}
		
		$sql .= "  order by k.nm_karyawan asc";
		
		return DB::select($sql);

	}

	
	public static function getkaryawanuser($id_perush = null)
	{
		$sql = "select k.nm_karyawan,k.id_karyawan,u.id_user from m_karyawan k
		join users u on k.id_karyawan = u.id_karyawan ";
		
		if($id_perush != null){
			$sql = $sql." where k.id_perush = '".$id_perush."'";
		}

		return DB::select($sql);

	}
	
	public static function getUserKaryawan($id_perush = null)
	{
		$sql = "select k.nm_karyawan,k.id_karyawan from m_karyawan k
		join users u on k.id_user = u.id_user ";
		
		if($id_perush != null){
			$sql = $sql." where k.id_perush = '".$id_perush."'";
		}

		return DB::select($sql);

	}
	
	public static function getList($id_perush = null, $id_karyawan = null, $status = null)
	{
		$data = self::select("m_karyawan.id_karyawan", "m_karyawan.nm_karyawan", "p.nm_perush", "p.id_perush")
					->join("s_perusahaan as p", "m_karyawan.id_perush", "=", "p.id_perush");
		
		if(isset($id_perush) and $id_perush!=null){
			$data = $data->where("m_karyawan.id_perush", $id_perush);
		}

		if(isset($id_karyawan) and $id_karyawan!=null){
			$data = $data->where("m_karyawan.id_karyawan", $id_karyawan);
		}
		
		if(isset($status) and $status!=null){
			$data = $data->where("m_karyawan.is_aktif", $status);
		}
		
		$data = $data->OrderBy("m_karyawan.nm_karyawan", "asc")->get();
		
		return $data;
	}
	
	public static function getFilter($id_perush = null, $id_karyawan = null, $is_aktif = null)
	{
		$data = self::with("perusahaan", "jenis", "mesin", "shift");
		if($id_perush != null){
			$data = $data->where("id_perush", $id_perush);
		}
		
		if($id_karyawan != null){
			$data = $data->where("id_karyawan", $id_karyawan);
		}
		
		if($is_aktif != null){
			$data = $data->where("is_aktif", $is_aktif);
		}
		
		$data = $data->orderBy("nm_karyawan", "asc");
		
		return $data;
	}

	public static function DataJabatan($id_perush)
	{
		$data = self::leftjoin("m_jabatan as a","m_karyawan.id_jabatan","a.id_jabatan")
				->where("m_karyawan.id_perush",$id_perush)
				->select("m_karyawan.*")
				->orderBy("m_karyawan.nm_karyawan");
		
				return $data;
	}

	public static function getJamKerja($id_finger, $cloud_id)
	{
		return self::select(
				'm_karyawan.id_karyawan',
				'm_karyawan.nm_karyawan',
				'm_karyawan.id_perush',
				'm_karyawan.id_jam_kerja',
				's_jam_kerja.jam_masuk',
				's_jam_kerja.jam_terlambat',
				's_jam_kerja.jam_toleransi',
				's_jam_kerja.jam_pulang',
				's_jam_kerja.jam_sabtu',
				'm_karyawan.id_finger',
				's_mesin_finger.cloud_id'
				)
			->join('s_mesin_finger','m_karyawan.id_mesin','=','s_mesin_finger.id_mesin')
			->join('s_jam_kerja','m_karyawan.id_jam_kerja','=','s_jam_kerja.id_setting')
			->where([
					['m_karyawan.id_finger', $id_finger],
					['s_mesin_finger.cloud_id', $cloud_id]
				])
			->get();
	}
}
