<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CompositeKey;
use DB;

class SttDm extends Model
{
    protected $fillable = ["id_stt", "id_dm"];
    protected $table = "t_order_dm";
    public $incrementing = false;
	protected $primaryKey = ["id_stt", "id_dm"];
    public $keyType = 'string';
    
    public function stt()
    {
        return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
    }

    public function dm()
    {
        return $this->belongsTo('Modules\Operasional\Entities\DaftarMuat', 'id_dm', 'id_dm');
    }

    public static function getPerushTj($id_stt)
    {
        $sql = "select p.kode_perush,p.kode_ref from t_order o
        left join t_order_dm t on o.id_stt = t.id_stt
        left join t_dm m on m.id_dm = t.id_dm
        join s_perusahaan p on m.id_perush_tj = p.id_perush
        where o.id_stt = '".$id_stt."' ";

        $data = DB::select($sql);
        $a_data = [];
        if(isset($data[0])){
            $a_data = $data[0];
        }
        
        return $a_data;
        
    }

    public static function getTotalPendapatan($id)
    {
        $sql = "select (count(b.id_stt) * a.n_tarif_koli) as  total
        from t_order as a 
        join t_dm_koli as b on a.id_stt = b.id_stt 
        where id_dm = '".$id."' group by a.id_stt ";

        $data = DB::select($sql);
        $total = 0;
        foreach($data as $key => $value){
            $total += $value->total;
        }
        
        return $total;
    }

    public static function getDmStt($id_dm)
    {
        $sql = "select t.*,d.ata, d.atd, o.n_koli,o.id_layanan,o.tgl_masuk,o.pengirim_nm,o.pengirim_alm,
        o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, a.nm_ord_stt_stat as nm_status, b.tgl tgl_tiba
        from t_order_dm t join t_dm d on t.id_dm= d.id_dm left join 
        t_dm_tiba b on t.id_dm=b.id_dm join
        t_order o on t.id_stt=o.id_stt join 
        m_ord_stt_stat a on o.id_status=a.id_ord_stt_stat join
        s_perusahaan p on o.id_perush_asal=p.id_perush join
        m_layanan l on o.id_layanan=l.id_layanan 
        where t.id_dm='".$id_dm."' GROUP BY t.id_stt,o.n_koli,o.id_layanan,o.tgl_masuk, o.tgl_keluar,o.pengirim_nm,o.pengirim_alm,
        o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, a.nm_ord_stt_stat, t.id_dm, d.ata, d.atd, b.tgl";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getStt($id_dm)
    {
        $sql = "select s.id_stt, s.kode_stt,s.pengirim_nm from t_order_dm d
        join t_order s on s.id_stt = d.id_stt where d.id_dm= '".$id_dm."'
        GROUP BY s.id_stt,s.kode_stt ";
        
        $data = DB::select($sql);

        return $data;
    }
    
    public function ceksql()
    {
        $sql = "select t.*,d.ata, d.atd, o.n_koli,o.id_layanan,o.tgl_masuk,o.pengirim_nm,o.pengirim_alm,
                o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, h.nm_status, b.tgl as tgl_tiba
                from t_order_dm t join
                        (
                   SELECT DISTINCT ON (id_stt) *
                   FROM t_history_stt
                   ORDER BY id_stt, created_at DESC
                ) as h on h.id_stt = t.id_stt join
                t_dm d on t.id_dm= d.id_dm left join 
                t_dm_tiba b on t.id_dm=b.id_dm join
                t_order o on t.id_stt=o.id_stt join
                s_perusahaan p on o.id_perush_asal=p.id_perush join
                m_layanan l on o.id_layanan=l.id_layanan 
                where t.id_dm='50' GROUP BY t.id_stt,o.n_koli,o.id_layanan,o.tgl_masuk, o.tgl_keluar,o.pengirim_nm,o.pengirim_alm,
                o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, t.id_dm, d.ata, d.atd, b.tgl,h.nm_status;";
    }

    public static function hitungOmset($id)
    {
        $total = DB::table('t_order_dm')
        ->select('t_order_dm.id_dm', DB::raw('SUM(t_order.c_total) AS total'))
        ->join('t_order','t_order.id_stt','=','t_order_dm.id_stt')
        ->where('t_order_dm.id_dm','=',$id)
        ->groupBy('t_order_dm.id_dm')
        ->get();

        return $total;
    }
}
