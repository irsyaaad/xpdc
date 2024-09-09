@extends('template.document')

@section('data')
<form action="{{ url('invoiceasuransi').'/'.Request::segment(2).'/savedraft' }}" method="POST">
	@csrf
	<div class="row" >
		<div class="col-md-12 text-right">
			<input type="hidden" name="id_invoice" id="id_invoice" value="{{Request::segment(2)}}">
			<button class="btn btn-sm btn-success" type="submit">
				<i class="fa fa-save"></i> Tambah
			</button>
			<a href="{{ url(Request::segment(1).'/'.Request::segment(2)."/show") }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		</div>
	</div>
	<table class="table" id="html_table" width="100%" style="margin-top: 1%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>Kode STT</th>
				<th>Pelanggan</th>
				<th>Pengirim</th>
				<th>Harga Pertanggungan</th>
				<th>Nominal</th>
				<th class="text-center">
					<label><input type="checkbox" value="1" id="c_all" name="c_all"> <b>Pilih Semua</b></label>
				</th>
			</tr>
		</thead>
		<tbody>
            {{-- {{dd($data)}} --}}
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $value->id_stt }}</td>
				<td>{{ $value->pelanggan->nm_perush }}</td>
				<td>{{ $value->nm_pengirim}}</td>
				<td>Rp. {{ number_format($value->harga_pertanggungan, 0, ',', '.') }}</td>
				<td>Rp. {{ number_format($value->nominal, 0, ',', '.') }}</td>
				<td><input id="id_stt" type="checkbox" name="stt_id[]" class="form-control c_koli" value="{{$value->id_asuransi}}"></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@endsection
@section("script")
<script type="text/javascript">
	
	$(function(){
		$('#c_all').change(function()
		{
			if($(this).is(':checked')) {
				$(".c_koli").prop("checked", true);
			}else{
				$(".c_koli").prop("checked", false);
			}
		});
	});
</script>
@endsection
