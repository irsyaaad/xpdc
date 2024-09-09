<?php

namespace Modules\Busdev\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class VendorBusdev extends Model
{
	protected $table = "m_vendor";
	protected $primaryKey = 'id_ven';

	public function group()
	{
		return $this->belongsTo('App\Models\GroupVendor', 'id_grup_ven', 'id_grup_ven');
	}

	public function wilayah()
	{
		return $this->belongsTo('App\Models\Wilayah', 'id_wil', 'id_wil');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}
	

	public function getdatafilter($page = null, $perpage = null, $id_ven = null, $id_grup_ven = null, $id_wil= null)
	{
		$sql = "select v.id_ven,v.nm_ven,v.telp_ven,v.id_grup_ven,v.id_wil
		from m_vendor v
		join 
		(
			select concat(kab.nama_wil,', ', prov.nama_wil) as nama_wil,kab.id_wil from m_wilayah kab
			left join m_wilayah prov  on prov.id_wil = kab.prov_id
		)
		as a on v.id_wil=a.id_wil::INTEGER";
		if($id_ven != null and $id_grup_ven == null){
			$sql.= " and v.id_ven = '".$id_ven."' ";
		}elseif($id_grup_ven != null and $id_ven == null){
			$sql.= " and v.id_grup_ven = '".$id_grup_ven."' ";
		}elseif($id_wil != null and $id_grup_ven ==null){
			$sql.= " and v.id_wil = '".$id_wil."' ";
		}elseif($id_wil != null and $id_ven == null){
			$sql.= " and v.id_wil = '".$id_wil."' ";
		}
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

}
