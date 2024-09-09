<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingLayananPerush extends Model
{
    protected $fillable = [];
    protected $table = "s_grup_layanan_ac_perush";
    protected $primaryKey = 'id_setting';
    
    public function layanan()
    {	
        return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
    }

    public function pendapatan()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_pendapatan', 'id_ac');
    }

    public function diskon()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_diskon', 'id_ac');
    }

    public function ppn()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_ppn', 'id_ac');
    }

    public function materai()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_materai', 'id_ac');
    }

    public function piutang()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_piutang', 'id_ac');
    } 

    public function asuransi()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_asuransi', 'id_ac');
    } 

    public function packing()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_packing', 'id_ac');
    } 
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
}
