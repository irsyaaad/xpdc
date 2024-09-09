<div class="row">  
    
    <div class="col-md-4 form-group m-form__group">
        <label for="id_biaya_grup">
            <b>Biaya Group</b><span class="span-required"> *</span>
        </label>
        
        <select class="form-control" name="id_biaya_grup" id="id_biaya_grup">
            <option value="">-- Pilih Biaya Group --</option>
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

    <div class="col-md-4 form-group m-form__group">
        <label for="ac_hutang">
            <b>Perkiraan Akun Hutang</b><span class="span-required"> *</span>
        </label>
        
        <select class="form-control" name="ac_hutang" id="ac_hutang">
            <option value="">-- Pilih Akun Hutang --</option>
            @foreach($hutang as $key => $value)
            <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('ac_hutang'))
        <label style="color: red">
            {{ $errors->first('ac_hutang') }}
        </label>
        @endif
    </div>

    <div class="col-md-4 form-group m-form__group">
        <label for="ac_biaya">
            <b>Perkiraan Akun Biaya</b><span class="span-required"> *</span>
        </label>
        
        <select class="form-control" name="ac_biaya" id="ac_biaya">
            <option value="">-- Pilih Akun Biaya --</option>
            @foreach($biaya as $key => $value)
            <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('ac_biaya'))
        <label style="color: red">
            {{ $errors->first('ac_biaya') }}
        </label>
        @endif
    </div>
</div>

@section('script')
<script>
    @if(Request::segment(3)=="edit")
        @if(isset($data->ac_hutang))
        $("#ac_hutang").val('{{ $data->ac_hutang}}');
        @endif

        @if(isset($data->ac_biaya))
        $("#ac_biaya").val('{{ $data->ac_biaya}}');
        @endif

        @if(isset($data->id_biaya_grup))
        $("#id_biaya_grup").val('{{ $data->id_biaya_grup}}');
        @endif
    @endif
</script>
@endsection