<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class InvHandlingStt extends Model
{
    protected $fillable = [];
    protected $table = "keu_inv_handling_stt";
    public $incrementing = false;
    protected $primaryKey = 'id_stt';
    public $keyType = 'string';
    
    public static function getStt($id = null)
    {
        $data = DB::table("keu_inv_handling_stt as i")
                    ->join("t_order as o", "i.id_stt", "=", "o.id_stt")
                    ->join("t_order_dm as d", "d.id_stt", "=", "o.id_stt")
                    ->join("t_dm as m", "d.id_dm", "=", "m.id_dm")
                    ->join("t_handling_stt as h", "h.id_stt", "=", "o.id_stt")
                    ->join("m_layanan as l", "o.id_layanan", "=", "l.id_layanan")
                    ->select("i.id_stt", "l.nm_layanan", "h.id_handling", "m.tgl_berangkat", "m.tgl_sampai", "d.id_dm", "o.pengirim_nm", "o.pengirim_perush", "o.tgl_masuk", "o.n_koli");

        if(isset($id)){
            $data = $data->where("i.id_invoice", $id);
        }
        
        $data = $data->get();

        return $data;
    }

    public static function getDaftarHandling($id_perush)
    {
        $data = DB::table("t_order as a")
                    ->rightjoin("t_handling_stt as b","a.id_stt","=","b.id_stt")
                    ->join("t_handling as c","b.id_handling","=","c.id_handling")
                    ->join("m_sopir as d","c.id_sopir","=","d.id_sopir")
                    ->join("m_armada as e","c.id_armada","=","e.id_armada")
                    ->leftjoin("keu_inv_handling_stt as f","f.id_stt","=","a.id_stt")
                    ->select("c.id_handling","c.tgl_berangkat","c.tgl_selesai","d.nm_sopir","e.nm_armada","a.id_stt")
                    ->where("a.id_perush_asal",$id_perush)
                    ->whereNotNull("c.tgl_selesai")
                    ->whereNull("f.id_stt");

        return $data;
    }
}
