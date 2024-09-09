<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $fillable = [];
    protected $table = "keu_pengeluaran";
	protected $primaryKey = 'id_pengeluaran';
	// public $incrementing = false;
    // public $keyType = 'string';
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
    
    public function perusahaan()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    } 
    
    public function debet()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac', 'id_ac')->where('id_perush',Session("perusahaan")["id_perush"]);
    }
}
