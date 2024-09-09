@extends('template.document2')

@section('data')

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }} @else{{ route('perijinan.update', $data->id_perijinan) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif

	@csrf
	<div class="row">
		<div class="form-group col-md-6">
			<label for="id_perush">
				<b>Perusahaan / Devisi</b> <span class="span-required"> *</span>
			</label>
			<select class="form-control" id="id_perush" name="id_perush">
				<option value=""> -- Pilih Perusahaan / Devisi --</option>
				@foreach($perusahaan as $key => $value)
				<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label for="id_karyawan">
				<b>Nama Karyawan</b> <span class="span-required"> *</span>
			</label>
			<select class="form-control" id="id_karyawan" name="id_karyawan">
				@foreach($karyawan as $key => $value)
				<option value="{{ $value->id_karyawan }}"> {{ $value->nm_karyawan }}</option>
				@endforeach
			</select>
		</div>
		
		<div class="form-group col-md-6">
			<label for="jenis_perijinan">
				<b>Jenis Perijinan</b> <span class="span-required"> *</span>
			</label>
			
			<select class="form-control" id="jenis_perijinan" name="jenis_perijinan">
				<option value="">-- Pilih Jenis Izin --</option>
				@foreach($jenis as $key => $value)
				<option value="{{$value->id_jenis}}">{{strtoupper($value->nm_jenis)}}</option>
				@endforeach
			</select>
			
			@if ($errors->has('nm_jenis'))
			<label style="color: red">
				{{ $errors->first('nm_jenis') }}
			</label>
			@endif
		</div>
		
		<div class="row form-group col-md-6">
			
			<div class="col">
				<label for="dr_tgl">
					<b>Dari Tanggal</b> <span class="span-required"> *</span>
				</label>
				<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($data->dr_tgl)){{$data->dr_tgl}}@else{{ old('dr_tgl') }}@endif">
			</div>
			
			<div class="col" id="div_tgl">
				<label for="sp_tgl">
					<b>Sampai Tanggal</b> <span class="span-required"> *</span>
				</label>
				<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($data->sp_tgl)){{$data->sp_tgl}}@else{{ old('sp_tgl') }}@endif">
			</div>
		</div>
		
		<div class="row form-group col-md-6" id="div_jam">
			<div class="col">
				<label for="dr_jam">
					<b>Dari Jam</b> <span class="span-required"> *</span>
				</label>
				<input type="time" class="form-control" name="dr_jam" id="dr_jam" value="@if(isset($data->dr_jam)){{$data->dr_jam}}@else{{ old('dr_jam') }}@endif">
			</div>
			<div class="col">
				<label for="sp_jam">
					<b>Sampai Jam</b> <span class="span-required"> *</span>
				</label>
				<input type="time" class="form-control" name="sp_jam" id="sp_jam" value="@if(isset($data->sp_jam)){{$data->sp_jam}}@else{{ old('sp_jam') }}@endif">
			</div>
		</div>
		
		<div class="form-group col-md-6">
			<label for="keterangan">
				<b>Foto Bukti Ijin</b>
			</label>
			
			<input class="form-control" name="dok1" id="dok1" type="file" />
			
			@if ($errors->has('dok1'))
			<label style="color: red">
				{{ $errors->first('dok1') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-6">
			<label for="keterangan">
				<b>Keterangan</b> <span class="span-required"> *</span>
			</label>
			
			<textarea type="text" class="form-control" name="keterangan" id="keterangan">@if(isset($data->keterangan)){{$data->keterangan}}@endif</textarea>
			
			@if ($errors->has('keterangan'))
			<label style="color: red">
				{{ $errors->first('keterangan') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-6">
			@include('template.inc_action')
		</div>
	</div>
</form>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Pengumuman</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h3 style="margin-left: 7%;color:red;font-weight: bold;">BATAS IJIN MAKSIMAL 4 HARI</h3>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>

	$("#id_karyawan").select2();
	$("#id_perush").select2();
	$("#div_tgl").hide();
	$("#div_jam").hide();
	
	@if(isset($data->id_jenis))
	$("#jenis_perijinan").val('{{ $data->id_jenis }}');
	@endif
	
	@if(isset($format->format) and $format->format=="1")
	$("#div_tgl").hide();
	$("#div_jam").show();
	@elseif(isset($format->format) and $format->format=="2")
	$("#div_tgl").show();
	$("#div_jam").hide();
	@endif
	
	$("#sp_tgl").on("change", function(e) {
		var oneDay = 24*60*60*1000;
		var firstDate = new Date($("#dr_tgl").val());
		var secondDate = new Date($("#sp_tgl").val());
		var hasil = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
		var ct = $("#jenis_perijinan").val();
		
		if (hasil > 4 && ct != "c") {
			$("#modal-status").modal('show');
			$("#sp_tgl").val(null);
		}	
		
	});
	
	$("#jenis_perijinan").on("change", function(e) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "{{ url('jenisperijinan') }}/"+$("#jenis_perijinan").val()+"/getjenis",
			success: function(data) {
				if(data.format=="2"){
					$("#div_tgl").show();
					$("#div_jam").hide();
				}else{
					$("#div_tgl").hide();
					$("#div_jam").show();
				}
			},
		});
		
		$("#dr_tgl").val("");
		$("#sp_tgl").val("");
		$("#dr_jam").val("");
		$("#sp_jam").val("");
	});
	
	@if(isset($data->id_perush))
	$("#id_perush").val('{{ $data->id_perush }}').trigger("change");
	@endif

	$('#id_perush').on("change", function(e) {
		$('#id_karyawan').empty();
		$.ajax({
			type: "GET",
			url: "{{ url('absensi/getkaryawan') }}/"+$("#id_perush").val(),
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				$('#id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
				$.each(response, function(index, value) {
					$('#id_karyawan').append('<option value="'+value.id_karyawan+'">'+value.nm_karyawan+'</option>');
				});
				$("#id_karyawan").select2();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});

	@if(isset($data->id_karyawan))
	$("#id_karyawan").val('{{ $data->id_karyawan }}').trigger("change");
	@endif

</script>
@endsection