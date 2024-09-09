<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "role";
    protected $primaryKey = 'id_role';
    
    public function RoleUser()
    {
    	return $this->hasMany('App\Models\RoleUser', 'id_role', 'id_role');
    }

    public static function getRole()
    {
        $data = self::where("id_role", ">", "1")->get();

        return $data;
    }
}
