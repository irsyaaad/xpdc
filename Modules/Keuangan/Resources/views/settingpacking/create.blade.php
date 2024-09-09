<div class="row">
    <div class="col-md-6">
        <div class="form-group m-form__group">
            <label for="ac_pendapatan">
                <b>Perkiraan Akun Pendapatan</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac_pendapatan" id="ac_pendapatan">
                @foreach($pendapatan as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac_pendapatan'))
            <label style="color: red">
                {{ $errors->first('ac_pendapatan') }}
            </label>
            @endif
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group m-form__group">
            <label for="ac_piutang">
                <b>Perkiraan Akun Piutang</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac_piutang" id="ac_piutang">
                @foreach($piutang as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac_piutang'))
            <label style="color: red">
                {{ $errors->first('ac_piutang') }}
            </label>
            @endif
        </div>
    </div>
    
</div>


@section('script')
<script type="text/javascript">
    @if(isset($data->ac_pendapatan))
    $("#ac_pendapatan").val('{{ $data->ac_pendapatan }}');
    @endif
    
    @if(isset($data->ac_piutang))
    $("#ac_piutang").val('{{ $data->ac_piutang }}');
    @endif
</script>
@endsection