<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class DetailGajiKaryawan extends Model
{
    protected $table = "m_karyawan_gaji";
    protected $primaryKey = 'id';
    protected $fillable = [];
}
