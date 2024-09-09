<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class HandlingBiaya extends Model
{
    protected $fillable = [];
    protected $table = "t_handling_biaya_bayar";
    protected $primaryKey = 'id_bayar';
    
    public function group()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
    }
    
    public function perush_pengirim()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_pengirim', 'id_perush');
    }

    public function perush_penerima()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_penerima', 'id_perush');
    }

    public function handling()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\Handling', 'id_handling', 'id_handling');
    }

    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function piutang_penerima()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_piutang_penerima', 'id_ac');
    }

    public function hutang_pengirim()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_hutang_pengirim', 'id_ac');
    }

    public function bayar_penerima()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_bayar_penerima', 'id_ac');
    }

    public function bayar_pengirim()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_bayar_pengirim', 'id_ac');
    }
}
