@extends('template.document')
@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('settingstatus') }}@else{{ route('settingstatus.update', $data->id_setting) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    @csrf
    
    <div class="row">
        <div class="form-group col-md-4" >
            <label for="id_status">
                <b>Perusahaan Asal</b> <span class="span-required">*</span>
            </label>
            
            <select class="form-control" id="id_status" name="id_status" required="required">
                <option value=""> -- Pilih Status -- </option>
                @foreach($status as $key => $value)
                <option value="{{ $value->id_ord_stt_stat }}"> {{ strtoupper($value->nm_ord_stt_stat) }} </option>
                @endforeach
            </select>
            
            @if ($errors->has('id_status'))
            <label style="color: red">
                {{ $errors->first('id_status') }}
            </label>
            @endif
        </div>

        <div class="form-group col-md-4" >
            <label for="id_status">
                <b>Type</b> <span class="span-required">*</span>
            </label>
            
            <select class="form-control" id="type" name="type" required="required">
                <option value=""> -- Pilih Type -- </option>
                <option value="1"> Pengirim </option>
                <option value="2"> Penerima </option>
            </select>
            
            @if ($errors->has('id_status'))
            <label style="color: red">
                {{ $errors->first('id_status') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4" >
            @include('template.inc_action')
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    @if(old("id_status")!=null )
    $("#id_status").val("{{ old('id_status') }}");
    @elseif(isset($data->id_status))
    $("#id_status").val("{{ $data->id_status }}");
    @endif

    @if(old("type")!=null )
    $("#type").val("{{ old('type') }}");
    @elseif(isset($data->type))
    $("#type").val("{{ $data->type }}");
    @endif
</script>
@endsection