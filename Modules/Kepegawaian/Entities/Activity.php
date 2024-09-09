<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = "t_activity";
    public $incrementing = false;
    protected $primaryKey = 'id_activity';
}
