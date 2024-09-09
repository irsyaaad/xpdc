@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('vendorbusdev') }}@else{{ route('vendorbusdev.update', $data->id_ven) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">
		
		<div class="form-group col-md-4">
			<label for="nm_ven">
				<b>Nama vendor</b> <span class="span-required">*</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" id="nm_ven" name="nm_ven" required="required" maxlength="64" value="@if(old('nm_ven')!=null){{ old('nm_ven') }}@elseif(isset($data->nm_ven)){{$data->nm_ven}}@endif">
			
			@if ($errors->has('nm_ven'))
			<label style="color: red">
				{{ $errors->first('nm_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="id_grup_ven">
				<b>Group Vendor</b> <span class="span-required">*</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_grup_ven" name="id_grup_ven">
				<option value="">-- Pilih Group Vendor --</option>
				@foreach($group as $key => $value)
				<option value="{{ $value->id_grup_ven }}">{{ strtoupper($value->nm_grup_ven) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('id_grup_ven'))
			<label style="color: red">
				{{ $errors->first('id_grup_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="telp_ven">
				<b>Telp Kantor</b> <span class="span-required">*</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" id="telp_ven" name="telp_ven" required="required" maxlength="16" value="@if(old('telp_ven')!=null){{ old('telp_ven') }}@elseif(isset($data->telp_ven)){{$data->telp_ven}}@endif">
			
			@if ($errors->has('telp_ven'))
			<label style="color: red">
				{{ $errors->first('telp_ven') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4">
			<label for="id_wil">
				<b>Kota Vendor</b> <span class="span-required">*</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_wil" name="id_wil">
				@if(!is_null(old('id_wil')))
				<option value="{{ old("id_wil") }}">{{ old('nama_wil') }}</option>
				@endif
			</select>
			
			<input type="hidden" name="nama_wil" id="nama_wil" value="{{ old('nama_wil') }}">
			
			@if ($errors->has('id_wil'))
			<label style="color: red">
				{{ $errors->first('id_wil') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="alm_ven">
				<b>Alamat Vendor</b>  <span class="span-required">*</span>
			</label>
			
			<textarea class="form-control m-input m-input--square" id="alm_ven" name="alm_ven"maxlength="128" style="height: 70px">@if(old('alm_ven')!=null){{ old('alm_ven') }}@elseif(isset($data->alm_ven)){{$data->alm_ven}}@endif</textarea>
			
			@if ($errors->has('alm_ven'))
			<label style="color: red">
				{{ $errors->first('alm_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="is_aktif">
				<b>Is Aktif</b>
			</label>
			
			<div class="row">
				<div class="col-md-12 checkbox">
					<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
				</div>
			</div>
			
			@if ($errors->has('is_aktif'))
			<label style="color: red">
				{{ $errors->first('is_aktif') }}
			</label>
			@endif
		</div>

		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
	</div>
</form>

@endsection
@section('script')

<script type="text/javascript">
	$('#id_wil').select2({
		placeholder: 'Cari Kota Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
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

	$('#id_wil').on("change", function(e) { 
		$("#nama_wil").val($('#id_wil').text());
	});
	
	@if(old("nama_wil") != null)
	$('#id_wil').append('<option value="{{ old("id_wil") }}">{{ strtoupper(old("nama_wil")) }}</option>');
	@elseif(isset($wilayah->id_wil))
	$('#id_wil').append('<option value="{{ $data->id_wil }}">{{ $wilayah->nama_wil }}</option>');
	@endif

	@if(old("id_grup_ven")!=null)
	$("#id_grup_ven").val('{{ old("id_grup_ven") }}');
	@elseif(isset($data->id_grup_ven))
	$("#id_grup_ven").val('{{ $data->id_grup_ven }}');
	@endif
	
	@if(old("is_aktif")!=null)
	$("#is_aktif").val('{{ old("is_aktif") }}');
	@elseif(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").attr("checked", true);
	@endif
</script>
@endsection