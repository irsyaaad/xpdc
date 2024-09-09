<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterCashFlowPerush extends Model
{
    protected $fillable = [];
    protected $table = "m_cashflow_perush";
	protected $primaryKey = "id";

    public function user()
 	{	
 	   	return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
 	}

    public function cashflow()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\MasterCashflow', 'id_cf', 'id_cf');
    }

    public function akun()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac', 'id_ac');
    }


}
