@extends('template.document')
@section('data')
    @if (Request::segment(2) == 'create')
        <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('complain') }}"
            enctype="multipart/form-data">
        @else
            <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                action="{{ url('complain', $data->id) }}" enctype="multipart/form-data">
                {{ method_field('PUT') }}
    @endif
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="tgl">
                <b>Tgl</b> <span class="span-required"> * </span>
            </label>
            <input type="date" class="form-control m-input m-input--square" maxlength="56" name="tgl" id="tgl"
                value="@if(isset($data->tgl_complain)){{ $data->tgl_complain }}@else{{ old('tgl') }}@endif"
                required="required">
            @if ($errors->has('tgl'))
                <label style="color: red">
                    {{ $errors->first('tgl') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="jenis">
                <b>Jenis</b> <span class="span-required"> * </span>
            </label>
            <select class="form-control m-input m-input--square" name="jenis" id="jenis" required="required">
                <option value="">Pilih Jenis</option>
                @foreach ($jenis_komplain as $item)
                    <option value="{{ $item->id }}"
                        @if (!empty($data->id_jenis_complain) && $data->id_jenis_complain == $item->id) selected @else {{ old('jenis') }} @endif>
                        {{ strtoupper($item->nama_jenis) }}</option>
                @endforeach
            </select>
            @if ($errors->has('jenis'))
                <label style="color: red">
                    {{ $errors->first('jenis') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="id_perush_tujuan">
                <b>Perusahaan Tujuan</b> <span class="span-required"> * </span>
            </label>
            <select class="form-control m-input m-input--square" name="id_perush_tujuan" id="id_perush_tujuan" required="required">
                <option value="">Pilih Perusahaan Tujuan</option>
                @foreach ($perusahaan as $item)
                    <option value="{{ $item->id_perush }}"
                        @if (!empty($data->id_perush_tujuan) && $data->id_perush_tujuan == $item->id_perush) selected @else {{ old('id_perush_tujuan') }} @endif>
                        {{ strtoupper($item->nm_perush) }}</option>
                @endforeach
            </select>
            @if ($errors->has('id_perush_tujuan'))
                <label style="color: red">
                    {{ $errors->first('activity') }}
                </label>
            @endif
        </div>
        
        <div class="form-group col-md-6">
            <label for="pelapor">
                <b>Pelapor</b> <span class="span-required"> * </span>
            </label>
            <input type="text" class="form-control m-input m-input--square" maxlength="56" name="pelapor" id="pelapor"
                value="{{ isset($data->pelapor) ? $data->pelapor : Auth::user()->nm_user }}">

            @if ($errors->has('pelapor'))
                <label style="color: red">
                    {{ $errors->first('pelapor') }}
                </label>
            @endif
        </div>
        
        <div class="form-group col-md-6">
            <label for="hp_pelapor">
                <b>HP Pelapor</b> <span class="span-required"> * </span>
            </label>
            <input type="text" class="form-control m-input m-input--square" maxlength="56" name="hp_pelapor" id="hp_pelapor"
                value="{{ isset($data->hp_pelapor) ? $data->hp_pelapor : '' }}">

            @if ($errors->has('hp_pelapor'))
                <label style="color: red">
                    {{ $errors->first('hp_pelapor') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="stt">
                <b>Kode STT</b> <span class="span-required"> * </span>
            </label>
            <select class="form-control" id="id_stt" name="id_stt">
                @if (isset($stt->id_stt))
                    <option value="{{ $stt->id_stt }}">{{ $stt->kode_stt }}</option>
                @endif
            </select>

            @if ($errors->has('pelapor'))
                <label style="color: red">
                    {{ $errors->first('pelapor') }}
                </label>
            @endif
        </div>

        <div class="form-group col-md-12">
            <label for="keterangan">
                <b>Keterangan</b>
            </label>

            <textarea class="form-control m-input m-input--square" name="keterangan" id="keterangan" maxlength="256"
                style="min-height: 100px">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old('keterangan') }}@endif
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
        $('#jenis').select2();
        $('#id_perush_tujuan').select2();

        $('#id_stt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_stt').empty();
                return {
                    results: $.map(data, function(item) {
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
    </script>
@endsection
