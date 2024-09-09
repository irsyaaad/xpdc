@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-striped table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Sopir</th>
						<th>Perusahaan Asal</th>
						<th>Alamat</th>
						<th>Telp</th>
						<th>Armada</th>
						<th>Is User</th>
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
							{{ strtoupper($value->nm_sopir) }}
						</td>
						<td>
							@if(isset($value->nm_perush))
							{{ strtoupper($value->nm_perush) }}
							@endif
						</td>
						<td>
							{{ $value->alamat }}
						</td>
						<td>
							{{ $value->telp }}
						</td>
						<td>
							@if(isset($value->nm_armada)){{ $value->nm_armada }}@endif
						</td>
						<td>
							@if($value->is_user==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
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
							<center>
								<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" x-placement="bottom-end" style="position: absolute; transform: translate3d(107px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
										<form method="POST" action="{{ url('sopir')."/".$value->id_sopir }}" id="form-delete{{ $value->id_sopir }}" name="form-delete{{ $value->id_sopir }}">
											{{ method_field("DELETE") }}
											<a class="dropdown-item" href="{{ url('sopir')."/".$value->id_sopir."/show" }}"><i class="fa fa-eye"></i> Detail</a>
											<a class="dropdown-item" href="{{ url('sopir')."/".$value->id_sopir."/edit" }}"><i class="fa fa-pencil"></i> Edit</a>
											@if($value->is_user!=true)
											<a class="dropdown-item" href="{{ url('sopir')."/".$value->id_sopir."/setAkses" }}"><i class="fa fa-lock"></i> Set Akses</a>
											@endif
											<a class="dropdown-item" href="#" onclick="CheckDelete('{{ $value->id_sopir }}')"><i class="fa fa-times"></i> Delete</a>
											@csrf
										</form>
									</div>
								</div>
							</center>
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


<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('sopir') }}@else{{ url('sopir', $data->id_sopir) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="foto">
					<b>Foto Sopir</b> <span class="span-required"></span>
				</label>
				
				<input type="file" class="form-control m-input" name="foto" id="foto">
				
				@if ($errors->has('foto'))
				<label style="color: red">
					{{ $errors->first('foto') }}
				</label>
				@endif
			</div>	
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="nm_sopir">
					<b>Nama Sopir</b> <span class="span-required"> *</span>
				</label>
				
				<input type="text" class="form-control m-input m-input--square" name="nm_sopir" id="nm_sopir" value="@if(old('nm_sopir')!=null){{ old('nm_sopir') }}@elseif(isset($data->nm_sopir)){{$data->nm_sopir}}@endif" required="required" maxlength="64">
				
				@if ($errors->has('nm_sopir'))
				<label style="color: red">
					{{ $errors->first('nm_sopir') }}
				</label>
				@endif
			</div>	
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="telp">
					<b>Telp Sopir</b> <span class="span-required"> *</span>
				</label>
				
				<input type="text" class="form-control m-input m-input--square" name="telp" id="telp"  value="@if(old('telp')!=null){{ old('telp') }}@elseif(isset($data->telp)){{$data->telp}}@endif" required="required" maxlength="16">
				
				@if ($errors->has('telp'))
				<label style="color: red">
					{{ $errors->first('telp') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="no_ktp">
					<b>No. KTP</b> <span class="span-required"> *</span>
				</label>
				
				<input type="text" class="form-control m-input m-input--square" name="no_ktp" id="no_ktp"  value="@if(old('no_ktp')!=null){{ old('no_ktp') }}@elseif(isset($data->no_ktp)){{$data->no_ktp}}@endif" required="required" maxlength="16">
				
				@if ($errors->has('no_ktp'))
				<label style="color: red">
					{{ $errors->first('no_ktp') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="foto_ktp">
					<b>Foto KTP</b> <span class="span-required"> *</span>
				</label>
				
				<input type="file" class="form-control m-input m-input--square" name="foto_ktp" id="foto_ktp">
				
				@if ($errors->has('foto_ktp'))
				<label style="color: red">
					{{ $errors->first('foto_ktp') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="exp_ktp">
					<b>Tgl Berakhir KTP</b> <span class="span-required"></span>
				</label>
				
				<input type="date" class="form-control m-input m-input--square" name="exp_ktp" id="exp_ktp"  value="@if(old('exp_ktp')!=null){{ old('exp_ktp') }}@elseif(isset($data->exp_ktp)){{$data->exp_ktp}}@endif" maxlength="16">
				
				@if ($errors->has('exp_ktp'))
				<label style="color: red">
					{{ $errors->first('exp_ktp') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="no_tkp">
					<b>No. SIM</b> <span class="span-required"> *</span>
				</label>
				
				<input type="text" class="form-control m-input m-input--square" name="no_sim" id="no_sim"  value="@if(old('no_sim')!=null){{ old('no_sim') }}@elseif(isset($data->no_sim)){{$data->no_sim}}@endif" required="required" maxlength="16">
				
				@if ($errors->has('no_sim'))
				<label style="color: red">
					{{ $errors->first('no_sim') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="foto_sim">
					<b>Foto SIM</b> <span class="span-required"> *</span>
				</label>
				
				<input type="file" class="form-control m-input m-input--square" name="foto_sim" id="foto_sim">
				
				@if ($errors->has('foto_sim'))
				<label style="color: red">
					{{ $errors->first('foto_sim') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="exp_sim">
					<b>Tgl Berakhir SIM</b> <span class="span-required">*</span>
				</label>
				
				<input type="date" class="form-control m-input m-input--square" name="exp_sim" id="exp_sim"  value="@if(old('exp_sim')!=null){{ old('exp_sim') }}@elseif(isset($data->exp_sim)){{$data->exp_sim}}@endif" required maxlength="16">
				
				@if ($errors->has('exp_sim'))
				<label style="color: red">
					{{ $errors->first('exp_sim') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="alamat">
					<b>Alamat KTP</b> <span class="span-required"> *</span>
				</label>	
				
				<textarea class="form-control m-input m-input--square" name="alamat" id="alamat" maxlength="128" required="required">@if(old('alamat')!=null){{ old('alamat') }}@elseif(isset($data->alamat)){{$data->alamat}}@endif</textarea>
				
				@if ($errors->has('alamat'))
				<label style="color: red">
					{{ $errors->first('alamat') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="alamat_domisili">
					<b>Alamat Domisili</b> <span class="span-required"> *</span>
				</label>	
				
				<textarea class="form-control m-input m-input--square" name="alamat_domisili" id="alamat_domisili" maxlength="128" required="required">@if(old('alamat_domisili')!=null){{ old('alamat_domisili') }}@elseif(isset($data->alamat_domisili)){{$data->alamat_domisili}}@endif</textarea>
				
				@if ($errors->has('alamat_domisili'))
				<label style="color: red">
					{{ $errors->first('alamat_domisili') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="foto_kk">
					<b>Foto Kartu Keluarga (KK)</b> <span class="span-required"></span>
				</label>
				
				<input type="file" class="form-control m-input m-input--square" name="foto_kk" id="foto_kk" >
				
				@if ($errors->has('foto_kk'))
				<label style="color: red">
					{{ $errors->first('foto_kk') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="telp_keluarga">
					<b>No. Telp Keluarga (Suami / Istri / Anak)</b> <span class="span-required"></span>
				</label>	
				
				<input class="form-control m-input m-input--square" name="telp_keluarga" id="telp_keluarga" maxlength="16" value="@if(old('telp_keluarga')!=null){{ old('telp_keluarga') }}@elseif(isset($data->telp_keluarga)){{$data->telp_keluarga}}@endif"/>
				
				@if ($errors->has('telp_keluarga'))
				<label style="color: red">
					{{ $errors->first('telp_keluarga') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="def_armada">
					<b>Default Armada</b>
				</label>
				
				<select class="form-control m-input m-input--square" name="def_armada" id="def_armada">
					<option value="">-- Pilih Armada --</option>
					@foreach($armada as $key => $value)
					<option value="{{ $value->id_armada }}">{{ strtoupper($value->nm_armada) }}</option>
					@endforeach
				</select>
				
				@if ($errors->has('def_armada'))
				<label style="color: red">
					{{ $errors->first('def_armada') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group m-form__group">
				<label for="is_aktif">
					<b>Is Aktif </b>
				</label>
				
				<div class="row">
					<div class="col-md-12 checkbox">
						
						<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>
		
	</div>
</form>

@elseif(Request::segment(3)=="show")
<style>
	.img-ktp{
		width: 300px;
		height: 200px;
		margin-left: 20px;
	}
</style>

<div class="col-md-12 text-right" style="padding-bottom: 1%">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>

@php
if (Storage::exists('public/uploads/ktp/'.$data->foto_ktp)) {
	$path = 'public/uploads/ktp/'.$data->foto_ktp;
	
	$full_path = Storage::path($path);
	$base64 = base64_encode(Storage::get($path));
	$image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
	$data->foto_ktp = $image;
}

if (Storage::exists('public/uploads/sim/'.$data->foto_sim)) {
	$path = 'public/uploads/sim/'.$data->foto_sim;
	
	$full_path = Storage::path($path);
	$base64 = base64_encode(Storage::get($path));
	$image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
	$data->foto_sim = $image;
}
@endphp

<div class="row">
	<div class="col-md-12">
		<table class="table table-responsive table-stripped">
			<tr>
				<td>
					<label> Nama Sopir : </label>
					
					<b>
						{{ strtoupper($data->nm_sopir) }}
					</b>
				</td>
				<td>
					<label> Perusahaan Asal : </label>
					
					<b>
						@if(isset($data->perusahaan->nm_perush))
						{{ strtoupper($data->perusahaan->nm_perush) }}
						@endif
					</b>
				</td>
				<td>
					<label> Telp : </label>
					
					<b>
						{{ strtoupper($data->telp) }}
					</b>
				</td>
			</tr>
			<tr>
				<td>
					<label> Telp : </label>
					
					<b>
						{{ strtoupper($data->telp) }}
					</b>
				</td>
				<td>
					<label> Alamat Lengkap KTP : </label>
					
					<b>
						{{ $data->alamat }}
					</b>
				</td>
				
				<td>
					<label> Alamat Domisil : </label>
					
					<b>
						@if(isset($data->alamat_domisili))
						{{ $data->alamat_domisili }}
						@endif
					</b>
				</td>
			</tr>
			<tr>
				<td>
					<label> No KTP :    </label>
					
					<b>
						{{ strtoupper($data->no_ktp) }}
					</b>
				</td>
				<td>
					<label> No SIM :    </label>
					
					<b>
						{{ strtoupper($data->no_sim) }}
					</b>
				</td>
				<td>
					<label> Armada : </label>
					
					<b>
						@if(isset($data->armada->nm_armada))
						{{ strtoupper($data->armada->nm_armada) }}
						@endif
					</b>
				</td>
			</tr>
			<tr>
				<td>
					<label> Masa Aktif KTP : </label>
					
					<b>
						{{ daydate($data->exp_ktp).", ".dateindo($data->exp_ktp) }}
					</b>
				</td>
				<td>
					<label>  Masa Aktif SIM : </label>
					
					<b>
						{{ daydate($data->exp_sim).", ".dateindo($data->exp_sim) }}
					</b>
				</td>
			</tr>
			<tr>
				<td>
					<label> Telp Keluarga (Suami / Istri / Anak) : </label>
					<b>
						{{$data->telp_keluarga }}
					</b>
				</td>
				<td>
					<label> is Aktif : </label>
					@if($data->is_aktif==1)
					<i class="fa fa-check" style="color: green"></i>
					@else
					<i class="fa fa-times" style="color: red"></i>
					@endif
				</td>
			</tr>
			<tr>
				<td>
					<label> Foto KTP : </label>
					
					<img src="{{ $data->foto_ktp }}" class="img-ktp" />
				</td>
				<td>
					<label> Foto SIM : </label>
					
					<img src="{{ $data->foto_sim }}" class="img-ktp"/>
				</td>
				<td>
					<label> Foto KK : </label>
					
					<img src="{{ $data->foto_kk }}" class="img-ktp"/>
				</td>
			</tr>
		</table>
	</div>
	<div>
		
	</div>
</div>
@endif

@endsection
@section('script')

<script type="text/javascript">
	
	$('#f_id_sopir').select2({
		placeholder: 'Cari Sopir ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getSopir') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_sopir').empty();
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

	$('#f_def_armada').select2({
		placeholder: 'Cari Default Armada ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getArmada') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_def_armada').empty();
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
	
	@if(isset($filter["f_id_sopir"]->nm_sopir))
	$("#f_id_sopir").empty();
	$("#f_id_sopir").append('<option value="{{ $filter["f_id_sopir"]->id_sopir }}">{{ strtoupper($filter["f_id_sopir"]->nm_sopir) }}</option>');
	@endif
	
	@if(isset($filter["f_def_armada"]->nm_armada))
	$("#f_def_armada").empty();
	$("#f_def_armada").append('<option value={{ $filter["f_def_armada"]->id_armada }}>'+"{{ strtoupper($filter["f_def_armada"]->nm_armada.' ( '.$filter["f_def_armada"]->no_plat.' )') }}"+'</option>');
	@endif
	
	$(".col-md-6").css("padding-top", "10px");
	$(".col-md-4").css("padding-top", "10px");
	$(".col-md-7").css("padding-top", "10px");
	
	@if(Request::segment(2)=="create" or Request::segment(3)=="edit")
	
	@if(isset($data->armada->nm_armada))
	$("#def_armada").val('{{ $data->armada->id_armada }}');
	@endif
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
	@endif
	
</script>
@endsection