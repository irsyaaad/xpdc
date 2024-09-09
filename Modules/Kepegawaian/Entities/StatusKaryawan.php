<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusKaryawan extends Model
{
    protected $table = "m_status_karyawan";
    public $incrementing = false;
	protected $primaryKey = 'id_status_karyawan';
	public $keyType = 'string';
    
    public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
}
