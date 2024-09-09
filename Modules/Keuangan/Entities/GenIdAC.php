<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class GenIdAC extends Model
{
    protected $fillable = [];
    protected $table = "gen_id_masterac";
    protected $primaryKey = 'id_masterac';
    public $incrementing = false;
	public $keyType = 'string';
}