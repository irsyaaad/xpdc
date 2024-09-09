<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class ACPerush extends Model
{
    protected $fillable = [];
	protected $table = "m_ac_perush";
    protected $primaryKey = 'id';
    
    public function perusahaan()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    } 
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function ac3()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'parent', 'id_ac');
    }
    
    public static function getACDebit($id_perush = null)
    {
        if(isset($id_perush)){
            $sql = "select id_ac, nama from m_ac_perush where id_perush='".$id_perush."' and (is_bank=true or is_kas=true)";
        }else{
            $sql = "select id_ac, nama from m_ac_perush where id_perush='".Session("perusahaan")["id_perush"]."' and (is_bank=true or is_kas=true)";
        }
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getKasBank($id_perush)
    {
        $sql = "select id_ac, nama as nm_ac from m_ac_perush where (is_bank = true or is_kas = true)  and id_perush = '".$id_perush."'";
        $data = DB::select($sql);
        return $data;
    }

    public static function getBank($id_perush)
    {
        $sql = "select id_ac, nama as nm_ac from m_ac_perush where (is_bank = true or is_kas = false)  and id_perush = '".$id_perush."'";
        $data = DB::select($sql);
        return $data;
    }

    public static function getList($id_perush)
    {
        $data = self::select("id_ac", "nama")->where("id_perush", $id_perush)->get();
        $a_data = [];

        foreach($data as $key => $value){
            $a_data[$value->id_ac] = $value;
        }

        return $a_data;
    }

    public  static function getPiutang($params, $id = null)
    {
        $sql = "select id_ac, nama from m_ac_perush where  lower(nama) like lower('%".strtolower($params)."%') ";

        if($id){
            $sql = $sql." and id_perush ='".$id."' ";
        }

        $sql = $sql." group by id_ac, nama";
        
        $data = DB::select($sql);

        return $data;
    }
    public static function getChild($id = null)
    {
        $data = [];
        if(isset($id)){
            $data = self::select("id_ac", "nama")->where("parent", $id)
            ->where("id_perush", Session("perusahaan")["id_perush"])->get();
        }else{
            $data = self::select("id_ac", "nama")
            ->where("id_perush", Session("perusahaan")["id_perush"])->get();
        }
        

        return $data;
    }
}
