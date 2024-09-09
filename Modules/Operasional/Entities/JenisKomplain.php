<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class JenisKomplain extends Model
{
    protected $table = "t_complain_jenis";
    public $incrementing = false;
	protected $primaryKey = 't_complain_jenis_pkey';
}
