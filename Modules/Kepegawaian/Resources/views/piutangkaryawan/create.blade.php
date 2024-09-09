@extends('template.document2')

@section('data')
@if(Request::segment(1)=="piutangkaryawan")

@if(Request::segment(2)=="create" )
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('piutangkaryawan.update', $data->id_piutang) }}" enctype="multipart/form-data">
{{ method_field("PUT") }} 
@endif
        
<div class="row">
    @csrf
    <div class="form-group m-form__group col-md-3">
        <label for="id_karyawan">
            <b>Karyawan</b> <span class="span-required"> *</span>
        </label>
        
        <select class="form-control" id="id_karyawan" name="id_karyawan" required>
            <option value=""> -- Pilih Karyawan -- </option>
            @foreach($karyawan as $key => $value)
                <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
            @endforeach
        </select>

        @if ($errors->has('id_karyawan'))
        <label style="color: red">
            {{ $errors->first('id_karyawan') }}
        </label>
        @endif
    </div>


    <div class="form-group m-form__group col-md-3">
        <label for="nominal">
            <b>Jumlah Piutang (Rp)</b> <span class="span-required"> *</span>
        </label>
        
        <input class="form-control" id="nominal" name="nominal" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{ old("nominal") }}@endif" placeholder="Masukan Jumlah Piutang" type="number" minlength="4" maxlength="20" required />

        @if ($errors->has('nominal'))
        <label style="color: red">
            {{ $errors->first('nominal') }}
        </label>
        @endif
    </div>

    <div class="form-group m-form__group col-md-3">
        <label for="frekuensi">
            <b>Tenor Angsuran</b> <span class="span-required"> *</span>
        </label>
        
        <select class="form-control" id="frekuensi" name="frekuensi" required>
            <option value=""> -- Pilih Angsuran -- </option>
            @for($i = 1; $i<=15; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        @if ($errors->has('frekuensi'))
        <label style="color: red">
            {{ $errors->first('frekuensi') }}
        </label>
        @endif
    </div>

    <div class="form-group m-form__group col-md-3">
        <label for="keperluan">
            <b>Keperluan</b> <span class="span-required"> *</span>
        </label>
        
        <textarea style="min-height: 100px" class="form-control" id="keperluan" name="keperluan" placeholder="Masukan keterangan keperluan" minlength="4" maxlength="200" required>@if(isset($data->keperluan)){{ $data->keperluan }}@else{{ old("keperluan") }}@endif</textarea>

        @if ($errors->has('keperluan'))
        <label style="color: red">
            {{ $errors->first('keperluan') }}
        </label>
        @endif
    </div>

    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
</div>
</form>
@endif
@endsection

@section('script')
<script>
   @if(isset($data->id_karyawan))
        $("#id_karyawan").val('{{ $data->id_karyawan }}');
    @else
    $("#id_karyawan").val('{{ old("id_karyawan") }}');
    @endif

    @if(isset($data->frekuensi))
        $("#frekuensi").val('{{ $data->frekuensi }}');
    @else
    $("#frekuensi").val('{{ old("frekuensi") }}');
    @endif
</script>
@endsection