<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class BiayaHpp extends Model
{
    protected $fillable = [];
    protected $table = "t_dm_biaya_bayar";
    protected $primaryKey = 'id_biaya';
    public $incrementing = false;
    public $keyType = 'string';
    
    public function proyeksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\ProyeksiDm', 'id_pro_bi', 'id_pro_bi');
    }
    
    public function debet()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_debit', 'id_ac');
    }
    
    public function kredit()
    {	
        return $this->belongsTo('Modules\Keuangan\Entities\ACPerush', 'ac4_kredit', 'id_ac');
    }

    public function user()
	{
		return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
	}
    
    public static function getBiayaInvoice($id)
    {
        $sql = "select DISTINCT(b.id_inv_pend) as id_biaya_pend, ac4_debit, ac4_kredit, id_invoice, g.nm_biaya_grup, b.id_stt, g.klp, b.kode_stt, b.id_dm, b.kode_dm,
        b.id_handling, b.kode_handling, m.id_ac as hutang, n.id_ac as biaya, b.nominal ,b.n_bayar as dibayar, b.is_lunas
        from t_dm_biaya b
        join m_biaya_grup g on b.id_biaya_grup = g.id_biaya_grup 
        join m_ac_perush m on b.ac4_debit = m.id_ac
        join m_ac_perush n on b.ac4_kredit = n.id_ac
        where id_invoice = '".$id."'";

        return DB::select($sql);
    }

    public static function getbayar($id, $id_perush)
    {
        $sql = "select b.*,g.klp,g.nm_biaya_grup,d.nama as debet,k.nama as kredit,u.nm_user from t_dm_biaya_bayar b 
        join m_biaya_grup g on b.id_biaya_grup = g.id_biaya_grup
        left join (
            select id_ac, nama from m_ac_perush where id_perush = '".$id_perush."'
        ) as d on d.id_ac = b.ac4_debit
        left join (
            select id_ac, nama from m_ac_perush where id_perush = '".$id_perush."'
        ) as k on k.id_ac = b.ac4_kredit
        left join users u on u.id_user = b.user_edit
        where b.id_dm ='".$id."' order by b.tgl_bayar desc";

        return DB::select($sql);
    }

    public static function getbayarVendor($id, $id_perush, $id_jenis)
    {
        $sql = "select b.*,g.klp,g.nm_biaya_grup,d.nama as debet,k.nama as kredit,u.nm_user from t_dm_biaya_bayar b 
        join m_biaya_grup g on b.id_biaya_grup = g.id_biaya_grup
        left join (
            select id_ac, nama from m_ac_perush where id_perush = '".$id_perush."'
        ) as d on d.id_ac = b.ac4_debit
        left join (
            select id_ac, nama from m_ac_perush where id_perush = '".$id_perush."'
        ) as k on k.id_ac = b.ac4_kredit
        left join users u on u.id_user = b.user_edit
        where b.id_dm ='".$id."' and b.id_jenis='".$id_jenis."' order by b.tgl_bayar desc";
            
        return DB::select($sql);
    }

    public static function getInvoiceBayar($id_biaya)
    {
        $sql = "select DISTINCT(b.id_biaya), b.id_inv_pend, b.created_at, b.n_bayar, b.ac4_kredit, b.ac4_debit, m.nm_biaya_grup from t_dm_biaya_bayar b
        join m_biaya_grup m on b.id_biaya_grup = m.id_biaya_grup
        where b.id_inv_pend= '".$id_biaya."'";
        
        return DB::select($sql);
    }
}
