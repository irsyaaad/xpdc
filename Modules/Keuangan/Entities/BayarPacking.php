<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class BayarPacking extends Model
{
    protected $table = "t_order_packing_bayar";
    protected $primaryKey = 'id_bayar';
}
