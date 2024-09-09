<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhiteList extends Model
{
    protected $table = "s_whitelistip";
    protected $primaryKey = 'id_whitelist';
	public $incrementing = true;

    public function perusahaan()
	{	
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	} 
}
