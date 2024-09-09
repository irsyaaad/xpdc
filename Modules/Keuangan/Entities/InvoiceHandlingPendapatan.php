<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;

class InvoiceHandlingPendapatan extends Model
{
    protected $fillable = [];
    protected $table = "keu_invoice_handling_pendapatan";
    protected $primaryKey = 'id_biaya_pend';

    public static function getBiaya($page, $id_invoice)
    {
        $sql = "select p.id_dm,p.id_stt,p.is_default, p.id_biaya,p.id_handling,p.kode_handling, p.id_biaya_grup,p.kode_stt, p.kode_dm, m.nm_biaya_grup, m.klp, p.nominal, p.dibayar, p.ac4_debit as pendapatan, 
         p.ac4_kredit as piutang, p.id_biaya_pend, p.is_lunas from keu_invoice_handling_pendapatan p 
        left join t_handling_biaya b on p.id_biaya = b.id_biaya
        join m_biaya_grup m on p.id_biaya_grup = m.id_biaya_grup
        where p.id_invoice='".$id_invoice."' ";

        $data = DB::select(DB::raw($sql));

        $data = new Paginator($data, $page);

        return $data;
    }
    
    public static function getBiayaBayar($id)
    {
        $sql = "select p.id_dm,p.id_stt,p.is_default, p.id_biaya_grup,p.kode_stt,p.id_handling,p.kode_handling, p.kode_dm, m.nm_biaya_grup, m.klp, p.nominal, b.dibayar, p.ac4_debit as pendapatan,  p.ac4_kredit as piutang, p.id_biaya_pend, p.is_lunas
        from keu_invoice_handling_pendapatan p 
        left join (
            select sum(nominal) as dibayar, id_biaya_pend from keu_invoice_pendapatan_bayar GROUP BY id_biaya_pend
        ) as b on b.id_biaya_pend = p.id_biaya_pend
        join m_biaya_grup m on p.id_biaya_grup = m.id_biaya_grup
        where p.id_invoice='".$id."'";
        
        return DB::select($sql);
    }
}
