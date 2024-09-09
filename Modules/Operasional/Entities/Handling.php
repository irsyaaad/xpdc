<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Handling extends Model
{
    protected $fillable = [];
    protected $table = "t_handling";
	protected $primaryKey = 'id_handling';

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function perusahaankirim()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush_kirim', 'id_perush');
    }

    public function sopir()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\Sopir', 'id_sopir', 'id_sopir');
    }

    public function armada()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\Armada', 'id_armada', 'id_armada');
    }

    public function layanan()
	{	
		return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
	}

    public function status_handling()
    {   
        return $this->belongsTo('Modules\Operasional\Entities\StatusDM', 'id_status', 'id_status');
    }

    public function asal()
    {   
        return $this->belongsTo('App\Models\Wilayah', 'region_dr', 'id_wil');
    }

    public function vendor()
    {   
        return $this->belongsTo('App\Models\Vendor', 'id_ven', 'id_ven');
    }

    public function tujuan()
    {   
        return $this->belongsTo('App\Models\Wilayah', 'region_tuju', 'id_wil');
    }

    public function user()
    {   
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function status()
    {   
        return $this->belongsTo('Modules\Operasional\Entities\StatusDM', 'id_status', 'id_status');
    }

    public static function getHandling($page, $perpage, $id_perush, $is_ven = 1, $id_ven = null, $id_handling = null, $id_perush_dr = null, $id_wil = null, $id_sopir = null, $id_armada = null, $dr_tgl = null, $sp_tgl = null, $id_status = null)
    {
        $sql = "select DISTINCT(h.id_handling), h.kode_handling, h.tgl_berangkat, h.tgl_selesai, wa.nama_wil as wil_asal, wt.nama_wil as wil_tujuan,
        s.nm_user as nm_sopir, a.nm_armada,h.created_at,h.id_status,h.ambil_gudang, d.nm_status,h.id_ven, v.nm_ven
        from t_handling h
        join m_wilayah wa on wa.id_wil = h.region_dr 
        join m_status_dm d on d.id_status = h.id_status 
        left join users s on s.id_user = h.id_sopir
        left join m_armada a on a.id_armada = h.id_armada
        left join m_vendor v on v.id_ven = h.id_ven
        left join m_wilayah wt on wt.id_wil = h.region_tuju
        left join t_handling_stt t on t.id_handling = h.id_handling
        where h.id_perush = '".$id_perush."' and h.is_kirim is null ";
        
        if($is_ven == 1){
            $sql.= " and  h.id_ven is null";
        }else{
            $sql.= " and  h.id_ven is not null";
        }
        
        if($id_ven != null){
            $sql .= " and h.id_ven = '".$id_ven."' ";
        }

        if($id_handling != null){
            $sql.= " and h.id_handling ='".$id_handling."' ";
        }

        if($id_perush_dr != null){
            $sql.= " and t.id_perush_asal ='".$id_perush_dr."' ";
        }

        if($dr_tgl != null){
            $sql.= " and h.tgl_berangkat >='".$dr_tgl."' ";
        }

        if($sp_tgl != null){
            $sql.= " and h.tgl_berangkat <='".$sp_tgl."' ";
        }
        
        if($id_wil != null){
            $sql.= " and h.region_tuju ='".$id_wil."' ";
        }

        if($id_sopir != null){
            $sql.= " and h.id_sopir ='".$id_sopir."' ";
        }

        if($id_armada != null){
            $sql.= " and h.id_armada ='".$id_armada."' ";
        }

        if($id_status != null){
            $sql.= " and h.id_status ='".$id_status."' ";
        }

        $sql.= " order by h.tgl_berangkat desc ";
        
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
    
    public static function getHandlingVendor()
    {
        $data = [];
        
        if(get_admin()){
            $data = self::with("perusahaan", "sopir", "armada", "status_handling", "asal", "tujuan", "vendor", "status")
            ->whereNotNull("id_ven");
        }else{
            $data = self::with("perusahaan", "sopir", "armada", "status_handling", "asal", "tujuan", "vendor", "status")
            ->where("id_perush", Session("perusahaan")["id_perush"])->whereNotNull("id_ven");
        }
        return $data;
    }

    public static function getHandlingKirim($page, $perpage, $id_perush, $id_ven = null, $id_handling = null, $id_perush_dr = null, $id_wil = null, $id_sopir = null, $id_armada = null, $dr_tgl = null, $sp_tgl = null, $id_status = null)
    {
        $sql = "select DISTINCT(h.id_handling), h.kode_handling, h.tgl_berangkat, h.tgl_selesai, wa.nama_wil as wil_asal, wt.nama_wil as wil_tujuan,
        s.nm_sopir, a.nm_armada,h.created_at,h.id_status,h.ambil_gudang, d.nm_status,h.id_ven, v.nm_ven
        from t_handling h
        join m_wilayah wa on wa.id_wil = h.region_dr 
        join m_status_dm d on d.id_status = h.id_status 
        left join m_sopir s on s.id_sopir = h.id_sopir
        left join m_armada a on a.id_armada = h.id_armada
        left join m_vendor v on v.id_ven = h.id_ven
        left join m_wilayah wt on wt.id_wil = h.region_tuju
        left join t_handling_stt t on t.id_handling = h.id_handling
        where h.id_perush = '".$id_perush."' and h.id_ven is not null and h.is_kirim = '1' ";
        
        if($id_ven != null){
            $sql .= " and h.id_ven = '".$id_ven."' ";
        }

        if($id_handling != null){
            $sql.= " and h.id_handling ='".$id_handling."' ";
        }

        if($id_perush_dr != null){
            $sql.= " and t.id_perush_asal ='".$id_perush_dr."' ";
        }

        if($dr_tgl != null){
            $sql.= " and h.tgl_berangkat >='".$dr_tgl."' ";
        }

        if($sp_tgl != null){
            $sql.= " and h.tgl_berangkat <='".$sp_tgl."' ";
        }
        
        if($id_wil != null){
            $sql.= " and h.region_tuju ='".$id_wil."' ";
        }

        if($id_sopir != null){
            $sql.= " and h.id_sopir ='".$id_sopir."' ";
        }

        if($id_armada != null){
            $sql.= " and h.id_armada ='".$id_armada."' ";
        }

        if($id_status != null){
            $sql.= " and h.id_status ='".$id_status."' ";
        }

        $sql.= " order by h.created_at desc ";

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
    
    public static function getDetail($id)
    {
        
    }

    public static function getById($id)
    {
       $data = self::with("perusahaan", "sopir", "armada", "status_handling", "asal", "tujuan", "user", "vendor")->findOrFail($id);

       return $data;
    }

    public static function getDmTiba($id_perush, $id_perush_tj, $id_layanan=null)
    {
        $sql = "select d.*,b.tgl as tgl_tiba from t_dm d join t_dm_tiba b on d.id_dm=b.id_dm 
        join t_order_dm r on b.id_dm=r.id_dm join t_order o on r.id_stt=o.id_stt 
        where d.id_perush_dr='".$id_perush."' and d.id_perush_tj='".$id_perush_tj."' 
        and d.id_status >= '5'
		and o.id_status < '7'";
        
        if(isset($id_layanan)){
            $sql = $sql." and d.id_layanan='".$id_layanan."' ";
        }

        $sql = $sql." group by d.id_dm, b.tgl";
        
        $data = DB::select($sql);
        
        return $data;
    }

    public static function getStt($id_perush)
    {
        $data = DB::table("t_dm_tiba")
        ->join('t_order_dm','t_order_dm.id_dm','=','t_dm_tiba.id_dm')
        ->join('t_order','t_order.id_stt','=','t_order_dm.id_stt')
        ->select('t_order.id_stt','t_order.kode_stt','t_order_dm.id_dm')
        ->where('t_dm_tiba.id_perush', $id_perush);
        
        return $data;
    }
    
    public static function getSttAmbilGudang($id_perush, $id_status,  $id_perush_kirim = null, $id_dm = null, $id_stt = null)
    {
        $sql = "select o.id_stt,o.kode_stt,o.pengirim_nm,o.pengirim_alm,o.pengirim_telp,o.penerima_nm,o.penerima_alm,o.penerima_telp,o.n_koli,o.n_berat,o.n_volume,
        o.tgl_masuk,t.tgl as tgl_tiba,d.tgl_berangkat, p.nm_perush as perush_asal, w.prov, w.kab, w.nama_wil
        from t_order o 
        join t_order_dm r on o.id_stt=r.id_stt
        join t_dm d on r.id_dm=d.id_dm
        join t_dm_tiba t on r.id_dm=t.id_dm
        join s_perusahaan p on d.id_perush_dr = p.id_perush
				join (
						SELECT r.id_wil,r.nama_wil
						, prov.nama_wil as prov
						, kab.nama_wil as kab
						, kec.nama_wil as kec
						FROM m_wilayah r
						left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
						left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
						left JOIN m_wilayah kec ON r.kec_id = kec.id_wil
				) as w on o.penerima_id_region = w.id_wil
        where t.id_perush='".$id_perush."' and o.id_status='".$id_status."' and o.id_stt not in ( select id_stt from t_handling_stt where id_perush = '".$id_perush."' and id_status='".$id_status."' ) ";
        
        if(!is_null($id_perush_kirim)){
            $sql.=" and d.id_perush_dr ='".$id_perush_kirim."' ";
        }

        if(!is_null($id_dm)){
            $sql.=" and d.id_dm ='".$id_dm."' ";
        }

        if(!is_null($id_stt)){
            $sql.=" and o.id_stt ='".$id_stt."' ";
        }
        
        $sql = $sql." order by o.tgl_masuk asc";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getSttKiriman($id_perush, $id_status, $id_dm =null, $id_stt = null, $kode_stt = null)
    {
        $sql = "select o.id_stt,o.kode_stt,o.pengirim_nm,o.pengirim_alm,o.pengirim_telp,o.penerima_nm,o.penerima_alm,o.penerima_telp,o.n_koli,o.n_berat,o.n_volume,
        o.tgl_masuk,d.tgl_berangkat, p.nm_perush as perush_asal,d.atd, d.ata as tgl_tiba,  w.prov, w.kab, w.nama_wil
        from t_order o
        join t_order_dm r on o.id_stt = r.id_stt
        join t_dm d on r.id_dm = d.id_dm
        join s_perusahaan p on d.id_perush_dr= p.id_perush
        left join (
        SELECT r.id_wil,r.nama_wil
        , prov.nama_wil as prov
        , kab.nama_wil as kab
        , kec.nama_wil as kec
        FROM m_wilayah r
        left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
        left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
        left JOIN m_wilayah kec ON r.kec_id = kec.id_wil
        ) as w on o.penerima_id_region = w.id_wil
        where o.id_perush_asal='".$id_perush."' and o.id_status='".$id_status."' and o.id_stt not in ( select id_stt from t_handling_stt where id_perush = '".$id_perush."' and id_status='".$id_status."' ) ";
            
        if(!is_null($id_dm)){
            $sql.=" and d.id_dm ='".$id_dm."' ";
        }

        if(!is_null($id_stt)){
            $sql.=" and o.id_stt ='".$id_stt."' ";
        }
        
        if(!is_null($kode_stt)){
            $sql.=" and o.kode_stt ='".$kode_stt."' ";
        }
        
        $sql = $sql." order by o.tgl_masuk asc ";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getSttTiba($id_perush, $id_status, $id_perush_kirim = null, $id_dm =null, $id_stt = null, $kode_stt = null)
    {
        $sql = "select o.id_stt,o.kode_stt,o.pengirim_nm,o.pengirim_alm,o.pengirim_telp,o.penerima_nm,o.penerima_alm,o.penerima_telp,o.n_koli,o.n_berat,o.n_volume,
        o.tgl_masuk,t.tgl as tgl_tiba,d.tgl_berangkat, p.nm_perush as perush_asal, w.prov, w.kab, w.nama_wil
        from t_order o 
        join t_order_dm r on o.id_stt=r.id_stt
        join t_dm d on r.id_dm=d.id_dm
        left join t_dm_tiba t on r.id_dm=t.id_dm
        join s_perusahaan p on d.id_perush_dr = p.id_perush
				join (
						SELECT r.id_wil,r.nama_wil
						, prov.nama_wil as prov
						, kab.nama_wil as kab
						, kec.nama_wil as kec
						FROM m_wilayah r
						left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
						left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
						left JOIN m_wilayah kec ON r.kec_id = kec.id_wil
				) as w on o.penerima_id_region = w.id_wil
        where d.id_perush_tj='".$id_perush."' and o.is_penerusan is null and o.id_status='".$id_status."' 
        and o.id_stt not in ( select id_stt from t_handling_stt where id_perush = '".$id_perush."' and id_status='".$id_status."' ) ";
        
        if(!is_null($id_perush_kirim)){
            $sql.=" and d.id_perush_dr ='".$id_perush_kirim."' ";
        }

        if(!is_null($id_dm)){
            $sql.=" and d.id_dm ='".$id_dm."' ";
        }

        if(!is_null($id_stt)){
            $sql.=" and o.id_stt ='".$id_stt."' ";
        }
        
        if(!is_null($kode_stt)){
            $sql.=" and o.kode_stt ='".$kode_stt."' ";
        }
        
        $sql = $sql." order by o.tgl_masuk asc ";
        
        $data = DB::select($sql);
        
        return $data;
    }

    public static function getKoliDMStt($id_stt)
    {
        $data = DB::table("t_dm_koli")
        ->join('t_order','t_order.id_stt','=','t_dm_koli.id_stt')
        ->select('t_order.id_stt','t_order.kode_stt','t_dm_koli.id_koli','t_order.tgl_masuk')
        ->where('t_dm_koli.id_stt',$id_stt);
        
        return $data;
    }

    public static function getBiayaHandling()
    {
        $data = self::select("t_handling.c_biaya", "t_handling.id_handling", "t_handling.n_bayar", "t_handling.is_approve","s.nm_sopir", "a.nm_armada","t_handling.is_confirm", "t_handling.status_inv","p.nm_perush","q.nm_perush as nm_perush_asal")
                    ->join("t_handling_stt", "t_handling_stt.id_handling","=", "t_handling.id_handling")
                    ->join("s_perusahaan as p", "p.id_perush","=", "t_handling.id_perush")
                    ->join("s_perusahaan as q", "t_handling_stt.id_perush_asal","=", "q.id_perush")
                    ->join("m_armada as a", "a.id_armada","=", "t_handling.id_armada")
                    ->join("m_sopir as s", "s.id_sopir","=", "t_handling.id_sopir")
                    ->groupBy("t_handling.id_handling", "t_handling_stt.id_dm","p.nm_perush", "q.nm_perush", "a.nm_armada", "s.nm_sopir")
                    ->orderBy("t_handling.created_at", "desc");
                    
        if(!get_admin()){
            $data = $data->where("t_handling.id_perush", Session("perusahaan")["id_perush"]);
        }
        
        return $data;
    }

    public static function getSttHandling($id_dm)
    {
        $sql = "select h.id_stt,h.id_dm from t_order o 
        left join t_handling_stt h on o.id_stt=h.id_stt
        left join t_dm d on h.id_dm= d.id_dm where h.id_dm='".$id_dm."' and d.id_status<='5' and o.id_status<='5'";
        
        $sql2 = "select t.*,d.ata, d.atd, o.n_koli,o.id_layanan,o.tgl_masuk,o.pengirim_nm,o.pengirim_alm,
        o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, a.nm_ord_stt_stat as nm_status, b.tgl tgl_tiba
        from t_order_dm t join t_dm d on t.id_dm= d.id_dm left join 
        t_dm_tiba b on t.id_dm=b.id_dm join
        t_order o on t.id_stt=o.id_stt join 
        m_ord_stt_stat a on o.id_status=a.id_ord_stt_stat join
        s_perusahaan p on o.id_perush_asal=p.id_perush join
        m_layanan l on o.id_layanan=l.id_layanan 
        where t.id_dm='".$id_dm."' and d.id_status<='5' and o.id_status<='5'  GROUP BY t.id_stt,o.n_koli,o.id_layanan,
        o.tgl_masuk, o.tgl_keluar,o.pengirim_nm,o.pengirim_alm,
        o.penerima_nm, o.penerima_alm, p.nm_perush, l.nm_layanan, 
        a.nm_ord_stt_stat, t.id_dm, d.ata, d.atd, b.tgl";
        
        $data = DB::select($sql);
        $data2 = DB::select($sql2);
        
        $a_data = [];
        
        if($data==null){

            $a_data = $data2;
        }elseif(count($data)==count($data2)){
            $a_data = [];
        }else{
            
            $a_data = [];
            foreach($data2 as $key => $value){
                foreach($data as $key2 => $value2){
                    if($value->id_stt!=$value2->id_stt){
                        $a_data[$key] = $value;
                    }
                }
            }
        }
        
        return $a_data;
    }
    
    public static function getFilter()
    {
        $data = self::with("perusahaan", "sopir", "armada", "status_handling", "asal", "tujuan")
        ->join('t_handling_stt','t_handling_stt.id_handling','=','t_handling.id_handling')
        ->join('t_order_dm','t_order_dm.id_stt','=','t_handling_stt.id_stt')
        ->select('t_handling.*','t_handling_stt.id_stt','t_order_dm.id_dm')
        ->orderBy('created_at', 'desc');

        return $data;
    }


}
