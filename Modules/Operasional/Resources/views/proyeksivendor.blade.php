@extends('template.document2')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<div class="row mt-1">
		<div class="col-md-12" >
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Perusahaan Asal</th>
						<th>Vendor Tujuan</th>
						<th>Layanan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>
							{{ $key+1 }}
						</td>
						<td>
							@if(isset($value->perusahaan->nm_perush)){{ strtoupper($value->perusahaan->nm_perush) }}@endif
						</td>
						<td>
							@if(isset($value->vendor->nm_ven)){{ strtoupper($value->vendor->nm_ven) }}@endif
						</td>
						<td>
							@if(isset($value->layanan->id_layanan)){{ strtoupper($value->layanan->nm_layanan) }}@endif
						</td>
						<td>
							{!! inc_dropdown_show($value->id_proyeksi) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('proyeksivendor') }} @else{{ url('proyeksivendor', $data->id_proyeksi) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif

	@csrf
	<div class="row">
		<input type="hidden" name="id_tarif" id="id_tarif">
		
		<div class="form-group m-form__group col-md-3">
			<label for="id_ven">
				<b>Vendor</b>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_ven" name="id_ven">
				<option value=""> -- Pilih Vendor -- </option>
				@foreach($vendor as $key => $value)
				<option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('id_ven'))
			<label style="color: red">
				{{ $errors->first('id_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-3">
			<label for="id_layanan">
				<b>Layanan</b>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan">
				<option value=""> -- Pilih Layanan -- </option>
				@foreach($layanan as $key => $value)
				<option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('id_layanan'))
			<label style="color: red">
				{{ $errors->first('id_layanan') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-3 text-right" style="padding-top: 14px">
			<div class="form-group m-form__group">
				<div class="m-form__actions">
					@if(Request::segment(3)!="show")
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-save"></i> Submit
					</button>
					@endif
					
					<a href="{{ url(Request::segment(1)) }}" class="btn btn-danger">
						<i class="fa fa-times"></i>	Cancel
					</a>
				</div>
			</div>
		</div>
	</div>
	
</form>
@elseif(Request::segment(3)=="show")

<div class="row">
	<div class="col-md-12 text-right">
		<a class="btn btn-sm btn-warning" href="{{ url(Request::segment(1)) }}">
			<i class="fa fa-reply"></i> Kembali 
		</a>
	</div>
	<div class="col-md-12">
		<table class="table table-responsive table-stripped">
			<tr>
				<td> Nama Perusahaan : <b>@if(isset($data->perusahaan->nm_perush)){{ strtoupper($data->perusahaan->nm_perush) }}@endif</b>
				</td>
				<td> Vendor Tujuan : <b>@if(isset($data->vendor->nm_ven)){{ strtoupper($data->vendor->nm_ven) }}@endif</b>
				</td>
				<td> Layanan : <b>@if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif</b>
				</td>
			</tr>
		</table>
	</div>
</div>
@include("operasional::detail-proyeksi")
@endif

@endsection
<div class="modal fade" id="modal-proyeksi" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center">
				<h5><b style="color: red" id="text-modal"></b></h5>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
		
	</div>
</div>

@section('script')
<script type="text/javascript">
	
	$('#shareselect').on("change", function(e) {
		$("#form-select").submit();
	});

	@if(isset($filter["f_id_layanan"]))
	$("#f_id_layanan").val('{{ $filter["f_id_layanan"] }}');
	@endif

	@if(isset($filter["f_id_ven"]->nm_ven))
	$("#f_id_ven").empty();
	$("#f_id_ven").append('<option value="{{ $filter["f_id_ven"]->id_ven }}">{{ strtoupper($filter["f_id_ven"]->nm_ven) }}</option>');
	@endif

	$('#id_ven').on("change", function(e) {
		getTarif();
	});
	
	$('#f_id_ven').select2({
		placeholder: 'Cari Cari Vendor ....',
		ajax: {
			url: '{{ url('getVendor') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_ven').empty();
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

	$('#id_asal').select2({
		placeholder: 'Cari Wilayah Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_asal').empty();
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
	
	$('#id_asal').on("change", function(e) {
		$("#nm_asal").val($("#id_asal").text());
		getTarif();
	});
	
	$('#id_tujuan').select2({
		placeholder: 'Cari Wilayah Tujuan ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_tujuan').empty();
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
	
	$('#id_tujuan').on("change", function(e) {
		$("#nm_tujuan").val($("#id_tujuan").text());
		getTarif();
	});
	
	function getTarif() {
		var id_ven = $('#id_ven').val();
		var id_tujuan = $('#id_tujuan').val();
		var id_asal = $('#id_asal').val();
		var token = "{{ csrf_token() }}";
		
		if(id_ven!=null && id_tujuan!=null && id_asal!=null){
			$.ajax({
				type: "POST",
				url: "{{ url('gettarifvendor') }}",
				dataType: "json",
				data: {_token: token, id_tujuan: id_tujuan, id_asal: id_asal, id_ven:id_ven},
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){ 
					if(response == 0){
						$("#text-modal").text("Tarif Vendor Wilayah ini belum dibuat");
						$('#modal-proyeksi').modal();
					}else{
						console.log(response);
						$("#id_tarif").val(response.id_tarif);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});
		}
	}
	
	@if(Request::segment(3)=="edit")
	@if(isset($data->id_ven))
	$("#id_ven").val('{{ $data->id_ven }}');
	@endif
	
	@if(isset($data->id_ven))
	$("#id_layanan").val('{{ $data->id_layanan }}');
	@endif
	@endif
	
</script>
@endsection