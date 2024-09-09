<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingPacking extends Model
{
    protected $table = "s_packing";
	protected $primaryKey = 'id_setting';

    public static function getData()
    {
        $sql = "
        SELECT
            s.id_setting,
            A.nama AS piutang,
            b.nama AS pendapatan 
        FROM
            s_packing s
            JOIN m_ac_perush A ON s.ac_piutang = A.id_ac
            JOIN m_ac_perush b ON s.ac_pendapatan = b.id_ac 
        GROUP BY
            s.id_setting,
            A.nama,
            b.nama 
        ";

        $data = DB::select($sql);

        return $data;
    }
}
