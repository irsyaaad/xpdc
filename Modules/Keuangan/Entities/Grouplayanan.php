<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Grouplayanan extends Model
{
    protected $table = "s_grup_layanan_ac";
    public $incrementing = false;
	protected $primaryKey = 'id_plgn_group';
	public $keyType = 'string';
}
