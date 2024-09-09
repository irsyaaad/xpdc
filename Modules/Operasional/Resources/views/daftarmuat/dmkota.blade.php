@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<input type="hidden" name="_method" value="GET">
	<div class="row mt-0">
		<div class="col-md-12">
			<table class="table table-responsive table-striped">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>No. DM</th>
						<th>Kota Tujuan</th>
						<th>Armada / Sopir</th>
						<th>Tgl Dm</th>
						<th>Tgl Berangkat</th>
						<th>Jumlah STT</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td width="3%">{{ ($key+1) }}</td>
						<td>
							<a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/show' }}" class="class-edit">{{ strtoupper($value->kode_dm) }}</a>
						</td>			
						<td>{{ strtoupper($value->nama_wil) }}</td>
						<td>{{ $value->nm_armada. " - ".$value->nm_sopir }}</td>
						<td>{{ dateindo($value->created_at) }}</td>
						<td>{{ dateindo($value->tgl_berangkat) }}</td>
						<td>{{ $value->stt }}</td>
						<td>
							@if(isset($value->nm_status) and $value->id_status == "1")
							<span class="label label-info label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@elseif(isset($value->nm_status) and $value->id_status == "5")
							<span class="label label-success label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@else
							<span class="label label-primary label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@endif
						</td>
						<td>
							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									@if($value->id_status==1)
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
									<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_dm) }}')"><i class="fa fa-trash"></i> Hapus</a>
									@endif
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/show") }}"><i class="fa fa-eye"></i> Detail</a>
									<a class="dropdown-item" href="{{route('updatestatus',$value->id_dm)}}" target="_blank" rel="nofollow"><i class="fa fa-edit"></i> Update Status</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					@if(count($data) < 1)
					<tr>
						<td colspan="9" class="text-center">Data Kosong</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		@include('template.paginator')
	</div>
</form>

@include('operasional::daftarmuat.confirm')
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('dmkota') }} @else{{ url('dmkota', $data->id_dm) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">
		@include('operasional::daftarmuat.createdmkota')
	</div>
</form>
@endif
@endsection

@if(Request::segment(2)==null or Request::segment(2)=="filter")
@section('script')
<script type="text/javascript">
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	$('#id_wil_tujuan').select2({
		placeholder: 'Cari Nama Kota ....',
		ajax: {
			url: '{{ url('getKota') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_wil_tujuan').empty();
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.value,
							id: item.kode
						}
					})
				};
			},
			cache: true
		}
	});
	
	$('#id_dm').select2({
		placeholder: 'Cari Nomor DM Kota - Kota ....',
		ajax: {
			url: '{{ url('dmkota/getdm') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_dm').empty();
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.value,
							id: item.kode
						}
					})
				};
			},
			cache: true
		}
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	@if(isset($filter["id_sopir"]))
	$("#id_sopir").val('{{ $filter["id_sopir"] }}');
	@endif
	
	@if(isset($filter["id_armada"]))
	$("#id_armada").val('{{ $filter["id_armada"] }}');
	@endif
	
	@if(isset($filter["id_status"]))
	$("#id_status").val('{{ $filter["id_status"] }}');
	@endif
	
	@if(isset($filter["id_wil_tujuan"]->nama_wil))
	$("#id_wil_tujuan").empty();
	$("#id_wil_tujuan").append('<option value="{{ $filter["id_wil_tujuan"]->id_wil }}">{{ strtoupper($filter["id_wil_tujuan"]->nama_wil) }}</option>');
	@endif
	
	@if(isset($filter["id_dm"]->kode_dm))
	$("#id_dm").empty();
	$("#id_dm").append('<option value="{{ $filter["id_dm"]->id_dm }}">{{ strtoupper($filter["id_dm"]->kode_dm) }}</option>');
	@endif
	
	
</script>
@endsection
@endif