<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Cetak</title>
    
</head>\

<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Laporan STT.xls");
?>

<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
    <table class="table table-bordered table-sm" style=" margin-top:10px; font-size:12px;">
    <thead class="text-center">
			<tr>
			<th>No</th>
			<th>No. STT</th>
			<th>Perusahaan</th>
			<th>Layanan</th>
			<th>Masuk</th>
			<th>Pengirim</th>
			<th>Asal</th>
			<th>Penerima</th>
			<th>Tujuan</th>
			<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
            <td class="text-center">{{$key+1}}</td>
			<td>
                {{ strtoupper($value->id_stt) }}
			</td>
			<td>
				@if(isset($value->perush_asal)){{ $value->perush_asal->nm_perush }}@endif
			</td>
			<td>
				@if(isset($value->layanan)){{ $value->layanan->nm_layanan }}@endif
			</td>
			<td>
				{{ dateindo($value->tgl_masuk) }}
			</td>
			<td>
				{{ $value->pengirim_nm }}
			</td>
			<td>
				@if(isset($value->asal)){{ $value->asal->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				{{ $value->penerima_nm }}
			</td>
			<td>
				@if(isset($value->tujuan)){{ $value->tujuan->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				@if(isset($value->status->nm_ord_stt_stat)){{ $value->status->nm_ord_stt_stat }}@endif
			</td>
			</tr>
			@endforeach
		</tbody>
    </table>
</body>
</html>