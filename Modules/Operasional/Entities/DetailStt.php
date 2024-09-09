<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class DetailStt extends Model
{
    protected $fillable = [];
    protected $table = "t_detail_stt";
	protected $primaryKey = 'id_detail';

	
	public function perush_asal()
 	{	
 	   	return $this->belongsTo('App\User', 'id_user', 'id_user');
 	} 
    
 	public function stt()
 	{	
 	   	return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
 	}
}
