<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingBiayaVendor extends Model
{
    protected $fillable = [];
    protected $table = "s_biaya_vendor_ac";
    protected $primaryKey = 'id_setting';

    public function user()
    {	
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public function hutang()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_hutang', 'id_ac');
    }
    
    public function biaya()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac_biaya', 'id_ac');
    }

    public function group()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
    } 
    
    public static function getBiaya()
    {
        $sql = "SELECT g.id_biaya_grup,g.nm_biaya_grup from s_biaya_vendor_ac s
        join m_biaya_grup g on s.id_biaya_grup = g.id_biaya_grup";  

        $data = DB::select($sql);

        return $data;
    }
}
