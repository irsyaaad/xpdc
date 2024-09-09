<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Budgeting extends Model
{
    protected $fillable = [];
    protected $table = "m_budgeting";
    protected $primaryKey = 'id';

    public function ac4()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\MasterACPerush', 'ac4', 'id_ac');
    }
}
