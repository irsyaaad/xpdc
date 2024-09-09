<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class DraftSttInvoice extends Model
{
    protected $fillable = [];
    protected $table = "keu_draft_invoice";
    protected $primaryKey = 'id_draft';
    public $incrementing = true;
    
    public static function getDetail($id){
        $sql = "select o.*,d.id_draft,asal.label as wil_asal,tujuan.label as wil_tujuan,b.bayar
        from keu_draft_invoice d
        join t_order o on o.id_stt = d.id_stt
        join (
                SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label, r.id_wil
                FROM m_wilayah r
                left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
        ) as asal on asal.id_wil = o.pengirim_id_region
        join (
            SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label,r.id_wil FROM m_wilayah r
                left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
        ) as tujuan on tujuan.id_wil = o.penerima_id_region
        left join (
            select sum(n_bayar) as bayar,id_stt from t_order_pay GROUP BY id_stt
        ) as b on b.id_stt=o.id_stt
        where d.id_invoice ='".$id."' ";
           
        $data = DB::select($sql);
        
        return $data;
    }
    public static function getDataSTT($id)
    {
        $data = DB::table('keu_draft_invoice as a')
        ->leftjoin('t_order as b','a.id_stt','=','b.id_stt')
        ->leftjoin('t_order_pay as c','a.id_stt','=','c.id_stt')
        ->leftjoin('m_wilayah as d','b.pengirim_id_region','=','d.id_wil')
        ->leftjoin('m_wilayah as e','b.penerima_id_region','=','e.id_wil')
        ->select('a.id_draft','id_invoice','b.*','c.n_bayar as bayar','d.nama_wil as asal','e.nama_wil as tujuan');
        
        if($id != null){
            $data->where("a.id_invoice", $id);
        }
        
        return $data;
    }

    public static function getSttInvoice($id)
    {
        $data = DB::select("select o.id_stt,o.kode_stt,o.no_awb,o.n_koli,o.n_tarif_brt,o.n_berat,o.n_volume,o.n_kubik,o.c_total, 
        o.n_diskon,o.n_asuransi,o.n_ppn,o.penerima_nm,o.info_kirim,o.tgl_masuk,wa.nama_wil as asal,wt.nama_wil as tujuan, tf.min_brt,
        COALESCE(sum(b.n_bayar), 0) as bayar from keu_draft_invoice i 
        join t_order o on o.id_stt = i.id_stt
        left join m_tarif tf ON tf.id_tarif = o.id_tarif
        join m_wilayah wa on wa.id_wil = o.pengirim_id_region
        join m_wilayah wt on wt.id_wil = o.penerima_id_region
        left join (
            select sum(n_bayar) as n_bayar,id_stt from t_order_pay GROUP BY id_stt 
        ) as b on o.id_stt = b.id_stt
        where i.id_invoice = '".$id."'
        GROUP BY o.id_stt,wa.nama_wil,wt.nama_wil ");
        
        return $data;
    }
}
        
        
        