<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class ProyeksiDetail extends Model
{
    protected $fillable = [];
    protected $table = "m_biaya_pro_det";
    
	protected $primaryKey = 'id_detail';

	public function proyeksi()
	{	
		return $this->belongsTo('App\Model\Proyeksi', 'id_proyeksi', 'id_proyeksi');
	}

	public function user()
	{	
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
	
	public function group()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
	}
}
