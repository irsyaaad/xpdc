<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;
use DB;

class Perusahaan extends Model
{
    protected $table = "s_perusahaan";
    protected $primaryKey = 'id_perush';

    public function wilayah()
    {
        return $this->belongsTo('App\Models\Wilayah', 'id_region', 'id_wil');
    }

    public function induk()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'cabang', 'id_perush');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\CabangGroup', 'id_cab_group', 'id_cabgroup');
    }

    public static function getPerusahaan()
    {
        $data = self::select("id_perush", "nm_perush")->get();

        return $data;
    }

    public static function getData()
    {
        $data = self::select("id_perush", "nm_perush","alamat", "telp", "provinsi", "kode_perush")->get();

        return $data;
    }

    public static function getDataExept(){
        $data = self::distinct()->select("s_perusahaan.id_perush", "s_perusahaan.nm_perush","s_perusahaan.alamat", "s_perusahaan.telp", "s_perusahaan.provinsi", "s_perusahaan.kode_perush")
        ->join('role_user', 'role_user.id_perush', '=', 's_perusahaan.id_perush')
        ->where("s_perusahaan.id_perush", "!=", Session("perusahaan")["id_perush"])
        ->where("role_user.id_user", "=", Auth::user()->id_user)
        ->get();

        return $data;
    }
    public static function getDevisi()
    {
        $devisi = [];
        $devisi["1"] = "Lsj Surabaya Container";
        $devisi["2"] = "Lsj Surabaya Utama";
        $devisi["3"] = "Lsj Surabaya Madya";
        $devisi["4"] = "Lsj Surabaya Transjawa";
        $devisi["5"] = "Lsj Surabaya Online";
        $devisi["6"] = "Lsj Surabaya LCL";

        return $devisi;
    }

    public static function getPerush($id_perush)
    {
        $sql = "select  DISTINCT(s.id_perush) as id_perush,s.nm_perush,s.alamat, s.telp, s.provinsi, s.kotakab, s.email, s.nm_dir,s.telp_cs,s.nm_cs from role_user r
        join s_perusahaan  s on s.id_perush=r.id_perush
        where s.id_perush='".$id_perush."' ";

        $data = DB::select($sql);

        return $data;
    }
    
    public static function getKodePerush($id_perush = null)
    {
        $sql = "select  DISTINCT(s.id_perush) as id_perush,s.nm_perush,s.alamat, s.telp, s.provinsi, s.kotakab, s.email, s.nm_dir,s.telp_cs,s.nm_cs from role_user r
        join s_perusahaan  s on s.id_perush=r.id_perush ";

        if($id_perush != null){
            $sql.= " where s.kode_perush='".$id_perush."'";
        }

        $data = DB::select($sql);

        return $data;
    }
    
    public static function getRoleUser()
    {
        $sql = "select s.id_perush,s.nm_perush from role_user r
        join s_perusahaan  s on s.id_perush=r.id_perush
        where r.id_user='".Auth::user()->id_user."' and r.id_role='".Session("role")["id_role"]."'
        GROUP BY s.id_perush,s.nm_perush";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function forFilter()
    {
        $sql = "
        SELECT A
            .id_region,
            b.nama_wil
        FROM
            s_perusahaan
            AS A JOIN m_wilayah AS b ON A.id_region = b.id_wil
        GROUP BY
            A.id_region,
            b.nama_wil
        ";

        $data = DB::select($sql);

        return $data;
    }
}
