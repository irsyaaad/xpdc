@extends('template.document')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12" >
			<table class="table table-hover table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Asal</th>
						<th>Tujuan</th>
						<th>Kg</th>
						<th>Kgv</th>
						<th>M3</th>
						<th>Borongan</th>
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
							@if(isset($value->asal->nama_wil)){{ strtoupper($value->asal->nama_wil) }}@endif
						</td>
						<td>
							@if(isset($value->tujuan->nama_wil)){{ strtoupper($value->tujuan->nama_wil) }}@endif
						</td>
						<td>
							Rp. {{ number_format($value->hrg_brt, 0, ',', '.')." / Kg "  }}
						</td>
						<td>
							@isset($value->hrg_volume)
							Rp. {{ number_format($value->hrg_brt, 0, ',', '.')." / KgV "  }}
							@endisset
						</td>
						<td>
							@isset($value->hrg_kubik)
							Rp. {{ number_format($value->hrg_kubik, 0, ',', '.')." / M3 "  }}
							@endisset
						</td>
						<td>
							Rp. {{ number_format($value->hrg_borongan, 2, ',', '.') }}
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
						<td class="text-center">
							<div class="dropdown ">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									<form method="POST" action="{{ url(Request::segment(1).'/'.$value->id_tarif) }}" id="form-delete{{ $value->id_tarif}}" name="form-delete{{ $value->id_tarif}}">
										@csrf
										{{method_field("DELETE") }}
										<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_tarif.'/edit') }}">
											<i class="fa fa-pencil"></i> Edit
										</a>
										<a class="dropdown-item" href="#" onclick="CheckDelete('{{ $value->id_tarif }}')"><i class="fa fa-times"></i> Delete</a>
									</form>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include("template.paginate")
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" or Request::segment(3)=="show")

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('tarifhandling') }} @else{{ route('tarifhandling.update', $data->id_tarif) }}@endif" enctype="multipart/form-data">
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
			<label for="hrg_brt" class="label-form">
				<b>Harga Kg</b> <span class="span-required"> *</span>
			</label>
			
			<input type="number" name="hrg_brt" id="hrg_brt" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_brt)){{ $data->hrg_brt }}@else{{ old("hrg_brt")}}@endif">
			
			@if ($errors->has('hrg_brt'))
			<label style="color: red">
				{{ $errors->first('hrg_brt') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="hrg_brt" class="label-form">
				<b>Harga Kgv</b> <span class="span-required"> *</span>
			</label>
			
			<input type="number" name="hrg_volume" id="hrg_volume" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_volume)){{ $data->hrg_volume }}@else{{ old("hrg_volume")}}@endif">
			
			@if ($errors->has('hrg_brt'))
			<label style="color: red">
				{{ $errors->first('hrg_brt') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="hrg_brt" class="label-form">
				<b>Harga M3</b> <span class="span-required"> *</span>
			</label>
			
			<input type="number" name="hrg_kubik" id="hrg_kubik" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_kubik)){{ $data->hrg_kubik }}@else{{ old("hrg_kubik")}}@endif">
			
			@if ($errors->has('hrg_brt'))
			<label style="color: red">
				{{ $errors->first('hrg_brt') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="hrg_vol" class="label-form">
				<b>Harga Borongan</b> <span class="span-required"> *</span>
			</label>
			
			<input type="number" name="hrg_borongan" id="hrg_borongan" class="form-control m-input m-input--square" required maxlength="100" onkeyup="ToRupiah()" value="@if(isset($data->hrg_borongan)){{$data->hrg_borongan}}@else{{old("hrg_borongan")}}@endif">
			
			@if ($errors->has('hrg_vol'))
			<label style="color: red">
				{{ $errors->first('hrg_vol') }}
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
@endif

@endsection

@section('script')

@if(Request::segment(2)=="create" or Request::segment(3)=="edit")
<link rel="stylesheet" href="{{ asset('select2/dist/css/select2.min.css') }}">
<script src="{{ asset('select2/dist/js/select2.js') }}"></script>
@endif
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
	
	@if(isset($page))
	$("#shareselect").val('{{ $page }}');
	@endif
	
	$(document).ready(function(){
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
	
	$("#shareselect").on("change", function(e) {
		$("#form-share").submit();
	});
	
	@if(isset($page))
	$("#shareselect").val({{ $page }});
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
	
	$.ajax({
		type: "GET", 
		url: "{{ url("getLayanan") }}", 
		dataType: "json",
		beforeSend: function(e) {
			if(e && e.overrideMimeType) {
				e.overrideMimeType("application/json;charset=UTF-8");
			}
		},
		success: function(response){
			$.each(response,function(key, value)
			{
				$("#filterlayanan").append('<option value=' + value.kode + '>' + value.value + '</option>');
			});
			
			@if(Session('id_layanan')!=null)
			$("#filterlayanan").val({{ Session('id_layanan') }});
			@endif
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.log(thrownError);
		}
	});
	
</script>
@endsection