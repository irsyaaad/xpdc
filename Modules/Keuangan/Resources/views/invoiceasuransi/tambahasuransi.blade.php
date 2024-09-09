<div class="row">
	<div class="col-md-12 text-right">
		
		@if(isset($data->id_status) and $data->id_status==2)
		
		@endif
		
		@if(isset($data->id_status) and $data->id_status==1)
		<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/asuransi' }}" class="btn btn-sm btn-primary">
			<i class="fa fa-plus"></i> Tambah Asuransi
		</a>
		@endif
		
		@if(isset($data->id_status) and $data->id_status==1)
		<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/send' }}" class="btn btn-sm btn-success">
			<i class="fa fa-send"></i> Terbitkan
		</a>
		@endif
		
		{{-- @if(isset($data->id_status) and $data->id_status==2)
		<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/bayarAll' }}" class="btn btn-sm btn-success">
			<i class="fa fa-money"></i> Bayar Invoice
		</a>
		@endif --}}
		
		<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	</div>
	@php
	$ldate = date('Y-m-d H:i:s')
	@endphp
</div>