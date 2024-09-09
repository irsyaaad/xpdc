@extends('template.document')

@section('data')
@php
$routes = explode(".", Route::currentRouteName());
@endphp

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-responsive table-hover">
				<thead  style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Perusahaan</th>
						<th>Nama Pemilik</th>
						<th>Alamat</th>
						<th>Kota Asal</th>
						<th>Telp</th>
						<th>NPWP</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ strtoupper($value->nm_perush) }}</td>
						<td>{{ strtoupper($value->nm_pemilik) }}</td>
						<td>{{ strtoupper($value->alamat) }}</td>
						<td>
							@if(isset($value->wil->nama_wil))
							{{ strtoupper($value->wil->nama_wil) }}
							@endif
						</td>
						<td>{{ $value->telp }}</td>
						<td>{{ $value->npwp }}</td>
						<td>
							{!! inc_edit($value->id_perush_armd) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }}@else {{ route('perusharmada.update', $data->id_perush_armd) }} @endif" enctype="multipart/form-data">	
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	
	@csrf
	<div class="row">
		
		<div class="form-group m-form__group col-md-2" style="margin-top: 10px">
			<label for="foto">
				<b>Foto Pemilik</b>
			</label>
			
			<input type="file" class="form-control m-input m-input--square" name="foto" id="foto" placeholder="Pilih Foto Pemilik">
			
			@if ($errors->has('foto'))
			<label style="color: red">
				{{ $errors->first('foto') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-5">
			<label for="nm_pemilik">
				<b>Nama Pemilik</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_pemilik" id="nm_pemilik" placeholder="Masukan Pemilik" value="@if(isset($data->nm_pemilik)){{ $data->nm_pemilik }}@else{{ old('nm_pemilik') }}@endif" maxlength="50">
			
			@if ($errors->has('nm_pemilik'))
			<label style="color: red">
				{{ $errors->first('nm_pemilik') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-5">
			<label for="nm_perush">
				<b>Nama Perusahaan</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_perush" id="nm_perush" placeholder="Masukan Nama" value="@if(isset($data->nm_perush)){{ $data->nm_perush }}@else{{ old('nm_perush') }}@endif" maxlength="100">
			
			@if ($errors->has('nm_perush'))
			<label style="color: red">
				{{ $errors->first('nm_perush') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-4">
			<label for="alamat">
				<b>Alamat Lengkap</b> <span class="span-required"> *</span>
			</label>
			
			<textarea class="form-control m-input m-input--square" style="min-height: 100px" name="alamat" id="alamat" placeholder="Masukan Alamat" maxlength="255" >@if(isset($data->alamat)){{ $data->alamat }}@else{{ old('alamat') }}@endif</textarea>
			
			@if ($errors->has('alamat'))
			<label style="color: red">
				{{ $errors->first('alamat') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-4">
			<label for="id_wil">
				<b>Kota Asal</b> <span class="span-required"> *</span>
			</label>
			
			<select id="id_wil" name="id_wil" class="form-control">
				@if(!is_null(old('id_wil')))
				<option value="{{ old("id_wil") }}">{{ old('nm_region') }}</option>
				@endif
			</select>
			
			<input type="hidden" name="nm_region" id="nm_region">
			@if ($errors->has('id_wil'))
			<label style="color: red">
				{{ $errors->first('id_wil') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-4">
			<label for="telp">
				<b>No. Telp</b> <span class="span-required"></span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="telp" id="telp" placeholder="Masukan No. Telp" value="@if(isset($data->telp)){{ $data->telp }}@else{{ old('telp') }}@endif" maxlength="16">
			
			@if ($errors->has('telp'))
			<label style="color: red">
				{{ $errors->first('telp') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-4">
			<label for="no_hp">
				<b>No. Handphone / Whatsapp</b> <span class="span-required"> *</span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="no_hp" id="no_hp" placeholder="Masukan No. Handphone / WA" value="@if(isset($data->no_hp)){{ $data->no_hp }}@else{{ old('no_hp') }}@endif" maxlength="16">
			
			@if ($errors->has('no_hp'))
			<label style="color: red">
				{{ $errors->first('no_hp') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-4">
			<label for="npwp">
				<b>NPWP</b>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="npwp" id="npwp" placeholder="Masukan NPWP" value="@if(isset($data->npwp)){{ $data->npwp }}@else{{ old('npwp') }}@endif" maxlength="16">
			
			@if ($errors->has('npwp'))
			<label style="color: red">
				{{ $errors->first('npwp') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
	</div>
	
</form>
@endif

@endsection

@section('script')

<link rel="stylesheet" href="{{ asset('select2/dist/css/select2.min.css') }}">
<script src="{{ asset('select2/dist/js/select2.js') }}"></script>

<script type="text/javascript">
	
	$('#id_wil').select2({
		placeholder: 'Cari Wilayah Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
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
	
	$('#f_id_wil').select2({
		placeholder: 'Cari Wilayah Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
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

	$('#f_id_perush').select2({
		placeholder: 'Cari Nama Pemilik ....',
		ajax: {
			url: '{{ url('getPerushArmada') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
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
	
	$('#id_wil').on("change", function(e) { 
		$("#nm_region").val($('#id_region').text());
	});
	
	@if(isset($data->wil->nama_wil))
	$("#id_wil").append('<option value="{{ $data->wil->id_wil }}">{{ $data->wil->nama_wil }}</option>');
	@endif
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	@if(isset($filter["f_id_perush"]->id_perush_armd))
	$("#f_id_perush").empty();
	$("#f_id_perush").append('<option value={{ $filter["f_id_perush"]->id_perush_armd }}>'+"{{ strtoupper($filter["f_id_perush"]->nm_perush) }}"+'</option>');
	@endif

	@if(isset($filter["f_id_wil"]->id_wil))
	$("#f_id_wil").empty();
	$("#f_id_wil").append('<option value={{ $filter["f_id_wil"]->id_wil }}>'+"{{ strtoupper($filter["f_id_wil"]->nama_wil) }}"+'</option>');
	@endif
</script>
@endsection