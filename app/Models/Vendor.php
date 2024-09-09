<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	protected $table = "m_vendor";
	protected $primaryKey = 'id_ven';
	
	public function perusahaan()
	{	
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	} 
	
	public function group()
	{	
		return $this->belongsTo('App\Models\GroupVendor', 'id_grup_ven', 'id_grup_ven');
	}  
	
	public function wilayah()
	{	
		return $this->belongsTo('App\Models\Wilayah', 'id_wil', 'id_wil');
	}
	
	public function user()
	{	
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
	
	public function cara()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\CaraBayar', 'cara_bayar', 'id_cr_byr_o');
	}  
	
	public static function getListVendor($page, $id_grup_ven = null, $id_ven = null)
	{
		$id_perush = Session("perusahaan")["id_perush"];
		$data = self::with("perusahaan", "group", "wilayah", "cara")->where("id_perush", $id_perush);

		if($id_grup_ven != null){
			$data = $data->where("id_grup_ven", $id_grup_ven);
		}

		if($id_ven != null){
			$data = $data->where("id_ven", $id_ven);
		}

		$data = $data->orderBy("nm_ven", "asc")->paginate($page);

		return $data;
	}
	
	public static function getVendor()
	{
		if(get_admin()){
			$data = self::with("perusahaan", "group", "wilayah", "cara");
		}else{
			$data = self::with("perusahaan", "group", "wilayah", "cara")->where("id_perush", Session("perusahaan")["id_perush"]);
		}
		
		$data->orderBy("nm_ven", "asc");
		
		return $data;
	}

	public static function getData($id_perush = null)
	{
		$data = self::select("id_ven", "nm_ven")->orderBy("nm_ven", "asc")->get();

		return $data;
	}
}
