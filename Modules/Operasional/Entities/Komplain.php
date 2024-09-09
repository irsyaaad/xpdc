<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    protected $table = "t_complain";
    protected $fillable = [];

    public function stt()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
    }

    public function jenis_komplain()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\JenisKomplain', 'id_jenis_complain', 'id');
    }

    public function perush_tujuan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tujuan', 'id_perush');
	}
}
