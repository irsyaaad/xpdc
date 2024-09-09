<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use App\Models\Role;
use App\Models\Menu;

class RoleMenu extends Model
{
    protected $table = "role_menu";
    protected $primaryKey = 'id_rm';
    
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu', 'id_menu', 'id_menu');
    }
    
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'id_role', 'id_role');
    }
    
    public static function ChekRole($id_module,$id_role)
    {
        $sql = "SELECT n.id_rm,n.id_role,n.id_menu,n.c_read,n.c_insert,n.c_update,n.c_delete,n.c_other,m.nm_menu,d.id_module,d.nm_module,r.nm_role
        from role_menu n join
        menu m on m.id_menu=n.id_menu join
        module d on d.id_module=m.id_module join
        role r on n.id_role=r.id_role
        where d.id_module='".$id_module."' and r.id_role='".$id_role."'";
        
        $data = DB::select($sql);
        
        return $data;
    }

    public static function getAccess($uri, $id_role)
    {   
        $uri = str_replace("cetak", "report", request()->segment(1));
        $menu = Menu::where("route", $uri)->firstorfail();
        $data = self::where("id_menu", $menu->id_menu)->where("id_role", $id_role)->first();
        if($data == null){
            $data = self::where("id_module", $menu->id_module)->where("id_role", $id_role)->get()->first();
        }
        
        return $data;
    }
    
    public static function getFilter($page = null, $id_role = null, $id_module = null)
    {
        $data = DB::table('role_menu')
        ->join('menu','menu.id_menu','=','role_menu.id_menu')
        ->join('module','module.id_module','=','menu.id_module')
        ->join('role','role.id_role','=','role_menu.id_role')
        ->select('role_menu.id_rm','role_menu.id_role','role_menu.id_menu',
        'role_menu.c_read','role_menu.c_insert','role_menu.c_update',
        'role_menu.c_delete','role_menu.c_other','role_menu.c_read',
        'menu.nm_menu','module.id_module','module.nm_module',
        'role.nm_role');
        
        if($id_role != null){
            $data->where("role_menu.id_role", $id_role);
        }

        if($id_module != null){
            $data->where("role_menu.id_module", $id_module);
        }
        $data->orderBy("menu.nm_menu", "asc");

        return $data->paginate($page);
    }
}
