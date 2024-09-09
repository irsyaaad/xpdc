<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class SettingHariLibur extends Model
{
    protected $fillable = [];
	protected $table = "s_hari_libur";
    protected $primaryKey = 'id_setting';

    public function perush()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}

    public function user()
	{
		return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
	}


    public static function getData($page, $id_perush, $dr_tgl = null, $sp_tgl = null)
    {
        $data = self::with("user", "perush")->where("id_perush", $id_perush);

        if($dr_tgl != null){
            $data = $data->where("dr_tgl", ">=", $dr_tgl);
        }

        if($sp_tgl != null){
            $data = $data->where("sp_tgl", "<=", $sp_tgl);
        }
        
        $data = $data->orderBy("dr_tgl", "desc")->paginate($page);

        return $data;
    }

    public static function getSum($id_perush, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "SELECT (sp_tgl::date - dr_tgl::date)+1
        as days, dr_tgl, sp_tgl from s_hari_libur 
        where id_perush ='".$id_perush."'";

        if($dr_tgl != null){
            $sql.= " and (dr_tgl >= '".$dr_tgl."' and dr_tgl <= '".$sp_tgl."') ";
        }

        if($sp_tgl != null){
            $sql.=" and (sp_tgl >= '".$dr_tgl."' and sp_tgl <= '".$sp_tgl."')";
        }

        $data = DB::select($sql);
        $total = 0;
        foreach($data as $key => $value){
            $total+= $value->days;
        }
       
        return $total;
    }
    
    public static function getSumCabang($id_perush = null, $dr_tgl = null, $sp_tgl = null)
    {
        $sql = "SELECT (sp_tgl::date - dr_tgl::date)+1
        as days, dr_tgl, sp_tgl,id_perush from s_hari_libur  where (dr_tgl >= '".$dr_tgl."' and dr_tgl <= '".$sp_tgl."') ";
        if($sp_tgl != null){
            $sql.=" and (sp_tgl >= '".$dr_tgl."' and sp_tgl <= '".$sp_tgl."')";
        }
        if($id_perush != null){
            $sql.=" and id_perush = '".$id_perush."'";
        }
        $sql .= " group by id_perush,dr_tgl,sp_tgl";
        
        $data = DB::select($sql);
        
        $total = [];
        foreach($data as $key => $value){
            $total[$value->id_perush] += $value->days;
        }
       
        return $total;
    }
    
    public static function getSunday($dr_tgl, $sp_tgl)
    {
        $start = new DateTime($dr_tgl);
        $end = new DateTime($sp_tgl);

        $days = $start->diff($end, true)->days;

        $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);

        return $sundays;
    }

    public static function getDateDiff($dr_tgl, $sp_tgl)
    {
        $datediff = strtotime($sp_tgl)-strtotime($dr_tgl);
        $total_hari = (Int)round($datediff / (60 * 60 * 24));
        $total_hari++;
        
        return $total_hari;
    }
}
