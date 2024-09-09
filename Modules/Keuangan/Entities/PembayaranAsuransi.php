<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class PembayaranAsuransi extends Model
{
    protected $fillable = [];
    protected $table = "t_asuransi_pay";
	protected $primaryKey = 'id_asuransi_pay';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function cara()
	{
		return $this->belongsTo('Modules\Operasional\Entities\CaraBayar', 'id_cr_byr', 'id_cr_byr_o');
    }

    public function asuransi()
	{
		return $this->belongsTo('Modules\Operasional\Entities\Asuransi', 'id_asuransi', 'id_asuransi');
    }

    public function pelanggan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_plgn', 'id_perush');
    }

    public function bank()
	{
		return $this->belongsTo('Modules\Keuangan\Entities\BankPerush', 'id_bank', 'id_bank_perush');
    }

    public function bayar() {
        
    }
}
