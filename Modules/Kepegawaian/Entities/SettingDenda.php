<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingDenda extends Model
{
    protected $fillable = [];
	protected $table = "s_denda";
    protected $primaryKey = 'id_setting';

    public function jenis()
	{
		return $this->belongsTo('Modules\Kepegawaian\Entities\JenisPerijinan', 'id_jenis', 'id_jenis');
	}

    public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}
	
	public static function getLate()
	{
		$data = self::where("id_perush", Session("perusahaan")["id_perush"])->where("id_jenis", "tk")->get()->first();
		
		return $data;
	}

	public static function getDatang($id_perush)
	{
		$sql = "select * from s_denda where id_perush='".$id_perush."' and (id_jenis='2' or id_jenis='3') ";
		$data = DB::select($sql);

		$a_datang = [];
        foreach($data as $key => $value){
            $a_datang[$value->id_jenis] = $value;
        }

		return $a_datang;
	}

	public static function getPulang($id_perush)
	{
		$sql = "select * from s_denda where id_perush='".$id_perush."' and (id_jenis='4' or id_jenis='5') ";
		$data = DB::select($sql);

		$a_pulang = [];
        foreach($data as $key => $value){
            $a_pulang[$value->id_jenis] = $value;
        }

		return $a_pulang;
	}

	public static function getAlpha($id_perush)
	{
		$data = self::where("id_perush", $id_perush)->where("id_jenis", "A")->get()->first();
		
		return $data;
	}
	
	public static function getJoinMapping($id_perush = null)
	{
		$sql = "select j.id_jenis,j.nm_jenis from m_jenis_perijinan j 
		join s_denda d on j.id_jenis = d.id_jenis where d.id_perush='".$id_perush."'";
		
		$data = DB::select($sql);
		
		return $data;
	}

	public static function getSettingDenda($id_perush = null)
	{
		$sql = "select id_jenis, frekuensi, nominal, id_perush from s_denda where id_perush ='".$id_perush."'";
		$data = DB::select($sql);

		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_jenis] = $value;
		}
		
		return $a_data;
	}
}
