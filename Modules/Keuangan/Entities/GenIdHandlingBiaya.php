<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class GenIdHandlingBiaya extends Model
{
    protected $fillable = [];
    protected $table = "gen_id_handling_biaya";
    protected $primaryKey = 'id_gen';
    public $incrementing = false;
    public $keyType = 'string';
}
