<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class AsuransiBiayaPay extends Model
{
    protected $fillable = [];
    protected $table = "t_asuransi_biaya_pay";
    protected $primaryKey = 'id_asuransi_biaya_pay';

    public function asuransi()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\Asuransi', 'id_asuransi', 'id_asuransi');
    }
}
