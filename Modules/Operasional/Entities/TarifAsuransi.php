<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class TarifAsuransi extends Model
{
    protected $table = "m_tarif_asuransi";
    protected $primaryKey = 'id_tarif';
	// public $keyType = 'string';

    public function perusahaan_asuransi()
 	{
 	   	return $this->belongsTo('Modules\Operasional\Entities\PerusahaanAsuransi', 'id_perush_asuransi', 'id_perush_asuransi');
 	}
    
     public function perusahaan()
 	{
 	   	return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
 	}
}
