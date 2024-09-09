<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingBiayaPerush extends Model
{
    protected $fillable = [];
    protected $table = "s_biaya_grup_ac_perush";
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
    
    public static function getData($id_perush)
    {
        $data = self::select(
                        'm_biaya_grup.id_biaya_grup',
                        's_biaya_grup_ac_perush.id_setting',
                        'm_biaya_grup.nm_biaya_grup',
                        'hutang.nama AS nama_hutang',
                        'biaya.nama AS nama_biaya',
                        'users.nm_user')
                    ->join('m_biaya_grup','m_biaya_grup.id_biaya_grup','=','s_biaya_grup_ac_perush.id_biaya_grup')
                    ->join('m_ac_perush AS hutang','hutang.id_ac','=','s_biaya_grup_ac_perush.id_ac_hutang')
                    ->join('m_ac_perush AS biaya','biaya.id_ac','=','s_biaya_grup_ac_perush.id_ac_biaya')
                    ->join('users','users.id_user','=','s_biaya_grup_ac_perush.id_user')
                    ->where([
                        ['s_biaya_grup_ac_perush.id_perush', $id_perush],
                        ['hutang.id_perush', $id_perush],
                        ['biaya.id_perush', $id_perush]
                        ])
                    ->get();
        
        return $data;
    }

    public static function DataHppPerush($id_perush)
    {
        $data = self::join("m_biaya_grup as a","a.id_biaya_grup", "=" ,"s_biaya_grup_ac_perush.id_biaya_grup")
                    ->select("s_biaya_grup_ac_perush.id_biaya_grup","a.nm_biaya_grup","s_biaya_grup_ac_perush.id_ac_biaya","s_biaya_grup_ac_perush.id_ac_hutang")
                    ->where("s_biaya_grup_ac_perush.id_perush", $id_perush)->get();
        
                    return $data;
    }
}
