<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingPerusahaan extends Model
{
    protected $table = "setting_perusahaan_asuransi";
    protected $primaryKey = 'id_setting';
    protected $fillable = [];
}
