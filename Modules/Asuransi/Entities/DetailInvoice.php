<?php

namespace Modules\Asuransi\Entities;

use DB;
use Illuminate\Database\Eloquent\Model;

class DetailInvoice extends Model
{
    protected $table = "keu_invoice_asuransi_detail";
    protected $primaryKey = 'id_detail';
    protected $fillable = [];

    public function invoice()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\Invoice', 'id_invoice', 'id_invoice');
    }

    public function asuransi()
    {
        return $this->belongsTo('Modules\Asuransi\Entities\Asuransi', 'id_asuransi', 'id_asuransi');
    }

    public static function getAsuransi($id)
    {
        $sql = "
        SELECT
            t_id_asuransi,
            t_nominal_jual,
            COALESCE ( bayar.bayar, 0 ) AS bayar,
            ( t_nominal_jual - COALESCE ( bayar.bayar, 0 ) ) AS sisa
        FROM
            keu_invoice_asuransi_detail
            JOIN t_asuransi ON t_id_asuransi = keu_invoice_asuransi_detail.id_asuransi
            LEFT JOIN ( SELECT id_asuransi, SUM ( n_bayar ) AS bayar FROM t_asuransi_pay GROUP BY id_asuransi ) AS bayar ON bayar.id_asuransi = t_id_asuransi
        WHERE
            t_status = 'Belum Lunas'
            AND keu_invoice_asuransi_detail.id_invoice = " . $id . ";
            ";
        $data = DB::select(DB::raw($sql));
        return $data;
    }
}
