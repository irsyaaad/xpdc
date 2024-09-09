<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class AsuransiPay extends Model
{
    protected $fillable = [];
    protected $table = "t_asuransi_pay";
    protected $primaryKey = 'id_asuransi_pay';

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_pelanggan', 'id_perush');
    }

    public function asuransi()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\Asuransi', 'id_asuransi', 'id_asuransi');
    }

}
