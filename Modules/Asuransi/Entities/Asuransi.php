<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    protected $table = "t_asuransi";
    protected $primaryKey = 'id_asuransi';
    protected $fillable = [];

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_pelanggan', 'id_perush');
    }

    public function asal()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_asal', 'id_wil');
    }

    public function tujuan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_tujuan', 'id_wil');
    }

    public function tipebarang()
    {
        return $this->belongsTo('Modules\Operasional\Entities\TipeKirim', 'id_tipe_barang', 'id_tipe_kirim');
    }

    public function perush_asuransi()
    {
        return $this->belongsTo('Modules\Operasional\Entities\PerusahaanAsuransi', 'broker', 'id_perush_asuransi');
    }

    public function bayar()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\AsuransiPay', 'id_asuransi', 'id_asuransi');
    }
}
