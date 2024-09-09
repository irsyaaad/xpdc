<?php

namespace Modules\Asuransi\Entities;

use Illuminate\Database\Eloquent\Model;

class SettingPelanggan extends Model
{
    protected $table = "setting_pelanggan";
    protected $primaryKey = 'id_setting';
    protected $fillable = [];
}
