@extends('template.document')

@section('data')
<form action="{{ url('invoice').'/'.Request::segment(2).'/savedraft' }}" method="POST">
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
	<br>
	<input type="text" class="form-control" id="search" placeholder="Type to search">
	<table class="table" id="table-stt" width="100%" style="margin-top: 1%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>Kode RESI</th>
				<th>Nama Pengirim</th>
				<th>Tujuan</th>
				<th>No Hp</th>
				<th>Layanan</th>
				<th>Omzet</th>
				<th class="text-center">
					<label><input type="checkbox" value="1" id="c_all" name="c_all"> <b>Pilih Semua</b></label>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $value->kode_stt }}</td>
				<td>{{ $value->pengirim_nm }}</td>
				<td>{{ $value->nama_wil}}</td>
				<td>{{ $value->pengirim_telp}}</td>
				<td>{{ $value->nm_layanan}}</td>
				<td>{{ toNumber($value->c_total) }}</td>
				<td><input id="id_stt" type="checkbox" name="stt_id[]" class="form-control c_koli" value="{{$value->id_stt}}"></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@endsection
@section("script")
<script type="text/javascript">

var $rows = $('#table-stt tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
	
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
