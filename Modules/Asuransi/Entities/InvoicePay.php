<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoicePay extends Model
{
    protected $table = "keu_invoice_asuransi_pay";
    protected $primaryKey = 'id_invoice_pay';
    protected $fillable = [];

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_pelanggan', 'id_perush');
    }

    public function invoice()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\Invoice', 'id_invoice', 'id_invoice');
    }
}
