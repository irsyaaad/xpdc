<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOrder extends Model
{
    protected $table = "t_order";
    // protected $primaryKey = 'id_menu';

    public function history()
    {
        return $this->hasMany('App\Models\MTHistoryStt', 'id_stt', 'id_stt');
    }
}
