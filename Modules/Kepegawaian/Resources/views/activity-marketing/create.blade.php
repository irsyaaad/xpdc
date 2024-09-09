@extends('template.document')
@section('data')
    @if (Request::segment(2) == 'create')
        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('activity-marketing') }}"
            enctype="multipart/form-data">
        @else
            <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                action="{{ url('activity-marketing', $data->id) }}" enctype="multipart/form-data">
                {{ method_field('PUT') }}
    @endif
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="tgl">
                <b>Tgl</b> <span class="span-required"> * </span>
            </label>
            <input type="date" class="form-control m-input m-input--square" maxlength="56" name="tgl" id="tgl"
                value="@if(isset($data->tgl)){{ $data->tgl }}@else{{ old('tgl') }}@endif"
                required="required">
            @if ($errors->has('tgl'))
                <label style="color: red">
                    {{ $errors->first('tgl') }}
                </label>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="marketing">
                <b>Marketing</b> <span class="span-required"> * </span>
            </label>
            
            <select class="form-control m-input m-input--square" name="marketing" id="marketing" required="required">
                <option value="">Pilih Marketing</option>
                @foreach ($marketing as $item)
                    <option value="{{ $item->id_marketing }}" @if (!empty($data->id_marketing) && $data->id_marketing == $item->id_marketing) selected @else {{ old("marketing") }} @endif>{{ $item->nm_marketing }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('cabang_tujuan'))
            <label style="color: red">
                {{ $errors->first('cabang_tujuan') }}
            </label>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="activity">
                <b>Activity</b> <span class="span-required"> * </span>
            </label>
            
            <select class="form-control m-input m-input--square" name="activity" id="activity" required="required">
                <option value="">Pilih Activity</option>
                @foreach ($activity as $item)
                    <option value="{{ $item->id_activity }}" @if (!empty($data->id_activity) && $data->id_activity == $item->id_activity) selected @else {{ old("activity") }} @endif>{{ $item->nama_activity }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('activity'))
            <label style="color: red">
                {{ $errors->first('activity') }}
            </label>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="pelanggan">
                <b>Pelanggan</b> <span class="span-required"> * </span>
            </label>
            
            <select class="form-control m-input m-input--square" name="pelanggan" id="pelanggan" required="required">
                <option value="">Pilih Pelanggan</option>
                @foreach ($pelanggan as $item)
                    <option value="{{ $item->id_pelanggan }}" @if (!empty($data->id_pelanggan) && $data->id_pelanggan == $item->id_pelanggan) selected @else {{ old("pelanggan") }} @endif>{{ strtoupper($item->nm_pelanggan) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('pelanggan'))
            <label style="color: red">
                {{ $errors->first('pelanggan') }}
            </label>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="nama">
                <b>Nama</b> <span class="span-required"> * </span>
            </label>
            <input type="text" class="form-control m-input m-input--square" maxlength="56" name="nama" id="nama"
                value="{{ Auth::user()->nm_user }}">

            @if ($errors->has('tgl'))
                <label style="color: red">
                    {{ $errors->first('tgl') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-12">
            <label for="alamat">
                <b>Alamat</b>
            </label>

            <textarea class="form-control m-input m-input--square" name="alamat" id="alamat" maxlength="256"
                style="min-height: 100px">@if (isset($data->alamat)){{ $data->alamat }}@else{{ old('keterangan') }}@endif
            </textarea>

            @if ($errors->has('alamat'))
                <label style="color: red">
                    {{ $errors->first('alamat') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-12">
            <label for="keterangan">
                <b>Keterangan</b>
            </label>

            <textarea class="form-control m-input m-input--square" name="keterangan" id="keterangan" maxlength="256"
                style="min-height: 100px">@if (isset($data->keterangan)){{ $data->keterangan }}@else{{ old('keterangan') }}@endif
            </textarea>

            @if ($errors->has('keterangan'))
                <label style="color: red">
                    {{ $errors->first('keterangan') }}
                </label>
            @endif
        </div>
    </div>
    <br>
    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
    </form>
    <script>
        $('#marketing').select2();
        $('#activity').select2();
        $('#pelanggan').select2();
    </script>
@endsection
