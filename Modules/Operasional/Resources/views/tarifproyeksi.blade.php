@extends('template.document2')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include("template.filter-collapse")
	@csrf
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Perusahaan Asal</th>
						<th>Perusahaan Tujuan</th>
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
							@if(isset($value->perusahaantj->nm_perush)){{ strtoupper($value->perusahaantj->nm_perush) }}@endif
						</td>
						<td>
							@if(isset($value->layanan->id_layanan)){{ strtoupper($value->layanan->nm_layanan) }}@endif
						</td>
						<td>
							<a href="{{ url(Request::segment(1)."/".$value->id_proyeksi."/show") }}" class="btn btn-sm btn-info"> <i class="fa fa-eye"> </i> Detail </a>
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

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('tarifproyeksi') }}@else {{ url('tarifproyeksi', $data->id_proyeksi) }} @endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">
		<div class="form-group col-md-3" style="margin-top: 15px">
			<label for="id_perush_tj">
				<b>Perusahaan Tujuan</b>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_perush_tj" name="id_perush_tj">
				@if(!is_null(old('id_perush_tj')))
				<option value="{{ old("id_perush_tj") }}">{{ old('nm_perush_tj') }}</option>
				@endif
			</select>
			
			<input type="hidden" name="nm_perush_tj" id="nm_perush_tj" value="{{ old('nm_perush_tj') }}">
			@if ($errors->has('id_perush_tj'))
			<label style="color: red">
				{{ $errors->first('id_perush_tj') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-3">
			<label for="id_layanan">
				<b>Layanan</b>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan">
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
		
		<div class="col-md-3" style="padding-top: 14px">
			<div class="form-group m-form__group">
				<div class="m-form__actions">
					@if(Request::segment(3)!="show")
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-save"></i> Submit
					</button>
					
					<a href="{{ url(Request::segment(1)) }}" class="btn btn-danger">
						<i class="fa fa-times"></i>	Cancel
					</a>
					@endif
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
				<td> Cabang Perusahaan Tujuan : <b>@if(isset($data->perusahaantj->nm_perush)){{ strtoupper($data->perusahaantj->nm_perush) }}@endif</b>
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
	@if(isset($filter["f_id_perush_tj"]))
	$("#f_id_perush_tj").val('{{ $filter["f_id_perush_tj"] }}');
	@endif

	@if(isset($filter["f_id_layanan"]))
	$("#f_id_layanan").val('{{ $filter["f_id_layanan"] }}');
	@endif

	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	$('#id_perush_tj').select2({
		placeholder: 'Cari Perusahaan Tujuan ....',
		// minimumInputLength: 3,
		ajax: {
			url: '{{ url('getPerusahExcept') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_perush_tj').empty();
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
	
	$('#id_perush_tj').on("change", function(e) {
		$("#nm_perush_tj").val($("#id_perush_tj").text());
	});
	
	$('#id_layanan').on("change", function(e) {
		getTarif();
	});
	
	function getTarif() {
		var id_layanan = $('#id_layanan').val();
		var token = "{{ csrf_token() }}";
		var id_tujuan = $('#id_perush_tj').val();
		$.ajax({
			type: "POST",
			url: "{{ url('getTarifAsalTj') }}",
			dataType: "json",
			data: {_token: token, id_tujuan: id_tujuan, id_layanan:id_layanan},
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				if(response == 0){
					$("#text-modal").text("Tarif Wilayah ini belum dibuat");
					$('#modal-proyeksi').modal();
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
				setTarif();
			}
		});
	}
	
	@if(Request::segment(3)=="edit" or Request::segment(3)=="show")
	@if(isset($data->perusahaantj->nm_perush))
	
	$("#id_perush_tj").empty();
	$("#id_perush_tj").append('<option value={{ $data->perusahaantj->id_perush }}>'+"{{ strtoupper($data->perusahaantj->nm_perush) }}"+'</option>');
	
	$("#nm_perush_tj").val('{{ strtoupper($data->perusahaantj->id_perush) }}');
	@endif
	
	@if(isset($data->layanan->id_layanan))
	
	$("#id_layanan").val('{{ $data->layanan->id_layanan }}');
	@endif
	@endif
</script>
@endsection