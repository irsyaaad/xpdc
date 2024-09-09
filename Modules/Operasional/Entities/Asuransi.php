<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Asuransi extends Model
{
    protected $table = "t_asuransi";
    protected $primaryKey = 'id_asuransi';

    public function stt()
    {
        return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'kode_stt');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_pelanggan', 'id_perush');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function asal()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_asal', 'id_wil');
    }

    public function tujuan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_tujuan', 'id_wil');
    }

    public function nm_broker()
    {
        return $this->belongsTo('Modules\Operasional\Entities\PerusahaanAsuransi', 'broker', 'id_perush_asuransi');
    }

    public function tipebarang()
	{
		return $this->belongsTo('Modules\Operasional\Entities\TipeKirim', 'id_tipe_barang', 'id_tipe_kirim');
	}

    public function bayar() {
        return $this->belongsTo('Modules\Keuangan\Entities\PembayaranAsuransi', 'id_asuransi', 'id_asuransi');
    }
}
