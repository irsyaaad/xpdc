<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class ReportModel extends Model
{
    

    public static function getSttCaraBayar($tgl_awal, $tgl_akhir)
    {
        $sql = "select o.id_stt,o.tgl_masuk,p.nm_pelanggan, o.pengirim_nm,o.penerima_nm,o.c_total,o.x_n_bayar,o.x_n_piut,t.nm_tipe_kirim
        from t_order o join m_plgn p on o.id_plgn=p.id_pelanggan
        join d_tipe_kirim t on o.id_tipe_kirim=t.id_tipe_kirim 
        where tgl_masuk>='".$tgl_awal."' and tgl_masuk<='".$tgl_akhir."' and o.id_perush_asal='".Session("perusahaan")["id_perush"]."'";

        $data = DB::select($sql);

        return $data;
    }

    public static function getSttCash($id_perush, $tgl_awal, $tgl_akhir)
    {
        $sql = "
        SELECT
            o.id_stt,
            o.tgl_masuk,
            P.nm_pelanggan,
            o.pengirim_nm,
            o.penerima_nm,
            o.c_total,
            o.x_n_bayar,
            o.x_n_piut,
            T.nm_tipe_kirim 
        FROM
            t_order o
            JOIN m_plgn P ON o.id_plgn = P.id_pelanggan
            JOIN d_tipe_kirim T ON o.id_tipe_kirim = T.id_tipe_kirim 
        WHERE
            o.id_cr_byr_o = 1
            AND tgl_masuk >= '".$tgl_awal."' 
            AND tgl_masuk <= '".$tgl_akhir."' 
            AND o.id_perush_asal = '".$id_perush."'
        ";
        $data = DB::select($sql);

        return $data;
    }
}
