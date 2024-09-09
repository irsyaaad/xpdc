<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class Invoice extends Model
{
    protected $fillable = [];
    protected $table = "keu_invoice_pelanggan";
    protected $primaryKey = 'id_invoice';
    
    public static function getInvoice($page = null, $perpage = null, $id_perush, $id_invoice = null, $id_pelanggan = null, $dr_tgl = null, $sp_tgl = null, $id_stt = null, $status = null){
        $sql = "select i.inv_j_tempo,i.kode_invoice,i.id_invoice,i.created_at,i.id_plgn,i.tgl,p.nm_pelanggan,s.nm_status,
        sum(b.bayar) as bayar,sum(b.total) as total ,sum(COALESCE(b.total-b.bayar, 0)) as sisa,i.id_status,i.hp
        from keu_invoice_pelanggan i join m_plgn p on i.id_plgn = p.id_pelanggan
        join m_status_invoice s on i.id_status=s.id_status
        left join keu_draft_invoice d on i.id_invoice=d.id_invoice
        left join (
            select coalesce(sum(y.n_bayar), 0) as bayar,o.id_stt,coalesce(o.c_total , 0) as total FROM t_order o 
            left join t_order_pay y on o.id_stt=y.id_stt  where o.id_perush_asal='".$id_perush."' GROUP BY o.id_stt
        ) as b on d.id_stt =b.id_stt
        where i.id_perush='".$id_perush."' ";

        if($id_pelanggan!= null){
            $sql .= " and i.id_plgn='".$id_pelanggan."' ";
        }

        if($id_stt!= null){
            $sql .= " and d.id_stt='".$id_stt."' ";
        }
        
        if($dr_tgl!= null){
            $sql .= " and i.tgl>='".$dr_tgl."' ";
        }

        if($sp_tgl!= null){
            $sql .= " and i.tgl<='".$sp_tgl."' ";
        }

        if($id_invoice!=null){
            $sql .= " and i.id_invoice='".$id_invoice."' ";
        }

        if($status!=null){
            if($status==1){
                $sql .= " and d.id_stt in (
                    select CASE WHEN coalesce(o.c_total , 0)-coalesce(sum(y.n_bayar), 0)>0 THEN o.id_stt ELSE NULL END
                    FROM t_order o 
                    left join t_order_pay y on o.id_stt=y.id_stt  where o.id_perush_asal='".$id_perush."'
                    GROUP BY o.id_stt ) ";
            }elseif($status==2){
                $sql .= " and d.id_stt in (
                    select CASE WHEN coalesce(o.c_total , 0)-coalesce(sum(y.n_bayar), 0)=0 THEN o.id_stt ELSE NULL END
                    FROM t_order o 
                    left join t_order_pay y on o.id_stt=y.id_stt  where o.id_perush_asal='".$id_perush."'
                    GROUP BY o.id_stt ) ";

            }elseif($status == 3){
                $sql .= " and d.id_stt in (
                    select CASE WHEN coalesce(o.c_total , 0)-coalesce(sum(y.n_bayar), 0)>0 THEN o.id_stt ELSE NULL END
                    FROM t_order o 
                    left join t_order_pay y on o.id_stt=y.id_stt  where o.id_perush_asal='".$id_perush."'
                    GROUP BY o.id_stt ) ";
                
                $sql .= " and i.inv_j_tempo < '".date("Y-m-d")."' ";
            }
        }
        
        $sql .=" group by i.id_invoice,p.nm_pelanggan,s.nm_status,i.inv_j_tempo ORDER BY i.tgl DESC";
        // dd($sql);
        $data = DB::select($sql);
		$collect = collect($data);

		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		);

		return $data;
    }

    public function status()
	{
		return $this->belongsTo('Modules\Keuangan\Entities\StatusInvoice', 'id_status', 'id_status');
    }
    
    public function pelanggan()
	{
		return $this->belongsTo('App\Models\Pelanggan', 'id_plgn', 'id_pelanggan');
    }
    
    public function stt(){
        return $this->belongsTo('Modules\Operasional\Entities\SttModel','id_stt','id_stt');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public static function getAktif()
    {
        $data = DB::table('keu_invoice_pelanggan')
                ->join('m_status_invoice','m_status_invoice.id_status','=','keu_invoice_pelanggan.id_status')
                ->select('keu_invoice_pelanggan.id_invoice','keu_invoice_pelanggan.id_perush','m_status_invoice.nm_status')
                ->where('keu_invoice_pelanggan.is_read', false)
                ->orWhereNull('keu_invoice_pelanggan.is_read')
                ->get();

        return $data;
    }

    public static function getSumBayar($id){
        return DB::select("select sum(p.n_bayar) as bayar,sum(o.c_total) as total from keu_draft_invoice i 
        left join t_order_pay p on i.id_stt=p.id_stt
        left join t_order o on o.id_stt=i.id_stt
        where i.id_invoice='".$id."'");
    }

    public static function getSttTotal($id, $id_perush){
        $data = DB::select("select i.id_stt,o.c_total,b.bayar,o.c_ac4_piut,o.id_plgn,o.pengirim_nm from keu_draft_invoice i 
        join t_order o on o.id_stt=i.id_stt
        left join (
            select sum(n_bayar) as bayar,id_stt from t_order_pay where id_perush='".$id_perush."' GROUP BY id_stt
        ) as b on b.id_stt=o.id_stt
        where i.id_invoice='".$id."' ");
        
        $a_data = [];
        foreach($data as $key => $value){
            if($value->c_total>$value->bayar){
                $a_data[$key] = $value;
            }
        }

        return $a_data;
    }

    public static function getSttPiutang($id, $id_perush){
        return DB::select("select i.id_stt,o.c_total,b.bayar,o.x_n_bayar,o.x_n_piut from keu_draft_invoice i 
        join t_order o on o.id_stt=i.id_stt
        left join (
            select sum(n_bayar) as bayar,id_stt from t_order_pay where id_perush='".$id_perush."' GROUP BY id_stt
        ) as b on b.id_stt=o.id_stt
        where i.id_invoice='".$id."' ");
    }
}
