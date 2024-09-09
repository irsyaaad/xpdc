<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class DetailProyeksi extends Model
{	
    protected $fillable = [];
    protected $table = "m_biaya_pro_det";
	protected $primaryKey = 'id_detail';
	
	public function grup()
	{
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
	}
	
	public static function getDetail($id)
	{
		$data = self::with("grup")->where("id_proyeksi", $id)->get();

		return $data;
	}
}
