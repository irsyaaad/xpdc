<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingGroupLayanan extends Model
{
    protected $fillable = [];
    protected $table = "s_grup_layanan_ac";
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

    public static function getSetting($id)
    {
        $sql = "select s.* from s_grup_plgn_ac s join m_plgn p on s.id_plgn_group=p.id_plgn_group where p.id_pelanggan='".$id."' limit 1";

        $data = DB::select($sql);
        
        return $data[0];
    }
}
