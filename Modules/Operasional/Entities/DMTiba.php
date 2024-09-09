<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;
use App\Perusahaan;
use DB;

class DMTiba extends Model
{
    protected $fillable = [];
    protected $table = "t_dm_tiba";
    public $incrementing = false;
	protected $primaryKey = 'id_dm_tiba';
	public $keyType = 'string';
    
    public function dm()
 	{	
 		return $this->belongsTo('Modules\Operasional\Entities\DaftarMuat', 'id_dm', 'id_dm');
 	}

 	public function perusahaan()
 	{	
 	   	return $this->belongsTo('App\Perusahaan', 'id_perush', 'id_perush');
 	} 

 	public static function generateId()
    {   
    	$tahun = date("Y");
    	$bulan = date("m");
        //dd("aa");
        $dm = self::where("id_perush", Session("perusahaan")["id_perush"])
            ->whereYear('tgl', '=', $tahun)
        	->whereMonth("tgl", '=', $bulan)
            ->orderBy("created_at", "desc")->first();
        
        $id = "";
        if ($dm==null) {
            $id = "DM"."TB".strtoupper(Session("perusahaan")["id_perush"]).date("ym")."01";
            $last = "1";
            
        }else{
            
            $last = (Int)$dm->last_id+1;
            $id = "DM"."TB".strtoupper(Session("perusahaan")["id_perush"]).date("ym").$last;

        }
        $data = [];
        $data["last_id"] = $last;
        $data["id_dm_tiba"] = $id;

        return $data;
    }

    public static function getSttReady()
    {
        $dm = DB::table("t_dm_tiba as t")
                    ->get();
        
        return $dm;
    }
    
    public static function getTibaInvoice($id_perush)
    {
        $sql = "select t.id_dm, t.tgl, t.kode_dm from t_dm_tiba t
        where t.id_perush_asal= '".$id_perush."' 
        and t.id_dm not in ( select id_dm from keu_invoice_handling where id_perush_tj = '".$id_perush."' )";
        
        $data = DB::select($sql);

        return $data;
    }

}
