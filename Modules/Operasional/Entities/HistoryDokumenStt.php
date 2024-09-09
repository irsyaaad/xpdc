<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class HistoryDokumenStt extends Model
{
    protected $fillable = [];
    protected $table = "t_history_dokumen_stt";
    public $incrementing = false;
    protected $primaryKey = 'id_history';
    public $keyType = 'string';
}
