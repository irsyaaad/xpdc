<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class PerusahaanAsuransi extends Model
{
    protected $table = "m_perusahaan_asuransi";
    protected $primaryKey = 'id_perush_asuransi';
    public $incrementing = false;
    public $keyType = 'string';

    public function wilayah()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_region', 'id_wil');
    }

    public function tarif()
    {
        return $this->belongsTo('Modules\Operasional\Entities\TarifAsuransi', 'id_perush_asuransi', 'id_perush_asuransi');
    }
}
