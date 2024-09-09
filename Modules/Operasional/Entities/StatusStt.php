<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusStt extends Model
{
    protected $fillable = [];
    protected $table = "m_ord_stt_stat";
    public $incrementing = false;
    protected $primaryKey = 'id_ord_stt_stat';
    public $keyType = 'string';
    
    public static function getList()
    {
        $status = self::select("id_ord_stt_stat", "nm_ord_stt_stat")
        ->wherenull("id_status")->where("id_ord_stt_stat",">", "1")->orderBy("id_ord_stt_stat", "asc")->get();
        $stt = [];
        foreach ($status as $key => $value) {
            $stt[$value->id_ord_stt_stat] = $value;
        }
        
        return $stt;
    }
    
    public function dm()
    {	
        return $this->belongsTo('Modules\Operasional\Entities\StatusDM', 'id_status', 'id_status');
    } 

    public static function getStatusKosong($dooring = null){
        $data = self::whereNull("id_status")->where("id_ord_stt_stat",">","1")
        ->orderBy("id_ord_stt_stat", "asc")->orderBy("nm_ord_stt_stat", "asc");

        if (isset($dooring) && $dooring == true) {
            $data = $data->orWhere('id_status', 7);
        }

        return $data->get();
    }

    public static function getMapping(){
        $self = self::select("id_status", "id_ord_stt_stat", "nm_ord_stt_stat")->get();
        $data  = [];
        foreach($self as $key => $value){
            $data[strtoupper($value->nm_ord_stt_stat)] = $value;
        }

        return $data;
    }
}
