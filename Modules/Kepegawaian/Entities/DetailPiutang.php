<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class DetailPiutang extends Model
{
    protected $table = "kep_detail_piutang";
    public $incrementing = false;
	protected $primaryKey = 'id_detail';
	public $keyType = 'string';

    public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
    
}
