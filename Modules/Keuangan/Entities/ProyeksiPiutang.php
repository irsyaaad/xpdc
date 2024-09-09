<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class ProyeksiPiutang extends Model
{
    protected $fillable = [];
    protected $table = "keu_proyeksi_piutang";
    protected $primaryKey = 'id';

    public static function getBulan(){
        $data = array("01"=>"01","02"=>"02", "03"=>"03", "04"=>"04", "05"=>"05", "06"=>"06", "07"=>"07", "08"=>"08", "09"=>"09", "10"=>"10", "11"=>"11", "12"=>"12");
        return $data;
    }

    public static function getTahun(){
        $date = date("Y");
        $data = [];

        for($i=0; $i<=10; $i++){
            $data[$i] = $date-$i;
        }

        return $data;
    }

    public function user()
	{
		return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
	}

    public function perush()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}

    public static function getListStt($bulan, $tahun, $id_perush){
        $sql = "select o.kode_stt,o.id_stt,o.c_total,p.nm_pelanggan,o.tgl_masuk,y.n_bayar,COALESCE(o.c_total-y.n_bayar, 0) as piutang,o.no_awb,o.created_at,o.updated_at,m.nm_marketing,o.info_kirim
        from t_order o 
        join m_plgn p on p.id_pelanggan=o.id_plgn
        left join m_marketing m on o.id_marketing=m.id_marketing 
        left join (
            select sum(n_bayar) as n_bayar,id_stt from t_order_pay GROUP BY id_stt
        ) as y on o.id_stt=y.id_stt
        where  o.id_stt not in (
            select id_stt from keu_proyeksi_piutang_detail where bulan='".$bulan."' and tahun='".$tahun."' and id_perush='".$id_perush."'
        ) and o.id_perush_asal='".$id_perush."'";

        $data = DB::select($sql);

        return $data;
    }

    public static function getSttByAdmin($bulan, $tahun, $id_perush, $id_user, $tgl_awal, $tgl_akhir){
        $sql = "select l.id,l.id_proyeksi,o.kode_stt,o.id_stt,o.c_total,p.nm_pelanggan,o.tgl_masuk,y.n_bayar,COALESCE(l.piutang, 0) as piutang,o.no_awb,o.created_at,o.updated_at,m.nm_marketing,o.info_kirim
        from keu_proyeksi_piutang g 
        join keu_proyeksi_piutang_detail l on g.id=l.id_proyeksi
        join t_order o on o.id_stt = l.id_stt
        join m_plgn p on p.id_pelanggan=o.id_plgn
        left join m_marketing m on o.id_marketing=m.id_marketing 
        left join (
                select sum(n_bayar) as n_bayar,id_stt from t_order_pay where id_perush='".$id_perush."' and tgl>='".$tgl_awal."'  and tgl <='".$tgl_akhir."' GROUP BY id_stt
        ) as y on l.id_stt=y.id_stt where l.tahun='".$tahun."' and l.bulan='".$bulan."' and l.id_perush='".$id_perush."' and g.id_user='".$id_user."'";
        
        $data = DB::select($sql);
        
        return $data;
    }

    public static function getProyeksi($id_perush, $tahun){
        $sql = "select p.*,u.nm_karyawan as users,COALESCE(sum(d.piutang), 0) as piutang from keu_proyeksi_piutang p 
        join (
            select u.id_user,k.nm_karyawan from users u 
            join m_karyawan k on u.id_karyawan=k.id_karyawan 
        ) as u on u.id_user = p.id_user
        left join keu_proyeksi_piutang_detail d on p.id = d.id_proyeksi 
        where p.id_perush='".$id_perush."' and p.tahun='".$tahun."'
        group by p.id,u.nm_karyawan";
            
        $data = DB::select($sql);

        return $data;
    }

    public static function getDataProyeksi($id){
        $sql = "select l.id,l.id_proyeksi,o.kode_stt,o.id_stt,o.c_total,p.nm_pelanggan,o.tgl_masuk,y.n_bayar,COALESCE(l.piutang, 0) as piutang,o.no_awb,o.created_at,o.updated_at,m.nm_marketing,o.info_kirim
        from keu_proyeksi_piutang_detail l
        join t_order o on o.id_stt = l.id_stt
        join m_plgn p on p.id_pelanggan=o.id_plgn
        left join m_marketing m on o.id_marketing=m.id_marketing 
        left join (
            select sum(n_bayar) as n_bayar,id_stt from t_order_pay GROUP BY id_stt
        ) as y on l.id_stt=y.id_stt where l.id_proyeksi='".$id."'";
            
        $data = DB::select($sql);

        return $data;
    }

    public static function getAdminPiutang($tahun, $bulan, $id_perush){
        $sql = "select p.id_user,u.nm_karyawan from  keu_proyeksi_piutang p 
        join (
            select u.id_user,k.nm_karyawan from users u 
            join m_karyawan k on u.id_karyawan=k.id_karyawan 
        ) as u on u.id_user = p.id_user where p.tahun='".$tahun."' and p.bulan='".$bulan."' and p.id_perush='".$id_perush."';";

        return DB::select($sql);
    }

    public static function getRepProyeksi($tahun, $bulan, $id_perush){
        $sql = "select count(d.id_stt) as stt,sum(o.c_total) as omzet,sum(d.piutang) as proyeksi,u.id_user 
        from keu_proyeksi_piutang_detail d 
        join keu_proyeksi_piutang u on u.id=d.id_proyeksi
        join t_order o on o.id_stt=d.id_stt where d.tahun='".$tahun."' and 
        d.bulan='".$bulan."' and d.id_perush='".$id_perush."' GROUP BY u.id_user ";
        
        $data = DB::select($sql);
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_user] = $value;
        }

        return $a_data;
    }

    public static function getPaymentWeek($dr_tgl, $sp_tgl, $id_perush){
        $sql = "select COALESCE(SUM(p.n_bayar), 0) as bayar,u.id_user from keu_proyeksi_piutang_detail d  
        join keu_proyeksi_piutang u on u.id=d.id_proyeksi
        left join t_order_pay p on p.id_stt = d.id_stt where p.id_perush ='".$id_perush."' 
        and p.tgl>='".$dr_tgl."' and p.tgl<='".$sp_tgl."' GROUP BY u.id_user ";

        $data = DB::select($sql);
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_user] = $value;
        }

        return $a_data;
    }

    public static function getCountStt($tahun, $bulan, $id_perush, $dr_tgl, $sp_tgl){
        $sql = "select o.stt,d.id_user 
        from keu_proyeksi_piutang_detail d 
        join (
						select count(id_stt) as stt,id_stt from t_order_pay 
						where id_perush='".$id_perush."' and tgl>='".$dr_tgl."' and tgl<='".$sp_tgl."' GROUP BY id_stt
					) as o on o.id_stt=d.id_stt where d.tahun='".$tahun."' and 
        d.bulan='".$bulan."' and d.id_perush='".$id_perush."' GROUP BY d.id_user,o.stt";
        
        $data = DB::select($sql);
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_user] = $value;
        }

        return $a_data;
    }

    public static function getInvoice($id_perush){
        $sql = "select i.kode_invoice,i.tgl,i.inv_j_tempo,f.id_stt from keu_draft_invoice f
        left join keu_invoice_pelanggan i on i.id_invoice=f.id_invoice where i.id_perush='".$id_perush."' 
        GROUP BY i.kode_invoice,i.tgl,i.inv_j_tempo,f.id_stt";

        $data =  DB::select($sql);
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_stt] = $value;
        }

        return $a_data;
    }
}
