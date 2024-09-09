<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class PackingBarang extends Model
{
    protected $table = "t_order_packing";
    protected $primaryKey = 'id_packing';

    public static function getListPacking($page, $id_perush = null, $id_perush_asal = null)
    {
        $sql = "select p.id_stt, p.no_awb, p.id_packing, p.kode_stt, p.nm_pelanggan, p.nm_pengirim, p.n_total, p.n_bayar,n.nm_perush,p.is_lunas,p.keterangan
        from t_order_packing p 
        left join t_order o on o.id_stt = p.id_stt
        left join m_plgn g on p.id_pelanggan = g.id_pelanggan
        left join s_perusahaan n on n.id_perush = p.id_perush_kirim
        where p.id_stt is NOT NULL ";

        if(isset($id_perush) and $id_perush != null){
            $sql = $sql."  and p.id_perush = '".$id_perush."' ";
        }

        if(isset($id_perush_asal) and $id_perush_asal != null){
            $sql = $sql."  and p.id_perush_kirim = '".$id_perush_asal."' ";
        }

        $data = DB::select(DB::raw($sql));

        $data = new Paginator($data, $page);

        return $data;
    }
}
