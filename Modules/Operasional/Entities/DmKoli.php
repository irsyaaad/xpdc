<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class DmKoli extends Model
{
    protected $fillable = [];
    protected $table = "t_dm_koli";
	protected $primaryKey = 'id_dm_koli';
	
	public function koli()
 	{
 		return $this->belongsTo('Modules\Operasional\Entities\OpOrderKoli', 'id_koli', 'id_koli');
 	}

 	public function stt()
 	{
 		return $this->hasMany('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
 	}
}
