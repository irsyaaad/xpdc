<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;

class ProyeksiHandling extends Model
{
    protected $fillable = [];
    protected $table = "m_handling_proyeksi";
	protected $primaryKey = 'id_proyeksi';
    public $incrementing = true;
    
	public static function getData()
	{
		$data = self::join("s_perusahaan as p", "m_handling_proyeksi.id_perush", "=", "p.id_perush")
					->join("m_biaya_grup as g", "g.id_biaya_grup", "=", "m_handling_proyeksi.id_biaya_grup")
					->select("m_handling_proyeksi.id_proyeksi", "m_handling_proyeksi.nominal", "g.nm_biaya_grup", "g.id_biaya_grup")
					->where("p.id_perush", Session("perusahaan")["id_perush"])
					->groupBy("g.nm_biaya_grup", "m_handling_proyeksi.id_proyeksi", "m_handling_proyeksi.nominal", "g.id_biaya_grup");
		
		return $data;
	}

    public function perusahaan()
	{	
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
	public function group()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
    }

	public function hutang()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_hutang', 'id_ac');
    }

	public function biaya()
	{	
		return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_biaya', 'id_ac');
    }

	public function user()
	{	
		return $this->belongsTo('App\User', 'id_user', 'id_user');
    }
}
