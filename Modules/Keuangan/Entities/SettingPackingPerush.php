<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingPackingPerush extends Model
{
    protected $table = "s_packing_perush";
	protected $primaryKey = 'id_setting';

    public static function getData($id_perush)
    {
        $sql = "select DISTINCT(s.id_setting), a.nama as piutang, b.nama as pendapatan from s_packing_perush s
        join m_ac_perush a on s.ac_piutang = a.id_ac
        join m_ac_perush b on s.ac_pendapatan = b.id_ac
        where s.id_perush = '".$id_perush."'";
        
        $data = DB::select($sql);

        return $data;
    }
}
