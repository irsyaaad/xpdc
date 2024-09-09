<?php

namespace Modules\Keuangan\Entities;
use DB;

use Illuminate\Database\Eloquent\Model;

class Proyeksi extends Model
{
    protected $fillable = [];
    protected $table = "m_proyeksi";
    protected $primaryKey = 'id';

    public static function getProyeksi($id_perush, $dr_tgl, $sp_tgl)
    {
        return self::select('ac4', DB::raw('SUM(proyeksi :: INTEGER) AS proyeksi'))
            ->where('id_perush', '=', $id_perush)
            ->whereBetween('tgl', [$dr_tgl, $sp_tgl])
            ->groupBy('ac4')
            ->get();
    }
}
