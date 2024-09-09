<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name', 'email', 'username','password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function RoleMenu()
    {
        return $this->hasMany('App\Models\RoleMenu', 'id_user', 'id_user');
    }
    
    public function RoleUser()
    {
        return $this->hasMany('App\Models\RoleUser', 'id_user', 'id_user');
    }

    public function Perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }
    
    public static function generatedId($id_perush)
    {
        $id = self::where("id_perush", $id_perush)->orderBy("last_id", "desc")->get()->first();
        
        $last = 0;
        if($id!=null){
            $last = (Int)$id->last_id+1;
        }

        return $last;
    }

    public static function getKacab($id_perush)
    {
        $sql = "select u.id_karyawan,k.nm_karyawan from users u join m_karyawan k on u.id_karyawan=k.id_karyawan
        where k.id_perush ='".$id_perush."' and u.is_kacab='1' limit 1 ";

        $data = DB::select($sql);


        return $data;
    }
}
