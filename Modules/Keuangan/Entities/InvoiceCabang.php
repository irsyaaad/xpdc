<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoiceCabang extends Model
{
    protected $table = "keu_invoice_cabang_perush";
    protected $primaryKey = 'id_invoice';
}
