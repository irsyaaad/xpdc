@extends('template.document')

@section('data')

<style type="text/css">
	.select2-selection {
		height: 40px !important;
		padding: 2px;
	}
</style>

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-hover table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Layanan</th>
						<th>Asal</th>
						<th>Tujuan</th>
						<th>Kgv</th>
						<th>Kg</th>
						<th>M3</th>
						<th>Estimasi Tiba</th>
						<th>Standart</th>
						<th>Is Aktif</th>
						<th class="text-center">
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>
							{{ $key+1 }}
						</td>			
						<td>
							@if(isset($value->layanan->nm_layanan)){{ strtoupper($value->layanan->nm_layanan) }}@endif
						</td>
						<td>
							@if(isset($value->asal->nama_wil)){{ strtoupper($value->asal->nama_wil) }}@endif
						</td>
						<td>
							@if(isset($value->tujuan->nama_wil)){{ strtoupper($value->tujuan->nama_wil) }}@endif
						</td>
						<td>
							{{ toRupiah($value->hrg_vol)." / ".$value->min_vol }}
						</td>
						<td>
							{{ toRupiah($value->hrg_brt)." / ".$value->min_brt  }}
						</td>
						<td>
							{{ toRupiah($value->hrg_kubik)." / ".$value->min_kubik  }}
						</td>
						<td>
							@if(isset($value->estimasi) and $value->estimasi!="")
							{{ $value->estimasi }}
							@endif
						</td>
						<td>
							@if($value->is_standart==true)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
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
							{!! inc_edit($value->id_tarif) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" or Request::segment(3)=="show")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('tarif') }}@else {{ route('tarif.update', $data->id_tarif) }} @endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	
	@csrf
	<div class="row">
		<div class="form-group col-md-6">
			<label for="id_asal" class="label-form">
				<b>Wilayah Asal</b> <span class="span-required"> *</span>
			</label>
			
			<select id="id_asal" name="id_asal" class="form-control" required></select>
			
			@if ($errors->has('id_asal'))
			<label style="color: red">
				{{ $errors->first('id_asal') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="id_tujuan" class="label-form"> 
				<b>Wilayah Tujuan</b><span class="span-required"> *</span>
			</label>
			
			<select id="id_tujuan" name="id_tujuan" class="form-control" required></select>
			
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
			
			<select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan" required>
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
			
			<input type="number" name="estimasi" id="estimasi" class="form-control m-input m-input--square" placeholder="3" value="@if(isset($data->estimasi)){{ $data->estimasi }}@else{{old("estimasi")}}@endif">
			
			@if ($errors->has('estimasi'))
			<label style="color: red">
				{{ $errors->first('estimasi') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="hrg_brt" class="label-form">
				<b>Harga Kg</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" name="hrg_brt" id="hrg_brt" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_brt)){{ $data->hrg_brt }}@else{{ old("hrg_brt")}}@endif">
			
			@if ($errors->has('hrg_brt'))
			<label style="color: red">
				{{ $errors->first('hrg_brt') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="min_brt" class="label-form">
				<b>Minimal Kg</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" name="min_brt" id="min_brt" class="form-control m-input m-input--square" maxlength="100" required value="@if(isset($data->min_brt)){{ $data->min_brt }}@else{{old("min_brt")}}@endif">
			
			@if ($errors->has('min_brt'))
			<label style="color: red">
				{{ $errors->first('min_brt') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="hrg_vol" class="label-form">
				<b>Harga Kgv</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" name="hrg_vol" id="hrg_vol" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_vol)){{$data->hrg_vol}}@else{{old("hrg_vol")}}@endif">
			
			@if ($errors->has('hrg_vol'))
			<label style="color: red">
				{{ $errors->first('hrg_vol') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-6">
			<label for="min_vol" class="label-form">
				<b>Minimal Kgv</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" name="min_vol" id="min_vol" class="form-control m-input m-input--square" maxlength="100" required value="@if(isset($data->min_vol)){{ $data->min_vol }}@else{{old("min_vol")}}@endif">
			
			@if ($errors->has('min_vol'))
			<label style="color: red">
				{{ $errors->first('min_vol') }}
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
			
			<textarea name="info" id="info" class="form-control m-input m-input--square" placeholder="Deskripsi Info" maxlength="200">@if(isset($data->info)){{ $data->info }}@else{{old("info")}}@endif</textarea>
			
			@if ($errors->has('info'))
			<label style="color: red">
				{{ $errors->first('info') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="is_standart" class="label-form">
				<b>Standart ? </b>
			</label>
			
			<div class="col-md-12 checkbox">
				<label><input type="checkbox" value="1" id="is_standart" name="is_standart"> Standart</label>
			</div>
			
			@if ($errors->has('is_standart'))
			<label style="color: red">
				{{ $errors->first('is_standart') }}
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
		
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
	</div>
</form>

@if(isset($proyeksi))
@include("detailproyeksi")
@endif

@endif
@endsection

@section('script')

<script type="text/javascript" src="{{ asset('assets/plugins/autoNumeric.js') }}"></script>

<script type="text/javascript">
	@if(isset($asal) and $asal!= null)
	$("#filterasal").empty();
	$("#filterasal").append('<option value=' + {{$asal->id_wil }} + '>'+"{{ strtoupper($asal->nama_wil) }}"+'</option>');
	@endif
	@if(isset($tujuan) and $tujuan!= null)
	$("#filtertujuan").empty();
	$("#filtertujuan").append('<option value=' + {{$tujuan->id_wil }} + '>'+"{{ strtoupper($tujuan->nama_wil) }}"+'</option>');
	@endif
	@if(Session('id_layanan'))
	$("#filterlayanan").val({{ Session('id_layanan') }});
	@endif
	
	$(document).ready(function(){
		$(".m-datatable").hide();
		$("#is_standart").prop("checked", true);
		$("#is_aktif").prop("checked", true);
		
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
		
		$('#id_pelanggan').select2({
			placeholder: 'Cari Pelanggan ....',
			minimumInputLength: 3,
			ajax: {
				url: '{{ url('getPelanggan') }}',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					$('#id_pelanggan').empty();
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
		
		$(".select2-selection__arrow").css("margin-top", "5%");
	});
	
	@if(isset($data->is_standart) and $data->is_standart==1)
	$("#is_standart").prop("checked", true);
	@endif
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
	@if(isset($asal->nama_wil))
	$('#id_asal').append('<option value="{{ $asal->id_wil }}">{{ $asal->nama_wil }}</option>');
	@endif
	
	@if(isset($tujuan->nama_wil))
	$('#id_tujuan').append('<option value="{{ $tujuan->id_wil }}">{{ $tujuan->nama_wil }}</option>');
	@endif
	
	@if(isset($pelanggan->nm_pelanggan))
	$('#id_pelanggan').append('<option value="{{ $pelanggan->id_pelanggan }}">{{ $pelanggan->nm_pelanggan }}</option>');
	@endif
	
	@if(Request::segment(1)=="tarif" && Request::segment(2)==null or Request::segment(2)=="filter")

	$('#f_id_asal').select2({
		placeholder: 'Cari Wilayah Asal ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_asal').empty();
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

	$('#f_id_tujuan').select2({
		placeholder: 'Cari Wilayah Tujuan ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_tujuan').empty();
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

	@if(isset($filter["f_id_asal"]->id_wil))
	$("#f_id_asal").empty();
	$("#f_id_asal").append('<option value={{ $filter["f_id_asal"]->id_wil }}>'+"{{ strtoupper($filter["f_id_asal"]->nama_wil) }}"+'</option>');
	@endif

	@if(isset($filter["f_id_tujuan"]->id_wil))
	$("#f_id_tujuan").empty();
	$("#f_id_tujuan").append('<option value={{ $filter["f_id_tujuan"]->id_wil }}>'+"{{ strtoupper($filter["f_id_tujuan"]->nama_wil) }}"+'</option>');
	@endif

	@if(isset($filter["f_id_layanan"]))
	$("#f_id_layanan").val('{{ $filter["f_id_layanan"] }}');
	@endif

	@endif
</script>
@endsection