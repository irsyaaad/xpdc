@extends('templatev2.defaultlayout')

@section('content')
    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
        action="@if (Request::segment(2) == 'create') {{ url(Request::segment(1)) }}@else{{ url(Request::segment(1), $data->id_invoice) }} @endif"
        enctype="multipart/form-data">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        <div class="row" style="margin-top: 15px">
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="id_invoice" id="id_invoice"
                            value="@if(isset($data->kode_invoice)){{ $data->kode_invoice }}@else{{ old('id_invoice') }}@endif"
                            placeholder="No. Invoice" required readonly>
                        <label class="form-label required">No. Invoice</label>
                        @if ($errors->has('id_invoice'))
                            <label style="color: red">
                                {{ $errors->first('id_invoice') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        @php
                            $tgl = old('tgl') != null ? old('tgl') : date('Y-m-d');
                        @endphp
                        <input type="date" class="form-control" id="tgl" name="tgl"
                            value="@if(isset($data->tgl)){{ $data->tgl }}@else{{ $tgl }}@endif"
                            required>
                        <label class="form-label required">Tanggal</label>
                        @if ($errors->has('tgl'))
                            <label style="color: red">
                                {{ $errors->first('tgl') }}
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="inv_j_tempo" name="inv_j_tempo"
                            value="@if(isset($data->inv_j_tempo)){{ $data->inv_j_tempo }}@else{{ old('inv_j_tempo') }}@endif"
                            required>
                        <label class="form-label required">Jatuh Tempo</label>
                        @if ($errors->has('inv_j_tempo'))
                            <label style="color: red">
                                {{ $errors->first('inv_j_tempo') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" data-control="select2" id="id_pelanggan" name="id_pelanggan"
                            data-placeholder="Pelanggan" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($perusahaan as $key => $value)
                                <option value="{{ $value->id_perush }}"
                                    {{ old('id_pelanggan', $data->id_perush ?? '') == $value->id_perush ? 'selected' : '' }}>
                                    {{ strtoupper($value->nm_perush) }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form-label required">Pelanggan</label>
                        @if ($errors->has('id_pelanggan'))
                            <label style="color: red">
                                {{ $errors->first('id_pelanggan') }}
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="kontak" id="kontak" placeholder="Kontak"
                            value="{{ old('kontak', $data->kontak ?? '') }}" required />
                        <label class="form-label required">Kontak</label>
                        @if ($errors->has('kontak'))
                            <label style="color: red">
                                {{ $errors->first('kontak') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="number" class="form-control" name="hp" id="hp" placeholder="HP"
                            value="{{ old('hp', $data->hp ?? '') }}" required />
                        <label class="form-label required">HP</label>
                        @if ($errors->has('hp'))
                            <label style="color: red">
                                {{ $errors->first('hp') }}
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-right" style="padding-top: 20px;">
                @include('template.inc_action')
            </div>
        </div>
    </form>
@endsection
