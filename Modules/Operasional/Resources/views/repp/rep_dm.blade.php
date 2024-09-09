
@extends('template.document')

@section('data')
<form action="{{ url('repstt/cetak') }}" method="POST">
	
	<div class="row">
		@csrf
		<div class="col-md-6">
			<label for="id_perush_asal">
				<b>Perusahaan Asal</b> <span class="span-required">  *</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_perush_asal" name="id_perush_asal">
				<option value="0">-- Pilih Perusahaan --</option>
				@foreach($perusahaan as $key => $value)
				<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
		</div>
		
		<div class="col-md-6">
			<label for="id_perush_tujuan">
				<b>Perusahaan Tujuan</b> <span class="span-required">  *</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_perush_tujuan" name="id_perush_tujuan">
				<option value="0">-- Pilih Perusahaan --</option>
				@foreach($perusahaan as $key => $value)
				<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
		</div>
		
		<div class="col-md-6">
			<label for="pengirim_id_region" style="margin-top: 10px">
				<b>Kota Asal</b> <span class="span-required">  *</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="pengirim_id_region" name="pengirim_id_region">
				@if(!is_null($pengirim_id_region))
				<option value="{{ $pengirim_id_region }}">{{ $pengirim_id_region }}</option>
				@endif
			</select>
		</div>
		
		<div class="col-md-6">
			<label for="id_layanan" style="margin-top: 10px">
				<b>Layanan</b> <span class="span-required">  *</span>
			</label>
			
			<select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan">
				<option value="0">-- Pilih Layanan --</option>
				@foreach($layanan as $key => $value)
				<option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
				@endforeach
			</select>
		</div>
		
		<div class="col-md-6">
			<label for="tgl_masuk" style="margin-top: 10px">
				<b>Tanggal Masuk</b> <span class="span-required">  *</span>
			</label>
			
			<input type="date" class="form-control m-input m-input--square" id="tgl_masuk" name="tgl_masuk" value="@if(isset($tgl_masuk)){{$tgl_masuk}}@endif">
		</div>
		
		<div class="col-md-6" style="padding-top:30px">
			@include("operasional::repp.cari")
		</div>
	</div>
</form>
<br>

@if(isset($data))
<table class="m-datatable" id="html_table" width="100%" style="margin-top: 2%">
	<thead>
		<tr>
			<th>No</th>
			<th>No. STT</th>
			<th>Perusahaan</th>
			<th>Layanan</th>
			<th>Masuk</th>
			<th>Pengirim</th>
			<th>Asal</th>
			<th>Penerima</th>
			<th>Tujuan</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
			<td>
				<a href="{{ url(Request::segment(1)).'/'.$value->id_stt.'/show' }}" class="class-edit">
					{{ strtoupper($value->id_stt) }}
				</a>
			</td>
			<td>
				@if(isset($value->perush_asal)){{ $value->perush_asal->nm_perush }}@endif
			</td>
			<td>
				@if(isset($value->layanan)){{ $value->layanan->nm_layanan }}@endif
			</td>
			<td>
				{{ dateindo($value->tgl_masuk) }}
			</td>
			<td>
				{{ $value->pengirim_nm }}
			</td>
			<td>
				@if(isset($value->asal)){{ $value->asal->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				{{ $value->penerima_nm }}
			</td>
			<td>
				@if(isset($value->tujuan)){{ $value->tujuan->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				@if(isset($value->status->nm_ord_stt_stat)){{ $value->status->nm_ord_stt_stat }}@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif
@endsection

@section('script')
<script type="text/javascript">
	$(".col-md-6 > label").css("font-weight", "bold");
	
	$('#pengirim_id_region').select2({
		placeholder: 'Cari Wilayah ....',
		minimumInputLength: 3,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#pengirim_id_region').empty();
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
	
	@if(isset($id_layanan) and $id_layanan != 0)
	$('#id_layanan').val('{{$id_layanan}}');
	@endif
	
	$(document).ready(function() {
		
		@if(isset($data))
		var DatatableHtmlTableDemo=function(){
			var e=function(){
				$(".m-datatable").mDatatable({
					search:{input:$("#generalSearch")},
					columns:[{field:"Deposit Paid",
					type:"number"},{field:"Order Date",
					type:"date",
					format:"YYYY-MM-DD"}]})};
					return{
						init:function(){e(
							)}
						}
					}();
					jQuery(document).ready(function(){
						DatatableHtmlTableDemo.init()
					});
					@endif
				});
				
				
			</script>
			@endsection