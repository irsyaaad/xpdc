<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKaryawan extends Model
{
    protected $table = "m_jenis_karyawan";
    public $incrementing = false;
	protected $primaryKey = 'id_jenis';
	public $keyType = 'string';
    
    public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
}