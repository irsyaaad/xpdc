@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }} @else{{ route('hargavendor.update', $data->id_harga) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">   
		<div class="form-group col-md-4">
			<label>Type Hpp :</label>
			<br>
			<label for="type">
				<b> <input type="radio" value="1" id="type" name="type" @if(Request::segment(3)=="edit") disabled @endif checked> Direct</b>
				<b style="margin-left: 10px"> <input type="radio" value="2" id="types" @if(Request::segment(3)=="edit") disabled @endif name="type"> Multivendor</b>
			</label>
		</div>
		
		<div class="form-group col-md-4">
			<label for="id_ven">
				<b>Wilayah Asal</b> <span class="span-required"> *</span>
			</label>
			
			<select class="form-control" id="id_asal" name="id_asal" @if(Request::segment(3)=="edit") disabled @endif required></select>
			
			<input type="hidden" name="nama_asal" id="nama_asal" value="{{ old('nama_asal') }}">
			
			@if($errors->has('id_asal'))
			<label style="color: red">
				{{ $errors->first('id_asal') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="id_tujuan">
				<b>Wilayah Tujuan</b> <span class="span-required"> *</span>
			</label>
			
			<select class="form-control" id="id_tujuan" name="id_tujuan" @if(Request::segment(3)=="edit") disabled @endif  required></select>
			
			<input type="hidden" name="nama_tujuan" id="nama_tujuan" value="{{ old('nama_tujuan') }}">
			
			@if ($errors->has('id_tujuan'))
			<label style="color: red">
				{{ $errors->first('id_tujuan') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4" id="lbl-vendor">
			<label for="id_ven">
				<b>Vendor</b>
			</label>
			
			<select class="form-control" id="id_ven" @if(Request::segment(3)=="edit") disabled @endif name="id_ven">
				<option value=""> -- Pilih Vendor --</option>
				@foreach($vendor as $key => $value)
				<option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('id_ven'))
			<label style="color: red">
				{{ $errors->first('id_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4" id="lbl-harga">
			<label for="harga">
				<b>Hpp Per Kg</b>
			</label>
			
			<input type="number" step="any" class="form-control" name="harga" id="harga" value="@if(old("harga")!=null){{ old("harga") }}@elseif(isset($data->harga)){{ $data->harga }}@endif">
			
			@if ($errors->has('harga'))
			<label style="color: red">
				{{ $errors->first('harga') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4" id="lbl-kubik">
			<label for="harga">
				<b>Hpp Per M3</b>
			</label>
			
			<input type="number" step="any" class="form-control" name="hrg_kubik" id="hrg_kubik" value="@if(old("hrg_kubik")!=null){{ old("hrg_kubik") }}@elseif(isset($data->hrg_kubik)){{ $data->harga }}@endif">
			
			@if ($errors->has('hrg_kubik'))
			<label style="color: red">
				{{ $errors->first('hrg_kubik') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4" id="lbl-min_kg">
			<label for="min_kg">
				<b>Min Kg</b>
			</label>
			
			<input type="number" step="any" class="form-control" name="min_kg" id="min_kg" value="@if(old("min_kg")!=null){{ old("min_kg") }}@elseif(isset($data->min_kg)){{ $data->min_kg }}@endif">
			
			@if ($errors->has('min_kg'))
			<label style="color: red">
				{{ $errors->first('min_kg') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4" id="lbl-min_kubik">
			<label for="min_kubik">
				<b>Min M3</b>
			</label>
			
			<input type="number" step="any" class="form-control" name="min_kubik" id="min_kubik" value="@if(old("min_kubik")!=null){{ old("min_kubik") }}@elseif(isset($data->min_kubik)){{ $data->min_kubik }}@endif">
			
			@if ($errors->has('min_kubik'))
			<label style="color: red">
				{{ $errors->first('min_kubik') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4" id="lbl-time">
			<label for="harga">
				<b>Lead Time (Hari)</b>
			</label>
			
			<input type="number" step="any" class="form-control" name="time" id="time" value="@if(old("time")!=null){{ old("time") }}@elseif(isset($data->time)){{ $data->time }}@endif">
			
			@if ($errors->has('time'))
			<label style="color: red">
				{{ $errors->first('time') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-4">
			<label for="rekomendasi">
				<input type="checkbox" name="rekomendasi" id="rekomendasi"  value="1">  <b> Rekomendasikan Harga ini </b>
			</label>
			
			@if ($errors->has('rekomendasi'))
			<label style="color: red">
				{{ $errors->first('rekomendasi') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="keterangan">
				<b>Keterangan</b>
			</label>
			
			<textarea type="text" class="form-control" name="keterangan" id="keterangan">@if(old("keterangan")!=null){{ old("keterangan") }}@elseif(isset($data->keterangan)){{ $data->keterangan }}@endif</textarea>
			
			@if ($errors->has('keterangan'))
			<label style="color: red">
				{{ $errors->first('keterangan') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-3">
			@include('template.inc_action')
		</div>
	</div>
</form>
@endsection

@section("script")
<script>
	$('#id_ven').select2();
	
	@if(Request::segment(2)=="create")
	$('#id_asal').select2({
		placeholder: 'Cari Wilayah Asal ....',
		minimumInputLength: 0,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_asal').empty();
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
	
	$('#id_asal').on("change", function(e) { 
		$("#nama_asal").val($('#id_asal').text());
	});
	
	$('#id_tujuan').select2({
		placeholder: 'Cari Wilayah Tujuan ....',
		minimumInputLength: 0,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_tujuan').empty();
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
	
	$('#id_tujuan').on("change", function(e) { 
		$("#nama_tujuan").val($('#id_tujuan').text());
	});
	@endif
	
	$("input[type='radio'][name='type']").on("change", function(e) { 
		if(this.value==1){
			$("#lbl-harga").show();
			$("#lbl-vendor").show();
			$("#lbl-kubik").show();
			$("#lbl-min_kubik").show();
			$("#lbl-min_kg").show();
			$("#lbl-time").show();
		}else{
			$("#lbl-harga").hide();
			$("#lbl-vendor").hide();
			$("#lbl-kubik").hide();
			$("#lbl-min_kubik").hide();
			$("#lbl-min_kg").hide();
			$("#lbl-time").hide();
		}
	});
	
	@if(old("nama_asal") != null)
	$('#id_asal').append('<option value="{{ old("id_asal") }}">{{ strtoupper(old("nama_asal")) }}</option>');
	@elseif(isset($asal->id_wil))
	$('#id_asal').append('<option value="{{ $asal->id_wil }}">{{ $asal->nama_wil }}</option>');
	@endif
	
	@if(old("nama_tujuan") != null)
	$('#id_tujuan').append('<option value="{{ old("id_tujuan") }}">{{ strtoupper(old("nama_tujuan")) }}</option>');
	@elseif(isset($tujuan->id_wil))
	$('#id_tujuan').append('<option value="{{ $tujuan->id_wil }}">{{ $tujuan->nama_wil }}</option>');
	@endif
	
	@if(isset($data->id_ven))$("#id_ven").select2().val("{{ $data->id_ven }}").trigger("change");@endif

	@if(isset($data->type) and $data->type == '1')
	$("input[name='type'][value='1']").attr("checked", true);
	@elseif(isset($data->type) and $data->type == '2')
	$("input[name='type'][value='2']").attr("checked", true);
	@endif
	
	@if(old("type")!=null and old("type") == 1)
	$("input[name='type'][value='1']").attr("checked", true);
	@elseif(old("type")!=null and old("type") == 2)
	$("input[name='type'][value='2']").attr("checked", true);
	@endif
	
	@if(old("id_asal") != null)$("#id_asal").select2().val("{{ old("id_asal") }}").trigger("change");@endif
	@if(old("id_ven") != null)$("#id_ven").select2().val("{{ old("id_ven") }}").trigger("change");@endif
	@if(old("id_tujuan") != null)$("#id_tujuan").select2().val("{{ old("id_tujuan") }}").trigger("change");@endif
	
	@if(isset($data->type) and $data->type=='1')
		$("#lbl-harga").show();
		$("#lbl-vendor").show();
		$("#lbl-kubik").show();
		$("#lbl-min_kubik").show();
		$("#lbl-min_kg").show();
		$("#lbl-time").show();
	@elseif(isset($data->type) and $data->type==2)
		$("#lbl-harga").hide();
		$("#lbl-vendor").hide();
		$("#lbl-kubik").hide();
		$("#lbl-min_kubik").hide();
		$("#lbl-min_kg").hide();
		$("#lbl-time").hide();
	@endif
	
	@if(old("type")==1)
		$("#lbl-harga").show();
		$("#lbl-vendor").show();
		$("#lbl-kubik").show();
		$("#lbl-min_kubik").show();
		$("#lbl-min_kg").show();
		$("#lbl-time").show();
	@elseif(old("type")==2)
		$("#lbl-harga").hide();
		$("#lbl-vendor").hide();
		$("#lbl-kubik").hide();
		$("#lbl-min_kubik").hide();
		$("#lbl-min_kg").hide();
		$("#lbl-time").hide();
	@endif
	
	@if(old("rekomendasi") != null and old("rekomendasi")==1)
	$('#rekomendasi').attr("checked", true);
	@elseif(old("rekomendasi") != null and old("rekomendasi")==0)
	$('#rekomendasi').attr("checked", false);
	@elseif(isset($data->rekomendasi) and $data->rekomendasi==1)
	$('#rekomendasi').attr("checked", true);
	@elseif(isset($data->rekomendasi) and $data->rekomendasi==0)
	$('#rekomendasi').attr("checked", false);
	@endif

</script>
@endsection