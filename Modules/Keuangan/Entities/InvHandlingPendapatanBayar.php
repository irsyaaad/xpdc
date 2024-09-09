<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class InvHandlingPendapatanBayar extends Model
{
    protected $fillable = [];
    protected $table = "keu_invoice_pendapatan_bayar";
    protected $primaryKey = 'id_bayar';
    
    public static function getDetailBayar($id_biaya)
    {
        $sql = "select b.id_inv_pend,b.created_at, g.nm_biaya_grup,b.n_bayar 
        from t_dm_biaya_bayar b 
        join m_biaya_grup g on g.id_biaya_grup = b.id_biaya_grup
        where b.id_inv_pend	= '".$id_biaya."'";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getBayar($id)
    {
        $sql = "select b.id_biaya_pend,b.created_at, g.nm_biaya_grup,b.nominal  as n_bayar
        from keu_invoice_pendapatan_bayar b 
        join m_biaya_grup g on g.id_biaya_grup = b.id_biaya_grup
        where b.id_biaya_pend= '".$id."'";
        
        $data = DB::select($sql);

        return $data;
    }
}
