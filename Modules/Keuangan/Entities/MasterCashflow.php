<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterCashflow extends Model
{
    protected $fillable = [];
    protected $table = "m_cashflow";
	protected $primaryKey = "id_cf";

    public function user()
 	{	
 	   	return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
 	}
}
