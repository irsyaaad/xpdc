<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusDokumenStt extends Model
{
    protected $fillable = [];
    protected $table = "m_ord_stt_stat_dok";
    public $incrementing = false;
    protected $primaryKey = 'id_ord_stt_stat_dok';
}
