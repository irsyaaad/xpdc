<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Auth;

class Module extends Model
{
    protected $table = "module";
    protected $primaryKey = 'id_module';

    public function menu()
    {
    	return $this->hasMany('App\Models\Menu', 'id_module', 'id_module');
    }

    public static function getSessionModul()
    {   
        $sql = "select m.id_module,m.nm_module,m.nm_module,m.icon,m.created_at from module m
        join menu u on m.id_module=u.id_module
        join role_menu r on r.id_menu=u.id_menu
        join role_user s on r.id_role=s.id_role
        where s.id_user='".Auth::user()->id_user."' and r.id_role='".Session("role")["id_role"]."'
        group by m.id_module,m.nm_module,m.nm_module,m.icon,m.created_at order by m.created_at";
        
        $data = DB::select($sql);
        
    	return $data;
    }
}
