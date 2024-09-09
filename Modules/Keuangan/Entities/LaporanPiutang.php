<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class LaporanPiutang extends Model
{
	protected $fillable = [];
	function OmsetVsCashin() 
	{
		$sql = "
		SELECT EXTRACT(MONTH FROM hasil.tgl_masuk) as bulan, 
		EXTRACT(YEAR FROM hasil.tgl_masuk) as tahun, 
		SUM(hasil.omset) as omset, 
		SUM(hasil.pembayaran) as bayar, 
		SUM (hasil.tunai) as tunai, 
		SUM (hasil.invoice) as invoice
		
		FROM (
		SELECT id_stt, tgl_masuk, id_perush_asal, 
		c_total as omset, 0 as pembayaran, 
		0 as tunai,
		0 as invoice
		FROM t_order
		UNION 
		SELECT id_order_pay, tgl, id_perush, 
		0 as omset , n_bayar as pembayaran, 
		0 as tunai,
		0 as invoice 
		FROM t_order_pay
		UNION
		SELECT t_order.id_stt, t_order_pay.tgl,
		t_order_pay.id_perush,
		0 as omset,
		0 as pembayaran,
		CASE WHEN t_order.tgl_masuk = t_order_pay.tgl THEN t_order_pay.n_bayar ELSE	0 END AS tunai,
		CASE WHEN t_order.tgl_masuk <> t_order_pay.tgl THEN t_order_pay.n_bayar ELSE	0 END AS invoice
		FROM t_order
		JOIN t_order_pay ON t_order.id_stt = t_order_pay.id_stt
		) as hasil
		WHERE hasil.id_perush_asal = '29'
		
		GROUP BY bulan, tahun";
	}
	
	function SaldoAwalOmsetVsCashIn() {
		$sql = "
		SELECT EXTRACT(MONTH FROM query.tgl) as bulan, 
		EXTRACT(YEAR FROM query.tgl) as tahun, 
		SUM (query.total) AS total, 
		SUM (query.bayar) As bayar
		FROM
		(
		SELECT 
		A.id_perush_asal AS id_perush,
		A.id_stt NO_BUKTI,
		A.id_plgn,
		A.tgl_masuk TGL,
		A.c_total TOTAL,
		0 AS BAYAR
		FROM
		t_order A
		UNION ALL
		SELECT
		A.id_perush,
		A.id_order_pay,
		B.id_plgn,
		A.TGL,
		0 AS TOTAL,
		A.n_bayar AS BAYAR
		FROM
		t_order_pay A
		LEFT OUTER JOIN T_ORDER B ON (A.ID_STT = B.ID_STT)
		) query
		WHERE query.id_perush = '29'
		GROUP BY bulan, tahun
		";
	}
}
