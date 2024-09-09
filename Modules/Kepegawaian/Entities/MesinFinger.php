<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class MesinFinger extends Model
{
    protected $table = "s_mesin_finger";
	protected $primaryKey = 'id_mesin';

    public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}
    
    public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
}
