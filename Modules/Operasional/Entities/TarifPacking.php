<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class TarifPacking extends Model
{
    protected $table = "m_tarif_packing";
    protected $primaryKey = 'id_tarif';
	// protected $fillable = [];
    // public $keyType = 'string';

    public function tipe()
    {
        return $this->belongsTo('Modules\Operasional\Entities\TipeKirim', 'id_tipe_barang', 'id_tipe_kirim');
    }

    public function packing()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Packing', 'id_jenis', 'id_packing');
    }

    public static function getTarifPacking($id_jenis, $volume = null)
    {
        $sql = "select id_tarif, volume, tarif from m_tarif_packing where id_jenis ='".$id_jenis."' ";

        if(isset($volume) and $volume!= null){
            $sql = $sql." and volume ='".$volume."' ";
        }

        $data = DB::select($sql);
        
        $a_data = [];
        if(count($data) > 0){
            $a_data["id_tarif"] = $data[0]->id_tarif;
            $a_data["volume"] = $data[0]->volume;
            $a_data["tarif"] = $data[0]->tarif;
        }else{
            $a_data["id_tarif"] = "";
            $a_data["volume"] = "0";
            $a_data["tarif"] = "0";
        }

        return $a_data;
    }
}
