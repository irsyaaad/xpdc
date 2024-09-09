<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class DraftInvoiceAsuransi extends Model
{
    protected $fillable = [];
    protected $table = "keu_draft_invoice_asuransi";
    protected $primaryKey = 'id_draft';
    // public $incrementing = true;

    public function asuransi()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Asuransi', 'id_asuransi', 'id_asuransi');
    }
}
