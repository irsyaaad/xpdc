<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusDM extends Model
{
   	protected $fillable = [];
    protected $table = "m_status_dm";
	protected $primaryKey = 'id_status';
	public $incrementing = false;
	public $keyType = 'string';
	
	public static function getList($tipe = null)
	{
		$status = self::select("id_status", "nm_status")->where("id_status", ">", "1");
        if($tipe != null){
			$status = $status->where("tipe", $tipe);
		}

		$status = $status->get();
		
		$stt = [];
        foreach ($status as $key => $value) {
            $stt[$value->id_status] = $value;
		}
			
		return $stt;
	}
}
