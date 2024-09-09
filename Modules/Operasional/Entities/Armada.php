<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Armada extends Model
{
    protected $fillable = ["nm_armada", "no_plat", "is_aktif", "id_user"];
    protected $table = "m_armada";
	protected $primaryKey = 'id_armada';
	
	public function perusahaan()
 	{
 		return $this->belongsTo('App\Perusahaan', 'id_perush', 'id_perush');
	}
	 
	public function pemilik()
 	{
 	   	return $this->belongsTo('App\PerushArmada', 'id_perush_armd', 'id_perush_armd');
	 }
	 
	 public function group()
 	{
 	   	return $this->belongsTo('Modules\Operasional\Entities\ArmadaGroup', 'id_armd_grup', 'id_armd_grup');
	 }
	 
	 public static function getData($id_perush)
	{
		$data = self::where("id_perush", $id_perush)->get();

		return $data;
	}
	
	 public static function getlist($page=10)
	 {
		 $data = [];
		if(get_admin()){
            $data= Armada::with("perusahaan", "group")->paginate($page);
        }else{
            $data = Armada::with("perusahaan")->where("id_perush", Session("perusahaan")["id_perush"])->paginate($page);
		}
		
		return $data;
	 }

	 public static function getFilter($id_perush, $id_armada = null, $id_perush_armd = null, $id_armd_grup =null)
	 {
		$armada = DB::table('m_armada')
		->join('s_perusahaan','s_perusahaan.id_perush','=','m_armada.id_perush')
		->leftjoin('m_armada_grup','m_armada.id_armd_grup','=','m_armada_grup.id_armd_grup')
		->leftjoin('m_perush_armada','m_perush_armada.id_perush_armd','=','m_armada.id_perush_armd')
        ->select('m_armada.id_armada', 'm_armada.nm_armada', 'm_armada.no_plat', 
		'm_armada_grup.nm_armd_grup','m_armada.is_aktif','s_perusahaan.nm_perush', 'm_perush_armada.nm_pemilik')
		->where("m_armada.id_perush", $id_perush);

		if($id_armada != null){
			$armada = $armada->where("m_armada.id_armada", $id_armada);
		}
		
		if($id_perush_armd != null){
			$armada = $armada->where("m_perush_armada.id_perush_armd", $id_perush_armd);
		}

		if($id_armd_grup != null){
			$armada = $armada->where("m_armada_grup.id_armd_grup", $id_armd_grup);
		}

		$armada = $armada->orderBy('m_armada.nm_armada', 'asc');
		
		return $armada;
	 }
}
