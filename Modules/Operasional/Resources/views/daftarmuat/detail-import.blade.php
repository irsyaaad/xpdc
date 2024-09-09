@extends('template.document')

@section('data')
<form action="{{ url(Request::segment(1)).'/'.Request::segment(2).'/import' }}" method="POST">
	<div class="row">
		@csrf
		@if(Request::segment(3)=="import")
		<div class="col-md-12">
			
			<div class="row" style="margin-top:20px">
				<div class="col-md-4">
					<label for="id_perush">
						<b>Vendor Lsj Group</b> <span class="span-required"> *</span>
					</label>
					
					<select class="form-control m-input m-input--square" id="id_perush" name="id_perush">
						<option value=""> -- Pilih Vendor Asal --</option>
						@foreach($perusahaan as $key => $value)
						<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
						@endforeach
					</select>
					
					@if ($errors->has('id_perush'))
					<label style="color: red">
						{{ $errors->first('id_perush') }}
					</label>
					@endif
				</div>
				
				<div class="col-md-3">
					<div class="form-group m-form__group">
						<label for="Module" style="font-weight: bold;">
							<b>Data STT : </b>
						</label>
						<select name="id_stt" id="id_stt" placeholder="Masukan Color menu" class="form-control m-input m-input--square">
							<option value=""> -- Pilih STT --</option>
							@foreach($stt as $key => $value)
							@if(count($value->koli2) < $value->n_koli)
							<option value="{{ $value->id_stt }}">{{ $value->id_stt }}</option>
							@endif
							@endforeach
						</select>
						
						@if ($errors->has('id_stt'))
						<label style="color: red">
							{{ $errors->first('id_stt') }}
						</label>
						@endif
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group m-form__group" style="margin-top: 14%">
						<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Pilih </button>
					</div>
				</div>
				
				<div class="col-md-2 text-right">
					<div class="form-group m-form__group" style="margin-top: 14%">
						<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/show' }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
					</div>
				</div>
				
			</div>
		</div>
		@endif
	</div>
</form>
@if(isset($data))
<div class="row">
	
	<div class="col-md-12">
		<table class="table">
			<tr>
				<td>KODE STT : <b>{{ strtoupper($data->id_stt) }}</b></td>
				<td>Perusahaan Pengirim : <b>{{ $data->perush_asal->nm_perush }}</b></td>
				<td>Layanan : <b>{{ strtoupper($data->layanan->nm_layanan) }}</b></td>
			</tr>
			<tr>
				<td>Tanggal Masuk : <b>{{ dateindo($data->tgl_masuk) }}</b></td>
				<td>Koli Termuat : <b>@if(isset($data->koli2)) {{ count($data->koli2) }} @endif</b></td>
				<td>Total Koli : <b>{{ $data->n_koli }}</b></td>
			</tr>
		</table>
	</div>
</div>

<hr>
<form action="{{ url('dmtrucking').'/'.Request::segment(2).'/savekoli' }}" method="POST">
	@csrf
	@if(isset($koli) and count($koli)>0)
	<div class="row">
		<div class="col-md-2">
			<input type="hidden" name="kode_stt" id="kode_stt">
		</div>
		<div class="col-md-2">
			<input type="number" name="dari_koli" id="dari_koli" placeholder="Dari Koli ..." class="form-control">
		</div>
		<div class="col-md-2">
			<input type="number" name="ke_koli" id="ke_koli" placeholder="Sampai Koli ..." class="form-control">
		</div>
		<div class="col-md-1">
			<button class="btn btn-md btn-primary" type="button" onclick="getCheck()">
				<i class="fa fa-check"></i> Cek
			</button>
		</div>
		<div class="col-md-2 checkbox" style="margin-top: 1%; margin-left: 2%">
			<label><input type="checkbox" value="1" id="c_all" name="c_all"> <b>Pilih Semua</b></label>
		</div>
		<div class="col-md-2">
			<button class="btn btn-md btn-success" type="submit">
				<i class="fa fa-save"></i> Simpan
			</button>
		</div>
	</div>
	@endif
	
	<table class="table table-responsive table-stripped" width="100%" style="margin-top: 2%">
		<thead style="background-color:grey; color: #fff">
			<tr>
				<th>No</th>
				<th>Kode Koli</th>
				<th>Koli Ke</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($koli as $key => $value)
			<tr>
				<td>{{ ($key+1) }}</td>
				<td>{{ $value->id_koli }}</td>
				<td>{{ $value->no_koli }}</td>
				<td><input type="checkbox" name="c_koli[]" id="c_koli{{ $value->no_koli }}" class="form-control c_koli" value="{{  $value->id_koli }}"></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@endif
@endsection

@section("script")
<script type="text/javascript">
	var darikoli = 0;
	var kekoli = 0;
		
		@if(isset($data))
		$("#id_stt").val('{{ $data->id_stt }}');
		$("#kode_stt").val('{{ $data->id_stt }}');
		$("#id_perush").val('{{ $data->id_perush_asal }}');
		$("#id_stt").append("<option value={{ $data->id_stt }}>{{ $data->id_stt }}</option>");
		$("#id_stt").val('{{ $data->id_stt }}');
		
		$(function(){
			$('#c_all').change(function(){

				if($(this).is(':checked')) {
					$(".c_koli").prop("checked", true);
				}else{   
					$(".c_koli").prop("checked", false);
				}

			});
		});
			
		function getCheck() {
			var dari_koli = $("#dari_koli").val();
			var ke_koli = $("#ke_koli").val();
			
			if(dari_koli=="" || ke_koli==""){
				alert("jangan kosongi koli ");
			}else{
				
				$(".c_koli").prop("checked", false);
				for (i = dari_koli; i <= ke_koli; i++) {
					$("#c_koli"+i).prop("checked", true);
				}
				
			}
		}
		
		$("#dari_koli").keyup(function() {
			darikoli = $("#dari_koli").val();
		});
		
		$("#ke_koli").keyup(function() {
			kekoli = $("#ke_koli").val();
		});
		
		if(isNaN(darikoli) || darikoli<0){
			$("#dari_koli").val('1');
		}
		
		if(isNaN(kekoli) || kekoli<0){
			$("#ke_koli").val('1');
		}
		@endif
		
		$("#id_perush").on('change', function() {
			var id_dm = '{{ Request::segment(2) }}';
			$.ajax({
				type: "GET", 
				url: "{{ url("getSttPerush") }}/"+this.value+"/"+id_dm, 
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){
					$("#id_stt").empty();
					$.each(response,function(key, value)
					{
	                    $("#id_stt").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});
		});
</script>
@endsection