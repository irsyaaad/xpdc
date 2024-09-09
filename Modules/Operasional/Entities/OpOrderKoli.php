<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class OpOrderKoli extends Model
{
    protected $fillable = ["id_stt", "no_koli", "dr_koli", "info", "id_user", "status", "status", "status_dm_ven"];
    protected $table = "t_order_koli";
	protected $primaryKey = 'id_koli';
	// public $keyType = 'string';
	// public $incrementing = false;
	
	public static function getKoliStt($id, $status=null)
	{
		$data = self::where("id_stt", $id)->where("status", $status)->orderBy("no_koli", "asc")->get();
		
		return $data;
	}
	
	public static function getSttKoliVendor($id, $status=null)
	{
		$data = self::where("id_stt", $id)->where("status_dm_ven", $status)->orderBy("no_koli", "asc")->get();
		
		return $data;
	}
}
