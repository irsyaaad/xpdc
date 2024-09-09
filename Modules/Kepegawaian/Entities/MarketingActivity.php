<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class MarketingActivity extends Model
{
    protected $table = "t_activity_marketing";
    public $incrementing = false;
    protected $primaryKey = 'id';

    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function marketing()
	{
		return $this->belongsTo('Modules\Kepegawaian\Entities\Marketing', 'id_marketing', 'id_marketing');
	}

    public function pelanggan()
	{
		return $this->belongsTo('App\Models\Pelanggan', 'id_pelanggan', 'id_pelanggan');
	}

    public function activity()
	{
		return $this->belongsTo('Modules\Kepegawaian\Entities\Activity', 'id_activity', 'id_activity');
	}

}
