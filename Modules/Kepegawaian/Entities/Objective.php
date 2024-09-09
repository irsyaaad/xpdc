<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    protected $table = "kpi_objective";   
	protected $primaryKey = 'id_objective';
    
    public function jenis()
	{
		return $this->belongsTo('App\Models\JenisKaryawan', 'id_jenis', 'id_jenis');
	}
}
