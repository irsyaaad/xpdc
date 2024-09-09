<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Kapal extends Model
{
    //protected $fillable = ["nm_kapal", "alamat", "telp", "id_user"];

    protected $table = "m_kapal";
	protected $primaryKey = 'id_kapal';
	
	public function kapalperush()
 	{	
 	   	return $this->belongsTo('Modules\Operasional\Entities\KapalPerush', 'id_kapal_perush', 'id_kapal_perush');
	 }
	 
	public static function getFilter($page, $id_kapal_perush = null, $id_kapal = null)
	{
		$data = DB::table('m_kapal')
        ->join('m_kapal_perush','m_kapal.id_kapal_perush','=','m_kapal_perush.id_kapal_perush')
        ->select('m_kapal.id_kapal', 'm_kapal.nm_kapal', 'm_kapal_perush.nm_kapal_perush', 
		'm_kapal.dr_rute','m_kapal.ke_rute','m_kapal.def_tarif','m_kapal.is_aktif');
		
		if($id_kapal_perush	 != null){
			$data = $data->where("m_kapal.id_kapal_perush", $id_kapal_perush);
		}
		
		if($id_kapal != null){
			$data = $data->where("m_kapal.id_kapal", $id_kapal);
		}
		$data = $data->orderBy("m_kapal.nm_kapal", "asc");

		return $data;
	}
}
