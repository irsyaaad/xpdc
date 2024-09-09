<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Packing extends Model
{
    protected $fillable = [];
    protected $table = "d_packing";
    protected $primaryKey = 'id_packing';
    public $keyType = 'string';

    public static function getList()
    {
        $data =  self::select("id_packing", "nm_packing")->orderBy("nm_packing", "asc")->get();
        
        return $data;
    }

    public static function getArray()
    {
        $data = DB::table("d_packing")->select("id_packing", "nm_packing")->get();
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_packing] = $value->nm_packing;
        }
        
        return $a_data;
    }
}
