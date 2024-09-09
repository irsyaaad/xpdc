<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class TipeKirim extends Model
{
    protected $fillable = [];
    protected $table = "d_tipe_kirim";
	protected $primaryKey = 'id_tipe_kirim';
	
    public static function getList()
    {
        $data = DB::table("d_tipe_kirim")->select("id_tipe_kirim", "nm_tipe_kirim")->get();
        
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_tipe_kirim] = $value->nm_tipe_kirim;
        }
        
        return $a_data;
    }

}
