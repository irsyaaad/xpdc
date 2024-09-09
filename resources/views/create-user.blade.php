@extends('template.document')
@section('data')
<form method="POST" action="@if(Request::segment(2)=="create"){{ url('user') }}@else{{ route('user.update', $data->id_user) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    @csrf
    
    <div class="row">
        <div class="form-group col-md-4">
            <label for="id_perush">
                <b>Perusahaan Asal</b> <span class="text-danger"> *</span>
            </label>
            
            <select required id="id_perush" name="id_perush" class="form-control m-input m-input--square">
                <option value="">-- pilih perusahaan --</option>
                @foreach($perush as $key => $value)
                    <option value="{{ $value->id_perush }}">{{ $value->nm_perush  }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_perush'))
            <label style="color: red">
                {{ $errors->first('id_perush') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="id_perush">
                <b>Karyawan</b> <span class="text-danger"> *</span>
            </label>
            
            <select required id="id_karyawan" name="id_karyawan" class="form-control m-input m-input--square">
                <option value="">-- pilih karyawan --</option>
                @foreach($karyawan as $key => $value)
                    <option value="{{ $value->id_karyawan }}">{{ $value->nm_karyawan  }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_karyawan'))
            <label style="color: red">
                {{ $errors->first('id_karyawan') }}
            </label>
            @endif
        </div>

        @if(Request::segment(2)=="create")
        <div class="form-group col-md-4">
            <label for="id_perush">
                <b>Role</b> <span class="text-danger"> *</span>
            </label>
            
            <select required id="id_role" name="id_role" class="form-control m-input m-input--square">
                <option value="">-- pilih role --</option>
                @foreach($role as $key => $value)
                    <option value="{{ $value->id_role }}">{{ $value->nm_role  }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_role'))
            <label style="color: red">
                {{ $errors->first('id_role') }}
            </label>
            @endif
        </div>
        @endif
        
        <div class="form-group col-md-4">
            <label for="username">
                <b>Username</b> <span class="span-required">*</span>
            </label>
            
            <input type="text" class="form-control m-input m-input--square" name="username" id="username" placeholder="Masukan Username"  required maxlength="40">
            
            @if ($errors->has('username'))
            <label style="color: red">
                {{ $errors->first('username') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="password">
                <b>Password</b>
            </label>
            
            <input type="password" class="form-control m-input m-input--square" name="password" id="password" placeholder="Masukan Password" value="{{ old('password') }}" maxlength="40">
            
            <div class="col-md-12 checkbox" style="margin-top: 1%">
                <label><input type="checkbox" value="1" id="showpass" name="showpass"> Tampilkan Password</label>
            </div>
            
            @if ($errors->has('password'))
            <label style="color: red">
                {{ $errors->first('password') }}
            </label>
            @endif
        </div>
    </div>
    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
</form>
@endsection

@section('script')
<script>
    $("#id_karyawan").select2();
    $("#id_perush").select2();
    $("#id_role").select2();

    @if(old("id_perush")!=null)
    $("#id_perush").val('{{ old("id_perush") }}').trigger("change");
    @elseif(isset($data->id_perush))
    $("#id_perush").val('{{ $data->id_perush }}').trigger("change");
    @endif

    @if(old("id_karyawan")!=null)
    $("#id_karyawan").val('{{ old("id_karyawan") }}').trigger("change");
    @elseif(isset($data->id_karyawan))
    $("#id_karyawan").val('{{ $data->id_karyawan }}').trigger("change");
    @endif

    @if(old("id_role")!=null)
    $("#id_role").val('{{ old("id_role") }}').trigger("change");
    @endif

    @if(old("username")!=null)
    $("#username").val('{{ old("username") }}');
    @elseif(isset($data->username))
    $("#username").val('{{ $data->username }}');
    @endif

    $('#showpass').change(function()
    {
        if($(this).is(':checked')) {
            $("#password").attr("type", "text");
        }else{
            $("#password").attr("type", "password");
        }
    });
    
    @if(Request::segment(3)!=null)
    $("#id_perush").attr("disabled", true);
    $("#id_perush").attr("readonly", true);
    @endif

    $('#id_perush').on("change", function(e) {
        $('#id_karyawan').empty();
        $.ajax({
            type: "GET",
            url: "{{ url('getkaryawannouser') }}/"+$("#id_perush").val(),
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $('#id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
                $.each(response, function(index, value) {
                    $('#id_karyawan').append('<option value="'+value.id_karyawan+'">'+value.nm_karyawan+'</option>');
                });
                $("#id_karyawan").select2();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });
</script>
@endsection