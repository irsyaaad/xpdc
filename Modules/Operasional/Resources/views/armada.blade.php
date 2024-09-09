
@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12" >
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Armada</th>
						<th>No Plat</th>
						<th>Perusahaan</th>
						<th>Pemilik</th>
						<th>Group Armada</th>
						<th>Is Aktif</th>
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
							{{ strtoupper($value->nm_armada) }}
						</td>
						<td>
							{{ strtoupper($value->no_plat) }}
						</td>
						<td>
							@if(isset($value->nm_perush))
							{{ strtoupper($value->nm_perush) }}
							@endif
						</td>
						<td>
							@if(isset($value->nm_pemilik))
							{{ strtoupper($value->nm_pemilik) }}
							@endif
						</td>
						<td>
							@if(isset($value->nm_armd_grup))
							{{ strtoupper($value->nm_armd_grup) }}
							@endif
						</td>
						<td>
							@if($value->is_aktif==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							{!! inc_dropdown($value->id_armada) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include("template.paginator")
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('armada') }}@else{{ url('armada', $data->id_armada) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		<div class="form-group m-form__group col-md-6">
			<label for="nm_armada">
				<b>Nama Armada</b><span class="span-required"> *</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_armada" id="nm_armada" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif" required="required" maxlength="64">
			
			@if ($errors->has('nm_armada'))
			<label style="color: red">
				{{ $errors->first('nm_armada') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6" style="margin-top: -15px">
			<label for="id_perush_armd">
				<b>Pemilik Armada</b><span class="span-required"> *</span>
			</label>
			
			<select class="form-control m-input m-input--square" name="id_perush_armd" id="id_perush_armd" >
				@foreach($perush as $key => $value)
				<option value="{{ $value->id_perush_armd }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
			@if ($errors->has('id_perush_armd'))
			<label style="color: red">
				{{ $errors->first('id_perush_armd') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="no_plat">
				<b>No Plat</b><span class="span-required"> *</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="no_plat" id="no_plat" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif" required="required" maxlength="12">
			
			@if ($errors->has('no_plat'))
			<label style="color: red">
				{{ $errors->first('no_plat') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="id_armd_grup">
				<b>Group Armada</b><span class="span-required"> *</span>
			</label>
			
			<select class="form-control m-input m-input--square" name="id_armd_grup" id="id_armd_grup">
				@if(!is_null(old('id_armd_grup')))
				<option value="{{ old("id_armd_grup") }}">{{ old('nm_armd_grup') }}</option>
				@endif
			</select>
			
			<input type="hidden" name="nm_armd_grup" id="nm_armd_grup" value=""> 
			
			@if ($errors->has('id_armd_grup'))
			<label style="color: red">
				{{ $errors->first('id_armd_grup') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="no_bpkb">
				<b>No BPKB</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="no_bpkb" id="no_bpkb" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif" maxlength="20">
			
			@if ($errors->has('no_bpkb'))
			<label style="color: red">
				{{ $errors->first('no_bpkb') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="gambar_bpkb">
				<b>Foto BPKB</b><span class="span-required"></span>
			</label>
			
			<input type="file" class="form-control m-input m-input--square" name="gambar_bpkb" id="gambar_bpkb" placeholder="Pilih Gambar BPKB">
			
			@if ($errors->has('gambar_bpkb'))
			<label style="color: red">
				{{ $errors->first('gambar_bpkb') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="no_stnk">
				<b>No STNK</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="no_stnk" id="no_stnk" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif" maxlength="20">
			
			@if ($errors->has('no_stnk'))
			<label style="color: red">
				{{ $errors->first('no_stnk') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="gambar_stnk">
				<b>Foto STNK</b><span class="span-required"></span>
			</label>
			
			<input type="file" class="form-control m-input m-input--square" name="gambar_stnk" id="gambar_stnk" placeholder="Pilih Gambar STNK">
			
			@if ($errors->has('gambar_stnk'))
			<label style="color: red">
				{{ $errors->first('gambar_stnk') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6" style="margin-top: -15px">
			<label for="harga">
				<b>Harga Sewa</b><span class="span-required"></span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="harga" id="harga" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif">
			
			@if ($errors->has('harga'))
			<label style="color: red">
				{{ $errors->first('harga') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="foto">
				<b>Foto Armada</b><span class="span-required"></span>
			</label>
			
			<input type="file" class="form-control m-input m-input--square" name="foto" id="foto">
			
			@if ($errors->has('foto'))
			<label style="color: red">
				{{ $errors->first('foto') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6" style="margin-top: -15px">
			<label for="volume">
				<b>Volume Armada</b><span class="span-required"></span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="volume" id="volume" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif">
			
			@if ($errors->has('volume'))
			<label style="color: red">
				{{ $errors->first('volume') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-6">
			<label for="is_aktif">
				<b><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif</label></b>
			</label>
			
		</div>
		
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
	</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	$('#f_id_armd_grup').select2({
		placeholder: 'Cari Group Armada ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getgrouparmada') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_armd_grup').empty();
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

	$('#f_id_armada').select2({
		placeholder: 'Cari Armada ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getArmada') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_armada').empty();
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

	$('#f_id_perush_armd').select2({
		placeholder: 'Cari Pemilik Armada ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getPerushArmada') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_perush_armd').empty();
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

	@if(isset($filter["f_id_armada"]->nm_armada))
	$("#f_id_armada").empty();
	$("#f_id_armada").append('<option value={{ $filter["f_id_armada"]->id_armada }}>'+"{{ strtoupper($filter["f_id_armada"]->nm_armada.' ( '.$filter["f_id_armada"]->no_plat.' )') }}"+'</option>');
	@endif

	@if(isset($filter["f_id_armd_grup"]->id_armd_grup))
	$("#f_id_armd_grup").empty();
	$("#f_id_armd_grup").append('<option value={{ $filter["f_id_armd_grup"]->id_armd_grup }}>'+"{{ strtoupper($filter["f_id_armd_grup"]->nm_armd_grup) }}"+'</option>');
	@endif
	
	@if(isset($filter["f_id_perush_armd"]->id_perush_armd))
	$("#f_id_perush_armd").empty();
	$("#f_id_perush_armd").append('<option value={{ $filter["f_id_perush_armd"]->id_perush_armd }}>'+"{{ strtoupper($filter["f_id_perush_armd"]->nm_perush) }}"+'</option>');
	@endif

	@if(isset($group->id_armd_grup))
	$("#id_armd_grup").empty();
	$("#id_armd_grup").append('<option value={{ $group->id_armd_grup }}>'+"{{ strtoupper($group->nm_armd_grup) }}"+'</option>');
	@endif
	

	// For edit and create
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
	$('#id_armd_grup').select2({
		placeholder: 'Cari Group Armada ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getArmadaGrup') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_armd_grup').empty();
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
	
	$('#id_armd_grup').on("change", function(e) {
		$("#nm_armd_grup").val($("#id_perush_tujuan").text());
	});
</script>
@endsection