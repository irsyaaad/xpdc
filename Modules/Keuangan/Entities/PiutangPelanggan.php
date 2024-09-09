<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class PiutangPelanggan extends Model
{
    protected $fillable = [];
    protected $table = "keu_pendapatan_det";
	protected $primaryKey = 'id_detail';
	public $incrementing = false;
    public $keyType = 'string';
    
}
