<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class Memorial extends Model
{
    protected $table = "keu_memorial";
    protected $primaryKey = 'id_memorial';
    protected $fillable = [];
    
    public static function getData($page, $id_perush, $dr_tgl = null, $sp_tgl=null, $id_memorial = null)
    {
        $sql = "select m.tgl, m.id_memorial, m.kode_memorial,p.nm_perush, u.nm_user,m.info,d.nominal from keu_memorial m
        join s_perusahaan p on m.id_perush = m.id_perush
        join users u on u.id_user = m.id_user
        join (
            select sum(n_debet) as nominal, id_memo from keu_memorial_det GROUP BY id_memo
        ) as d on d.id_memo = m.id_memorial
        where p.id_perush = '".$id_perush."' ";

        if($dr_tgl != null){
            $sql = $sql." and m.tgl >='".$dr_tgl."' ";
        }

        if($sp_tgl != null){
            $sql = $sql." and m.tgl <='".$sp_tgl."' ";
        }
        
        $data = DB::select(DB::raw($sql));
            
        $data = new Paginator($data, $page);
    
        return $data;
    }
    
    public function user()
    {	
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    } 

    public function perusahaan()
    {	
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    public function debet()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_debet', 'id_ac');
    }
    public function kredit()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'id_ac_kredit', 'id_ac');
    }
}
