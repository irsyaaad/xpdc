<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class ArmadaGroup extends Model
{
    protected $fillable = ["nm_armd_grup", "gr_armd", "is_aktif", "id_user"];
    protected $table = "m_armada_grup";
    protected $primaryKey = 'id_armd_grup';
}
