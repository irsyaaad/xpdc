<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\Models\Pelanggan;

class SettingLimitPiutang extends Model
{
    protected $fillable = [];
    protected $table = "s_limit_piutang_plgn";
	protected $primaryKey = 'id_setting';

    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 

    public static function ceklimit($id_pelanggan)
    {
        $sql = "select coalesce(b.bayar,0) as bayar,coalesce(o.total, 0) as total from m_plgn p 
        left join (
            select sum(n_bayar) as bayar,id_plgn from t_order_pay where id_plgn='".$id_pelanggan."' group by id_plgn
        ) as b on p.id_pelanggan=b.id_plgn
        left join (
            select sum(c_total) as total,id_plgn from t_order where id_plgn='".$id_pelanggan."' group by id_plgn
        ) as o on o.id_plgn=p.id_pelanggan where p.id_pelanggan='".$id_pelanggan."'; ";
        
        $limit = Pelanggan::where("id_pelanggan", $id_pelanggan)->get()->first();
        $piutang = collect(\DB::select($sql))->first();
        
        $data = [];
        $total = isset($piutang->total)?$piutang->total:0;
        $bayar = isset($piutang->bayar)?$piutang->bayar:0;
        
        $sisa = $total-$bayar;

        $data["piutang"] = $total-$bayar;
        $data["sisa"] = $limit->n_limit_piutang-$sisa;
        $data["limit"] = $limit->n_limit_piutang;
        
        return $data;
    }
    
    public static function ceklimitUpdate($id_pelanggan, $stt)
    {
        $sql = "select coalesce(b.bayar,0) as bayar,coalesce(o.total, 0) as total from m_plgn p 
        left join (
            select sum(n_bayar) as bayar,id_plgn from t_order_pay where id_plgn='".$id_pelanggan."' group by id_plgn
        ) as b on p.id_pelanggan=b.id_plgn
        left join (
            select sum(c_total) as total,id_plgn from t_order where id_plgn='".$id_pelanggan."' group by id_plgn
        ) as o on o.id_plgn=p.id_pelanggan where p.id_pelanggan='".$id_pelanggan."'; ";
        
        $limit = Pelanggan::where("id_pelanggan", $id_pelanggan)->get()->first();
        $piutang = collect(\DB::select($sql))->first();
        
        $data = [];
        $total = isset($piutang->total)?$piutang->total:0;
        $bayar = isset($piutang->bayar)?$piutang->bayar:0;
        
        $sisa = $total-$stt-$bayar;

        $data["piutang"] = $total-$bayar;
        $data["sisa"] = $limit->n_limit_piutang-$sisa;
        $data["limit"] = $limit->n_limit_piutang;
        
        return $data;
    }
    
}

