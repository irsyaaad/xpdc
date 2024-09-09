@extends('template.document')

@section('data')

@if((Request::segment(1)=="perusahaanasuransi" && Request::segment(2)==null) or (Request::segment(2)=="filter" or Request::segment(2)=="page"))
@include("template.filter2")

<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead  style="background-color: grey; color : #ffff">>
		<tr>
			<th>No</th>
			<th>Nama Perusahaan</th>
			<th>Kota</th>
			<th>Alamat</th>
			<th>Nama CP</th>
			<th>Nomer CP</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>{{ strtoupper($value->nm_perush_asuransi) }}</td>
			<td>{{ $value->wilayah->nama_wil }}</td>
			<td>{{ strtoupper($value->alamat) }}</td>
			<td>{{ strtoupper($value->cp) }}</td>
			<td>{{ strtoupper($value->no_cp) }}</td>
			<td>
				{!! inc_edit($value->id_perush_asuransi) !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class="row" style="margin-top: 4%; font-weight: bold;">
	<div class="col-md-2">
		Halaman : <b>{{ $data->currentPage() }}</b>
	</div>
	<div class="col-md-2">
		Jumlah Data : <b>{{ $data->total() }}</b>
	</div>
	<div class="col-md-3">
		{{-- rubah setia view disini --}}
		@if(Request::segment(2)=="filter")
		<form method="POST" action="{{ url('perusahaan/filter') }}" id="form-share" name="form-share">
		@else
		<form method="POST" action="{{ url('perusahaan/page') }}" id="form-share" name="form-share">
		@endif
			@csrf
			<select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
				<option value="10">-- Tampil 10 Data --</option>
				<option value="50">50 Data</option>
				<option value="100">100 Data</option>
				<option value="500">500 Data</option>
			</select>
		</form>
	</div>
	<div class="col-md-5" style="width: 100%">
		{{ $data->links() }}
	</div>
</div>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )
<style type="text/css">
	.select2-selection {
		height: 43px !important;
		padding: 1px;
	}
</style>

{{-- for insert data --}}
@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('perusahaanasuransi') }}" enctype="multipart/form-data">	
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('perusahaanupdate', $data->id_perush_asuransi) }}" enctype="multipart/form-data">
{{ method_field("PUT") }} 
@endif

@csrf	
	@if(Request::segment(2)=="create")
		<div class="form-group m-form__group">
			<label for="id_perush">
				<b>ID Perusahaan Asuransi</b> <span class="span-required"> *</span>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="id_perush_asuransi" id="id_perush_asuransi" placeholder="ex: KBRU ..." value="@if(isset($data->id_perush_asuransi)){{ $data->id_perush_asuransi }}@else{{ old('id_perush_asuransi') }}@endif" maxlength="100" required>
			
			@if ($errors->has('nm_perush_asuransi'))
			<label style="color: red">
				{{ $errors->first('nm_perush_asuransi') }}
			</label>
			@endif
		</div>
		@endif
		<div class="form-group m-form__group">
			<label for="nm_perush">
				<b>Nama Perusahaan Asuransi</b> <span class="span-required"> *</span>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="nm_perush_asuransi" id="nm_perush_asuransi" placeholder="ex : PT. KALIBESAR RAYA UTAMA" value="@if(isset($data->nm_perush_asuransi)){{ $data->nm_perush_asuransi }}@else{{ old('nm_perush_asuransi') }}@endif" maxlength="100" required>
			
			@if ($errors->has('nm_perush_asuransi'))
			<label style="color: red">
				{{ $errors->first('nm_perush_asuransi') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group">
			<label for="nm_perush">
				<b>Jenis Asuransi</b> <span class="span-required"> *</span>
			</label>

			<select class="form-control" id="jenis_asuransi" name="jenis_asuransi" required>
				<option value="">-- Pilih Jenis Asuransi --</option>
				<option value="Construction Insurance">Construction Insurance</option>
				<option value="Electronic & Heavy Equipment Insurance">Electronic & Heavy Equipment Insurance</option>
				<option value="Health Insurance">Health Insurance</option>
				<option value="Liability Insurance">Liability Insurance</option>
				<option value="Marine Cargo & Hull Insurance">Marine Cargo & Hull Insurance</option>
				<option value="Motor Vehicle Insurance">Motor Vehicle Insurance</option>
				<option value="Neon Sign Insurance">Neon Sign Insurance</option>
				<option value="Property Insurance">Property Insurance</option>
				<option value="Surety Bond">Surety Bond</option>
				<option value="Travel Insurance">Travel Insurance</option>
				<option value="Asuransi Kredit Usaha">Asuransi Kredit Usaha</option>
			</select>
			
			@if ($errors->has('nm_perush_asuransi'))
			<label style="color: red">
				{{ $errors->first('nm_perush_asuransi') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group">
			<label for="nm_perush">
				<b>Jenis Resiko yang Dicover</b> <span class="span-required"> *</span>
			</label>

			<select class="form-control" id="jenis_resiko" name="jenis_resiko" required>
				<option value="">-- Pilih Jenis Resiko --</option>
				<option value="ICCA">ICCA</option>
				<option value="ICCB">ICCB</option>
				<option value="ICCC">ICCC</option>
			</select>
			
			@if ($errors->has('jenis_resiko'))
			<label style="color: red">
				{{ $errors->first('jenis_resiko') }}
			</label>
			@endif
		</div>


		<div class="form-group m-form__group ">
			<label for="id_region">
				<b>Wilayah Asal</b> <span class="span-required"> *</span>
			</label>

			<select id="id_region" name="id_region" class="form-control" required>
				@if(!is_null(old('id_region')))
				<option value="{{ old("id_region") }}">{{ old('nm_region') }}</option>
				@endif
			</select>

			<input type="hidden" name="nm_region" id="nm_region">
			@if ($errors->has('id_region'))
			<label style="color: red">
				{{ $errors->first('id_region') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group ">
			<label for="alamat">
				<b>Alamat Lengkap</b> <span class="span-required"> *</span>
			</label>

			<textarea class="form-control m-input m-input--square" name="alamat" id="alamat" placeholder="Masukan Alamat" maxlength="200" required>@if(isset($data->alamat)){{ $data->alamat }}@else{{ old('alamat') }}@endif</textarea>

			@if ($errors->has('alamat'))
			<label style="color: red">
				{{ $errors->first('alamat') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group">
			<label for="fax">
				<b>No. Fax</b>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="fax" id="fax" placeholder="Masukan No. Fax" value="@if(isset($data->fax)){{ $data->fax }}@else{{ old('fax') }}@endif" maxlength="16">

			@if ($errors->has('fax'))
			<label style="color: red">
				{{ $errors->first('fax') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group ">
			<label for="email">
				<b>Email</b><span class="span-required"> *</span>
			</label>

			<input type="email" class="form-control m-input m-input--square" name="email" id="email" placeholder="Masukan Email" value="@if(isset($data->email)){{ $data->email }}@else{{ old('email') }}@endif" maxlength="50" required>

			@if ($errors->has('email'))
			<label style="color: red">
				{{ $errors->first('email') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group ">
			<label for="npwp">
				<b>NPWP</b>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="npwp" id="npwp" placeholder="Masukan NPWP" value="@if(isset($data->npwp)){{ $data->npwp }}@else{{ old('npwp') }}@endif" maxlength="16">

			@if ($errors->has('npwp'))
			<label style="color: red">
				{{ $errors->first('npwp') }}
			</label>
			@endif
		</div>		

		<div class="form-group m-form__group ">
			<label for="nm_cs">
				<b>Nama Contact Person</b>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="cp" id="cp" placeholder="Masukan Contact Person" value="@if(isset($data->cp)){{ $data->cp }}@else{{ old('cp') }}@endif" maxlength="50" required>

			@if ($errors->has('cp'))
			<label style="color: red">
				{{ $errors->first('cp') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group ">
			<label for="telp_cs">
				<b>No. Telp Contact Person</b> <span class="span-required"> *</span>
			</label>

			<input type="text" class="form-control m-input m-input--square" name="no_cp" id="no_cp" placeholder="Masukan No. Telp Cs" value="@if(isset($data->no_cp)){{ $data->no_cp }}@else{{ old('no_cp') }}@endif" maxlength="16" required>

			@if ($errors->has('telp_cs'))
			<label style="color: red">
				{{ $errors->first('telp_cs') }}
			</label>
			@endif
		</div>

		<div class="form-group m-form__group ">
			<label for="is_aktif">
				<b>Is Aktif </b>
			</label>

			<div class="row">
				<div class="col-md-12 checkbox">

					<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>

				</div>
			</div>
		</div>
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
	
</form>
@endif

<script>
	$('#id_region').select2({
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
		
		@if(isset($data->wilayah->nama_wil))
			$("#id_region").empty();
			$("#id_region").append('<option value=' + {{ $data->wilayah->id_wil }} + '>'+"{{ strtoupper($data->wilayah->nama_wil) }}"+'</option>');
		@endif
</script>
@endsection