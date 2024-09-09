<?php

namespace Modules\Keuangan\Entities;
use DB;

use Illuminate\Database\Eloquent\Model;
use Modules\Operasional\Entities\CaraBayar;
use Illuminate\Pagination\LengthAwarePaginator;

class Pembayaran extends Model
{
    protected $fillable = [];
    protected $table = "t_order_pay";
    protected $primaryKey = 'id_order_pay';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }
    
    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
    public function cara()
    {
        return $this->belongsTo('Modules\Operasional\Entities\CaraBayar', 'id_cr_byr', 'id_cr_byr_o');
    }
    
    public function stt()
    {
        return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
    }
    
    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'id_plgn', 'id_pelanggan');
    }
    
    public function bank()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\BankPerush', 'id_bank', 'id_bank_perush');
    }
    
    public static function BayarTujuan($id_perush)
    {
        $data = DB::table('t_dm')
        ->join('t_order_dm','t_dm.id_dm','=','t_order_dm.id_dm')
        ->join('t_order','t_order.id_stt','=','t_order_dm.id_stt')
        ->join('m_cr_bayar_order as m','t_order.id_cr_byr_o','=','m.id_cr_byr_o')
        ->join('s_perusahaan','t_dm.id_perush_dr','=','s_perusahaan.id_perush')
        ->select('t_dm.id_dm','t_dm.id_perush_tj','t_order_dm.id_stt','t_order.id_cr_byr_o','t_order.c_total','t_order.id_perush_asal','s_perusahaan.nm_perush')
        ->where('m.kode_cr_byr_o','BYTJ')
        ->where('t_order.is_bayar','=',false)
        ->where('t_dm.id_perush_tj',$id_perush);
        
        return $data;
    }
    
    public static function getSttBelumBayarNoPage($id_perush){
        $sql = "select o.id_stt,o.kode_stt,p.id_pelanggan,wa.nama_wil as asal,wt.nama_wil as tujuan,p.nm_pelanggan,o.c_total,b.n_bayar,o.tgl_masuk,
        o.pengirim_nm,o.penerima_nm,o.is_lunas,(o.c_total-b.n_bayar) as sisa
        from t_order o 
        join m_wilayah wa on wa.id_wil = o.pengirim_id_region
        join m_wilayah wt on wt.id_wil = o.penerima_id_region
        join m_plgn p on o.id_plgn = p.id_pelanggan
        left join (
            select sum(n_bayar) as n_bayar,id_stt from t_order_pay GROUP BY id_stt 
        ) as b on o.id_stt = b.id_stt
        where o.is_lunas is not true and o.id_perush_asal = '".$id_perush."' GROUP BY o.id_stt,wa.nama_wil,wt.nama_wil,p.id_pelanggan,b.n_bayar 
        order by o.tgl_masuk desc ";

        $data = DB::select($sql);
        return $data;
    }
    public static function getSttBelumBayar($page, $perpage, $id_perush = null,  $id_layanan = null, $id_stt = null, $id_pelanggan = null, $dr_tgl = null, $sp_tgl = null){
        $sql = "select o.id_stt,o.kode_stt,p.id_pelanggan,wa.nama_wil as asal,wt.nama_wil as tujuan,p.nm_pelanggan,o.c_total,b.n_bayar,o.tgl_masuk,
        o.pengirim_nm,o.penerima_nm,o.is_lunas,(o.c_total-b.n_bayar) as sisa
        from t_order o 
        join m_wilayah wa on wa.id_wil = o.pengirim_id_region
        join m_wilayah wt on wt.id_wil = o.penerima_id_region
        join m_plgn p on o.id_plgn = p.id_pelanggan
        left join (
            select sum(n_bayar) as n_bayar,id_stt from t_order_pay GROUP BY id_stt 
        ) as b on o.id_stt = b.id_stt
        where o.is_lunas is not true ";
        
        if($id_perush != null){
            $sql .= " and o.id_perush_asal = '".$id_perush."' ";
        }
        
        if($id_layanan != null){
            $sql .= " and o.id_layanan = '".$id_layanan."' ";
        }
        
        if($id_stt != null){
            $sql .= " and o.id_stt = '".$id_stt."' ";
        }
        
        if($id_pelanggan != null){
            $sql .= " and o.id_plgn = '".$id_pelanggan."' ";
        }
        
        if($dr_tgl != null){
            $sql .= " and o.tgl_masuk >= '".$dr_tgl."' ";
        }
        
        if($sp_tgl != null){
            $sql .= " and o.tgl_masuk <= '".$sp_tgl."' ";
        }

        $sql .= " GROUP BY o.id_stt,wa.nama_wil,wt.nama_wil,p.id_pelanggan,b.n_bayar order by o.tgl_masuk desc ;";
        
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
    
    public static function getBayarTujuan($id_perush)
    {
        $kode = CaraBayar::where(strtolower("kode_cr_byr_o"), "bytj")->get()->first();
        $data = DB::table('t_order_pay')
        ->join('t_order','t_order.id_stt','=','t_order_pay.id_stt')
        ->join('t_order_dm','t_order_dm.id_stt','=','t_order.id_stt')
        ->join('t_dm','t_dm.id_dm','=','t_order_dm.id_dm')
        ->join('s_perusahaan','s_perusahaan.id_perush','=','t_dm.id_perush_dr')
        ->select('t_order_pay.id_order_pay','t_order_pay.id_stt','t_order_pay.no_kwitansi','t_order_pay.nm_bayar','t_order_pay.n_bayar','t_order_pay.tgl','t_order_pay.is_konfirmasi',
        't_order.id_cr_byr_o','t_order.id_perush_asal',
        't_order_dm.id_dm','t_order_dm.id_stt', 't_dm.id_perush_dr','t_dm.id_perush_tj',
        's_perusahaan.nm_perush')
        ->where('t_order.id_cr_byr_o','=', $kode->id_cr_byr_o)
        ->where('t_order_pay.is_konfirmasi','!=',NULL)
        ->where(function($q)use ($id_perush) {
            $q->where('t_dm.id_perush_tj', $id_perush)
            ->orWhere('t_order.id_perush_asal', $id_perush);
        });
        
        return $data;
    }
}
