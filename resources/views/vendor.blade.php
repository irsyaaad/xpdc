@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Vendor</th>
						<th>Group</th>
						<th>Perusahaan Asal</th>
						<th>Kota</th>
						<th>Contact</th>
						<th>Telp Kontak</th>
						<th>Is Aktif</th>
						<th class="text-center">
							Action
						</th>
					</tr>
				</thead>
				
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ strtoupper($value->nm_ven) }}</td>
							<td>
								@if(isset($value->group->nm_grup_ven))
								{{ strtoupper($value->group->nm_grup_ven) }}
								@endif
							</td>
							<td>
								@if(isset($value->perusahaan->nm_perush))
								{{ strtoupper($value->perusahaan->nm_perush) }}
								@endif
							</td>
							<td>
								@if(isset($value->wilayah->nama_wil))
								{{ strtoupper($value->wilayah->nama_wil) }}
								@endif
							</td>
							<td>{{ strtoupper($value->kontak_ven) }}</td>
							<td>{{ $value->kontak_hp }}</td>
							<td>
								@if($value->is_aktif==1)
								<i class="fa fa-check" style="color: green"></i>
								@else
								<i class="fa fa-times" style="color: red"></i>
								@endif
							</td>
							<td>
								{!! inc_edit($value->id_ven) !!}
							</td>
						</tr>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('vendor') }}@else{{ route('vendor.update', $data->id_ven) }}@endif" enctype="multipart/form-data">
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
				<option>-- Pilih Group Vendor --</option>
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
			<label for="nm_pemilik">
				<b>Pemilik Vendor (Owner)</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" id="nm_pemilik" name="nm_pemilik" maxlength="32" value="@if(old('nm_pemilik')!=null){{ old('nm_pemilik') }}@elseif(isset($data->nm_pemilik)){{$data->nm_pemilik}}@endif">
			
			@if ($errors->has('nm_pemilik'))
			<label style="color: red">
				{{ $errors->first('nm_pemilik') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="email_ven">
				<b>Email</b> 
			</label>
			
			<input type="email" class="form-control m-input m-input--square" name="email_ven" id="email_ven" maxlength="64" value="@if(old('email_ven')!=null){{ old('email_ven') }}@elseif(isset($data->email_ven)){{$data->email_ven}}@endif">
			
			@if ($errors->has('email_ven'))
			<label style="color: red">
				{{ $errors->first('email_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="kontak_ven">
				<b>Contact Person</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" id="kontak_ven" name="kontak_ven" maxlength="64" value="@if(old('kontak_ven')!=null){{ old('kontak_ven') }}@elseif(isset($data->kontak_ven)){{$data->kontak_ven}}@endif">
			
			@if ($errors->has('kontak_ven'))
			<label style="color: red">
				{{ $errors->first('kontak_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="kontak_hp">
				<b>Telp Contact Person</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" id="kontak_hp" name="kontak_hp" maxlength="16" value="@if(old('kontak_hp')!=null){{ old('kontak_hp') }}@elseif(isset($data->kontak_hp)){{$data->kontak_hp}}@endif">
			
			@if ($errors->has('kontak_hp'))
			<label style="color: red">
				{{ $errors->first('kontak_hp') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="npwp">
				<b>NPWP Perusahaan</b>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" id="npwp" name="npwp" maxlength="16" value="@if(old('npwp')!=null){{ old('npwp') }}@elseif(isset($data->npwp)){{$data->npwp}}@endif">
			
			@if ($errors->has('npwp'))
			<label style="color: red">
				{{ $errors->first('npwp') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="cara_bayar">
				<b>Cara Bayar</b>
			</label>
			
			<select class="form-control m-input m-input--square" id="cara_bayar" name="cara_bayar">
				@foreach($carabayar as $key => $value)
				<option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('cara_bayar'))
			<label style="color: red">
				{{ $errors->first('cara_bayar') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4" id="div-inv">
			<label for="hari_inv">
				<b>Lama Hari Invoice</b>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" id="hari_inv" name="hari_inv" maxlength="16" value="@if(old('hari_inv')!=null){{ old('hari_inv') }}@elseif(isset($data->hari_inv)){{$data->hari_inv}}@endif">
			
			@if ($errors->has('hari_inv'))
			<label style="color: red">
				{{ $errors->first('hari_inv') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4">
			<label for="id_biaya_grup">
				<b>Group biaya</b> <span class="text-danger"> * </span>
			</label>
			
			<select class="form-control m-input m-input--square" required id="id_biaya_grup" name="id_biaya_grup">
				@foreach($biaya as $key => $value)
				<option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
				@endforeach
			</select>

			@if ($errors->has('id_biaya_grup'))
			<label style="color: red">
				{{ $errors->first('id_biaya_grup') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4">
			<label for="ac4_kredit">
				<b>AC Kredit</b> <span class="text-danger"> * </span>
			</label>
			
			<select class="form-control m-input m-input--square" required id="ac4_kredit" name="ac4_kredit">
				@foreach($kredit as $key => $value)
				<option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
				@endforeach
			</select>

			@if ($errors->has('ac4_kredit'))
			<label style="color: red">
				{{ $errors->first('ac4_kredit') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4">
			<label for="ac4_debet">
				<b>AC Debet</b> <span class="text-danger"> * </span>
			</label>
			
			<select class="form-control m-input m-input--square" required id="ac4_debet" name="ac4_debet">
				@foreach($kredit as $key => $value)
				<option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
				@endforeach
			</select>

			@if ($errors->has('ac4_debet'))
			<label style="color: red">
				{{ $errors->first('ac4_debet') }}
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
@endif

@endsection
@section('script')

<script type="text/javascript">
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	$('#f_id_ven').select2({
		placeholder: 'Cari Nama Vendor ....',
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

	@if(isset($filter["f_id_grup_ven"]))
	$("#f_id_grup_ven").val('{{ $filter["f_id_grup_ven"] }}');
	@endif

	@if(isset($filter["f_id_ven"]->nm_ven))
	$("#f_id_ven").empty();
	$("#f_id_ven").append('<option value="{{ $filter["f_id_ven"]->id_ven }}">{{ strtoupper($filter["f_id_ven"]->nm_ven) }}</option>');
	@endif
	
	$('#id_wil').select2({
		placeholder: 'Cari Kota Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getKota') }}',
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
	
	$('#id_grup_ven').on("change", function(e) { 
		$("#nm_grup_ven").val($('#id_grup_ven').text());
	});

	$('#id_biaya_grup').select2();
	$('#ac4_kredit').select2();
	$('#ac4_debet').select2();

	@if(Request::segment(3)=="edit" and isset($data->is_aktif))
	$("#is_aktif").prop("checked", true);
	@endif
	
	@if(Request::segment(3)=="edit" and isset($data->cara->nm_cr_byr_o))
	$("#cara_bayar").val('{{ $data->cara->id_cr_byr_o }}');
	@endif
	
	@if(Request::segment(3)=="edit" and isset($data->group->nm_grup_ven))
	$("#id_grup_ven").val('{{ $data->group->id_grup_ven }}');
	@endif
	
	@if(Request::segment(3)=="edit" and isset($data->wilayah->nama_wil))
	$("#id_wil").append('<option value="{{ $data->wilayah->id_wil }}">{{ strtoupper($data->wilayah->nama_wil) }}</option>');
	@endif
	
	@if(old("kontak_ven")!=null)
	$("#kontak_ven").val('{{ old("kontak_ven") }}');
	@endif
	
	@if(old("id_grup_ven")!=null)
	$("#id_grup_ven").val('{{ old("id_grup_ven") }}');
	@endif
	
	@if(old("cara_bayar")!=null)
	$("#cara_bayar").val('{{ old("cara_bayar") }}');
	@endif
	
	@if(old("is_aktif")!=null)
	$("#is_aktif").val('{{ old("is_aktif") }}');
	@endif

	@if(old("id_biaya_grup")!=null)
	$("#id_biaya_grup").val('{{ old("id_biaya_grup") }}').trigger("change");
	@elseif(isset($data->id_biaya_grup))
	$("#id_biaya_grup").val('{{ $data->id_biaya_grup }}').trigger("change");
	@endif

	@if(old("ac4_kredit")!=null)
	$("#ac4_kredit").val('{{ old("ac4_kredit") }}').trigger("change");
	@elseif(isset($data->id_biaya_grup))
	$("#ac4_kredit").val('{{ $data->ac4_kredit }}').trigger("change");
	@endif

	@if(old("ac4_debet")!=null)
	$("#ac4_debet").val('{{ old("ac4_debet") }}').trigger("change");
	@elseif(isset($data->id_biaya_grup))
	$("#ac4_debet").val('{{ $data->ac4_debet }}').trigger("change");
	@endif
	
</script>
@endsection