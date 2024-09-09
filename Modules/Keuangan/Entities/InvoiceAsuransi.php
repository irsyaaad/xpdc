<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoiceAsuransi extends Model
{
    protected $fillable = [];
    protected $table = "keu_invoice_asuransi";
    // public $incrementing = false;
    protected $primaryKey = 'id_invoice';
    // public $keyType = 'string';

    public function pelanggan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_plgn', 'id_perush');
    }
    
    public function stt(){
        return $this->belongsTo('Modules\Operasional\Entities\SttModel','id_stt','id_stt');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function status(){
        return $this->belongsTo('Modules\Keuangan\Entities\StatusInvoice','id_status','id_status');
    }
}
