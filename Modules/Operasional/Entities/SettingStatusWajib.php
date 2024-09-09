<?php

namespace Modules\Operasional\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingStatusWajib extends Model
{
    protected $fillable = [];
    protected $table = "s_status_wajib";
	protected $primaryKey = 'id_setting';

    public static  function getStatusWajib($id_perush){
        $sql = " select
        s.id_status,s.id_setting,p.nm_perush,u.username,b.nm_ord_stt_stat,s.type
        from s_status_wajib s 
        join 
        (
            select id_ord_stt_stat,nm_ord_stt_stat,id_status 
            from m_ord_stt_stat where id_status is not null
        ) as b on s.id_status = b.id_ord_stt_stat::INTEGER
        join s_perusahaan p on p.id_perush=s.id_perush
        join users u on u.id_user=s.id_user
        where s.id_perush='".$id_perush."'  order by s.id_status asc";

        $data = DB::select($sql);

        return $data;
    }

    public static function getStt($id_perush, $dr_tgl, $sp_tgl){
        $sql = " select o.*,asal.label as wil_asal,tujuan.label as wil_tujuan,coalesce(total) as total from t_order o
        join (
            SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label, r.id_wil
            FROM m_wilayah r
            left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
            ) as asal on asal.id_wil = o.pengirim_id_region
        join (
            SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label,r.id_wil FROM m_wilayah r
            left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
        ) as tujuan on tujuan.id_wil = o.penerima_id_region
        left join (
            select id_stt, count(id_status) as total from t_history_stt where id_perush='".$id_perush."' group by id_stt
        ) as s on s.id_stt=o.id_stt
        where o.id_perush_asal='".$id_perush."' and o.tgl_masuk >='".$dr_tgl."' 
        and o.tgl_masuk<='".$sp_tgl."' ";
        
        $data = DB::select($sql);

        return $data;
    }
    
    public static function getSttPenerima($id_perush, $dr_tgl, $sp_tgl){
        $sql = "select t.*,coalesce(t1.total,0) as total1,coalesce(t2.total,0) as total2 from t_dm d 
        join t_order_dm s on s.id_dm=d.id_dm 
        join (
                select o.*,asal.label as wil_asal,tujuan.label as wil_tujuan,coalesce(total) as total from t_order o
                join (
                        SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label, r.id_wil
                        FROM m_wilayah r
                        left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
                        ) as asal on asal.id_wil = o.pengirim_id_region
                join (
                        SELECT r.id_wil as value ,concat(r.nama_wil, ', ' , prov.nama_wil) as label,r.id_wil FROM m_wilayah r
                        left JOIN m_wilayah prov ON r.prov_id = prov.id_wil
                ) as tujuan on tujuan.id_wil = o.penerima_id_region
                left join (
                        select id_stt, count(id_status) as total from t_history_stt group by id_stt
                ) as s on s.id_stt=o.id_stt
                where o.tgl_masuk>='".$dr_tgl."' and o.tgl_masuk<='".$sp_tgl."'
            ) as t on t.id_stt=s.id_stt
            join (
                select (
                            CASE 
                                WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='1') THEN
                                        1
                                ELSE
                                        0
                                END
                ) as total,h.id_stt from t_history_stt h GROUP BY h.id_stt
            ) as t1 on t1.id_stt=s.id_stt
            join (
                select (
                            CASE 
                                WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='2') THEN
                                        1
                                ELSE
                                        0
                                END
                ) as total,h.id_stt from t_history_stt h GROUP BY h.id_stt
            ) as t2 on t2.id_stt=s.id_stt
            where d.id_perush_tj='".$id_perush."'";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getDetailStatusWajib($id_perush, $dr_tgl, $sp_tgl, $type){
        $sql = "select o.id_stt,c.total from t_order o 
        join (
            select (
                    CASE 
                    WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='".$type."') THEN
                        1
                    ELSE
                        0
                    END
            ) as total,h.id_stt
                from t_history_stt h
            where h.id_perush='".$id_perush."'
            GROUP BY h.id_stt
        ) as c on c.id_stt=o.id_stt
        where o.id_perush_asal>='".$id_perush."' and o.tgl_masuk>='".$dr_tgl."' and o.tgl_masuk<='".$sp_tgl."' ";

        $data = DB::select($sql);
        $a_data = [];
        
        foreach($data as $key => $value){
            $a_data[$value->id_stt] = $value;
        }

        return $a_data;
    }

    public static function getStatus(){
        $sql = "select id_ord_stt_stat,nm_ord_stt_stat,id_status 
        from m_ord_stt_stat where id_status is not null order by id_ord_stt_stat asc ";
            
        $data = DB::select($sql);

        return $data;
    }

    public static function getTotalPengirim($id_perush, $dr_tgl, $sp_tgl){
        $sql = "select count(o.id_stt) as stt,sum(b.status) as status,sum(coalesce(d.total)) as total1,sum(coalesce(e.total)) as total2 from t_order o 
        left join (
                select count(id_status) as status,id_stt from t_history_stt where id_perush='".$id_perush."' GROUP BY id_stt
        ) as b on o.id_stt=b.id_stt
        left join (
            select (
                CASE 
                    WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='1') THEN
                        1
                    ELSE
                        0
                    END
            ) as total,h.id_stt
                from t_history_stt h
            where h.id_perush='".$id_perush."'
            GROUP BY h.id_stt
        ) as d on d.id_stt=o.id_stt
        join (
            select (
                CASE 
                    WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='2') THEN
                        1
                    ELSE
                        0
                    END
            ) as total,h.id_stt
                from t_history_stt h
            where h.id_perush='".$id_perush."'
            GROUP BY h.id_stt
        ) as e on e.id_stt=o.id_stt
        where o.id_perush_asal='".$id_perush."' and o.tgl_masuk>='".$dr_tgl."' and o.tgl_masuk<='".$sp_tgl."' ";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getTotalPenerima($id_perush, $dr_tgl, $sp_tgl){
        $sql = "select d.id_perush_dr,p.nm_perush,
        count(s.id_stt) as stt,coalesce(h.total, 0) as status,coalesce(t1.total,0) as total1,coalesce(t2.total,0) as total2
        from t_dm d 
        join t_order_dm s on s.id_dm=d.id_dm 
        join (
            select id_stt from t_order where tgl_masuk>='".$dr_tgl."' and tgl_masuk<='".$sp_tgl."'
        ) as o on o.id_stt=s.id_stt
        join (
            select id_stt,count(id_status) as total from t_history_stt GROUP BY id_stt
        ) as h on h.id_stt=s.id_dm
        join (
            select (
                        CASE 
                                WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='1') THEN
                                        1
                                ELSE
                                        0
                                END
                ) as total,h.id_stt
                        from t_history_stt h
                GROUP BY h.id_stt
        ) as t1 on t1.id_stt=s.id_stt
        join (
            select (
                        CASE 
                                WHEN count(h.id_status)=(select count(id_status) from s_status_wajib where id_perush='".$id_perush."'  and type='2') THEN
                                        1
                                ELSE
                                        0
                                END
                ) as total,h.id_stt
                        from t_history_stt h
                GROUP BY h.id_stt
        ) as t2 on t2.id_stt=s.id_stt
        join s_perusahaan p on p.id_perush=d.id_perush_dr
        where d.id_perush_tj='".$id_perush."' 
        GROUP BY d.id_perush_dr,h.total,t1.total,t2.total,p.nm_perush ";
        
        $data = DB::select($sql);

        return $data;
    }

    public static function getDataFix($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT SUM
            ( total_stt ) AS total_stt,
            SUM ( total_status_ok_pengirim ) AS total_status_ok_pengirim,
            SUM ( total_status_ok_penerima ) AS total_status_ok_penerima,
            SUM ( total_status ) AS total_status 
        FROM
            (
            SELECT COUNT
                ( id_stt ) AS total_stt,
                SUM ( statusnya ) AS total_status_ok_pengirim,
                0 total_status_ok_penerima,
                0 AS total_status 
            FROM
                (
                SELECT A
                    .id_stt,
                CASE
                    WHEN B.total_status >= ( SELECT COUNT ( DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '1' ) THEN
                        1 ELSE 0 
                    END AS statusnya 
                FROM
                    t_order
                    A LEFT JOIN (
                    SELECT DISTINCT
                        id_stt,
                        COUNT ( DISTINCT id_status ) AS total_status 
                    FROM
                        t_history_stt 
                    WHERE
                        id_status :: INTEGER IN ( SELECT id_status FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '1' ) 
                    GROUP BY
                        id_stt 
                    ) B ON A.id_stt = B.id_stt 
                WHERE
                    A.id_perush_asal = {$id_perush} 
                    AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                    AND '{$sp_tgl}' 
                ) AS Q UNION
            SELECT
                0 AS total_stt,
                0 AS total_status_ok_pengirim,
                SUM ( statusnya ) AS total_status_penerima,
                0 AS total_status 
            FROM
                (
                SELECT A
                    .id_stt,
                CASE
                    WHEN B.total_status >= ( SELECT COUNT ( DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '2' ) THEN
                        1 ELSE 0 
                    END AS statusnya 
                FROM
                    t_order
                    A LEFT JOIN (
                    SELECT DISTINCT
                        id_stt,
                        COUNT ( DISTINCT id_status ) AS total_status 
                    FROM
                        t_history_stt 
                    WHERE
                        id_status :: INTEGER IN ( SELECT id_status FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '2' ) 
                    GROUP BY
                        id_stt 
                    ) B ON A.id_stt = B.id_stt 
                WHERE
                    A.id_perush_asal = {$id_perush} 
                    AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
                    AND '{$sp_tgl}' 
                ) X UNION
            SELECT
                0 AS total_stt,
                0 AS total_status,
                0 AS total_status_pengirim,
                COUNT ( * ) 
            FROM
                t_history_stt B
                JOIN t_order A ON B.id_stt = A.id_stt 
            WHERE
                A.id_perush_asal = {$id_perush} 
                AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}' 
            ) AS Q
        ";

        // dd($sql);
        $data = DB::select($sql)[0];
        return $data;
    }

    public static function getDetailFix($id_perush, $dr_tgl, $sp_tgl)
    {
        $sql = "
        SELECT A
            .id_stt,
            A.kode_stt,
            A.tgl_masuk,
            A.pengirim_nm,
            A.penerima_alm,
            C.total,
            ( SELECT COUNT (DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '1' ) AS status_wajib_pengirim,
        CASE
            WHEN B.total_status >= ( SELECT COUNT (DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '1' ) THEN
                1 ELSE 0 
            END AS status_ok_pengirim,
            ( SELECT COUNT (DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '2' ) AS status_wajib_penerima,
        CASE
            WHEN D.total_status >= ( SELECT COUNT (DISTINCT id_status ) FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '2' ) THEN
                1 ELSE 0 
            END AS status_ok_penerima 
        FROM
            t_order
            A LEFT JOIN ( SELECT id_stt, COUNT ( id_history ) AS total FROM t_history_stt GROUP BY id_stt ) C ON A.id_stt = C.id_stt
            LEFT JOIN (
                SELECT DISTINCT
                    id_stt,
                    COUNT ( DISTINCT id_status ) AS total_status 
                FROM
                    t_history_stt 
                WHERE
                    id_status :: INTEGER IN ( SELECT id_status FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '1' ) 
                GROUP BY
                    id_stt 
            ) B ON A.id_stt = B.id_stt
            LEFT JOIN (
                SELECT DISTINCT
                    id_stt,
                    COUNT ( DISTINCT id_status ) AS total_status 
                FROM
                    t_history_stt 
                WHERE
                    id_status :: INTEGER IN ( SELECT id_status FROM s_status_wajib WHERE id_perush = {$id_perush} AND TYPE = '2' ) 
                GROUP BY
                    id_stt 
            ) D ON A.id_stt = D.id_stt 
        WHERE
            A.id_perush_asal = {$id_perush} 
            AND A.tgl_masuk BETWEEN '{$dr_tgl}' 
            AND '{$sp_tgl}'
        ";

        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }
}
