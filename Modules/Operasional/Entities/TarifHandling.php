<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class TarifHandling extends Model
{
    protected $table = "m_tarif_handling";
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

 	public function layanan()
 	{
 	   	return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
 	}
}
