<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class DetailKaryawan extends Model
{
    protected $table = "m_detail_karyawan";
    protected $primaryKey = 'id_detail';
}
