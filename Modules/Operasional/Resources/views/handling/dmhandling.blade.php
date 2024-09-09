
@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include("template.filter-collapse")
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-responsive table-hover" >
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>No. Manifest Kurir</th>
						<th>Daerah Asal / Tujuan</th>
						<th>Sopir > Armada</th>
						<th>Tgl. Berangkat > Selesai</th>
						<th>Status</th>
						<th>Ambil Gudang</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td><a href="{{ url(Request::segment(1).'/'.$value->id_handling."/show") }}">{{ strtoupper($value->kode_handling) }}</a>
							<br>
							<label>{{ dateindo($value->created_at) }}</label>
						</td>
						<td>
							<label style="font-size: 9pt">
								@if(isset($value->wil_asal))
								{{ strtoupper($value->wil_asal)  }}
								@endif
							</label>
							<br>
							> 
							<label style="font-size: 9pt">
								@if(isset($value->wil_tujuan))
								{{ strtoupper($value->wil_tujuan)  }}
								@endif
							</label>
						</td>
						<td>
							@if(isset($value->nm_sopir))
							{{ strtoupper($value->nm_sopir)  }}
							@endif
							<br>
							> 
							@if(isset($value->nm_armada))
							{{ strtoupper($value->nm_armada)  }}
							@endif
						</td>
						<td> 
							@if($value->tgl_berangkat != null){{ dateindo($value->tgl_berangkat) }} @endif
							@if($value->tgl_selesai != null)
							<br> 
							> {{ dateindo($value->tgl_selesai) }}
							@endif
						</td>
						<td>
							@if(isset($value->nm_status))
							<label class="badge badge-md @if($value->id_status=="5") badge-info @elseif($value->id_status=="6") badge-primary @else badge-success @endif"> 
								{{ strtoupper($value->nm_status)  }}
							</label>
							@endif
						</td>
						<td>
							@if(isset($value->ambil_gudang) and $value->ambil_gudang=="1")
							<label class="badge badge-md badge-success">Ambil Gudang</label>
							@else
							<label class="badge badge-md badge-danger">Tidak</label>
							@endif
						</td>
						<td>
							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										@if($value->id_status < 6)
										<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_handling."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
										@endif
										
										<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_handling."/show") }}"><i class="fa fa-eye"></i> Detail</a>
										<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_handling."/cetak") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak</a>
									</form>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					@if(count($data) < 1)
					<tr>
						<td colspan="10" class="text-center">Data Kosong</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		
		@include('template.paginator')
	</div>
</form>
@include('operasional::handling.confirm')
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('dmhandling') }}@else{{ url('dmhandling', $data->id_handling) }}@endif" enctype="multipart/form-data" id="form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }}
	@endif
	
	@csrf
	@include('operasional::handling.create')
</form>

@endif

@endsection

@section('script')
<script type="text/javascript">
	
	$('#id_handling').select2({
		placeholder: 'Cari Nomor Handling....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url("dmhandling/gethandling") }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_handling').empty();
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
	
	$('#id_wil').select2({
		placeholder: 'Cari Nama Kota ....',
		ajax: {
			url: '{{ url('getKota') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_wil').empty();
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
	
	$('#id_sopir').on("change", function(e) {
		$.ajax({
			type: "GET", 
			url: "{{ url("ChainArmada") }}/"+$("#id_sopir").val(), 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				console.log(response.id_armada);
				$("#id_armada").val(response.id_armada);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});
	
	@if(isset($filter["id_sopir"]))
	$("#id_sopir").val('{{ $filter["id_sopir"] }}');
	@endif
	
	@if(isset($filter["id_armada"]))
	$("#id_armada").val('{{ $filter["id_armada"] }}');
	@endif
	
	@if(isset($filter["id_status"]))
	$("#id_status").val('{{ $filter["id_status"] }}');
	@endif
	
	@if(isset($filter["id_perush_dr"]))
	$("#id_perush_dr").val('{{ $filter["id_perush_dr"] }}');
	@endif
	
	@if(isset($filter["id_handling"]->kode_handling))
	$("#id_handling").empty();
	$("#id_handling").append('<option value="{{ $filter["id_handling"]->id_handling }}">{{ strtoupper($filter["id_handling"]->kode_handling) }}</option>');
	@endif
	
	@if(isset($filter["id_wil"]->nama_wil))
	$("#id_wil").empty();
	$("#id_wil").append('<option value="{{ $filter["id_wil"]->id_wil }}">{{ strtoupper($filter["id_wil"]->nama_wil) }}</option>');
	@endif
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
</script>
@endsection