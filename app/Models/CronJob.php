<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $table = "p_cron_job";
	protected $primaryKey = 'id_cron';
    
}
