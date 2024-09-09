@extends('template.document')

@section('data')

<div class="col-md-12">
    <div class="col-md-6">
        <label for="id_pelanggan">
            <b>Pilih Stt Asuransi</b> <span class="span-required"> *</span>
        </label>
    
        <select class="form-control m-input m-input--square" id="stt_asur" name="stt_asur">
            @if(!is_null(old('stt_asur')))
            <option value="{{ old("stt_asur") }}">{{ old('stt_asur') }}</option>
            @endif
        </select>
    
        @if ($errors->has('id_pelanggan'))
        <label style="color: red">
            {{ $errors->first('id_pelanggan') }}
        </label>
        @endif
    </div>
</div>
<hr>
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('simpanasuransi') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <div class="col-md-6">
            <input type="hidden" name="id_stt" id="id_stt" class="form-control" placeholder="Ex : 22010001" required>

            @if ($errors->has('id_stt'))
            <label style="color: red">
                {{ $errors->first('id_stt') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
            <label for="id_pelanggan">
                <b>Nama Pelanggan</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_pelanggan" name="id_pelanggan" required disabled>
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

            <input type="text" name="nm_pengirim" id="nm_pengirim" class="form-control" placeholder="Ex : Ayu Mutia" disabled>

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

            <select class="form-control m-input m-input--square" id="id_asal" name="id_asal" disabled>
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

            <select class="form-control m-input m-input--square" id="id_tujuan" name="id_tujuan" disabled>
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

            <input type="date" name="tgl_berangkat" id="tgl_berangkat" class="form-control" readonly>

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
            <label for="id_pelanggan">
                <b>Tipe Barang</b> <span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_tipe_barang" name="id_tipe_barang" required disabled>
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

            <input type="number" name="qty" id="qty" class="form-control" disabled>

            @if ($errors->has('qty'))
            <label style="color: red">
                {{ $errors->first('qty') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col-md-6">
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

        <div class="col-md-6">
            <label for="n_pertanggungan">
                <b>Harga Pertanggungan</b> <span class="span-required"> *</span>
            </label>
            <input type="number" class="form-control m-input m-input--square" id="n_pertanggungan" name="n_pertanggungan" maxlength="100" step="any" value="@if(isset($data->n_pertanggungan)){{ $data->n_pertanggungan }}@else{{ old("n_pertanggungan") }}@endif" required="required" disabled>

            @if ($errors->has('qty'))
            <label style="color: red">
                {{ $errors->first('qty') }}
            </label>
            @endif
        </div>
    </div>

    <div class="row form-group m-form__group">
        <div class="col">
            <label for="n_pertanggungan">
                <b>Harga Beli</b> <span class="span-required"> *</span>
            </label>
            <input type="number" class="form-control m-input m-input--square" id="nominal_beli" name="nominal_beli" maxlength="100" step="any" value="@if(isset($data->nominal_beli)){{ $data->nominal_beli }}@else{{ old("nominal_beli") }}@endif" required="required" readonly>
        </div>
        <div class="col">
            <label for="nominal">
                <b>Harga Jual</b> <span class="span-required"> *</span>
            </label>
            <input type="number" class="form-control m-input m-input--square" id="nominal_jual" name="nominal_jual" maxlength="100" step="any" value="@if(isset($data->nominal_jual)){{ $data->nominal_jual }}@else{{ old("nominal_jual") }}@endif" required="required" readonly>
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

    $('#stt_asur').select2({
        placeholder: 'Cari Stt ....',
        minimumInputLength: 0,
        allowClear: true,
        ajax: {
            url: '{{ url('getasuransistt') }}',
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

    $('#stt_asur').on("change", function(e) {
        console.log(this.value);

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('getdataasuransistt') }}/"+this.value,
            success: function(data) {
                console.log(data);
                $("#id_stt").val(data.stt.kode_stt);
                $("#nm_pengirim").val(data.stt.pengirim_nm);
                $("#qty").val(data.stt.n_koli);
                $("#tgl_berangkat").val(data.stt.tgl_masuk);
                $("#n_pertanggungan").val(data.stt.n_harga_pertanggungan);
                $("#id_asal").append(`<option value="${data.asal.id_wil}">${data.asal.nama_wil}</option>`);
                $("#id_tujuan").append(`<option value="${data.tujuan.id_wil}">${data.tujuan.nama_wil}</option>`);
                $("#id_tipe_barang").append(`<option value="${data.tipe.id_tipe_kirim}">${data.tipe.nm_tipe_kirim}</option>`);
                $("#id_pelanggan").append(`<option value="${data.perush.id_perush}">${data.perush.nm_perush
}</option>`);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                }
            });
        });

    var harga_jual = 0;
    var harga_beli = 0;

    function hitung() {
        var nilai = parseFloat($("input[name='n_pertanggungan']" ).val());
        var jual = (nilai*harga_jual)/100;
        var beli = (nilai*harga_beli)/100;
        $("#nominal_jual").val(jual);
        $("#nominal_beli").val(beli);
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
                harga_jual = data.harga_jual;
                harga_beli = data.harga_beli;
                hitung();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    setNull();
                }
            });
        });
    </script>
    @endsection
