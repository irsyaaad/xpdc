<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class PiutangKaryawan extends Model
{
    protected $table = "kep_piutang_karyawan";
    protected $primaryKey = 'id_piutang';
    
    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }
    
    public static function getData()
    {
        $data = self::join("m_karyawan as k", "kep_piutang_karyawan.id_karyawan", "=", "k.id_karyawan")
        ->join("s_perusahaan as p", "kep_piutang_karyawan.id_perush", "=", "p.id_perush")
        ->join("users as u", "kep_piutang_karyawan.id_user", "=", "u.id_user")
        ->select("kep_piutang_karyawan.id_piutang", "kep_piutang_karyawan.approve", "kep_piutang_karyawan.is_lunas",  "kep_piutang_karyawan.bayar", "kep_piutang_karyawan.sisa", "k.nm_karyawan", "k.id_karyawan", "p.nm_perush", "u.nm_user", "kep_piutang_karyawan.nominal", "kep_piutang_karyawan.frekuensi", "kep_piutang_karyawan.n_angsuran", "kep_piutang_karyawan.tgl_piutang", "kep_piutang_karyawan.tgl_selesai")
        ->where("p.id_perush", Session("perusahaan")["id_perush"]);
        return $data;
    }
    
    public static function getPiutang($id_perush = null)
    {
        $sql = "select id_karyawan,bayar,sisa,nominal, n_angsuran from kep_piutang_karyawan where is_lunas = 'f' ";
        
        if(isset($id_perush) and $id_perush !=null){
            $sql = $sql." and id_perush = '".$id_perush."' ";
        }
        
        $data = DB::select($sql);
        
        $a_data = [];
        foreach($data as $key => $value){
            
            if($value->bayar == null){
                $a_data[$value->id_karyawan] = $value->n_angsuran;
            }else{
                if($value->sisa > $value->n_angsuran){
                    $a_data[$value->id_karyawan] = $value->n_angsuran;
                }
                
                if($value->sisa < $value->n_angsuran){
                    $a_data[$value->id_karyawan] = $value->sisa;
                }
            }
        }
        
        return $a_data;
    }
}
