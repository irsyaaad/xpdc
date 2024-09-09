<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class BiayaHppDel extends Model
{
    protected $fillable = [];
    protected $table = "t_dm_biaya_bayar_del";
    protected $primaryKey = 'id_biaya';

}