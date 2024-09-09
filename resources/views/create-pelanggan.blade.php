@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('pelanggan') }}@else{{ route('pelanggan.update', $data->id_pelanggan) }}@endif" enctype="multipart/form-data">

    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    @csrf

    <div class="row">
        <div class="form-group m-form__group col-md-4" style="margin-top: 15px">
            <label for="nm_user">
                <b>Nama Pelanggan <span class="span-required"> * </span> </b>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="nm_pelanggan" id="nm_pelanggan" value="@if(old('nm_pelanggan')!=null){{ old('nm_pelanggan') }}@elseif(isset($data->nm_pelanggan)){{$data->nm_pelanggan}}@endif" required="required" maxlength="100">

            @if ($errors->has('nm_pelanggan'))
            <label style="color: red">
                {{ $errors->first('nm_pelanggan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="id_plgn_group">
                <b>Group Pelanggan <span class="span-required"> * </span></b>
            </label>

            <select class="form-control m-input m-input--square" name="id_plgn_group" id="id_plgn_group">
                @foreach($group as $key => $value)
                <option value="{{ $value->kode }}">{{ strtoupper($value->kode.' - '.$value->nm_group) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_plgn_group'))
            <label style="color: red">
                {{ $errors->first('id_plgn_group') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="alamat">
                <b>Alamat <span class="span-required"> * </span></b>
            </label>

            <textarea class="form-control m-input m-input--square" name="alamat" id="alamat">@if(old('alamat')!=null){{ old('alamat') }}@elseif(isset($data->alamat)){{$data->alamat}}@endif</textarea>

            @if ($errors->has('alamat'))
            <label style="color: red">
                {{ $errors->first('alamat') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="id_wil">
                <b>Wilayah </b>
            </label>

            <select id="id_wil" name="id_wil" class="form-control"></select>

            @if ($errors->has('id_wil'))
            <label style="color: red">
                {{ $errors->first('id_wil') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="telp">
                <b>No Telp / WA</b> <span class="span-required"> *</span>
            </label>

            <input type="telp" class="form-control m-input m-input--square" name="telp" id="telp" maxlength="14" value="@if(old('telp')!=null){{ old('telp') }}@elseif(isset($data->telp)){{$data->telp}}@endif" required>

            @if ($errors->has('telp'))
            <label style="color: red">
                {{ $errors->first('telp') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="email">
                <b>Email</b>
            </label>

            <input type="email" class="form-control m-input m-input--square" name="email" id="email" maxlength="40" value="@if(old('email')!=null){{ old('email') }}@elseif(isset($data->email)){{$data->email}}@endif">

            @if ($errors->has('email'))
            <label style="color: red">
                {{ $errors->first('email') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="fax">
                <b>No Fax</b>
            </label>

            <input type="telp" class="form-control m-input m-input--square" name="fax" id="fax" maxlength="14" value="@if(old('fax')!=null){{ old('fax') }}@elseif(isset($data->fax)){{$data->fax}}@endif">

            @if ($errors->has('fax'))
            <label style="color: red">
                {{ $errors->first('fax') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="nm_kontak">
                <b>Contact Person</b>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="nm_kontak" id="nm_kontak" maxlength="40" value="@if(old('nm_kontak')!=null){{ old('nm_kontak') }}@elseif(isset($data->nm_kontak)){{$data->nm_kontak}}@endif">

            @if ($errors->has('nm_kontak'))
            <label style="color: red">
                {{ $errors->first('nm_kontak') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="no_kontak">
                <b>Nomor Contact Person</b>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="no_kontak" id="no_kontak"  maxlength="14" value="@if(old('no_kontak')!=null){{ old('no_kontak') }}@elseif(isset($data->no_kontak)){{$data->no_kontak}}@endif">

            @if ($errors->has('no_kontak'))
            <label style="color: red">
                {{ $errors->first('no_kontak') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="npwp">
                <b>NPWP Perusahaan</b>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="npwp" id="npwp" maxlength="14" value="@if(old('npwp')!=null){{ old('npwp') }}@elseif(isset($data->npwp)){{$data->npwp}}@endif">

            @if ($errors->has('npwp'))
            <label style="color: red">
                {{ $errors->first('npwp') }}
            </label>
            @endif
        </div>

        @if(strtolower(Session("role")["nm_role"]) == "keuangan")
        <div class="form-group m-form__group col-md-4">
            <label for="n_limit_piutang">
                <b>Limit Piutang</b>
            </label>

            <input type="number" name="n_limit_piutang" id="n_limit_piutang" class="form-control">

            <!-- <select class="form-control m-input m-input--square" name="n_limit_piutang" id="n_limit_piutang">
                <option value="0">0</option>
                @foreach($limit as $key => $value)
                <option value="{{ $value->nominal }}">{{ tonumber($value->nominal) }} @if($value->is_default==true) (Default) @endif</option>
                @endforeach
            </select> -->

            @if ($errors->has('n_limit_piutang'))
            <label style="color: red">
                {{ $errors->first('n_limit_piutang') }}
            </label>
            @endif
        </div>
        @endif

        <div class="form-group m-form__group col-md-4">
            <label for="isaktif">
                <b>Status Aktif</b>
            </label>

            <div class="row">
                <div class="col-md-12 checkbox">
                    <label><input type="checkbox" value="1" id="isaktif" name="isaktif"> Aktif ?</label>
                </div>
            </div>

            @if ($errors->has('isaktif'))
            <label style="color: red">
                {{ $errors->first('isaktif') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-4">
            <label for="isaktif">
                <b>Status Pelanggan</b>
            </label>

            <div class="row">
                <div class="col-md-12" style="vertical-align: middle;">
                    <input style="width: 15px; height: 15px;" type="checkbox" value="1" id="is_member" name="is_member">
                    <span class="">Member ?</span>
                </div>
            </div>

            @if ($errors->has('isaktif'))
            <label style="color: red">
                {{ $errors->first('isaktif') }}
            </label>
            @endif
        </div>
        <div class="col-md-12 text-right">
            @include('template.inc_action')
        </div>

    </div>

</form>
@endsection

@section('script')
<script>

    @if(isset($data->isaktif))
    $("#isaktif").prop("checked", true);
    @endif

    @if(isset($data->id_wil))
    $("#id_wil").val({{ $data->id_wil }});
    @endif

    @if(isset($data->wilayah->nm_wil))
    $("#wilayah").val({{ $data->wilayah->nm_wil }});
    @endif

    $('#id_wil').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 3,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_wil').empty();
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });

    @if(isset($data->id_plgn_group))
    $('#id_plgn_group').val('{{ $data->id_plgn_group }}');
    @endif

    @if(isset($data->n_limit_piutang))
    $('#n_limit_piutang').val('{{ $data->n_limit_piutang }}');
    @endif

    @if(isset($wilayah->nama_wil))
    $('#id_wil').append('<option value="{{ $wilayah->id_wil }}">{{ $wilayah->nama_wil }}</option>');
    @endif
</script>
@endsection
