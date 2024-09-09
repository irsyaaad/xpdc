
@extends('template.document')

@section('data')

<div class="row">
	<div class="col-md-12">
		<div class="form-group text-right">
			<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/show' }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		</div>
		<table class="table">
			<tr>
				<td>Nomor STT : <b>{{ strtoupper($data->kode_stt) }}</b></td>
				<td>Perusahaan Pengirim : <b>{{ $data->perush_asal->nm_perush }}</b></td>
				<td>Layanan : <b>{{ strtoupper($data->layanan->nm_layanan) }}</b></td>
			</tr>
			<tr>
				@php
				$muat = count($koli);
				$sisa = $data->n_koli - $muat;
				@endphp
				<td>Tanggal Masuk : <b>{{ dateindo($data->tgl_masuk) }}</b></td>
				<td>Koli Termuat : <b>{{ $muat }}</b></td>
				<td>Koli Tersisa : <b>{{ $sisa }}</b></td>
			</tr>
		</table>
	</div>
</div>

<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table tabl-hover table-responsive" width="100%" style="margin-top: 2%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Kode Koli</th>
				<th>Koli Ke</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@if(isset($koli))
			@foreach($koli as $key => $value)
			<tr>
				<td>{{ ($key+1) }}</td>
				<td>{{ $value->id_koli }}</td>
				<td>{{ $value->koli->no_koli }}</td>
				<td>
					@if($data->ata==null and $data->atd==null)
					<input type="hidden" name="id_koli" id="id_koli" value="{{ $value->koli->id_koli }}">
					<a href="#" class="btn btn-sm btn-danger" onclick="CheckDelete('{{ url('dmtrucking/'.$value->id_dm_koli.'/deletekoli') }}')" > <span><i class="fa fa-times"></i></span></a>
					@endif
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</form>
@endsection