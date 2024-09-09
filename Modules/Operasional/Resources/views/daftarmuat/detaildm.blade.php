@extends('template.document')

@section('data')
<form action="{{ url(Request::segment(1)).'/'.Request::segment(2).'/detail' }}" method="POST">
	<div class="row">
		@csrf
		<div class="col-md-5">
			<div class="form-group m-form__group">
				<label for="Module" style="font-weight: bold;">
					<b>Data RESI : </b>
				</label>

				<select name="id_stt" id="id_stt" placeholder="Masukan Color menu" class="form-control m-input m-input--square">
					<option value=""> -- Pilih RESI --</option>
					@foreach($stt as $key => $value)
					<option value="{{ $value->id_stt }}">{{ $value->kode_stt." ( ".$value->pengirim_nm." )" }}</option>
					@endforeach
				</select>
				
				@if ($errors->has('id_stt'))
				<label style="color: red">
					{{ $errors->first('id_stt') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="form-group m-form__group" style="margin-top: 30px">
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Pilih </button>
			</div>
		</div>
		
		<div class="col-md-4 text-right" >
			<div class="form-group m-form__group"style="margin-top: 30px">
				<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/show' }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
			</div>
		</div>
	</div>
</form>
@if(isset($data))
<div class="row">
	
	<div class="col-md-12">
		<table class="table">
			<tr>
				<td>No. STT :<br> <b>{{ strtoupper($data->kode_stt) }}</b></td>
				<td>No. AWB :<br>  {{ $data->no_awb }}</td>
				<td>Tanggal Masuk :<br>  <b>{{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}</b></td>
				<td>Layanan : <br> @if(isset($data->layanan->nm_layanan)){{ $data->layanan->nm_layanan }}@endif</td>
			</tr>
			<tr>
				<td>Nama Pengirim :<br>  <b>{{ $data->pengirim_nm }}</b></td>
				<td>Alamat Pengirim : <br> @if(isset($data->asal->nama_wil)){{ $data->asal->nama_wil }} @endif, {{ $data->pengirim_alm }}  -  {{ $data->pengirim_kodepos }}</td>
				<td>Nama Penerima : <br>  <b>{{ $data->penerima_nm }}</b></td>
				<td>Alamat Penerima :<br>  @if(isset($data->tujuan->nama_wil)){{ $data->tujuan->nama_wil }}@endif, {{ $data->penerima_alm }}  -  {{ $data->penerima_kodepos }}</td>
			</tr>
			<tr>
				<td>Perusahaan Pengirim : <b>{{ $data->perush_asal->nm_perush }}</b></td>
				<td>Tipe Kirim : <b>@if(isset($data->tipekirim->nm_tipe_kirim)){{ $data->tipekirim->nm_tipe_kirim }} @endif</b></td>
				<td>Total Koli : <b>{{ $data->n_koli }}</b></td>
				<td>Koli Termuat : <b>@if(isset($data->koli2)) {{ count($data->koli2) }} @endif</b></td>
			</tr>
			<tr>
				<td>Harga Netto : <b>{{ torupiah($data->c_total) }}</b></td>
				<td>Harga Per Koli: <b>{{ torupiah($data->n_tarif_koli) }}</b></td>
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
		<div class="col-md-3 text-right">
			<button class="btn btn-md btn-success" type="submit">
				<i class="fa fa-save"></i> Simpan
			</button>
		</div>
	</div>
	@endif
	
	<table class="table table-responsive table-stripped"  width="100%" style="margin-top: 2%">
		<thead style="background-color:grey; color: #fff">
			<tr>
				<th>No</th>
				<th>Kode Koli</th>
				<th>Koli Ke</th>
				<th class="text-center">
					<label><input type="checkbox" value="1" id="c_all" name="c_all"> <b>Pilih Semua</b></label>
				</th>
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
	$("#id_stt").select2();
	var darikoli = 0;
	var kekoli = 0;
	
		@if(isset($data))
		$("#id_stt").val('{{ $data->id_stt }}');
		$("#kode_stt").val('{{ $data->id_stt }}');
		
		$(function(){
			$('#c_all').change(function()
			{
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
	</script>
	@endsection