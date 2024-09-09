<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusInvoice extends Model
{
    protected $fillable = [];
    protected $table = "m_status_invoice";
	protected $primaryKey = 'id_status';
	public $incrementing = false;
	public $keyType = 'string';

	public static function getList()
	{
		$status = self::select("id_status", "nm_status")->get();
        $stt = [];
        foreach ($status as $key => $value) {
            $stt[$value->id_status] = $value;
		}
			
		return $stt;
	}
}
