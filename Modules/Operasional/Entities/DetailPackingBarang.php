<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class DetailPackingBarang extends Model
{
    protected $table = "t_order_packing_detail";
    protected $primaryKey = 'id_detail';

    public static function getListDetail($id)
    {
        $sql = "select d.id_detail,d.id_packing, t.nm_tipe_kirim, p.nm_packing, d.panjang, d.lebar,d.tinggi, d.volume,d.tarif, d.n_total, d.is_borongan, d.n_borongan,d.koli from t_order_packing_detail d 
        left join d_tipe_kirim t on d.id_tipe_kirim = t.id_tipe_kirim
        left join d_packing p on p.id_packing = d.id_jenis_packing
        where d.id_packing ='".$id."' ";
        
        $data = DB::select($sql);

        return $data;
    }
}
