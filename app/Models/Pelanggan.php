<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pelanggan extends Model
{
	protected $table = "m_plgn";
	protected $primaryKey = 'id_pelanggan';

	public function wilayah()
	{
		return $this->belongsTo('App\Models\Wilayah', 'id_wil', 'id_wil');
	}

	public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}

	public function group()
	{
		return $this->belongsTo('App\Models\Grouppelanggan', 'id_plgn_group', 'id_plgn_group');
	}

	public static function getNumber($number)
	{
		$subs = substr($number, 0,2);
		if($subs == 62){
			$number = substr($number, 3,16);
		}else{
			return $number;
		}
	}

	public static function getFilter($id_perush, $id_pelanggan = null, $id_plgn_group = null, $id_wil = null)
	{
		$data = DB::table('m_plgn')
			->join('s_perusahaan','m_plgn.id_perush','=','s_perusahaan.id_perush')
			->join('m_plgn_group','m_plgn.id_plgn_group','=','m_plgn_group.id_plgn_group')
			->select('m_plgn.id_pelanggan','s_perusahaan.nm_perush','m_plgn_group.nm_group',
			'm_plgn.alamat','m_plgn.telp','m_plgn.email','m_plgn.isaktif','m_plgn.nm_pelanggan', 'm_plgn.is_user', "m_plgn.n_limit_piutang")
			->where("m_plgn.id_perush", $id_perush);

		if($id_pelanggan != null){
			$data = $data->where("m_plgn.id_pelanggan", $id_pelanggan);
		}

		if($id_plgn_group != null){
			$data = $data->where("m_plgn.id_plgn_group", $id_plgn_group);
		}

		if($id_wil != null){
			$data = $data->where("m_plgn.id_wil", $id_wil);
		}

		$data = $data->orderBy("m_plgn.nm_pelanggan", "asc");

		return $data;
	}

	public static function getPelanggan($id_perush)
	{
		$sql = "select id_perush,nm_pelanggan,alamat, telp,email, nm_kontak as nm_cs, no_kontak as no_cs, id_plgn_group,id_vendor,id_perush_cabang,id_perush
		from m_plgn where id_perush = '".$id_perush."'";

		$data = DB::select($sql);

		return $data;
	}

	public static function getPelangganKode($id_perush = null)
	{
		$sql = "select id_perush,nm_pelanggan,alamat, telp,email, nm_kontak as nm_cs, no_kontak as no_cs, id_plgn_group,id_vendor,id_perush_cabang,id_perush
		from m_plgn p join s_perusahaan s on p.id_perush = s.id_perush";

		if($id_pelanggan != null){
			$sql.="where p.kode_perush = '".$id_perush."'";
		}

		$data = DB::select($sql);

		return $data;
	}

    public function filter_show_master_data($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$data = DB::table('m_plgn');
		if (isset($search)) {
            $data->where('nm_pelanggan', 'like', '%' . $search . '%');
		}
		if (isset($order_field) and isset($order_ascdesc)) {
			$data->orderBy($order_field, $order_ascdesc);
		}
		if (isset($limit) and isset($start)) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function count_all_show_master_data(){
		return $this->db->count_all('kons_rab_template');
	}

	public function count_filter_show_master_data($search){
		$this->db->select('a.*,b.total_harga as total_harga');
		$this->db->from('kons_rab_template as a');

		if (isset($search)) {
			$this->db->like('a.nama_template',$search);
		}
		$this->db->join('kons_rap_template as b', 'a.id_rab_template = b.id_rab_template','inner');
		$query =  $this->db->get()->num_rows();
		return $query;
	}
}
