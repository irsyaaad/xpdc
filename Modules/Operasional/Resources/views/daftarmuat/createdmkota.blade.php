<div class="col-md-12 text-right">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>
<div class="col-md-3 mt-2">
    <label for="pengirim_id_region">
        <b>Kota Tujuan</b> <span class="span-required"> * </span>
    </label>
    
    <select class="form-control m-input m-input--square" id="id_wil_tujuan" name="id_wil_tujuan">
        @if(!is_null(old('id_wil_tujuan')))
        <option value="{{ old("id_wil_tujuan") }}">{{ old('nm_wil_tujuan') }}</option>
        @endif
    </select>
    
    @if ($errors->has('id_wil_tujuan'))
    <label style="color: red">
        {{ $errors->first('id_wil_tujuan') }}
    </label>
    @endif
    
    <input type="hidden" name="nm_wil_tujuan" id="nm_wil_tujuan" value="{{ old("nm_wil_tujuan") }}">
</div>

<div class="col-md-3 mt-2">
    <label for="id_layanan">
        <b>Layanan</b> <span class="span-required"> *</span>
    </label>
    
    <select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan">
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

<div class="col-md-3 mt-2">
    <label for="id_sopir">
        <b>Nama Sopir</b> <span class="span-required"> *</span>
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

<div class="col-md-3 mt-2">
    <label for="id_armada">
        <b>Armada</b> <span class="span-required"> * </span>
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

<div class="col-md-3 mt-2" >
    <label for="tgl_berangkat">
        <b>Rencana Berangkat</b> <span class="span-required"> *</span>
    </label>
    
    <input type="date" class="form-control" name="tgl_berangkat" id="tgl_berangkat" maxlength="16" value="@if(isset($data->tgl_berangkat)){{ $data->tgl_berangkat }}@else{{ old("tgl_berangkat") }}@endif" required="required" style="background-color: #fff">
    
    @if ($errors->has('tgl_berangkat'))
    <label style="color: red">
        {{ $errors->first('tgl_berangkat') }}
    </label>
    @endif
</div>

<div class="col-md-3 mt-2">
    <label for="tgl_sampai">
        <b>Estimasi Sampai</b> <span class="span-required"> *</span>
    </label>
    
    <input type="date" class="form-control" name="tgl_sampai" id="tgl_sampai" maxlength="16" value="@if(isset($data->tgl_sampai)){{ $data->tgl_sampai }}@else{{ old("tgl_sampai") }}@endif" required="required" style="background-color: #fff">
    
    @if ($errors->has('tgl_sampai'))
    <label style="color: red">
        {{ $errors->first('tgl_sampai') }}
    </label>
    @endif
</div>

<div class="col-md-12 text-right">
    @include('template.inc_action')
</div>

@section('script')
<script>
    $('#id_wil_tujuan').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_wil_tujuan').empty();
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
    
    @if(!is_null(old('id_kapal')))
    $('#id_kapal').val({{ old("id_kapal") }});
    @endif
    
    @if(!is_null(old('id_sopir')))
    $('#id_sopir').val({{ old("id_sopir") }});
    @endif
    
    @if(!is_null(old('id_layanan')))
    $('#id_layanan').val({{ old("id_layanan") }});
    @endif
    
    @if(!is_null(old('id_armada')))
    $('#id_armada').val({{ old("id_armada") }});
    @endif
    
    @if(isset($data->tgl_berangkat))
    {{ $data->tgl_berangkat }}
    @else 
    $("#tgl_berangkat").val('{{ date("Y-m-d") }}'); 
    @endif
    
    @if(isset($data->id_sopir))
    $("#id_sopir").val('{{ $data->id_sopir }}'); 
    @endif
    
    @if(isset($data->id_armada))
    $("#id_armada").val('{{ $data->id_armada }}'); 
    @endif
    
    @if(isset($wilayah->nama_wil))
    $("#id_wil_tujuan").empty();
    $("#id_wil_tujuan").append('<option value="{{ $wilayah->id_wil }}">{{ strtoupper($wilayah->nama_wil) }}</option>');
    $("#nm_wil_tujuan").val('{{ strtoupper($wilayah->nm_wil_tujuan) }}');
    @endif
</script>
@endsection