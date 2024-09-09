<div class="row">
	<div class="col-md-12 text-right">
		
		@if(isset($data->id_status) and $data->id_status==2)
		
		@endif
		
		@if(isset($data->id_status) and $data->id_status==1)
		<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/stt' }}" class="btn btn-sm btn-primary">
			<i class="fa fa-plus"></i> Tambah RESI
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
		@php
			$urls = "dr_tgl=".$filter["dr_tgl"]."&sp_tgl=".$filter["sp_tgl"]."&status=".$filter["status"]."&shareselect=".$filter["page"]."";
		@endphp
		<a href="{{ url(Request::segment(1)."/".$data->id_invoice."/cetak") }}" class="btn btn-sm btn-success"><i class="fa fa-print"></i> Print</a>
		<a href="{{ url(Request::segment(1)."?".$urls) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	</div>
	@php
	$ldate = date('Y-m-d H:i:s')
	@endphp
</div>