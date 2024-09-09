<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingBiaya extends Model
{
    protected $fillable = [];
    protected $table = "s_biaya_grup_ac";
    protected $primaryKey = 'id_setting';
    
    public function hutang()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_hutang', 'id_ac');
    }
    
    public function biaya()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_biaya', 'id_ac');
    }
    
    public function group()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
    } 

    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
    
    public static function getData()
    {
        $data = self::join("m_ac_perush as a", "a.id_ac", "=", "s_biaya_grup_ac.id_ac_hutang")
                    ->join("m_biaya_grup as b", "b.id_biaya_grup", "=", "s_biaya_grup_ac.id_biaya_grup")
                    ->select("a.id_ac", "a.nama as nm_ac", "b.nm_biaya_grup", "b.id_biaya_grup")
                    ->groupBy("a.id_ac", "a.nama", "b.nm_biaya_grup", "b.id_biaya_grup")->get();
        
        return $data;
    }
}
