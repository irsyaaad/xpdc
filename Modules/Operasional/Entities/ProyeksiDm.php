<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProyeksiDm extends Model
{
    protected $fillable = [];
    protected $table = "t_dm_biaya";
	protected $primaryKey = 'id_pro_bi';
	
	public function dm()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\DaftarMuat', 'id_dm', 'id_dm');
	}
	
	public function group()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
	}

	public function vendor()
	{	
		return $this->belongsTo('App\Models\Vendor', 'id_ven', 'id_ven');
	}
	
	public function proyeksi()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\DetailProyeksi', 'id_pro_det', 'id_detail');
	}

	public function stt()
	{	
		return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
	}
	
	public function user()
	{	
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}

	public static function getProyeksi($id, $id_jenis = null)
	{
		$sql = "select b.id_jenis,b.id_pro_bi,b.keterangan,b.id_dm, b.tgl_posting, b.kode_dm, b.id_handling, b.is_lunas,b.kode_handling, b.id_stt, b.kode_stt, g.klp, 
		g.nm_biaya_grup, b.id_biaya_grup,b.nominal, b.n_bayar,b.id_jenis from t_dm_biaya b 
		join m_biaya_grup g on g.id_biaya_grup = b.id_biaya_grup
		where b.id_dm = '".$id."' ";

		if($id_jenis != null){
			$sql .= " and b.id_jenis = '".$id_jenis."' ";
		}

		$sql .= " order by b.tgl_posting,b.id_pro_bi asc";
		
		return DB::select($sql);
	}

	public static function getRepProyeksi($id)
	{
		$sql = "select b.id_pro_bi,b.id_jenis,b.keterangan,b.id_dm,b.tgl_posting, 
		b.kode_dm, b.id_handling, b.is_lunas,b.kode_handling, b.ac4_debit,b.ac4_kredit,
		b.kode_stt,b.id_biaya_grup,b.nominal,b.n_bayar,d.nama as debit,k.nama as kredit,
		u.nm_user,b.updated_at
		from t_dm_biaya b 
		join m_ac_perush d on b.ac4_debit=d.id_ac
		join m_ac_perush k on b.ac4_kredit=k.id_ac
		join users u on u.id_user=b.id_user
		where b.id_dm = '".$id."' GROUP BY b.id_pro_bi,d.nama,k.nama,u.nm_user order by b.tgl_posting,b.id_pro_bi asc;";
		
		return DB::select($sql);
	}

	public static function getGroupProyeksi($id_perush, $dr_tgl, $sp_tgl, $id_jenis = null)
	{
		$sql = "select m.id_dm,sum(b.nominal) as biaya from t_dm m 
		left join t_dm_biaya b on m.id_dm=b.id_dm
		where m.id_perush_dr='".$id_perush."' 
		and m.tgl_berangkat>='".$dr_tgl."' 
		and m.tgl_berangkat<='".$sp_tgl."' and b.id_jenis='".$id_jenis."' GROUP BY m.id_dm";
		
		$data = DB::select($sql);
		$a_data = [];

		foreach($data as $key => $value){
			$a_data[$value->id_dm] = $value;
		}
		
		return $a_data;
	}
}
