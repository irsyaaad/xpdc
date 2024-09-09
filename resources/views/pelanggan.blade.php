@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-striped table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Perusahaan Asal</th>
						<th>Group Pelanggan</th>
						<th>Alamat</th>
						<th>Telp</th>
						<th>Email</th>
						<th>Limit Piutang</th>
						<th>Aktif ?</th>
						<th>Is User ?</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $value->nm_pelanggan }}</td>
						<td>@if(isset($value->nm_perush)) {{ $value->nm_perush }} @endif</td>
						<td>{{ $value->nm_group }}</td>
						<td>{{ $value->alamat }}</td>
						<td>{{ $value->telp }}</td>
						<td>{{ $value->email }}</td>
						<td>{{ $value->n_limit_piutang }}</td>
						<td>
							@if($value->isaktif==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							@if($value->is_user==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							<center>
								<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" x-placement="bottom-end" style="position: absolute; transform: translate3d(107px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
										<a class="dropdown-item" href="{{ url('pelanggan')."/".$value->id_pelanggan."/edit" }}"><i class="fa fa-pencil"></i> Edit</a>
										@if($value->is_user!=true)
										<a class="dropdown-item" href="{{ url('pelanggan')."/".$value->id_pelanggan."/setakses" }}"><i class="fa fa-lock"></i> Set Akses</a>
										@endif
										<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url()->current().'/'.$value->id_pelanggan }}')"><i class="fa fa-times"></i> Delete</a>
									</div>
								</div>
							</center>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>
@endsection
@section('script')
<script type="text/javascript">
	$('#f_id_wil').select2({
		placeholder: 'Cari Wilayah ....',
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_wil').empty();
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
	
	$('#f_id_pelanggan').select2({
		placeholder: 'Cari Pelanggan ....',
		ajax: {
			url: '{{ url('getPelanggan') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_pelanggan').empty();
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
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	@if(isset($filter["f_id_plgn_grup"]))
	$("#f_id_plgn_grup").val('{{ $filter["f_id_plgn_grup"] }}');
	@endif
	
	@if(isset($filter["f_id_pelanggan"]->nm_pelanggan))
	$("#f_id_pelanggan").empty();
	$("#f_id_pelanggan").append('<option value="{{ $filter["f_id_pelanggan"]->id_pelanggan }}">{{ strtoupper($filter["f_id_pelanggan"]->nm_pelanggan) }}</option>');
	@endif
	
	@if(isset($filter["f_id_wil"]->nama_wil))
	$("#f_id_wil").empty();
	$("#f_id_wil").append('<option value="{{ $filter["f_id_wil"]->id_wil }}">{{ strtoupper($filter["f_id_wil"]->nama_wil) }}</option>');
	@endif
	
</script>
@endsection
