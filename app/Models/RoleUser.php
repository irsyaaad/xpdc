<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

class RoleUser extends Model
{
    protected $table = "role_user";
    protected $primaryKey = 'id_ru';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }
    
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'id_role', 'id_role');
    }
    
    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
    public static function ChekPerush($id)
    {
        $sql ="SELECT o.id_perush,o.nm_perush, o.cabang
        from role_user r 
        join s_perusahaan o on r.id_perush=o.id_perush ";
        if(Session("role")["id_role"]!="1"){
            $sql .= " where r.id_user='".$id."' and r.id_role = '".Session("role")["id_role"]."' ";

        }
        $sql .= " GROUP BY o.id_perush ";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function ChekRole($id)
    {   
        $sql ="SELECT r.id_role,o.nm_role from role_user r 
        join role o on r.id_role=o.id_role
        where r.id_user='".$id."' GROUP BY o.id_role,r.id_role";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getUserRole($id = null)
    {   
        $sql ="select DISTINCT(u.id_user),u.nm_user from role_user r
        join users u on u.id_user=r.id_user  ";
        
        if($id != null){
            $sql .= " where r.id_perush = '".$id."'";
        }
        
        $sql .= " group by u.id_user";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getFilter($page = null, $perpage = null, $id_role = null, $id_perush = null, $id_user = null)
    {
        $sql = "select e.id_ru,u.username,u.nm_user,r.nm_role,p.nm_perush,k.nm_karyawan from role_user e 
        join users u on e.id_user = u.id_user
        join role r on r.id_role = e.id_role
        join s_perusahaan p on e.id_perush = p.id_perush
        join m_karyawan k on u.id_karyawan = k.id_karyawan 
        where e.id_role is not null ";
        
        if($id_role != null){
            $sql .= " and e.id_role = '".$id_role."' "; 
        }
        if($id_perush != null){
            $sql .= " and e.id_perush = '".$id_perush."' "; 
        }
        if($id_user != null){
            $sql .= " and e.id_user = '".$id_user."' "; 
        }
        
        $sql .= " order by k.nm_karyawan,u.username asc";
        
        $data = DB::select(DB::raw($sql));
        $collect = collect($data);
        
        $data = new LengthAwarePaginator(
            $collect->forPage($page, $perpage),
            $collect->count(),
            $perpage,
            $page
        );
        
        
        return $data;
    }
}
