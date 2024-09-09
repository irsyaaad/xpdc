<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class CaraBayar extends Model
{
    protected $fillable = [];
    protected $table = "m_cr_bayar_order";
	protected $primaryKey = 'id_cr_byr_o';
    public $incrementing = false;
    public $keyType = 'string';
    
    public static function getList()
    {
        return self::select("id_cr_byr_o", "nm_cr_byr_o")->where("is_aktif", "true")->orderBy("nm_cr_byr_o", "asc")->get();
    }
}
