<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class MasterAC extends Model
{
    protected $table = "m_ac";
    protected $primaryKey = 'id_ac';
	public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = [];
    
    public function parents()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'id_parent', 'id_ac');
    } 
    
    public static function getLevel4($parent, $level)
    {
        $sql = "select l4.id_ac, l4.id_parent,l4.nama, l4.level from m_ac l4  join (
            select id_ac,id_parent from m_ac where level ='".$level."' and id_parent='".$parent."') as l2 on l4.id_parent=l2.id_ac";
        
        $data = DB::select($sql);
        
        return $data;
    }

    public static function getChild($id)
    {    
        $data = self::where("id_parent", $id)->get();
        
        return $data;
    }
}
