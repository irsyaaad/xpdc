@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('acperush') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group m-form__group" id="form-parent1">
        <label for="parent1">
            <b>Parent 1</b>
        </label>
        <select class="form-control m-input m-input--square" name="parent1" id="parent1">
            <option value="0">-- Pilih Parent 1 --</option>
            @foreach($parent as $key => $value)
            <option value="{{ $value->id_ac }}">{{ $value->nama }} </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group m-form__group" id="form-parent2">
        <label for="parent2">
            <b>Parent 2</b>
        </label>
        <select class="form-control m-input m-input--square" name="parent2" id="parent2">
            <option value="0">-- Pilih Parent 2 --</option>
        </select>
        
    </div>
    
    <div class="form-group m-form__group" id="form-parent3">
        <label for="parent">
            <b>Parent 3</b>
        </label>
        <select class="form-control m-input m-input--square" name="parent" id="parent">
            <option value="0">-- Pilih Parent 3 --</option>
        </select>
        
        @if ($errors->has('parent'))
        <label style="color: red">
            {{ $errors->first('parent') }}
        </label>
        @endif
    </div>

    <div class="form-group m-form__group">
        <label for="nama">
            <b>Nama</b> <span class="span-required"> * </span>
        </label>
        
        <input type="text" class="form-control m-input m-input--square" name="nama" id="nama" required="required">
        
        @if ($errors->has('nama'))
        <label style="color: red">
            {{ $errors->first('nama') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <label for="def_pos">
            <b>Default Pos</b> <span class="span-required"> * </span>
        </label>
        
        <select class="form-control m-input m-input--square" name="def_pos" id="def_pos">
            <option value="">-- Default Pos --</option>
            <option value="D" >Debit</option>
            <option value="K" >Kredit</option>
        </select>
        
        @if ($errors->has('def_pos'))
        <label style="color: red">
            {{ $errors->first('def_pos') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <label for="jenis">
            <b>Kas / Bank</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="jenis" id="jenis">
            <option value="">-- Kas / Bank --</option>
            <option value="B" >Bank</option>
            <option value="K" >Kas</option>
        </select>
        
        @if ($errors->has('jenis'))
        <label style="color: red">
            {{ $errors->first('jenis') }}
        </label>
        @endif
    </div>
    
    <div class="form-group col-md-6">
        <div class="row">

            <div class="form-group col-md-5">
                <label for="kode_debet" class="label-form">
                    <b>Kode Debet </b>
                </label>
                <input type="text" class="form-control m-input m-input--square" name="kode_debet" id="kode_debet" maxlength="6">
                @if ($errors->has('kode_debet'))
                <label style="color: red">
                    {{ $errors->first('kode_debet') }}
                </label>
                @endif
            </div>
            
            <div class="form-group col-md-5">
                <label for="kode_kredit" class="label-form">
                    <b>Kode Kredit </b>
                </label>
                <input type="text" class="form-control m-input m-input--square" name="kode_kredit" id="kode_kredit" maxlength="6">
                @if ($errors->has('kode_kredit'))
                <label style="color: red">
                    {{ $errors->first('kode_kredit') }}
                </label>
                @endif
            </div>

            <div class="form-group m-form__group col-md-2">
                <label for="is_aktif" class="label-form">
                    <b>Aktif ? </b>
                </label>
                
                <div class="col-md-12 checkbox">
                    <label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"></label>
                </div>
                
                @if ($errors->has('is_aktif'))
                <label style="color: red">
                    {{ $errors->first('is_aktif') }}
                </label>
                @endif
            </div>

        </div>
    </div>
    
    @include('template.inc_action')
</form>

<script type="text/javascript">
    $("#form-parent1").prop("hidden", false);
    $("#form-parent2").prop("hidden", true);
    $("#form-parent3").prop("hidden", true);
    $("#form-jenis").prop("hidden", true);
    
    $('#form-parent2').on('change', function() {
        $("#form-parent3").prop("hidden", false);
    });
    
    $('#parent1').on('change', function() {
        var id = this.value;
        $.ajax({
            type: "GET", 
            url: "{{ url('getAC2') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#parent2").empty();
                $("#parent2").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
                $.each(response,function(key, value)
                {
                    $("#parent2").append('<option value=' + value.kode + '>(' + value.kode +') '+ value.value + '</option>');
                });
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
        
        if (this.value == 0) {
            $("#form-parent2").prop("hidden", true);
        }else{
            $("#form-parent2").prop("hidden", false);
        }
    });    
    
    $('#parent2').on('change', function() {
        var id = this.value;
        $.ajax({
            type: "GET", 
            url: "{{ url('getAC3') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#parent").empty();
                $("#parent").append('<option value=' + 0 + '>' + "-- Pilih Parent 3 --" + '</option>');
                $.each(response,function(key, value)
                {
                    $("#parent").append('<option value=' + value.kode + '>(' + value.kode +') ' + value.value + '</option>');
                });
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
        
        if (this.value == 0) {
            $("#form-parent3").prop("hidden", true);
        }else{
            $("#form-parent3").prop("hidden", false);
        }
    });    
    
    @if(isset($data->is_aktif) and $data->is_aktif==1)
    $("#is_aktif").prop("checked", true);
    @endif
</script>
@endsection