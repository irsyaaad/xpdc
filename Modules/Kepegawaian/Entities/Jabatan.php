<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = "m_jabatan";
    public $incrementing = false;
	protected $primaryKey = 'id_jabatan';
	public $keyType = 'string';
    
	// format jenis ijin
	// 1 / Hituang Jam {Datang Terlambat, Pulang Cepat, Keluar Kantor}
	// 2 / Hitung Harian {Cuti, Sakit, Izin Tidak Masuk}
	
    public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
}
