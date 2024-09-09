<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Proyeksi extends Model
{
    protected $table = "m_biaya_proyeksi";
    protected $primaryKey = 'id_proyeksi';
    
    public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}

	public function tarif()
	{
		return $this->belongsTo('App\Models\Tarif', 'id_tarif', 'id_tarif');
	}

	public function vendor()
	{
		return $this->belongsTo('App\Models\Vendor', 'id_ven', 'id_ven');
	}

	public function layanan()
	{
		return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
	}

	public function perusahaantj()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tj', 'id_perush');
	}
	
	public function user()
	{
		return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
	}
	
	public static function getByVendor($id_perush, $id_ven = null, $id_layanan = null)
	{	
		$data = self::with("perusahaan", "tarif", "perusahaantj", "user", "layanan")->whereNotnull("id_ven")->where("id_perush", $id_perush);
		
		if($id_ven != null ){
			$data = $data->where("id_ven", $id_ven);
		}

		if($id_layanan != null){
			$data = $data->where("id_layanan", $id_layanan);
		}

		return $data;
	}
	
	public static function getByTarif($id_perush , $id_perush_tj = null , $id_layanan = null)
	{	
		$data = self::with("perusahaan", "tarif", "vendor", "user", "layanan")->wherenull("id_ven")->where("id_perush", $id_perush);

		if($id_perush_tj != null){
			$data = $data->where("id_perush_tj", $id_perush_tj);
		}

		if($id_layanan != null){
			$data = $data->where("id_layanan", $id_layanan);
		}

		return $data;
	}
}
