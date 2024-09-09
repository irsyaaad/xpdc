<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class HandlingStt extends Model
{
    protected $fillable = [];
    protected $table = "t_handling_stt";
    public $incrementing = false;
	protected $primaryKey = 'id_detail';
    public $keyType = 'string';
    
    public function pelanggan()
	{
		return $this->belongsTo('App\Models\Pelanggan', 'id_plgn', 'id_pelanggan');
	}
	
	public function perush_asal()
	{	
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush_asal', 'id_perush');
	} 
	
	public function perush_tujuan()
	{	
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush_tujuan', 'id_perush');
	}
	
	public function layanan()
	{	
		return $this->belongsTo('App\Models\Layanan', 'id_layanan', 'id_layanan');
	}
	
	public function tipekirim()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\TipeKirim', 'id_tipe_kirim', 'id_tipe_kirim');
	}
	
	public function asal()
	{	
		return $this->belongsTo('App\Models\Wilayah', 'pengirim_id_region', 'id_wil');
	}
	
	public function tujuan()
	{	
		return $this->belongsTo('App\Models\Wilayah', 'penerima_id_region', 'id_wil');
	}
	
	public function tarif()
	{	
		return $this->belongsTo('App\Models\Tarif', 'id_tarif', 'id_tarif');
	}
	
	public function marketing()
	{	
		return $this->belongsTo('App\Models\Marketing', 'id_marketing', 'id_marketing');
	}
	
	public function packing()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\Packing', 'id_packing', 'id_packing');
	}
	
	// public function cara()
	// {
	// 	return $this->belongsTo('Modules\Operasional\Entities\CaraBayar', 'id_cr_byr_o', 'id_cr_byr_o');
	// }
	
	public function koli()
	{
		return $this->hasMany('Modules\Operasional\Entities\OpOrderKoli', 'id_stt', 'id_stt');
	}
	
	public function koli2()
	{
		return $this->hasMany('Modules\Operasional\Entities\OpOrderKoli', 'id_stt', 'id_stt');
	}
	
	public function sttdm()
	{
		return $this->hasMany('Modules\Operasional\Entities\SttDm', 'id_stt', 'id_stt');
	}
	
	public function status()
	{
		return $this->belongsTo('Modules\Operasional\Entities\StatusStt', 'id_status', 'id_ord_stt_stat');
	}

	public static function getStt($id)
	{
		$sql = "SELECT DISTINCT(s.kode_stt),s.kode_dm,s.id_stt, s.id_dm,s.penerima_nm,s.penerima_telp,s.penerima_alm,s.tgl_masuk,s.cara_hitung,
		s.penerima_alm,s.penerima_perush,s.penerima_id_region,s.n_koli,s.n_berat,s.n_volume,s.id_detail,s.id_status,
		s.n_kubik,s.n_total,P.nm_perush AS perush_asal , w.prov, w.kab, w.nama_wil, s.n_hrg_handling_brt,s.n_hrg_handling_vol,s.n_hrg_handling_kubik, s.n_borongan 
		FROM t_handling_stt s 
		JOIN t_order o on s.id_stt = o.id_stt
		JOIN t_handling T ON s.id_handling = s.id_handling 
		JOIN s_perusahaan P ON P.id_perush = s.id_perush_asal 
		JOIN ( SELECT r.id_wil,r.nama_wil
		, prov.nama_wil as prov
		, kab.nama_wil as kab
		, kec.nama_wil as kec
		FROM m_wilayah r
		left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
		left JOIN m_wilayah kab ON r.kab_id = kab.id_wil
		left JOIN m_wilayah kec ON r.kec_id = kec.id_wil
		) as w on s.penerima_id_region = w.id_wil WHERE s.id_handling = '".$id."' order by s.kode_stt desc;";
// dd($sql);
		$data = DB::select($sql);
		
		return $data;
	}

	public static function getTotal($id_dm)
	{
		$sql = "select sum(n_total) as total, id_handling, kode_handling from t_handling_stt 
		where id_dm = '".$id_dm."' GROUP BY id_handling, kode_handling";

		$sum = DB::select($sql);

		return $sum;
	}
}