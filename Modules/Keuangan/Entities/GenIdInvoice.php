<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class GenIdInvoice extends Model
{
    protected $fillable = [];
    protected $table = "gen_id_invoice";
    protected $primaryKey = 'id_invoice';
    public $incrementing = false;
	public $keyType = 'string';
}
