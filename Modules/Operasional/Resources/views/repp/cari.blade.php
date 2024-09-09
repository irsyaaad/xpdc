<div class="col-md-12 text-right">

	@if(isset($data))
	<a href="{{ route('data', [
		'id_perush_asal' => $pengirim_perush, 
		'id_perush_tujuan' => $penerima_perush, 
		'pengirim_id_region' => $pengirim_id_region, 
		'id_layanan' => $id_layanan,
		'tgl_masuk' => $tgl_masuk
		]) }}" class="btn btn-md btn-warning"><i class="fa fa-print"></i>  HTML</a>
	<a href="{{ route('excel', [
		'id_perush_asal' => $pengirim_perush, 
		'id_perush_tujuan' => $penerima_perush, 
		'pengirim_id_region' => $pengirim_id_region, 
		'id_layanan' => $id_layanan,
		'tgl_masuk' => $tgl_masuk
		]) }}" class="btn btn-md btn-success"><i class="fa fa-print"></i>  EXCEL</a>
	@endif
	<button class="btn btn-md btn-accent" type="submit">
		<span><i class="fa fa-search"></i></span> Cari
	</button>
</div>