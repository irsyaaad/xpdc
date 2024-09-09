<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    protected $table = "m_marketing";
    protected $primaryKey = 'id_marketing';

    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function Karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }

    public static function getMarketing($id_perush = null, $id_marketing = null)
    {
        $data = self::select("id_marketing", "nm_marketing");

        if($id_perush != null){
            $data->where("id_perush", $id_perush);
        }

        if($id_marketing != null){
            $data->where("id_marketing", $id_marketing);
        }

        return $data->get();
    }
}
