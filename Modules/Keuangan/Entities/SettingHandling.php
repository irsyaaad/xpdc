<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingHandling extends Model
{
    protected $table = "s_handling_ac";
    protected $primaryKey = 'id_setting';

    public function hutang()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_hutang', 'id_ac');
    } 

    public function piutang()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_piutang_penerima', 'id_ac');
    }
    
    public function pendapatan()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_pend_penerima', 'id_ac');
    }

    public function biaya()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_biaya', 'id_ac');
    } 

    public function perush()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
    public function pengirim()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_pengirim', 'id_perush');
    }

    public static function getData()
    {
        $data = self::with("piutang", "hutang", "perush", "pendapatan", "biaya")->get();
        
        return $data;
    }
    
    public static function getBiaya()
    {
        $data = [];
    }
}
