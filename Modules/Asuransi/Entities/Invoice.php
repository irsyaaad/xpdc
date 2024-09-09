<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "keu_invoice_asuransi";
    protected $primaryKey = 'id_invoice';
    protected $fillable = [];

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_pelanggan', 'id_perush');
    }
}
