<div class="form-group m-form__group">
    <label for="ac4_pend_penerima">
        <b>Nama Setting</b><span class="span-required"> *</span>
    </label>
    
    <input type="text" name="nm_setting" id="nm_setting" class="form-control">
    
    @if ($errors->has('nm_setting'))
    <label style="color: red">
        {{ $errors->first('nm_setting') }}
    </label>
    @endif
</div>
<div class="row">
    
    <div class="col-md-6">
        <div class="form-group m-form__group">
            <label for="ac4_pend_penerima">
                <b>Perkiraan Akun Pendapatan</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac4_pend_penerima" id="ac4_pend_penerima">
                @foreach($pendapatan as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac4_pend_penerima'))
            <label style="color: red">
                {{ $errors->first('ac4_pend_penerima') }}
            </label>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group m-form__group">
            <label for="ac4_piutang_penerima">
                <b>Perkiraan Akun Piutang</b><span class="span-required"> *</span>
            </label>
            
            <select class="form-control" name="ac4_piutang_penerima" id="ac4_piutang_penerima">
                @foreach($piutang as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('ac4_piutang_penerima'))
            <label style="color: red">
                {{ $errors->first('ac4_piutang_penerima') }}
            </label>
            @endif
        </div>
    </div>

    <div class="col-md-6">
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

    <div class="col-md-6">
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
    

</div>