<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class PengeluaranDetail extends Model
{
    protected $fillable = [];
    protected $table = "keu_pengeluaran_det";
	protected $primaryKey = 'id_detail';
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
    
    public function akun()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac', 'id_ac')->where('id_perush',Session("perusahaan")["id_perush"]);
    }
}
