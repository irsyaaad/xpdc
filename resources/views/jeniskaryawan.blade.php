@extends('template.document')

@section('data')
@if(Request::segment(1)=="jeniskaryawan" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-striped table-responsive">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kode Jenis Karyawan</th>
                <th>Jenis Karyawan</th>
                <th>Golongan / Pangkat</th>
                <th>Gaji Pokok</th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ strtoupper($value->kode_jenis) }}</td>
                <td>{{ strtoupper($value->nm_jenis) }}</td>
                <td>{{ strtoupper($value->golongan) }} / {{ strtoupper($value->pangkat) }}</td>
                <td>{{ torupiah($value->n_gaji) }}</td>
                <td>
                    {!! inc_edit($value->id_jenis) !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url(Request::segment(1)) }}@else{{ route('jeniskaryawan.update', $data->id_jenis) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit" )
    {{ method_field("PUT") }}
    @endif
    @csrf
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="id_jenis">
                <b>Kode Jenis Karyawan</b> <span class="span-required"> *</span>
            </label>

            <input type="text"
            id="id_jenis"
            name="id_jenis"
            maxlength="4" class="form-control"
            placeholder="Ex : DVT"
            value="@if(old('id_jenis')!=null){{ old('id_jenis') }}@elseif(isset($data->id_jenis)){{$data->id_jenis}}@endif">

            @if ($errors->has('id_jenis'))
            <label style="color: red">
                {{ $errors->first('id_jenis') }}
            </label>
            @endif
        </div>

        <div class="col-md-4 form-group">
            <label for="nm_jenis">
                <b>Nama Jenis Karyawan</b> <span class="span-required"> *</span>
            </label>

            <input type="text"
            id="nm_jenis"
            name="nm_jenis"
            maxlength="32"
            class="form-control"
            placeholder="Ex : Driver Training"
            value="@if(old('nm_jenis')!=null){{ old('nm_jenis') }}@elseif(isset($data->nm_jenis)){{$data->nm_jenis}}@endif">

            @if ($errors->has('nm_jenis'))
            <label style="color: red">
                {{ $errors->first('nm_jenis') }}
            </label>
            @endif
        </div>

        <div class="col-md-4 form-group">
            <label for="nm_jenis">
                <b>Golongan</b>
            </label>
            <select class="form-control m-input m-input--square" id="golongan" name="golongan">
                <option value="">-- Pilih Golongan --</option>
                <option value="1"> I </option>
                <option value="2"> II </option>
                <option value="3"> III </option>
                <option value="4"> IV </option>
                <option value="5"> V </option>
            </select>

            @if ($errors->has('golongan'))
            <label style="color: red">
                {{ $errors->first('golongan') }}
            </label>
            @endif
        </div>
        <div class="col-md-4 form-group">
            <label for="nm_jenis">
                <b>Pangkat</b>
            </label>

            <select class="form-control m-input m-input--square" id="pangkat" name="pangkat">
                <option value="">-- Pilih Pangkat --</option>
                <option value="A"> A </option>
                <option value="B"> B </option>
                <option value="C"> C </option>
                <option value="D"> D </option>
            </select>

            @if ($errors->has('pangkat'))
            <label style="color: red">
                {{ $errors->first('pangkat') }}
            </label>
            @endif
        </div>

        <div class="col-md-4 form-group">
            <label for="n_gaji">
                <b>Nilai Gaji</b> <span class="span-required"> *</span>
            </label>

            <input type="number"
            id="n_gaji"
            name="n_gaji"
            step="any"
            maxlength="16" class="form-control"
            placeholder="Ex : 2000000"
            value="@if(old('n_gaji')!=null){{ old('n_gaji') }}@elseif(isset($data->n_gaji)){{$data->n_gaji}}@endif">

            @if ($errors->has('n_gaji'))
            <label style="color: red">
                {{ $errors->first('n_gaji') }}
            </label>
            @endif
        </div>

        <div class="col-md-4 form-group">
            @include('template.inc_action')
        </div>
    </div>
</form>
@endif
@endsection
@section('script')
<script>
    @if(isset($data->golongan))
    $('#golongan').val({{$data->golongan}});
    @endif
    @if(isset($data->pangkat))
    $('#pangkat').val("{{$data->pangkat}}");
    @endif
</script>
@endsection
