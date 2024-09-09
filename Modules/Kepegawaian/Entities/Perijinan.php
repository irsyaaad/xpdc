<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Session;

class Perijinan extends Model
{
    protected $table = "kep_perijinan";    
	protected $primaryKey = 'id_perijinan';

    public function perusahaan()
	{
		return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
	}
    public function karyawan()
	{
		return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
	}
	public function ijin()
	{
		return $this->belongsTo('Modules\Kepegawaian\Entities\JenisPerijinan', 'id_jenis', 'id_jenis');
	}
	public function user()
	{
		return $this->belongsTo('App\User', 'id_user', 'id_user');
	}

	public static function getIjinHari($dr_tgl, $sp_tgl, $id_perush)
	{
		$sql = "select sum(jumlah) as ijin, id_karyawan,j.id_jenis
		from kep_perijinan i 
		join m_jenis_perijinan j on i.id_jenis = j.id_jenis
		where ((i.dr_tgl>='".$dr_tgl."' and i.dr_tgl <='".$sp_tgl."') or (i.sp_tgl>='".$dr_tgl."' and i.sp_tgl <='".$sp_tgl."'))
		and i.id_perush ='".$id_perush."' and j.format ='2'  GROUP BY i.id_karyawan,j.id_jenis ";
		//dd($sql);
		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan][$value->id_jenis]["total"] = $value->ijin;
			$a_data[$value->id_karyawan][$value->id_jenis]["jenis"] = $value->id_jenis;
		}
		//dd($a_data);
		return $a_data;
	}

	public static function getIjinHariCabang($dr_tgl, $sp_tgl, $id_role = null, $id_perush = null)
	{
		$sql = "select sum(i.jumlah) as ijin, i.id_karyawan,j.id_jenis
		from kep_perijinan i 
		join m_jenis_perijinan j on i.id_jenis = j.id_jenis
		where ((i.dr_tgl>='".$dr_tgl."' and i.dr_tgl <='".$sp_tgl."') or (i.sp_tgl>='".$dr_tgl."' and i.sp_tgl <='".$sp_tgl."')) and j.format ='2' ";
		
		$id_perush != null? $sql.=" and i.id_perush ='".$id_perush."'":$sql;
		$sql .= " GROUP BY i.id_karyawan,j.id_jenis";

		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan][$value->id_jenis]["total"] = $value->ijin;
			$a_data[$value->id_karyawan][$value->id_jenis]["jenis"] = $value->id_jenis;
		}
		//dd($a_data);
		return $a_data;
	}

	public static function getIjinJam($dr_tgl, $sp_tgl, $id_perush)
	{
		$sql = "select sum((i.sp_jam - i.dr_jam)) as jam, id_karyawan,j.id_jenis
		from kep_perijinan i 
		join m_jenis_perijinan j on i.id_jenis = j.id_jenis
		where ((i.dr_tgl>='".$dr_tgl."' and i.dr_tgl <='".$sp_tgl."') or (i.sp_tgl>='".$dr_tgl."' and i.sp_tgl <='".$sp_tgl."')) 
		and i.id_perush ='".$id_perush."' and j.format ='1' GROUP BY i.id_karyawan,j.id_jenis ";
		
		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan][$value->id_jenis]["total"] = $value->jam;
			$a_data[$value->id_karyawan][$value->id_jenis]["jenis"] = $value->id_jenis;
		}

		return $a_data;
	}

	public static function getIjinJamCabang($dr_tgl, $sp_tgl,$id_role = null,  $id_perush = null)
	{
		$sql = "select sum((i.sp_jam - i.dr_jam)) as jam, i.id_karyawan,j.id_jenis
		from kep_perijinan i 
		join m_jenis_perijinan j on i.id_jenis = j.id_jenis
		where ((i.dr_tgl>='".$dr_tgl."' and i.dr_tgl <='".$sp_tgl."') or (i.sp_tgl>='".$dr_tgl."' and i.sp_tgl <='".$sp_tgl."')) 
		and j.format ='1' ";
		
		$id_perush != null ? $sql.= " and i.id_perush ='".$id_perush."' ":$sql;
		$sql .= " GROUP BY i.id_karyawan,j.id_jenis";

		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan][$value->id_jenis]["total"] = $value->jam;
			$a_data[$value->id_karyawan][$value->id_jenis]["jenis"] = $value->id_jenis;
		}
		//dd($a_data);
		return $a_data;
	}

	public static function getIjin($id_jenis, $dr_tgl, $sp_tgl, $id_perush)
	{
		$sql = "select sum(jumlah) as ijin, id_karyawan
		from kep_perijinan where lower(id_jenis)='".$id_jenis."' 
		and ((dr_tgl>='".$dr_tgl."' and dr_tgl <='".$sp_tgl."') or (sp_tgl>='".$dr_tgl."' and sp_tgl <='".$sp_tgl."')) and id_perush ='".$id_perush."' ";
		
		$sql = $sql." GROUP BY id_karyawan ";

		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan] = $value->ijin;
		}

		return $a_data;
	}

	public static function getIjinCabang($id_jenis, $dr_tgl, $sp_tgl, $id_perush = null)
	{
		$sql = "select sum(jumlah) as ijin, id_karyawan
		from kep_perijinan where lower(id_jenis)='".$id_jenis."' 
		and((dr_tgl>='".$dr_tgl."' and dr_tgl <='".$sp_tgl."') or (sp_tgl>='".$dr_tgl."' and sp_tgl <='".$sp_tgl."')) ";
		
		if($id_perush != null){
			$sql += " and id_perush ='".$id_perush."'";
		}
		$sql += " GROUP BY id_perush ";

		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_perush] = $value->ijin;
		}

		return $a_data;
	}

	public static function getCuti($dr_tgl, $sp_tgl)
	{
		$sql = "select sum(jumlah) as cuti, id_karyawan
		from kep_perijinan where lower(id_jenis)='c' and is_konfirmasi='true' 
		and ((dr_tgl>='".$dr_tgl."' and dr_tgl <='".$sp_tgl."') or (sp_tgl>='".$dr_tgl."' and sp_tgl <='".$sp_tgl."')) ";
		
		if(!get_admin()){
			$sql = $sql." and id_perush='".Session("perusahaan")["id_perush"]."' ";
		}

		$sql = $sql." GROUP BY id_karyawan ";
		
		$data = DB::select($sql);
		
		$a_data = [];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan]["id_karyawan"] = $value->id_karyawan;
			$a_data[$value->id_karyawan]["cuti"] = $value->cuti;
		}

		return $a_data;
	}

	public static function getData($dr_tgl,$sp_tgl, $id_jenis = null,$id_perush=null)
	{
		$sql = "select k.id_jenis,j.nm_jenis,k.keterangan,k.dr_tgl, k.sp_tgl, k.id_karyawan,n.nm_karyawan, j.format,k.is_konfirmasi, k.created_at
		from kep_perijinan k 
		join m_jenis_perijinan j on k.id_jenis=j.id_jenis
		join m_karyawan n on k.id_karyawan = n.id_karyawan
		where ((k.dr_tgl >= '".$dr_tgl."'
		and k.dr_tgl <= '".$sp_tgl."') or (k.sp_tgl >= '".$dr_tgl."'
		and k.sp_tgl <= '".$sp_tgl."')) and n.is_aktif='true' ";
		
		if(isset($id_perush) and $id_perush!=null){
			$sql = $sql." and k.id_perush='".$id_perush."' ";
		}
		
		if(isset($id_jenis) and $id_jenis!=null){
			$sql = $sql." and k.id_jenis='".$id_jenis."' ";
		}
		
		$data = DB::select($sql);
		
		return $data;
	}
	
	public static function getIzin($dr_tgl,$sp_tgl, $id_perush=null)
	{
		$sql = "select sum(k.jumlah) as ijin, k.id_jenis, k.id_karyawan, j.format
		from kep_perijinan k 
		join m_jenis_perijinan j on k.id_jenis=j.id_jenis
		where ((dr_tgl>='".$dr_tgl."' and dr_tgl <='".$sp_tgl."') or (sp_tgl>='".$dr_tgl."' and sp_tgl <='".$sp_tgl."')) ";
		
		if(isset($id_perush) and $id_perush!=null){
			$sql = $sql." and k.id_perush='".$id_perush."' ";
		}

		$sql = $sql." GROUP BY k.id_jenis,k.id_karyawan,j.format";
		
		$data = DB::select($sql);
		
		return $data;
	}

	public static function getizinkolektif($id_karyawan, $dr_tgl)
	{
		$sql = "select p.id_karyawan, p.id_jenis from kep_perijinan p 
		join m_jenis_perijinan j on p.id_jenis = j.id_jenis
		where j.format = '1' and p.id_karyawan ='".$id_karyawan."' and p.dr_tgl='".$dr_tgl."' ";
		$data = DB::select($sql);
		$a_data =[];
		foreach($data as $key => $value){
			$a_data[$value->id_karyawan] = $value;
		}

		return $a_data;
	}

	public static function getDenda($dr_tgl, $sp_tgl, $id_perush = null, $id_karyawan=null, $denda = null)
	{
		$sql = "select k.id_karyawan, m.nm_karyawan,k.id_jenis, sum(k.jumlah) as jumlah
		from kep_perijinan k
		join m_karyawan m on k.id_karyawan = m.id_karyawan
		join m_jenis_perijinan j on k.id_jenis = j.id_jenis
		where m.is_aktif =true and ((k.dr_tgl>='".$dr_tgl."' and k.dr_tgl<='".$sp_tgl."') or  (k.sp_tgl>='".$dr_tgl."' and k.sp_tgl<='".$sp_tgl."')) and k.is_konfirmasi!='0' ";

		if(isset($id_karyawan) and $id_karyawan!=null){
			$sql = $sql." and k.id_karyawan='".$id_karyawan."' ";
		}
		
		if(isset($id_perush) and $id_perush!=null){
			$sql = $sql." and k.id_perush='".$id_perush."'";
		}
		
		$sql = $sql." group by k.id_karyawan,k.id_jenis,m.nm_karyawan
		order by m.nm_karyawan asc ";
		$data = DB::select($sql);

		$a_ijin = [];
        foreach($data as $key => $value){
            $nominal = 0;
            $frekuensi = 0;
            if(isset($denda[$value->id_jenis])){
                $s_denda = $denda[$value->id_jenis];
                $nominal = $s_denda->nominal;
                $frekuensi = $s_denda->frekuensi;
            }
            
			$a_ijin[$value->id_karyawan][$value->id_jenis]["nm_karyawan"] = $value->nm_karyawan;
            $a_ijin[$value->id_karyawan][$value->id_jenis]["jumlah"] = $value->jumlah;
            $a_ijin[$value->id_karyawan][$value->id_jenis]["nominal"] = $nominal;
            $a_ijin[$value->id_karyawan][$value->id_jenis]["frekuensi"] = $frekuensi;
			$a_ijin[$value->id_karyawan][$value->id_jenis]["format"] = $frekuensi;
        }

		return $a_ijin;
	}

	public static function getJam($dr_tgl, $sp_tgl, $id_perush = null, $id_karyawan=null)
	{
		$sql = "select m.id_karyawan,  (m.sp_jam-m.dr_jam) as dif_jam,  (m.sp_tgl-m.dr_tgl)*8 as dif_hari, m.id_jenis from kep_perijinan m join s_denda d on m.id_jenis=d.id_jenis
				where ((m.dr_tgl>='".$dr_tgl."' and m.dr_tgl <='".$sp_tgl."') or (m.sp_tgl>='".$dr_tgl."' and m.sp_tgl <='".$sp_tgl."'))";
		
		if(isset($id_perush) and $id_perush!=null){
			$sql = $sql." and m.id_perush='".$id_perush."'";
		}

		if(isset($id_karyawan) and $id_karyawan!=null){
			$sql = $sql." and m.id_karyawan='".$id_karyawan."'";
		}
		
		$data = DB::select($sql);
		
        $a_ijin = [];
        foreach($data as $key => $value){
            if($value->id_karyawan!=null){
                $a_ijin[$value->id_karyawan][$value->id_jenis] = $value;
            }
        }
		
		return $a_ijin;
	}

	public static function getFilterDenda($id_perush,$bulan,$tahun)
	{
		$sql = "select m.id_karyawan,k.id_jenis,NULLIF(d.frekuensi, 0) as frekuensi,NULLIF(d.nominal, 0) as nominal, k.jumlah as jumlah
		from kep_perijinan k
		join s_denda d on d.id_jenis=k.id_jenis
		join m_karyawan m on m.id_karyawan=k.id_karyawan
		where to_char(k.dr_tgl , 'MM')='".$bulan."'
		and to_char(k.dr_tgl , 'yyyy')='".$tahun."'
		and k.id_perush = '".$id_perush."'
		and k.is_konfirmasi!='0' 
		group by m.id_karyawan,d.nominal,d.frekuensi,k.id_jenis,k.jumlah
		order by m.nm_karyawan asc";
		
		$data = DB::select($sql);
		
		return $data;
	}

	public static function getRolePerush()
	{
		$sql = "select a.id_perush,a.nm_perush,ru.id_user from s_perusahaan as a
		join role_user as ru on a.id_perush = ru.id_perush
		where ru.id_user = '".Auth::user()->id_user."'
		group by a.id_perush,ru.id_user";
		
		$data = DB::select($sql);
		
		return $data;
	}
}
