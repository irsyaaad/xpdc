<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class MemorialDetail extends Model
{
    protected $fillable = [];
    protected $table = "keu_memorial_det";
	protected $primaryKey = 'id_detail';
	// public $incrementing = false;
    // public $keyType = 'string';
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
    
    public function kredit()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_debet', 'id_ac')->where('id_perush', Session("perusahaan")["id_perush"]);
    }

    public function debet()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_kredit', 'id_ac')->where('id_perush', Session("perusahaan")["id_perush"]);
    }
}
