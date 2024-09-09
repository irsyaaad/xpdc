<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class GroupBiaya extends Model
{
    protected $fillable = [];
    protected $table = "m_biaya_grup";
    public $incrementing = false;
	protected $primaryKey = "id_biaya_grup";
	public $keyType = 'string';
	
    public function user()
 	{	
 	   	return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
 	}

	 public static function getBiaya()
	 {
		$data = DB::table("m_biaya_grup as b")
					->join("s_biaya_grup_ac as p", "b.id_biaya_grup", "=", "p.id_biaya_grup")
					->select("b.id_biaya_grup", "b.nm_biaya_grup","b.klp")->get();

		return $data;
	}

	public static function getList()
	{
		$data = DB::select("select nm_biaya_grup, id_biaya_grup from m_biaya_grup");

		return $data;
	}
}
