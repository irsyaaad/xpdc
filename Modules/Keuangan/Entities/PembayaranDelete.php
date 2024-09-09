<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class PembayaranDelete extends Model
{
    protected $fillable = [];
    protected $table = "t_order_pay_del";
	protected $primaryKey = 'id_order_pay';
}
