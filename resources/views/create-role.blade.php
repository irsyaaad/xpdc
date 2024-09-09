@extends('template.document')

@section('data')

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('roleuser') }}@else{{ route('roleuser.update', $data->id_ru) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    
    @csrf
    <div class="form-group m-form__group">
        <label for="id_user">
            <b>User</b> <span class="span-required"> * </span>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_user" name="id_user">
            @foreach($user as $key => $value)
            <option value="{{ $value->id_user }}">{{ strtoupper($value->nm_karyawan) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('id_user'))
        <label style="color: red">
            {{ $errors->first('id_user') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <label for="id_perush">
            <b>Perusahaan</b> <span class="span-required"> * </span>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_perush" name="id_perush">
            @foreach($perusahaan as $key => $value)
            <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('id_perush'))
        <label style="color: red">
            {{ $errors->first('id_perush') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <label for="id_role">
            <b>Role User</b>  <span class="span-required"> * </span>
        </label>
        
        <select class="form-control m-input m-input--square" id="id_role" name="id_role">
            @foreach($role as $key => $value)
            <option value="{{ $value->id_role }}">{{ strtoupper($value->nm_role) }}</option>
            @endforeach
        </select>
        
        @if ($errors->has('id_role'))
        <label style="color: red">
            {{ $errors->first('id_role') }}
        </label>
        @endif
    </div>
    
    @include('template.inc_action')
    
</form>

@endsection

@section('script')
<script>
    $( document ).ready(function() {
        $("#id_user").select2();
        $("#id_perush").select2();
        $("#id_role").select2();
    });
    
    @if(old('id_user')!=null)
    $("#id_user").val('{{ old("id_user") }}').trigger("change");
    @elseif(isset($data->id_user))
    $("#id_user").val('{{ $data->id_user }}').trigger("change");
    @endif

    @if(old('id_role')!=null)
    $("#id_role").val('{{ old("id_role") }}').trigger("change");
    @elseif(isset($data->id_role))
    $("#id_role").val('{{ $data->id_role }}').trigger("change");
    @endif

    @if(old('id_perush')!=null)
    $("#id_perush").val('{{ old("id_perush") }}').trigger("change");
    @elseif(isset($data->id_perush))
    $("#id_perush").val('{{ $data->id_perush }}').trigger("change");
    @endif

</script>
@endsection