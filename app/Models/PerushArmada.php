<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerushArmada extends Model
{
    protected $table = "m_perush_armada";
    protected $primaryKey = 'id_perush_armd';

    public function wil()
 	{
 	   	return $this->belongsTo('App\Models\Wilayah', 'id_wil', 'id_wil');
 	}
}
