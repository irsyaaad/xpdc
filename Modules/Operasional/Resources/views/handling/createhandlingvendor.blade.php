<div class="row">
    
    <div class="col-md-3">
        <label for="id_ven">
            <b>Vendor Tujuan</b> <span class="span-required"> *</span>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_ven" name="id_ven" required>
            <option value="">-- Vendor Tujuan --</option>
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
    
    <div class="col-md-3">
        <label for="id_sopir">
            <b>Nama Sopir</b>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_sopir" name="id_sopir">
            <option value="">-- Pilih Sopir --</option>
            @foreach($sopir as $key => $value)
            <option value="{{ $value->id_sopir }}">{{ strtoupper($value->nm_sopir) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('id_sopir'))
        <label style="color: red">
            {{ $errors->first('id_sopir') }}
        </label>
        @endif
    </div>
    
    <div class="col-md-3">
        <label for="id_armada">
            <b>Armada</b>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_armada" name="id_armada">
            <option value="">-- Pilih Armada --</option>
            @foreach($armada as $key => $value)
            <option value="{{ $value->id_armada }}">{{ strtoupper($value->nm_armada." - ".$value->no_plat) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('id_armada'))
        <label style="color: red">
            {{ $errors->first('id_armada') }}
        </label>
        @endif
    </div>	
    
    <div class="col-md-3">
        <label for="region_tuju">
            <b>Region Tujuan</b> <span class="span-required"> * </span>
        </label>
        
        <select class="form-control m-input m-input--square" id="region_tuju" name="region_tuju" required></select>
        
        @if ($errors->has('region_tuju'))
        <label style="color: red">
            {{ $errors->first('region_tuju') }}
        </label>
        @endif
    </div>
    
    <div class="col-md-3 mt-1">
        <label for="keternagan">
            <b>Keterangan</b>
        </label>
        
        <textarea id="keterangan" style="min-height:100px" name="keterangan" class="form-control" placeholder="Masukan Keterangan ..." maxlength="200">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif</textarea>
        
        @if ($errors->has('keternagan'))
        <label style="color: red">
            {{ $errors->first('keternagan') }}
        </label>
        @endif
    </div>

    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
    
</div>


@section('script')
<script type="text/javascript">

    $('#region_tuju').select2({
		placeholder: 'Cari Nama Kota ....',
		ajax: {
			url: '{{ url('getKota') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#region_tuju').empty();
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
    
    $('#id_sopir').on("change", function(e) {
		$.ajax({
			type: "GET", 
			url: "{{ url("ChainArmada") }}/"+$("#id_sopir").val(), 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				console.log(response.id_armada);
				$("#id_armada").val(response.id_armada);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});
    
	@if(isset($data->id_sopir))
	$("#id_sopir").val('{{ $data->id_sopir }}');
	@endif
	
	@if(isset($data->id_armada))
	$("#id_armada").val('{{ $data->id_armada }}');
	@endif

    @if(isset($data->id_ven))
	$("#id_ven").val('{{ $data->id_ven }}');
	@endif
    
    @if(isset($tujuan->id_wil))
    $("#region_tuju").empty();
	$("#region_tuju").append('<option value="{{ $tujuan->id_wil }}">{{ strtoupper($tujuan->nama_wil) }}</option>');
	@endif

</script>
@endsection