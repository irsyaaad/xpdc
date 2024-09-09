@extends('template.document2')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include("template.filter-collapse")
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Group Pelanggan</th>
						<th>Alamat</th>
						<th>Telp</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $value->nm_pelanggan }}</td>
						<td>{{ $value->nm_group }}</td>
						<td>{{ $value->alamat }}</td>
						<td>{{ $value->telp }}</td>
						<td>{{ $value->email }}</td>
						<td>
							<a href="{{ url(Request::segment(1)."/".$value->id_pelanggan."/show") }}" class="btn btn-sm btn-primary">
								<span><i class="fa fa-eye"></i></span> Lihat Tarif
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(3)=="show" or Request::segment(3)=="edit" or Request::segment(3)=="create" or Request::segment(3)=="filtershow")
<div class="row">
	
	@if(isset($data) and Request::segment(3)=="show" or Request::segment(3)=="filtershow")
	<div class="col-md-12">
		<table class="table">
			<tr>
				<td>Nama Pelanggan : <b>@if(isset($data->nm_pelanggan)) {{ strtoupper($data->nm_pelanggan) }} @endif</b></td>
				<td>Perusahaan Asal : <b>@if(isset($data->perusahaan->nm_perush)){{ strtoupper($data->perusahaan->nm_perush) }} @endif</b></td>
				<td>Kota Asal : <b>@if(isset($data->wilayah->nama_wil)){{ strtoupper($data->wilayah->nama_wil) }} @endif</b></td>
			</tr>
			<tr>
				<td>Group : <b>{{ strtoupper($data->group->nm_group) }}</b></td>
				<td>Alamat : <b>{{ $data->alamat }}</b></td>
				<td class="text-right">
					<a href="{{ url("tarifpelanggan") }}" class="btn btn-sm btn-warning">
						<span><i class="fa fa-arrow-left"></i></span> Kembali
					</a>
					
					<a href="{{ url("tarifpelanggan/".Request::segment(2)."/create") }}" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Lihat Tarif">
						<span><i class="fa fa-plus"></i></span> Tambah Tarif
					</a>
				</td>
			</tr>
		</table>
	</div>
	@endif
	
	@if(Request::segment(3)=="create" or Request::segment(3)=="edit")
	@if(Request::segment(3)=="create")
	<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('tarifpelanggan') }}" enctype="multipart/form-data">
		<input type="hidden" name="id_pelanggan" id="id_pelanggan" value="{{ Request::segment(2) }}">
		@else
		<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('tarifpelanggan.update', $datas->id_tarif) }}" enctype="multipart/form-data">
			{{ method_field("PUT") }} 
			<input type="hidden" name="id_pelanggan" id="id_pelanggan" value="{{ $datas->id_pelanggan }}">
			@endif
			
			@csrf
			<div class="row" style="padding: 10px">
				
				<div class="form-group col-md-6">
					<label for="id_asal" class="label-form">
						<b>Wilayah Asal</b> <span class="span-required"> *</span>
					</label>
					
					<select id="id_asal" name="id_asal" class="form-control" maxlength="100" required ></select>
					
					@if ($errors->has('id_asal'))
					<label style="color: red">
						{{ $errors->first('id_asal') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="id_tujuan" class="label-form">
						<b>Wilayah Tujuan</b> <span class="span-required"> *</span>
					</label>
					
					<select id="id_tujuan" name="id_tujuan" class="form-control" maxlength="100" required ></select>
					
					@if ($errors->has('id_tujuan'))
					<label style="color: red">
						{{ $errors->first('id_tujuan') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="id_layanan" class="label-form">
						<b>Layanan</b> <span class="span-required"> *</span>
					</label>
					
					<select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan" maxlength="100" required >
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
				
				<div class="form-group col-md-6">
					<label for="estimasi" class="label-form">
						<b>Estimasi Tiba / Hari</b>
					</label>
					
					<input type="number" name="estimasi" id="estimasi" class="form-control m-input m-input--square" maxlength="100" required  placeholder="">
					
					@if ($errors->has('estimasi'))
					<label style="color: red">
						{{ $errors->first('estimasi') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="hrg_vol" class="label-form">
						<b>Harga Kgv</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="hrg_vol" id="hrg_vol" maxlength="100" required  class="form-control m-input m-input--square" value="@if(isset($datas->hrg_vol)){{$datas->hrg_vol}}@else{{old("hrg_vol")}}@endif">
					
					@if ($errors->has('hrg_vol'))
					<label style="color: red">
						{{ $errors->first('hrg_vol') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="hrg_brt" class="label-form">
						<b>Harga Kg</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="hrg_brt" id="hrg_brt" class="form-control m-input m-input--square" maxlength="100" required  value="@if(isset($datas->hrg_brt)){{ $datas->hrg_brt }}@else{{ old("hrg_brt")}}@endif">
					
					@if ($errors->has('hrg_brt'))
					<label style="color: red">
						{{ $errors->first('hrg_brt') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="min_vol" class="label-form">
						<b>Minimal Kgv</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="min_vol" id="min_vol" class="form-control m-input m-input--square" maxlength="100" required  value="@if(isset($datas->min_vol)){{ $datas->min_vol }}@else{{old("min_vol")}}@endif">
					
					@if ($errors->has('min_vol'))
					<label style="color: red">
						{{ $errors->first('min_vol') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="min_brt" class="label-form">
						<b>Minimal Kg</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="min_brt" id="min_brt" class="form-control m-input m-input--square" maxlength="100" required  value="@if(isset($datas->min_brt)){{ $datas->min_brt }}@else{{old("min_brt")}}@endif">
					
					@if ($errors->has('min_brt'))
					<label style="color: red">
						{{ $errors->first('min_brt') }}
					</label>
					@endif
				</div>
				
				
				<div class="form-group col-md-6">
					<label for="hrg_brt" class="label-form">
						<b>Harga M3</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="hrg_kubik" id="hrg_kubik" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_kubik)){{ $data->hrg_kubik }}@else{{ old("hrg_kubik")}}@endif">
					
					@if ($errors->has('hrg_brt'))
					<label style="color: red">
						{{ $errors->first('hrg_brt') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="min_brt" class="label-form">
						<b>Minimal M3</b> <span class="span-required"> *</span>
					</label>
					
					<input type="text" name="min_kubik" id="min_kubik" class="form-control m-input m-input--square" maxlength="100" required value="@if(isset($data->min_kubik)){{ $data->min_kubik }}@else{{old("min_kubik")}}@endif">
					
					@if ($errors->has('min_brt'))
					<label style="color: red">
						{{ $errors->first('min_brt') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-6">
					<label for="info" class="label-form">
						<b>Info Deskripsi</b>
					</label>
					
					<textarea name="info" id="info" class="form-control m-input m-input--square" placeholder="Deskripsi Info" maxlength="200">@if(isset($datas->info)){{ $datas->info }}@else{{old("info")}}@endif</textarea>
					
					@if ($errors->has('info'))
					<label style="color: red">
						{{ $errors->first('info') }}
					</label>
					@endif
				</div>
				
				<div class="form-group col-md-3">
					<label for="is_aktif" class="label-form">
						<b>Aktif ? </b>
					</label>
					
					<div class="col-md-12 checkbox">
						<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif</label>
					</div>
					
					@if ($errors->has('is_aktif'))
					<label style="color: red">
						{{ $errors->first('is_aktif') }}
					</label>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-save"></i> Submit
					</button>
					
					@if(Request::segment(3)=="create")
					<a href="{{ url(Request::segment(1).'/'.Request::segment(2)."/show") }}" class="btn btn-danger">
						<i class="fa fa-times"></i>	Cancel
					</a>
					@else
					<a href="{{ url(Request::segment(1).'/'.$datas->id_pelanggan."/show") }}" class="btn btn-danger">
						<i class="fa fa-times"></i>	Cancel
					</a>
					@endif
				</div>
			</div>
		</form>
		@elseif(Request::segment(3)=="show" or Request::segment(3)=="filtershow")
		<form action="{{ url(Request::segment(1).'/'.Request::segment(2)."/filtershow") }}" class="col-xl-12" name="form-filter" id="form-filter" method="post"> 
			@csrf
			<div class="col-xl-12">				
				<div class="form-group row">
					<input type="hidden" name="id_pelanggan" value="{{Request::segment(2)}}">
					<div class="col-md-3">
						<div class="m-form__control">
							<label style="font-weight : bold ">
								Asal
							</label>
							<select class="form-control" id="filterasal" name="filterasal"></select>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="m-form__control">
							<label style="font-weight : bold ">
								Tujuan
							</label>
							<select class="form-control" id="filtertujuan" name="filtertujuan"></select>
						</div>
					</div>
					
					<div class="col-md-3" style="padding-top:4px">
						<br>
						<button class="btn btn-md btn-primary"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1).'/'.Request::segment(2)."/show") }}" class="btn btn-md btn-warning"><span><i class="fa fa-refresh"></i></span></a>
					</div>
				</div>
			</div>
		</form>
		<div class="col-md-12">
			<br>
			<table class="table table-responsive table-striped">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Layanan</th>
						<th>Asal</th>
						<th>Tujuan</th>
						<th>Volume</th>
						<th>Berat</th>
						<th>Kubik</th>
						<th>Deskripsi</th>
						<th>Estimasi Tiba</th>
						<th>Is aktif</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($tarif as $key => $value)
					<tr>
						<td>{{ ($key+1) }}</td>
						<td>{{ strtoupper($value->layanan->nm_layanan) }}</td>
						<td>{{ strtoupper($value->asal->nama_wil) }}</td>
						<td>{{ strtoupper($value->tujuan->nama_wil) }}</td>
						<td>
							{{ number_format($value->hrg_vol, 2, ',', '.')." / ".$value->min_vol." Kgv" }}
						</td>
						<td>
							{{ number_format($value->hrg_brt, 2, ',', '.')." / ".$value->min_brt." Kg"  }}
						</td>
						<td>
							{{ number_format($value->hrg_kubik, 2, ',', '.')." / ".$value->min_kubik." M3"  }}
						</td>
						<td>
							{{ $value->info }}
						</td>
						<td>
							@if(isset($value->estimasi) and $value->estimasi!="")
							{{ $value->estimasi }} Hari
							@endif
						</td>
						<td>
							@if($value->is_aktif==true)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							{!! inc_dropdown($value->id_tarif) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
	</div>
	@endif
	
	@endsection
	
	@section("script")
	<script type="text/javascript">
		@if(isset($filter["page"]))
		$("#shareselect").val('{{ $filter["page"] }}');
		@endif
		
		@if(isset($filter["f_id_plgn_group"]))
		$("#f_id_plgn_group").val('{{ $filter["f_id_plgn_group"] }}');
		@endif

		@if(isset($filter["f_id_pelanggan"]->id_pelanggan))
		$("#f_id_pelanggan").empty();
		$("#f_id_pelanggan").append('<option value="{{ $filter["f_id_pelanggan"]->id_pelanggan }}">{{ strtoupper($filter["f_id_pelanggan"]->nm_pelanggan) }}</option>');
		@endif

		$("#shareselect").on("change", function(e) {
			$("#form-select").submit();
		});
		
		$('#id_asal').select2({
			placeholder: 'Cari Wilayah Asal ....',
			minimumInputLength: 3,
			ajax: {
				url: '{{ url('getwilayah') }}',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					$('#tujuan').empty();
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
		
		$('#id_tujuan').select2({
			placeholder: 'Cari Wilayah Tujuan ....',
			minimumInputLength: 3,
			ajax: {
				url: '{{ url('getwilayah') }}',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					$('#tujuan').empty();
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
		
		@if(isset($datas->is_standart) and $datas->is_standart==1)
		$("#is_standart").prop("checked", true);
		@endif
		
		@if(isset($datas->is_aktif) and $datas->is_aktif==1)
		$("#is_aktif").prop("checked", true);
		@endif
		
		@if(isset($datas->asal->nama_wil))
		$('#id_asal').append('<option value="{{ $datas->asal->id_wil }}">{{ $datas->asal->nama_wil }}</option>');
		@endif
		
		@if(isset($datas->tujuan->nama_wil))
		$('#id_tujuan').append('<option value="{{ $datas->tujuan->id_wil }}">{{ $datas->tujuan->nama_wil }}</option>');
		@endif
		
		@if(isset($pelanggan->nm_pelanggan))
		$('#id_pelanggan').append('<option value="{{ $pelanggan->id_pelanggan }}">{{ $pelanggan->nm_pelanggan }}</option>');
		@endif

		@if(isset($asal->nama_wil))
		$('#id_asal').append('<option value="{{ $asal->id_wil }}">{{ $asal->nama_wil }}</option>');
		$('#filterasal').append('<option value="{{ $asal->id_wil }}">{{ $asal->nama_wil }}</option>');
		@endif
		@if(isset($tujuan->nama_wil))
		$('#filtertujuan').append('<option value="{{ $tujuan->id_wil }}">{{ $tujuan->nama_wil }}</option>');
		@endif

		$('#filterasal').select2({
			placeholder: 'Cari Wilayah Asal ....',
			minimumInputLength: 3,
			ajax: {
				url: '{{ url('getwilayah') }}',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					$('#tujuan').empty();
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
		
		$('#filtertujuan').select2({
			placeholder: 'Cari Wilayah Tujuan ....',
			minimumInputLength: 3,
			ajax: {
				url: '{{ url('getwilayah') }}',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					$('#tujuan').empty();
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

	</script>
	@endsection