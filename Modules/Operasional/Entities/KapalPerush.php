<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class KapalPerush extends Model
{
    protected $fillable = ["nm_kapal_perush", "alamat", "telp", "id_user"];
    protected $table = "m_kapal_perush";
	protected $primaryKey = 'id_kapal_perush';
	
	// public function perusahaan()
 // 	{	
 // 	   	return $this->belongsTo('App\Perusahaan', 'id_perush', 'id_perush');
 // 	} 
}
