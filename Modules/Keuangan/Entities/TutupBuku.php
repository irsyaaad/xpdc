<?php

namespace Modules\Keuangan\Entities;
use DB;

use Illuminate\Database\Eloquent\Model;

class TutupBuku extends Model
{
    protected $fillable = [];
    protected $table = "tutup_buku";
    protected $primaryKey = 'id';

    public static function getData($id_perush, $tahun)
    {
        $sql = "
        select a.id_ac,a.id_ac, a.nama, a.level,a.id_parent,b.parent,
        b.id_ac as ac_perush, 
        b.nama as nama_ac_perush,
		
        b.debit as debit,
        b.kredit as kredit,
        b.total as total
        from m_ac as a
        left join
        ( 
            select a.id_ac, a.nama, a.parent,
            coalesce(keu_pendapatan.total,0) as pend,
            coalesce(keu_pendapatan_det.tot_pend,0) as pend_det,
            coalesce(keu_pengeluaran.total,0) as peng,
            coalesce(keu_pengeluaran_det.tot_pend,0) as peng_det,
            coalesce(order_pay.byr_d,0) as pembayaran_d,
            coalesce(order_pay_k.byr_k,0) as pembayaran_k,

        --Handling Bayar
            coalesce(bayarhandling_piutang_pengirim.pp,0) as piutang_pengirim,
            coalesce(bayarhandling_hutang_pengirim.hp,0) as hutang_pengirim,
            coalesce(bayarhandling_bayar_penerima.bp,0) as bayar_penerima,
            coalesce(bayarhandling_bayar_pengirim.bpg,0) as bayar_pengirim,
        --Handling Biaya
            coalesce(biayahandling_pend_penerima.nominal,0) as byhandling_pend_penerima,
            coalesce(biayahandling_piut_penerima.nominal,0) as byhandling_piut_penerima,
            coalesce(biayahandling_hutang_pengirim.nominal,0) as byhandling_hutang_penerima,
        --DM
            coalesce(dmd.dm_d,0) as dm_debit,
            coalesce(dmk.dm_k,0) as dm_kredit,
            --STT
            coalesce(pend.pendapatan,0) as pendapatan,
            coalesce(asur.asuransi,0) as asuransi,
            coalesce(dis.diskon,0) as diskon,
            coalesce(stt.ppn,0) as ppn,
			coalesce(piut_pel.p_pelanggan,0) as piutang_pelanggan,

        --Debit
        (
        coalesce(keu_pendapatan.total,0)
        + coalesce(keu_pengeluaran_det.tot_pend,0) 
        + coalesce(order_pay.byr_d,0) 
        + coalesce(dmd.dm_d,0) 
        + coalesce(pend.pendapatan,0)
        + coalesce(biayahandling_pend_penerima.nominal,0)
        + coalesce(biayahandling_piut_penerima.nominal,0)
        + coalesce(biayahandling_hutang_pengirim.nominal,0)
        + coalesce(asur.asuransi,0)
        + coalesce(dis.diskon,0)
        + coalesce(stt.ppn,0)
		+ coalesce(piut_pel.p_pelanggan,0)

        ) as debit,
        --Kredit
        (
        coalesce(keu_pendapatan_det.tot_pend,0)
        + coalesce(keu_pengeluaran.total,0)
        + coalesce(order_pay_k.byr_k,0)
        + coalesce(bayarhandling_piutang_pengirim.pp,0)
        + coalesce(bayarhandling_hutang_pengirim.hp,0)
        + coalesce(bayarhandling_bayar_penerima.bp,0) 
        + coalesce(bayarhandling_bayar_pengirim.bpg,0)
        + coalesce(dmk.dm_k,0)

        ) as kredit,

        --#Total
        --Pendapatan
        (
            coalesce(keu_pendapatan.total,0)
        + coalesce(keu_pengeluaran_det.tot_pend,0) 
        + coalesce(order_pay.byr_d,0) 
        + coalesce(dmd.dm_d,0) 
        + coalesce(pend.pendapatan,0)
        + coalesce(biayahandling_pend_penerima.nominal,0)
        + coalesce(biayahandling_piut_penerima.nominal,0)
        + coalesce(biayahandling_hutang_pengirim.nominal,0)
        + coalesce(asur.asuransi,0)
        + coalesce(dis.diskon,0)
        + coalesce(stt.ppn,0)
		+ coalesce(piut_pel.p_pelanggan,0)
            
            - coalesce(keu_pendapatan_det.tot_pend,0)
        - coalesce(keu_pengeluaran.total,0)
        - coalesce(order_pay_k.byr_k,0)
        - coalesce(bayarhandling_piutang_pengirim.pp,0)
        - coalesce(bayarhandling_hutang_pengirim.hp,0)
        + coalesce(bayarhandling_bayar_penerima.bp,0) 
        - coalesce(bayarhandling_bayar_pengirim.bpg,0)
        - coalesce(dmk.dm_k,0)
            
        ) as total
        from m_ac_perush as a left join
        (
            select id_ac, sum(c_total) as total from keu_pendapatan 
            where id_perush = '".$id_perush."'
            and tgl_masuk <= '".$tahun."-12-31'
            group by id_ac
        )as keu_pendapatan on a.id_ac = keu_pendapatan.id_ac left join
        ( 
            select a.id_ac, sum(total) as tot_pend from keu_pendapatan_det as a join
            keu_pendapatan as b on a.id_pendapatan = b.id_pendapatan
            where b.id_perush = '".$id_perush."'
			and b.tgl_masuk <= '".$tahun."-12-31'
            group by a.id_ac
        )as keu_pendapatan_det on a.id_ac = keu_pendapatan_det.id_ac left join
        (
            select id_ac, sum(c_total) as total from keu_pengeluaran 
            where id_perush = '".$id_perush."'
            and tgl_keluar <= '".$tahun."-12-31'
            group by id_ac
        )as keu_pengeluaran on a.id_ac = keu_pengeluaran.id_ac left join
        ( 
            select a.id_ac, sum(total) as tot_pend from keu_pengeluaran_det as a join
            keu_pengeluaran as b on a.id_pengeluaran = b.id_pengeluaran
            where b.id_perush = '".$id_perush."'
			and b.tgl_keluar <= '".$tahun."-12-31'
            group by a.id_ac
        )as keu_pengeluaran_det on a.id_ac = keu_pengeluaran_det.id_ac left join
        --Pembayaran
        ( 
            select ac4_d, sum(n_bayar) as byr_d from t_order_pay 
            where id_perush = '".$id_perush."'
			and tgl <= '".$tahun."-12-31'
            group by ac4_d
        )as order_pay on a.id_ac = order_pay.ac4_d left join
        ( 
            select ac4_k, sum(n_bayar) as byr_k from t_order_pay 
            where id_perush = '".$id_perush."'
			and tgl <= '".$tahun."-12-31'
            group by ac4_k
        )as order_pay_k on a.id_ac = order_pay_k.ac4_k left join

        --Biaya Handling
        ( 
            select ac4_pend_penerima, sum(nominal) as nominal from t_handling_biaya 
            where id_perush_penerima = '".$id_perush."'
			and created_at <= '".$tahun."-12-31'
            group by ac4_pend_penerima
        )as biayahandling_pend_penerima on a.id_ac = biayahandling_pend_penerima.ac4_pend_penerima left join
        ( 
            select ac4_piutang_penerima, sum(nominal) as nominal from t_handling_biaya 
            where id_perush_penerima = '".$id_perush."'
			and created_at <= '".$tahun."-12-31'
            group by ac4_piutang_penerima
        )as biayahandling_piut_penerima on a.id_ac = biayahandling_piut_penerima.ac4_piutang_penerima left join
        ( 
            select ac4_hutang_pengirim, sum(nominal) as nominal from t_handling_biaya 
            where id_perush_pengirim = '".$id_perush."'
			and created_at <= '".$tahun."-12-31'
            group by ac4_hutang_pengirim
        )as biayahandling_hutang_pengirim on a.id_ac = biayahandling_hutang_pengirim.ac4_hutang_pengirim left join
        --Bayar Handling
        ( 
            select ac4_piutang_penerima, sum(n_bayar) as pp from t_handling_biaya_bayar 
            where id_perush_penerima = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_piutang_penerima
        )as bayarhandling_piutang_pengirim on a.id_ac = bayarhandling_piutang_pengirim.ac4_piutang_penerima left join
        ( 
            select ac4_hutang_pengirim, sum(n_bayar) as hp from t_handling_biaya_bayar 
            where id_perush_pengirim = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_hutang_pengirim
        )as bayarhandling_hutang_pengirim on a.id_ac = bayarhandling_hutang_pengirim.ac4_hutang_pengirim left join
        ( 
            select ac4_bayar_penerima, sum(n_bayar) as bp from t_handling_biaya_bayar 
            where id_perush_penerima = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_bayar_penerima
        )as bayarhandling_bayar_penerima on a.id_ac = bayarhandling_bayar_penerima.ac4_bayar_penerima left join
        ( 
            select ac4_bayar_pengirim, sum(n_bayar) as bpg from t_handling_biaya_bayar 
            where id_perush_pengirim = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_bayar_pengirim 
        )as bayarhandling_bayar_pengirim on a.id_ac = bayarhandling_bayar_pengirim.ac4_bayar_pengirim left join
        --Biaya DM
        ( 
            select ac4_debit, sum(n_bayar) as dm_d from t_dm_biaya_bayar 
            where id_perush = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_debit
        )as dmd on a.id_ac = dmd.ac4_debit left join
        ( 
            select ac4_kredit, sum(n_bayar) as dm_k from t_dm_biaya_bayar 
            where id_perush = '".$id_perush."'
			and tgl_bayar <= '".$tahun."-12-31'
            group by ac4_kredit
        )as dmk on a.id_ac = dmk.ac4_kredit left join
        --t_Order
        ( 
            select c_ac4_pend, sum(c_total) as pendapatan from t_order 
            where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
            group by c_ac4_pend
        )as pend on a.id_ac=pend.c_ac4_pend left join
        ( 
            select c_ac4_asur, sum(n_asuransi) as asuransi from t_order 
            where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
            group by c_ac4_asur 
        )as asur on a.id_ac=asur.c_ac4_asur left join
        ( 
            select c_ac4_ppn, sum(n_ppn) as ppn from t_order 
            where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
            group by c_ac4_ppn 
        )as stt on a.id_ac=stt.c_ac4_ppn left join
        ( 
            select c_ac4_disc, sum(n_diskon) as diskon from t_order 
            where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
            group by c_ac4_disc
        )as dis on a.id_ac=dis.c_ac4_disc left join
        ( 
            select c_ac4_mat, sum(n_materai) as materai from t_order 
            where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
            group by c_ac4_mat 
        )as mat on a.id_ac=mat.c_ac4_mat left join
		(
			select c_ac4_piut, sum(c_total) as p_pelanggan from t_order
			where id_perush_asal = '".$id_perush."'
			and tgl_masuk <= '".$tahun."-12-31'
			group by c_ac4_piut
		)as piut_pel on a.id_ac=piut_pel.c_ac4_piut
            where a.id_perush = '".$id_perush."'
        )as b on a.id_ac = b.parent
        order by id_ac;
        ";

        $data = DB::select($sql);
		return $data;
    }
}
