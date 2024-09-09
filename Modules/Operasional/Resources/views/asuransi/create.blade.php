@extends('template.document')

@section('data')

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('simpanasuransi') }}" enctype="multipart/form-data">
    @csrf
    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>No STT/AWB</b> <span class="span-required"> *</span>
            </label>

            <input type="text" name="id_stt" id="id_stt" class="form-control" placeholder="Ex : 22010001" required>

            @if ($errors->has('id_stt'))
            <label style="color: red">
                {{ $errors->first('id_stt') }}
            </label>
            @endif
        </div>
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>No DM</b> <span class="span-required"> *</span>
            </label>

            <input type="text" name="no_dm" id="no_dm" class="form-control" placeholder="Ex : 75-22100045">

            @if ($errors->has('nm_pengirim'))
            <label style="color: red">
                {{ $errors->first('nm_pengirim') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Nama Pelanggan</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_pelanggan" name="id_pelanggan" required>
                @if(!is_null(old('id_pelanggan')))
                <option value="{{ old("id_pelanggan") }}">{{ old('pengirim_nm') }}</option>
                @endif
            </select>

            @if ($errors->has('id_pelanggan'))
            <label style="color: red">
                {{ $errors->first('id_pelanggan') }}
            </label>
            @endif
        </div>
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Nama Pengirim</b> <span class="span-required"> *</span>
            </label>

            <input type="text" name="nm_pengirim" id="nm_pengirim" class="form-control" placeholder="Ex : Ayu Mutia">

            @if ($errors->has('nm_pengirim'))
            <label style="color: red">
                {{ $errors->first('nm_pengirim') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Asal</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_asal" name="id_asal">
                @if(!is_null(old('id_asal')))
                <option value="{{ old("id_asal") }}">{{ old('id_asal') }}</option>
                @endif
            </select>

            @if ($errors->has('id_asal'))
            <label style="color: red">
                {{ $errors->first('id_asal') }}
            </label>
            @endif
        </div>

        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Tujuan</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_tujuan" name="id_tujuan">
                @if(!is_null(old('id_tujuan')))
                <option value="{{ old("id_tujuan") }}">{{ old('id_tujuan') }}</option>
                @endif
            </select>

            @if ($errors->has('id_pelanggan'))
            <label style="color: red">
                {{ $errors->first('id_pelanggan') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Tgl Berangkat</b> <span class="span-required"> *</span>
            </label>

            <input type="date" name="tgl_berangkat" id="tgl_berangkat" class="form-control">

            @if ($errors->has('tgl_berangkat'))
            <label style="color: red">
                {{ $errors->first('tgl_berangkat') }}
            </label>
            @endif
        </div>

        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Estimasi Sampai</b> <span class="span-required"> *</span>
            </label>

            <input type="date" name="tgl_sampai" id="tgl_sampai" class="form-control">

            @if ($errors->has('tgl_sampai'))
            <label style="color: red">
                {{ $errors->first('tgl_sampai') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="nm_kapal">
                <b>Nama Kapal</b> <span class="span-required"> *</span>
            </label>

            <input type="text" name="nm_kapal" id="nm_kapal" class="form-control" placeholder="Ex : KM. DHARMA KENCANA VII">

            @if ($errors->has('nm_kapal'))
            <label style="color: red">
                {{ $errors->first('nm_kapal') }}
            </label>
            @endif
        </div>

        <div class="col-md-6">
            <label for="no_identity">
                <b>No. SEAL/CONT/PLAT</b> <span class="span-required"> *</span>
            </label>

            <input type="text" name="no_identity" id="no_identity" class="form-control" placeholder="Ex : SITU 999 33531">

            @if ($errors->has('no_identity'))
            <label style="color: red">
                {{ $errors->first('no_identity') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Tipe Barang</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_tipe_barang" name="id_tipe_barang" required>
                @if(!is_null(old('id_pelanggan')))
                <option value="{{ old("id_pelanggan") }}">{{ old('pengirim_nm') }}</option>
                @endif
            </select>

            @if ($errors->has('id_tipe_barang'))
            <label style="color: red">
                {{ $errors->first('id_tipe_barang') }}
            </label>
            @endif
        </div>

        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Qty Barang (Jml Koli)</b> <span class="span-required"> *</span>
            </label>

            <input type="number" name="qty" id="qty" class="form-control">

            @if ($errors->has('qty'))
            <label style="color: red">
                {{ $errors->first('qty') }}
            </label>
            @endif
        </div>
    </div>

    <div class="form-group m-form__group">
        <label for="info_kirim">
            <b>Nama Broker</b> <span class="span-required"> *</span>
        </label>

        <select class="form-control m-input m-input--square" id="broker" name="broker" required>
            <option value="">-- Pilih Broker --</option>
            @foreach ($perush_asuransi as $item)
            <option value="{{ $item->id_perush_asuransi }}">{{ $item->nm_perush_asuransi }}</option>
            @endforeach
        </select>

        @if ($errors->has('info_kirim'))
        <label style="color: red">
            {{ $errors->first('info_kirim') }}
        </label>
        @endif
    </div>

    <div class="row form-group m-form__group">
        <div class="col">
            <label for="n_pertanggungan">
                <b>Harga Pertanggungan</b> <span class="span-required"> *</span>
            </label>
            <input type="number" class="form-control m-input m-input--square" id="n_pertanggungan" name="n_pertanggungan" maxlength="100" step="any" value="@if(isset($data->n_pertanggungan)){{ $data->n_pertanggungan }}@else{{ old("n_pertanggungan") }}@endif" required="required">
        </div>
        <div class="col">
            <label for="nominal">
                <b>Harga</b> <span class="span-required"> *</span>
            </label>
            <input type="number" class="form-control m-input m-input--square" id="nominal" name="nominal" maxlength="100" step="any" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{ old("nominal") }}@endif" required="required" readonly>
        </div>
    </div>

    <div class="form-group m-form__group">
        <label for="info_kirim">
            <b>Keterangan / Info Kirim</b> <span class="span-required"> *</span>
        </label>

        <textarea class="form-control m-input m-input--square" id="keterangan" name="keterangan" maxlength="200" required="required">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif</textarea>

        @if ($errors->has('info_kirim'))
        <label style="color: red">
            {{ $errors->first('info_kirim') }}
        </label>
        @endif
    </div>

    <div class="col-md-12 text-right">
        @include('template.inc_action')
    </div>
</form>
<script>
    $('#id_asal').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 1,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_asal').empty();
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
    $('#id_tujuan').select2({
        placeholder: 'Cari Wilayah ....',
        minimumInputLength: 1,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_tujuan').empty();
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
    $('#id_pelanggan').select2({
        placeholder: 'Cari Pelanggan ....',
        minimumInputLength: 0,
        allowClear: true,
        ajax: {
            url: '{{ url('getPelanggan') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_pelanggan').empty();
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

    $('#id_tipe_barang').select2({
        placeholder: 'Cari Tipe Kirim ....',
        ajax: {
            url: '{{ url('getTipeKirim') }}',
            minimumInputLength: 0,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_tipe_kirim').empty();
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

    var harga = 0;

    function hitung() {
        var nilai = parseFloat($("input[name='n_pertanggungan']" ).val());
        var hasil = (nilai)*(harga/100);
        $("#nominal").val(hasil);
    }

    $("#n_pertanggungan").keyup(function() {
        hitung();
    });

    $('#broker').on("change", function(e) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('getTarifAsuransi') }}/"+this.value,
            success: function(data) {
                console.log(data);
                harga = data.harga_jual;
                hitung();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    setNull();
                }
            });
        });
    </script>
    @endsection
