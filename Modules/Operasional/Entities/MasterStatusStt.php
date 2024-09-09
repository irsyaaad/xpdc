<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterStatusStt extends Model
{
    protected $fillable = [];
    protected $table = "m_status_stt";
    public $incrementing = false;
    protected $primaryKey = 'id_status_stt';
    public $keyType = 'string';
}
