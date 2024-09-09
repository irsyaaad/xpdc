<div class="row">
    <div class="col-md-3">
        <div class="form-group m-form__group">
            <label for="id_biaya_grup">
                <b>Group Biaya</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="id_biaya_grup" id="id_biaya_grup">
                @foreach($group as $key => $value)
                <option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_biaya_grup'))
            <label style="color: red">
                {{ $errors->first('id_biaya_grup') }}
            </label>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group m-form__group">
            <label for="ac4_biaya">
                <b>Perkiraan Akun Biaya</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac4_biaya" id="ac4_biaya">
                @foreach($biaya as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac4_biaya'))
            <label style="color: red">
                {{ $errors->first('ac4_biaya') }}
            </label>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group m-form__group">
            <label for="ac4_hutang">
                <b>Perkiraan Akun Hutang</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac4_hutang" id="ac4_hutang">
                @foreach($hutang as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac4_hutang'))
            <label style="color: red">
                {{ $errors->first('ac4_hutang') }}
            </label>
            @endif
        </div>
    </div>

<script>
    $("#ac4_hutang").select2();
    $("#id_biaya_grup").select2();
    $("#ac4_biaya").select2();
    @if(isset($data->id_ac_hutang))
    $("#ac4_hutang").val("{{ $data->id_ac_hutang }}").trigger("change");
    @endif
    @if(isset($data->id_biaya_grup))
    $("#id_biaya_grup").val("{{ $data->id_biaya_grup }}").trigger("change");
    @endif
    @if(isset($data->id_ac_biaya))
    $("#ac4_biaya").val("{{ $data->id_ac_biaya }}").trigger("change");
    @endif
</script>