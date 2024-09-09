<?php

namespace Modules\Kepegawaian\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Absensi extends Model
{
    protected $table = "absensi";
    public $incrementing = false;
    protected $primaryKey = 'id_absensi';
    public $keyType = 'string';
    
    // status absensi
    // 0 = absen tertib
    //1 = tidak hadir
    
    // status kedatangan
    // 1 = absen sebelum jam masuk
    // 2 = absen terlambat
    // 3 = tidak absen masuk
    
    // status pulang
    // 4 =  tidak absen pulang
    //5 = absen pulang duluan
    
    // status istirahat 
    // 0 = pas, 1 = terlambat , 2 =  tidak absen
    // status istirahat masuk 
    // 0 = pas, 1= terlambat, 2 = tidak absen
    
    public function admin()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_admin');
    }
    
    public static function getJamKerja($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "select count(a.id_absen) as total, a.id_karyawan
        from s_jam_kerja s
        join m_karyawan k on k.id_jam_kerja = s.id_setting
        join absensi a on k.id_karyawan = a.id_karyawan
        where k.id_perush = '".$id_perush."' and a.tgl_absen >='".$dr_tgl."' and a.tgl_absen<='".$sp_tgl."' and
        (a.jam_datang != '00:00:00' and a.jam_istirahat != '00:00:00' and a.jam_istirahat_masuk != '00:00:00' and a.jam_pulang!= '00:00:00')
        GROUP BY a.id_karyawan";
        
        $data = DB::select($sql);
        
        $a_data = [];
        foreach($data as $key => $value){
            $a_data[$value->id_karyawan] = $value->total;
        }
        
        return $a_data;
    }
    
    public static function getAbsensi($dr_tgl = null, $sp_tgl = null, $id_perush = null, $status = null, $id_karyawan = null)
    {
        $absen = self::join("s_perusahaan as s", "absensi.id_perush", "=", "s.id_perush")
        ->join("m_karyawan as k", "k.id_karyawan", "=", "absensi.id_karyawan")
        ->select("absensi.tgl_absen", "absensi.jam_datang", "absensi.jam_pulang", "absensi.jam_istirahat","absensi.jam_istirahat_masuk", "k.id_karyawan", "k.id_finger", "k.nm_karyawan", "s.nm_perush", "k.id_perush", "absensi.status_datang", "absensi.status_pulang", "absensi.status");
        
        if(isset($id_perush) and $id_perush!=null){
            $absen = $absen->where("absensi.id_perush", $id_perush);
        }
        
        if(isset($status) and $status!=null){
            $absen = $absen->where("k.is_aktif", $status);
        }
        
        if(isset($id_karyawan) and $id_karyawan!=null){
            $absen = $absen->where("k.id_karyawan", $id_karyawan);
        }
        
        if(isset($dr_tgl) and $dr_tgl!=null){
            $absen = $absen->where("absensi.tgl_absen" ,">=", $dr_tgl);
        }
        
        if(isset($sp_tgl) and $sp_tgl!=null){
            $absen = $absen->where("absensi.tgl_absen" ,"<=", $sp_tgl);
        }
        
        $absen = $absen->orderBy("absensi.tgl_absen", "desc")->orderBy("k.nm_karyawan", "asc");
        
        return $absen;
    }
    
    public function jenis()
    {
        return $this->belongsTo('Modules\Kepegawaian\Entities\JenisPerijinan', 'id_jenis ', 'id_jenis ');
    }
    
    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }
    
    public function perush()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }
    
    public static function newlaporan($dr_tgl = null, $sp_tgl=null, $id_perush=null, $id_karyawan=null)
    {
        $sql = "select a.id_karyawan, m.total, f.minus, k.nm_karyawan from absensi a
        join m_karyawan k on a.id_karyawan = k.id_karyawan
        left join (
        select count(id_absen) as minus,id_karyawan from absensi where 
        ( status_datang = '3' OR status_pulang = '4' OR status_pulang = '5')
        and tgl_absen >= '".$dr_tgl."' and tgl_absen <= '".$sp_tgl."' and id_perush ='".$id_perush."' GROUP BY id_karyawan
        ) as f on a.id_karyawan = f.id_karyawan
        left join (
        select count(id_absen) as total,id_karyawan from absensi where 
        status_istirahat_masuk !='0' and (status_datang = '0' or status_datang ='2') and (status_pulang = '0' or status_pulang='5') 
        and tgl_absen >= '".$dr_tgl."' and tgl_absen <= '".$sp_tgl."' and id_perush ='".$id_perush."' GROUP BY id_karyawan
        ) as m on m.id_karyawan = a.id_karyawan
        where a.tgl_absen >= '".$dr_tgl."' and a.tgl_absen <= '".$sp_tgl."' and a.id_perush ='".$id_perush."' GROUP BY a.id_karyawan,k.nm_karyawan,m.total,f.minus;";
        $data = DB::select($sql);
        // dd($sql);
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan]["nm_karyawan"] = $value->nm_karyawan;
            $a_absen[$value->id_karyawan]["id_karyawan"] = $value->id_karyawan;
            $a_absen[$value->id_karyawan]["total"] = ($value->total*8)+($value->minus*4);
        }
        
        return $a_absen;
    }

    public static function KehadiranCabang($dr_tgl = null, $sp_tgl=null, $id_role =null ,$id_perush=null, $id_karyawan=null)
    {
        $sql = "select a.id_karyawan, m.minus, f.total, k.nm_karyawan from absensi a
        join
        (
            select k.id_karyawan,k.nm_karyawan,k.id_perush,k.id_jam_kerja from role_user r
            join m_karyawan k on r.id_perush = k.id_perush
            where r.id_role = '".$id_role."'
        ) as k on k.id_karyawan = a.id_karyawan
        left join (
        select count(id_absen) as total,id_karyawan from absensi where 
        status_istirahat_masuk ='0' and (status_datang = '0' or status_datang ='2') and (status_pulang = '0' or status_pulang='5')
        and tgl_absen >= '".$dr_tgl."' and tgl_absen <= '".$sp_tgl."' GROUP BY id_karyawan
        ) as f on a.id_karyawan = f.id_karyawan
        left join (
        select count(id_absen) as minus,id_karyawan from absensi where 
        status_istirahat_masuk !='0' and (status_datang = '0' or status_datang ='2') and (status_pulang = '0' or status_pulang='5') 
        and tgl_absen >= '".$dr_tgl."' and tgl_absen <= '".$sp_tgl."' GROUP BY id_karyawan
        ) as m on m.id_karyawan = a.id_karyawan
        where a.tgl_absen >= '".$dr_tgl."' and a.tgl_absen <= '".$sp_tgl."' ";
        
        $id_perush != null ? $sql.=" and a.id_perush ='".$id_perush."'":$sql;
        $sql .= " GROUP BY a.id_karyawan,k.nm_karyawan,m.minus,f.total;";
        
        $data = DB::select($sql);
        //dd($sql);
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan]["nm_karyawan"] = $value->nm_karyawan;
            $a_absen[$value->id_karyawan]["id_karyawan"] = $value->id_karyawan;
            $a_absen[$value->id_karyawan]["total"] = ($value->total*8)+($value->minus*8);
        }
        
        return $a_absen;
    }

    public static function getTerlambat($dr_tgl = null, $sp_tgl=null, $id_perush=null)
    {
        $sql = "select a.id_karyawan,sum(a.jam_datang-s.jam_terlambat) as jt from absensi a
        join m_karyawan k on a.id_karyawan = k.id_karyawan 
        join s_jam_kerja s on k.id_jam_kerja = s.id_setting 
        where a.status_datang = '2' and a.tgl_absen>='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' and a.id_perush ='".$id_perush."' group by a.id_karyawan";
        
        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->jt;
        }
        
        return $a_absen;
    }

    public static function getPulang($dr_tgl = null, $sp_tgl=null, $id_perush=null)
    {
        $sql = "select a.id_karyawan,sum(s.jam_pulang-a.jam_pulang) as jt from absensi a
        join m_karyawan k on a.id_karyawan = k.id_karyawan 
        join s_jam_kerja s on k.id_jam_kerja = s.id_setting 
        where a.status_pulang = '2' and a.tgl_absen>='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' and a.id_perush ='".$id_perush."' group by a.id_karyawan ";
        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->jt;
        }
        
        return $a_absen;
    }

    public static function getIstirahat($dr_tgl = null, $sp_tgl=null, $id_perush=null)
    {
        $sql = "select sum(a.jam_istirahat_masuk-(s.jam_istirahat_masuk+'00:15:00')) as d_istirahat_masuk,a.id_karyawan from absensi a
        join m_karyawan k on a.id_karyawan = k.id_karyawan 
        join s_jam_kerja s on k.id_jam_kerja = s.id_setting
        where  a.tgl_absen >= '".$dr_tgl."' and a.tgl_absen <= '".$sp_tgl."' and a.id_perush ='".$id_perush."' and a.status_istirahat_masuk ='1' group by a.id_karyawan";
        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->d_istirahat_masuk;
        }
        return $a_absen;
    }

    public static function getIstirahatCabang($dr_tgl = null, $sp_tgl=null, $id_role = null, $id_perush = null)
    {
        $sql = "select sum(a.jam_istirahat_masuk-(s.jam_istirahat_masuk+'00:15:00')) as d_istirahat_masuk,a.id_karyawan from absensi a
        join m_karyawan kr on a.id_karyawan = kr.id_karyawan 
        join s_jam_kerja s on kr.id_jam_kerja = s.id_setting
        where  a.tgl_absen >= '".$dr_tgl."' and a.tgl_absen <= '".$sp_tgl."' and a.status_istirahat_masuk ='1' ";
        $id_perush != null ? $sql .= " and a.id_perush ='".$id_perush."'":$sql;
        
        $sql .= " group by a.id_karyawan";

        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->d_istirahat_masuk;
        }
        return $a_absen;
    }

    public static function getTerlambatCabang($dr_tgl = null, $sp_tgl=null, $id_role = null, $id_perush=null)
    {
        $sql = "select a.id_karyawan,sum(a.jam_datang-s.jam_terlambat) as jt from absensi a
        join m_karyawan kr on a.id_karyawan = kr.id_karyawan
        join s_jam_kerja s on kr.id_jam_kerja = s.id_setting 
        where a.status_datang = '2' and a.tgl_absen>='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' ";
        $id_perush != null ? $sql .= " and a.id_perush ='".$id_perush."'": $sql;
            
        $sql .= " group by a.id_karyawan";

        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->jt;
        }
        
        return $a_absen;
    }

    public static function getPulangCabang($dr_tgl = null, $sp_tgl=null, $id_role = null, $id_perush=null)
    {
        $sql = "select a.id_karyawan,sum(s.jam_pulang-a.jam_pulang) as jt from absensi a
        join m_karyawan kr on a.id_karyawan = kr.id_karyawan
        join s_jam_kerja s on kr.id_jam_kerja = s.id_setting  
        where a.status_pulang = '2' and a.tgl_absen>='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."'  ";
        
        $id_perush != null ? $sql .= "  and a.id_perush ='".$id_perush."'":$sql;
        $sql .= " group by a.id_karyawan";
        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan] = $value->jt;
        }
        
        return $a_absen;
    }
    
    public static function getLaporan($dr_tgl = null, $sp_tgl=null, $id_perush=null, $id_karyawan=null)
    {
        $sql = "select count(a.id_absen) as absen,a.id_karyawan,k.nm_karyawan, p.nm_perush, j.nm_jenis from absensi a
        left join m_karyawan k on a.id_karyawan=k.id_karyawan
        left join m_jenis_karyawan j on k.id_jenis=j.id_jenis
        join s_perusahaan p on a.id_perush=p.id_perush
        where k.is_aktif ='true' ";
        
        if(isset($dr_tgl) and isset($sp_tgl)){
            $sql = $sql." and a.tgl_absen >='".$dr_tgl."' and a.tgl_absen<='".$sp_tgl."' ";
        }
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush='".$id_perush."' ";
        }
        
        if(isset($id_karyawan) and $id_karyawan!=null){
            $sql = $sql." and a.id_karyawan='".$id_karyawan."' ";
        }
        
        $sql = $sql." GROUP BY k.nm_karyawan, p.nm_perush, j.nm_jenis, a.id_karyawan order by k.nm_karyawan asc";
        
        $data = DB::select($sql);
        
        $a_absen = [];
        foreach($data as $key => $value){
            $a_absen[$value->id_karyawan]["nm_karyawan"] = $value->nm_karyawan;
            $a_absen[$value->id_karyawan]["absen"] = $value->absen;
            $a_absen[$value->id_karyawan]["id_karyawan"] = $value->id_karyawan;
        }
        
        return $a_absen;
    }
    
    public static function getStatistik($dr_tgl = null, $sp_tgl=null, $id_perush=null, $id_karyawan=null, $status = null)
    {
        $sql = "select count(a.id_absen) as absen,a.id_karyawan,k.nm_karyawan, p.nm_perush, j.nm_jenis from absensi a
        left join m_karyawan k on a.id_karyawan=k.id_karyawan
        left join m_jenis_karyawan j on k.id_jenis=j.id_jenis
        join s_perusahaan p on a.id_perush=p.id_perush ";
        
        if(isset($dr_tgl) and isset($sp_tgl)){
            $sql = $sql." where a.tgl_absen >='".$dr_tgl."' and a.tgl_absen<='".$sp_tgl."' ";
        }
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush='".$id_perush."' ";
        }
        
        if(isset($id_karyawan) and $id_karyawan!=null){
            $sql = $sql." and a.id_karyawan='".$id_karyawan."' ";
        }
        
        if(isset($status) and $status!=null){
            $sql = $sql." and k.is_aktif='".$status."' ";
        }
        
        $sql = $sql." GROUP BY k.nm_karyawan, p.nm_perush, j.nm_jenis, a.id_karyawan order by k.nm_karyawan asc";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getLaporanBulan($dr_tgl, $sp_tgl, $id_perush = null, $is_aktif = null)
    {
        $sql = "select a.id_karyawan, a.tgl_absen from absensi a
        join m_karyawan k on a.id_karyawan = k.id_karyawan
        where tgl_absen>='".$dr_tgl."' and  tgl_absen <='".$sp_tgl."' ";
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush ='".$id_perush."'";
        }
        
        if(isset($is_aktif) and $is_aktif!=null){
            $sql = $sql." and k.is_aktif ='".$is_aktif."'";
        }
        
        $sql = $sql."  GROUP BY a.tgl_absen, a.id_karyawan";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getFilterLaporanKehadiran($id_perush,$bulan,$tahun)
    {
        $sql = "
        select id_karyawan, tgl_absen from absensi
        where to_char(tgl_absen , 'MM')='".$bulan."'
        and to_char(tgl_absen , 'yyyy')='".$tahun."'
        and id_perush = '".$id_perush."'
        GROUP BY tgl_absen,id_karyawan;";
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getStatusDatang($dr_tgl, $sp_tgl, $id_perush = null, $id_karyawan = null)
    {
        $sql = "select
        CASE
        WHEN count(a.id_karyawan) < 0 THEN 0
        ELSE count(a.id_karyawan)
        END
        AS jumlah,
        a.id_karyawan, a.status_datang,m.nm_karyawan from absensi a
        join m_karyawan m on a.id_karyawan=m.id_karyawan  where m.is_aktif=true and (a.status_datang='2' or a.status_datang='3') and a.tgl_absen >='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' ";
        
        if(isset($id_karyawan)){
            $sql = $sql." and a.id_karyawan ='".$id_karyawan."' ";
        }
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush ='".$id_perush."' ";
        }
        
        $sql = $sql." group by a.id_karyawan, a.status_datang, m.nm_karyawan";
        
        $data = DB::select($sql);
        
        $a_status_d = [];
        foreach($data as $key => $value){
            if($value->id_karyawan!=null){
                $a_status_d[$value->id_karyawan][$value->status_datang] = $value;
            }
        }
        return $a_status_d;
    }
    
    public static function getStatusPulang($dr_tgl, $sp_tgl, $id_perush=null ,$id_karyawan = null)
    {
        $sql = "select
        CASE
        WHEN count(a.id_karyawan)-3 < 0 THEN 0
        ELSE count(a.id_karyawan)-3
        END
        AS jumlah,
        a.id_karyawan, a.status_pulang,m.nm_karyawan from absensi a
        join m_karyawan m on a.id_karyawan=m.id_karyawan  where m.is_aktif=true and (a.status_pulang='4' or a.status_pulang='5') and a.tgl_absen >='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' ";
        
        if(isset($id_karyawan)){
            $sql = $sql." and a.id_karyawan ='".$id_karyawan."' ";
        }
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush ='".$id_perush."' ";
        }
        
        $sql = $sql." group by a.id_karyawan, a.status_pulang, m.nm_karyawan";
        $data = DB::select($sql);
        
        $a_status_d = [];
        foreach($data as $key => $value){
            if($value->id_karyawan!=null){
                $a_status_d[$value->id_karyawan][$value->status_pulang] = $value;
            }
        }
        return $a_status_d;
    }

    public static function getStatusIstirahat($dr_tgl, $sp_tgl, $id_perush=null ,$id_karyawan = null)
    {
        $sql = "select
        CASE
        WHEN count(a.id_karyawan)-3 < 0 THEN 0
        ELSE count(a.id_karyawan)-3
        END
        AS jumlah,
        a.id_karyawan, a.status_pulang,m.nm_karyawan from absensi a
        join m_karyawan m on a.id_karyawan=m.id_karyawan  where m.is_aktif=true and a.status_istirahat_masuk!='0' and a.tgl_absen >='".$dr_tgl."' and a.tgl_absen <='".$sp_tgl."' ";
        
        if(isset($id_karyawan)){
            $sql = $sql." and a.id_karyawan ='".$id_karyawan."' ";
        }
        
        if(isset($id_perush) and $id_perush!=null){
            $sql = $sql." and a.id_perush ='".$id_perush."' ";
        }
        
        $sql = $sql." group by a.id_karyawan, a.status_pulang, m.nm_karyawan";
        $data = DB::select($sql);
        
        $a_status_d = [];
        foreach($data as $key => $value){
            if($value->id_karyawan!=null){
                $a_status_d[$value->id_karyawan] = $value;
            }
        }
        return $a_status_d;
    }
    
    public static function getStatus($id_karyawan = null)
    {
        $sql = "select count(id_karyawan) as jumlah, id_karyawan,status, status_datang, status_pulang  from absensi ";
        
        if(isset($id_karyawan)){
            $sql = $sql." where id_karyawan ='".$id_karyawan."' ";
        }
        
        $sql = $sql." group by id_karyawan,status, status_datang, status_pulang";
        
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function getLate($dr_tgl, $sp_tgl, $id_perush)
    {
        $sql = "select  id_karyawan,status,jam_datang,status_datang from absensi where id_perush='".$id_perush."'
        and status_datang='2' and tgl_absen >='".$dr_tgl."' and tgl_absen <='".$sp_tgl."'
        group by id_karyawan,status,jam_datang,status_datang";
        
        $data = DB::select($sql);
        $a_late = [];
        foreach($data  as $key => $value){
            $a_late[$value->id_karyawan][$value->status_datang] = $value;
        }
        
        return $a_late;
    }
    
    public static function getJam($id_perush = null)
    {
        $sql = "select jam_datang,jam_pulang,id_karyawan,status from absensi ";
        
        if(isset($id_perush) and $id_perush != null){
            $sql = $sql." where id_perush ='".$id_perush."' ";
        }
        
        $sql = $sql." group by jam_datang,jam_pulang,id_karyawan,status";
        
        $data = DB::select($sql);
        
        $a_status = [];
        foreach($data as $key => $value){
            if($value->id_karyawan!=null){
                $a_status[$value->id_karyawan][$value->status] = $value;
            }
        }
        
        return $a_status;
    }
    
    public static function AllMesinFinger()
    {
        $sql = "SELECT m.authorization, m.cloud_id FROM s_mesin_finger m GROUP BY m.authorization, m.cloud_id;";
        $data = DB::select($sql);
        
        return $data;
    }
    
    public static function AllKaryawan()
    {
        $sql = "
        SELECT A
        .id_karyawan,
        A.id_mesin,
        A.is_sopir,
        A.id_finger,
        A.id_perush,
        b.cloud_id,
        C.jam_masuk,
        c.jam_terlambat,
        C.jam_pulang,
        C.jam_istirahat,
        C.jam_istirahat_masuk,
        c.jam_toleransi,
        c.jam_sabtu,
        A.id_jam_kerja
        FROM
        m_karyawan
        AS A JOIN s_mesin_finger AS b ON A.id_mesin = b.id_mesin
        JOIN s_jam_kerja AS C ON A.id_jam_kerja = C.id_setting;
        ";
        $data = DB::select($sql);
        
        return $data;
    }
}
