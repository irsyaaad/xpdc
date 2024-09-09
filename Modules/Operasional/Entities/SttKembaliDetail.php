<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SttKembaliDetail extends Model
{
    protected $table = "t_stt_kembali_detail";
    public $incrementing = false;
	protected $primaryKey = 'id_stt_kembali_detail';
	public $keyType = 'integer';
    
    public function stt()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\SttModel', 'id_stt', 'id_stt');
    }
    
    public static function generateId($id)
    {
        $data = self::where("id_kembali", $id)->orderBy("last_id", "desc")->get()->first();
        
        $a_data = [];
        if($data==null){
            $a_data["id_detail"] = strtolower("dt".$id."001");
            $a_data["last_id"] = "1";

        }else{
            $id = (Int)$data->last_id+1;
            $a_data["id_detail"] = strtolower("dt".$id."00".$id);
            $a_data["last_id"] = $id;
        }

        return $a_data;
    }

    public static function getStt($id)
    {
        $sql = "select t.id_stt,k.id_detail,t.tgl_masuk,t.n_koli, d.nm_tipe_kirim, l.nm_layanan, t.pengirim_nm
        from t_order t 
        join m_layanan l on t.id_layanan=l.id_layanan
        join d_tipe_kirim d on t.id_tipe_kirim=d.id_tipe_kirim
        join t_detail_kembali k on t.id_stt=k.id_stt
        where k.id_kembali='".$id."'";
        
        $data = DB::select($sql);

        return $data;
    }
}
