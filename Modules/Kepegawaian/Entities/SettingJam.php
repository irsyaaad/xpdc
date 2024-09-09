<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingJam extends Model
{
    protected $fillable = [];
	protected $table = "s_jam_kerja";
    protected $primaryKey = 'id_setting';
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 
    public function perush()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    } 

}
