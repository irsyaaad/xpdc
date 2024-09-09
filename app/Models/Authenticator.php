<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class Authenticator extends Model
{
    protected $table = "m_authenticator";
	protected $primaryKey = 'id_auth';
    
    public static function getAuth($id_perush = null)
    {
        $sql = "select a.id_auth,p.nm_perush,s.nm_user,a.auth_kode  from m_authenticator a 
        join users s on a.id_karyawan = s.id_user
        join s_perusahaan p on a.id_perush = p.id_perush";
        
        if(isset($id_perush) and $id_perush != null){
            $sql = $sql." where a.id_perush = '".$id_perush."' ";
        }
        
        $data = DB::select(DB::raw($sql));
        
        return $data;
    }
}
