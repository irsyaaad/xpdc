<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class BiayaHandling extends Model
{
    protected $fillable = [];
    protected $table = "t_handling_biaya";
    protected $primaryKey = 'id_biaya';

    public function group()
	{
		return $this->belongsTo('Modules\Keuangan\Entities\GroupBiaya', 'id_biaya_grup', 'id_biaya_grup');
    }

    public static function getIdDM($id_handling)
    {
        $sql = "select id_stt,id_dm, kode_dm from t_handling_stt where id_handling = '".$id_handling."';";
        $data = DB::select($sql);
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_stt] = $value;
        }

        return $a_data;
    }

    public static function getDetailBiaya($page, $id_handling)
    {
        $sql = "select b.id_biaya, b.is_lunas,m.id_biaya_grup,  m.nm_biaya_grup, m.klp, b.nominal,b.n_bayar, b.ac4_debit, b.ac4_kredit, b.id_stt, b.kode_stt from t_handling_biaya b
        join m_biaya_grup m on m.id_biaya_grup =b.id_biaya_grup
        where b.id_handling = '".$id_handling."'";
        $data = DB::select(DB::raw($sql));
        $data = new Paginator($data, $page);

        return $data;
    }

    public static function getBiayaHandling($perpage, $page, $id_perush, $id_handling = null, $tgl_handling = null, $tgl_berangkat_dr = null, $tgl_berangkat_sp = null, $tgl_selesai_dr = null, $tgl_selesai_sp = null)
    {
        $sql = "select h.id_handling,h.tgl_berangkat,h.tgl_selesai,h.created_at, b.total,b.bayar,s.stt, h.kode_handling from t_handling h
        left join (
            select sum(nominal) as total, sum(n_bayar) as bayar , id_handling from t_handling_biaya GROUP BY id_handling
        ) as b on h.id_handling=b.id_handling
        join(
            select count(id_stt) as stt, id_handling from t_handling_stt GROUP BY id_handling
        ) as s on h.id_handling = s.id_handling
        where h.id_perush='".$id_perush."' ";

        if($id_handling != null){
			$sql.= " and  h.id_handling = '".$id_handling."' ";
		}

        if($tgl_handling != null){
			$sql.= " and  h.created_at = '".$tgl_handling."' ";
		}

        if($tgl_berangkat_dr != null){
			$sql.= " and  h.tgl_berangkat >= '".$tgl_berangkat_dr."' ";
		}
        if($tgl_berangkat_sp != null){
			$sql.= " and  h.tgl_berangkat <= '".$tgl_berangkat_sp."' ";
		}

        if($tgl_selesai_dr != null){
			$sql.= " and  h.tgl_selesai >= '".$tgl_selesai_dr."' ";
		}
        if($tgl_selesai_sp != null){
			$sql.= " and  h.tgl_selesai <= '".$tgl_selesai_sp."' ";
		}
        // dd($sql);
        $data = DB::select(DB::raw($sql));
        $collect = collect($data);

		$data = new LengthAwarePaginator(
			$collect->forPage($page, $perpage),
			$collect->count(),
			$perpage,
			$page
		  );

        return $data;
    }

    public static function getPerushBiaya($page, $id_invoice, $id_dm = null, $id_stt = null, $id_biaya = null)
    {
        $sql = "select DISTINCT(b.id_biaya),b.id_biaya_grup, g.nm_biaya_grup, g.klp,b.nominal,b.id_stt, b.kode_stt, b.id_handling, b.kode_handling from t_handling_biaya b
        join t_handling_stt t on b.id_handling = t.id_handling
        join m_biaya_grup g on b.id_biaya_grup = g.id_biaya_grup
        where b.id_biaya not in(
                select id_biaya from keu_invoice_handling_pendapatan where id_invoice = '".$id_invoice."'
        ) ";

        if($id_dm != null){
            $sql = $sql." and t.id_dm ='".$id_dm."' ";
        }

        if($id_stt != null){
            $sql = $sql." and b.id_stt ='".$id_stt."' ";
        }

        if($id_biaya != null){
            $sql = $sql." and b.id_biaya_grup ='".$id_biaya."' ";
        }

        $data = DB::select(DB::raw($sql));
        $data = new Paginator($data, $page);

        return $data;
    }

    public static function getBiaya($id_invoice, $id_dm = null, $id_biaya = null)
    {
        $sql = "select DISTINCT(b.id_biaya),g.id_biaya_grup, g.nm_biaya_grup from t_handling_biaya b
        join t_handling_stt t on b.id_handling = t.id_handling
        join m_biaya_grup g on b.id_biaya_grup = g.id_biaya_grup
        where b.id_biaya not in(
            select id_biaya from keu_invoice_handling_pendapatan where id_invoice ='".$id_invoice."'
        ) ";

        if($id_dm != null){
            $sql = $sql." and t.id_dm ='".$id_dm."' ";
        }

        if($id_biaya != null){
            $sql = $sql." and b.id_biaya_grup ='".$id_biaya."' ";
        }

        $data = DB::select($sql);

        return $data;
    }

    public static function getHandlingDm($id_perush, $id_perush_tj)
    {
        $sql = "select DISTINCT(b.id_handling),t.id_dm, t.kode_dm from t_handling_biaya b
        join t_handling_stt t on b.id_handling = t.id_handling
        where t.id_perush = '".$id_perush."' and t.id_perush_asal = '".$id_perush_tj."'";
        $data = DB::select($sql);

        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_handling] = $value;
        }

        return $a_data;
    }

    public static function getDaftarBiaya($id_invoice)
    {
        $sql = "select b.id_biaya,b.nominal,b.n_bayar, b.id_biaya_grup,
        bg.nm_biaya_grup,bg.klp, b.is_lunas, b.keterangan
        from keu_inv_handling as th
        join t_handling_biaya as b on th.id_invoice=b.id_invoice
        join m_biaya_grup as bg on b.id_biaya_grup = bg.id_biaya_grup
        where th.id_invoice = '".$id_invoice."'";

        $data = DB::select($sql);
        return $data;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Perusahaan', 'id_perush_penerima', 'id_perush');
    }

    public function debit()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_debit', 'id_ac');
    }

    public function kredit()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\MasterAC', 'ac4_kredit', 'id_ac');
    }
}
