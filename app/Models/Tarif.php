<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tarif extends Model
{
	protected $table = "m_tarif";
	protected $primaryKey = 'id_tarif';
    
    public function asal()
 	{
 	   	return $this->belongsTo('App\Models\Wilayah', 'id_asal', 'id_wil');
 	}
 	
 	public function tujuan()
 	{
 	   	return $this->belongsTo('App\Models\Wilayah', 'id_tujuan', 'id_wil');
 	}

 	public function perusahaan()
 	{
 	   	return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
 	}

 	public function pelanggan()
 	{
 	   	return $this->belongsTo('App\Models\Pelanggan', 'id_pelanggan', 'id_pelanggan');
 	}

 	public function layanan()
 	{
 	   	return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
 	}
 	
 	public static function getListTarif($asal=null, $tujuan=null, $id_layanan = null, $id_pelanggan = null)
 	{	
 		$where = "";
 		if(isset($asal) and isset($tujuan)){
			$where = " where T.id_asal='".$asal."' and T.id_tujuan='".$tujuan."'";
		}

		if(isset($id_layanan) and isset($asal) and isset($tujuan)){
			$where = $where." and T.id_layanan='".$id_layanan."'";
		}elseif(isset($id_layanan) and !isset($asal) and !isset($tujuan)){
			$where = " where T.id_layanan='".$id_layanan."'";
		}
		
 		$sql = "
		SELECT T.id_tarif,
			T.id_asal,
			T.id_tujuan,
			T.hrg_brt,
			T.hrg_vol,
			T.hrg_coly,
			T.hrg_vol,
			T.min_brt,
			T.is_standart,
			concat ( T.hrg_vol, ' / ', T.min_vol, 'KgV' ) AS trvol,
			concat ( T.hrg_brt, ' / ', T.min_brt, 'Kg' ) AS trbrt,
			concat ( T.hrg_kubik, ' / ', T.min_kubik, 'M3' ) AS trkbk,
			T.info,
			T.min_vol,
			T.estimasi,
			T.estimasi,
			A.nama_wil AS asal,
			u.nama_wil AS tujuan,
			l.nm_layanan,
			P.nm_perush,
			v.nm_ven 
		FROM
			m_tarif
			T JOIN m_wilayah A ON T.id_asal = A.id_wil
			JOIN m_wilayah u ON T.id_tujuan = u.id_wil
			JOIN m_layanan l ON T.id_layanan = l.id_layanan
			JOIN s_perusahaan P ON P.id_perush = T.id_perush
			LEFT JOIN m_vendor v ON T.id_ven = v.id_ven";

		if (isset($id_pelanggan)) {
			$sql = $sql." left join (select id_tarif,id_layanan,id_pelanggan from m_tarif where id_layanan='".$id_layanan."' and id_asal='".$asal."' and id_tujuan='".$tujuan."' and id_pelanggan='".$id_pelanggan."') as pe on T.id_tarif=pe.id_tarif";
		}
		
		if(get_admin()!=1){
			$where = $where." and T.id_perush='".Session("perusahaan")["id_perush"]."' ";
		}
		
		$sql = $sql.$where." order by T.id_tarif asc";
		
		$data = DB::select($sql);
		
		return $data;
 	}
	 
 	public static function getTarif($asal=null, $tujuan=null, $id_layanan = null, $id_ven =null)
 	{
		$data = self::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")
 			->where("id_perush", Session("perusahaan")["id_perush"])->whereNull("id_ven")->whereNull("id_pelanggan");
		
		if(isset($asal) and $asal != null){
			$data->where("id_asal", $asal);
		}

		if(isset($tujuan) and $tujuan != null){
			$data->where("id_tujuan", $tujuan);
		}

		if(isset($id_layanan) and $id_layanan != null){
			$data->where("id_layanan", $id_layanan);
		}

		$data->orderBy("created_at", "asc");

 		return $data;
 	}

 	public static function getTarifPelanggan($id)
 	{
 		$data = self::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")->where("id_pelanggan", $id)->get();

 		return $data;
 	}

 	public static function getTarifVendor($id = null)
 	{
 		$data = self::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")->where("id_ven", $id)->get();
 		
 		return $data;
 	}
	 
	 public static function filterTarif($id_ven=null, $asal=null, $tujuan = null)
 	{
 		$data = [];

 		$data = self::with("asal", "tujuan", "perusahaan", "layanan", "pelanggan")
 			->where("id_perush", Session("perusahaan")["id_perush"])
			 ->whereNull("id_ven")
			 ->whereNull("id_asal")
			 ->whereNull("id_tujuan");
		 
		if(isset($id_ven)){
			$data->where("id_ven", $id_ven);
		}
		if(isset($asal)){
			$data->where("id_asal", $asal);
		}
		
		if(isset($tujuan)){
			$data->where("id_tujuan", $tujuan);
		}

 		return $data;
 	}
}