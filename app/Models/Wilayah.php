<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Wilayah extends Model
{
	protected $table = "m_wilayah";
	protected $primaryKey = 'id_wil';
	public $incrementing = false;
	public $keyType = 'string';
	
	public static function getWilayah($nama = null, $level = null)
	{
		$sql = "SELECT r.*
		,prov.nama_wil AS provinsi
		, kab.nama_wil AS kabupaten
		, kec.nama_wil AS kecamatan
		FROM m_wilayah r
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
		left JOIN m_wilayah kec ON r.kec_id = kec.id_wil";
		
		if (isset($nama)) {
			$sql = $sql." where lower(r.nama_wil) like lower('%".$nama."%')";
			
		}
		
		if (isset($level)) {
			$sql = $sql." and r.level_wil='".$level."' ";
		}
		
		$sql = $sql." order by r.level_wil,nama_wil asc";
		
		$data = DB::select(DB::raw($sql));
		
		return $data;
	}
	
	public static function getKota($nama = null)
	{
		$sql = "SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label,r.level_wil,r.id_wil
		,prov.nama_wil AS provinsi
		FROM m_wilayah r
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		where r.level_wil = '2' ";
		
		if (isset($nama)) {
			$sql = $sql." and lower(r.nama_wil) like lower('%".$nama."%')";
		}
		
		$sql = $sql." order by r.nama_wil asc";
		
		$data = DB::select(DB::raw($sql));
		
		return $data;
	}
	
	public static function getKecamatan()
	{
		$sql = "SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label,r.level_wil,r.id_wil
		,prov.nama_wil AS provinsi
		FROM m_wilayah r
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		where r.level_wil = '2' ";
		
		$sql = $sql." order by r.level_wil,r.nama_wil asc";
		
		$data = DB::select(DB::raw($sql));
		
		return $data;
	}
	
	public static function getwil($page = 1, $perpage = 50, $id = null)
	{
		$sql = "select kec.id_wil,concat(kec.nama_wil ,' ', kab.nama_wil,' ', prov.nama_wil) as nama_wil from m_wilayah kec 
		left join m_wilayah kab on kec.kab_id = kab.id_wil
		left join m_wilayah  prov on kec.prov_id = prov.id_wil ";
		if($id != null){
			$sql.=" where kec.id_wil = '".$id."'";
		}
		
		$data = DB::select(DB::raw($sql));
		$collect = collect($data);
		
		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		);
		
		
		return $data;
	}
	
	public static function getOnes($id){
		$sql = "select w.id_wil,upper(k.nama_wil) as nama_wil from m_wilayah w join ( 
			select 
			case
			when (kec.level_wil='3') then concat('Kec. ', kec.nama_wil, ', ', kab.nama_wil,', Prov. ', prov.nama_wil)
			when (kec.level_wil ='2') then concat(kec.nama_wil, ', Prov. ' ,prov.nama_wil)
			when (kec.level_wil='1') then concat('Prov. ', kec.nama_wil)
			end as nama_wil,kec.id_wil,kec.level_wil
			from m_wilayah kec 
			left join m_wilayah kab on kec.kab_id = kab.id_wil
			left join m_wilayah prov  on prov.id_wil = kec.prov_id 
		) as k on w.id_wil=k.id_wil 
		where w.id_wil='".$id."' ";

		$data = DB::select($sql);
		$a_data = [];
		if(count($data)>0){
			$a_data = $data[0];
		}

		return $a_data;
	}
	
	public static function getKecamatan2($nama = null)
	{
		$sql = "SELECT r.id_wil as value, r.nama_wil as label
		FROM m_wilayah r where r.level_wil='1' ";
		
		if (isset($nama)) {
			$sql = $sql." and lower(r.nama_wil) like lower('%".$nama."%')";
		}
		
		$sql = $sql." union 
		SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label
		FROM m_wilayah r
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		where r.level_wil = '2' ";
		
		if (isset($nama)) {
			$sql = $sql." and lower(r.nama_wil) like lower('%".$nama."%')";
		}
		
		$sql = $sql." union
		SELECT r.id_wil as value ,concat('Kec. ', r.nama_wil, ', ' ,kab.nama_wil,', ', prov.nama_wil) as label
		FROM m_wilayah r
		left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		where r.level_wil = '3' ";
		
		if (isset($nama)) {
			$sql = $sql." and lower(r.nama_wil) like lower('%".$nama."%')";
		}

		$data = DB::select(DB::raw($sql));
		
		return $data;
	}
	
	public static function getPublic()
	{
		$sql = "SELECT a.id_wil,a.prov_id,prov.nama_wil AS provinsi,kab.nama_wil AS kabupaten,kec.nama_wil as kecamatan FROM m_wilayah a
		LEFT JOIN (
			SELECT id_wil, nama_wil from m_wilayah WHERE level_wil = '1'
			) AS prov ON prov.id_wil = a.prov_id
			JOIN (
				SELECT id_wil, nama_wil from m_wilayah WHERE level_wil = '2'
				) AS kab ON kab.id_wil = a.kab_id 
				LEFT JOIN (
					SELECT id_wil, nama_wil from m_wilayah WHERE level_wil = '3'
					) AS kec ON kec.id_wil = a.kec_id 
					where a.level_wil is not null
					GROUP BY a.id_wil,a.prov_id,prov.nama_wil,kab.nama_wil,kec.nama_wil;";
					
					$data = DB::select(DB::raw($sql));
					
					return $data;
				}
			}
			