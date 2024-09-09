<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceHandling extends Model
{
    protected $fillable = [];
    protected $table = "keu_invoice_handling";
    protected $primaryKey = 'id_invoice';

    public function perush_tj()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tj', 'id_perush');
    }

    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\StatusInvoice', 'id_status', 'id_status');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function penerima()
    {
        return $this->belongsTo('App\Models\User', 'id_penerima', 'id_user');
    }

    public static function getDetail($id)
    {
        $sql = "select i.id_invoice, i.kode_invoice, r.nm_user as admin, s.nm_status, i.id_status, i.tgl_invoice, i.tgl_tagihan, i.tgl_jatuh_tempo, i.total, i.is_confirm, i.is_approve,u.nm_user,
        p.nm_perush, p.alamat, p.telp,
        i.dibayar
        from keu_invoice_handling i
        join s_perusahaan p on i.id_perush_tj=p.id_perush
        join users r on r.id_user = i.id_user
        left join users u on u.id_user = i.id_penerima
        left join m_status_invoice s on s.id_status=i.id_status
        where i.id_invoice = '".$id."'";

        $data = collect(\DB::select($sql))->first();

        return $data;
    }

    public static function getDetailTerima($id)
    {
        $sql = "select i.id_invoice, i.kode_invoice, i.id_perush, i.id_perush_tj, r.nm_user as admin, s.nm_status, i.id_status, i.tgl_invoice, i.tgl_tagihan, i.tgl_jatuh_tempo, i.total, i.is_confirm, i.is_approve,u.nm_user, p.nm_perush,i.dibayar
        from keu_invoice_handling i
        join s_perusahaan p on i.id_perush=p.id_perush
        join users r on r.id_user = i.id_user
        left join users u on u.id_user = i.id_penerima
        left join m_status_invoice s on s.id_status=i.id_status
        where i.id_invoice = '".$id."'";

        $data = collect(\DB::select($sql))->first();

        return $data;
    }

    public static function getDataInvoiceHandling($id_perush)
    {
        $sql = "
        SELECT
            i.id_invoice,
            i.kode_invoice,
            i.id_status,
            r.nm_user AS ADMIN,
            i.is_lunas,
            i.tgl_invoice,
            s.nm_status,
            i.tgl_tagihan,
            i.tgl_jatuh_tempo,
            i.total AS c_total,
            i.is_confirm,
            i.is_approve,
            u.nm_user,
            P.nm_perush,
            i.dibayar
        FROM
            keu_invoice_handling i
            JOIN s_perusahaan P ON i.id_perush_tj = P.id_perush
            JOIN users r ON r.id_user = i.id_user
            LEFT JOIN users u ON u.id_user = i.id_penerima
            LEFT JOIN m_status_invoice s ON s.id_status = i.id_status
        WHERE
            i.id_perush ='".$id_perush."'
        ";

        $data = DB::select(DB::raw($sql));

        return $data;
    }

    public static function getDataInvoiceHandlingtj($id_perush)
    {
        $sql = "
        SELECT
            i.id_invoice,
            i.kode_invoice,
            i.id_status,
            r.nm_user AS ADMIN,
            i.is_lunas,
            i.tgl_invoice,
            s.nm_status,
            i.tgl_tagihan,
            i.tgl_jatuh_tempo,
            i.total AS c_total,
            i.is_confirm,
            i.is_approve,
            u.nm_user,
            P.nm_perush,
            i.dibayar
        FROM
            keu_invoice_handling i
            JOIN s_perusahaan P ON i.id_perush_tj = P.id_perush
            JOIN users r ON r.id_user = i.id_user
            LEFT JOIN users u ON u.id_user = i.id_penerima
            LEFT JOIN m_status_invoice s ON s.id_status = i.id_status
        WHERE
            i.id_perush_tj ='".$id_perush."'
        ";

        $data = DB::select(DB::raw($sql));

        return $data;
    }

    public static function getListData($perpage, $page, $id_perush = null, $id_perush_tj = null, $id_invoice = null ,$dr_tgl = null, $sp_tgl = null)
    {
        $sql = "
        SELECT
            i.id_invoice,
            i.kode_invoice,
            i.id_status,
            r.nm_user AS ADMIN,
            i.is_lunas,
            i.tgl_invoice,
            s.nm_status,
            i.tgl_tagihan,
            i.tgl_jatuh_tempo,
            i.total AS c_total,
            i.is_confirm,
            i.is_approve,
            u.nm_user,
            P.nm_perush,
            i.dibayar
        FROM
            keu_invoice_handling i
            JOIN s_perusahaan P ON i.id_perush_tj = P.id_perush
            JOIN users r ON r.id_user = i.id_user
            LEFT JOIN users u ON u.id_user = i.id_penerima
            LEFT JOIN m_status_invoice s ON s.id_status = i.id_status
        WHERE
            i.id_perush ='".$id_perush."'
        ";

        if($id_perush_tj != null){
            $sql .= " and i.id_perush_tj = '".$id_perush_tj."' ";
        }
        if($id_invoice != null){
            $sql .= " and i.id_invoice = '".$id_invoice."' ";
        }
        if($dr_tgl != null){
            $sql .= " and i.tgl_invoice >= '".$dr_tgl."' ";
        }
        if($sp_tgl != null){
            $sql .= " and i.tgl_invoice <= '".$sp_tgl."' ";
        }
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }

    public static function getListTerima($perpage, $page, $id_perush = null, $id_perush_tj = null, $id_invoice = null ,$dr_tgl = null, $sp_tgl = null)
    {
        $sql = "
        SELECT
            i.id_invoice,
            i.kode_invoice,
            i.id_status,
            r.nm_user AS ADMIN,
            i.is_lunas,
            i.tgl_invoice,
            s.nm_status,
            i.tgl_tagihan,
            i.tgl_jatuh_tempo,
            i.total AS c_total,
            i.is_confirm,
            i.is_approve,
            u.nm_user,
            P.nm_perush,
            i.dibayar
        FROM
            keu_invoice_handling i
            JOIN s_perusahaan P ON i.id_perush_tj = P.id_perush
            JOIN users r ON r.id_user = i.id_user
            LEFT JOIN users u ON u.id_user = i.id_penerima
            LEFT JOIN m_status_invoice s ON s.id_status = i.id_status
        WHERE
            i.id_perush_tj ='".$id_perush."'
        ";

        if($id_perush_tj != null){
            $sql .= " and i.id_perush = '".$id_perush_tj."' ";
        }
        if($id_invoice != null){
            $sql .= " and i.id_invoice = '".$id_invoice."' ";
        }
        if($dr_tgl != null){
            $sql .= " and i.tgl_invoice >= '".$dr_tgl."' ";
        }
        if($sp_tgl != null){
            $sql .= " and i.tgl_invoice <= '".$sp_tgl."' ";
        }

        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );

        return $data;
    }
}
