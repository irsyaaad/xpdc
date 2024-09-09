<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sopir extends Model
{
    protected $fillable = ["nm_armada", "no_plat", "is_aktif", "id_user", "def_armada"];
    protected $table = "m_sopir";
    
	protected $primaryKey = 'id_sopir';
	
	public function armada()
	{
		return $this->belongsTo('Modules\Operasional\Entities\Armada', 'def_armada', 'id_armada');
	}
	
	public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}

	public static function getData($id_perush)
	{
		$data = self::where("id_perush", $id_perush)->get();

		return $data;
	}

	public static function getDriver($id_user)
	{
		$sql = "select DISTINCT(u.id_user), u.username, u.id_sopir, s.id_perush from users u join m_sopir s on u.id_sopir = s.id_sopir
		where u.id_user = '".$id_user."'";
		
		$data = DB::select($sql);

		return $data;
	}

	public static function getSopirInActive($id_perush = null)
	{
		$sql = "select s.id_sopir, nm_sopir , d.id_status
		from m_sopir s 
		left join t_dm d on s.id_sopir=d.id_sopir
		where s.id_perush='".$id_perush."'
		group by s.id_sopir, d.id_status";
		$data = DB::select($sql);

		$a_data = [];
		foreach($data  as $key => $value){
			if($value->id_status==null or $value->id_status > 5){
				$a_data[$key] = $value;
			}
		}	
		
		return $data;
	}
	
	public static function getFilter($id_perush, $id_sopir = null, $def_armada)
	{
		$data = DB::table('m_sopir')
		->join('s_perusahaan','m_sopir.id_perush','=','s_perusahaan.id_perush')
		->leftjoin('m_armada','m_sopir.def_armada','=','m_armada.id_armada')
		->select('m_sopir.id_sopir', 'm_sopir.is_user', 'm_sopir.nm_sopir','s_perusahaan.nm_perush','m_sopir.alamat',
			'm_armada.nm_armada','m_sopir.is_aktif','m_sopir.telp')
			->where("m_sopir.id_perush", $id_perush);
		
		if($id_sopir != null){
			$data = $data->where("m_sopir.id_sopir", $id_sopir);
		}
		
		if($def_armada != null){
			$data = $data->where("m_sopir.def_armada", $def_armada);
		}

		$data = $data->orderBy("m_sopir.nm_sopir", "asc");

		return $data;
	}
	
	public static function get_sopir($id_perush)
	{
		return DB::table('users')
		->join('role_user', 'role_user.id_user', '=', 'users.id_user')
		->where('role_user.id_role', 14)
		->where('role_user.id_perush', $id_perush)->get();
	}
}
