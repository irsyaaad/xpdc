@extends('templatev2.defaultlayout')
@section('content')
    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
        action="@if (Request::segment(2) == 'create') {{ url(Request::segment(1)) }}@else{{ route(Request::segment(1) . '.update', $data->id_memorial) }} @endif"
        enctype="multipart/form-data" id="form-data">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        <div class="card card-flush h-lg-100" id="kt_contacts_main">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
                <div class="card-title">
                    <i class="ki-outline ki-badge fs-1 me-2"></i> Head
                </div>
            </div>
            <div class="card-body pt-5">
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Debit</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <select id="id_ac_debet" class="form-select form-select-solid" name="id_ac_debet"
                                data-placeholder="Pilih Akun">
                                <option value=""></option>
                                @foreach ($akun as $item)
                                    <option value="{{ $item->id_ac }}"
                                        {{ isset($data->id_ac_debet) && $data->id_ac_debet == $item->id_ac ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Kredit</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <select id="id_ac_kredit" class="form-select form-select-solid" name="id_ac_kredit"
                                data-placeholder="Pilih Akun">
                                <option value=""></option>
                                @foreach ($akun as $item)
                                    <option value="{{ $item->id_ac }}"
                                        {{ isset($data->id_ac_kredit) && $data->id_ac_kredit == $item->id_ac ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Tanggal Terima</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="date" class="form-control form-control-solid" name="tgl_masuk" id="tgl_masuk"
                                value="@if(old('tgl_masuk') != null) {{ old('tgl_masuk') }}@elseif(isset($data->tgl)){{ $data->tgl }}@endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('tgl_masuk'))
                                <label style="color: red">
                                    {{ $errors->first('tgl_masuk') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">No Referensi</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="no_referensi"
                                id="no_referensi"
                                value="@if (old('no_referensi') != null) {{ old('no_referensi') }}@elseif(isset($data->no_referensi)){{ $data->no_referensi }} @endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('no_referensi'))
                                <label style="color: red">
                                    {{ $errors->first('no_referensi') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Nominal</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="nominal" id="nominal"
                                value="@if (old('nominal') != null) {{ old('nominal') }}@elseif(isset($data->nominal)){{ $data->nominal }} @endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('nominal'))
                                <label style="color: red">
                                    {{ $errors->first('nominal') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Keterangan</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <textarea class="form-control form-control-solid" name="info" id="info" cols="30" rows="5">{{ isset($data->info) ? $data->info : '' }}</textarea>
                            @if ($errors->has('terima_dr'))
                                <label style="color: red">
                                    {{ $errors->first('terima_dr') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator mb-6"></div>
        <div class="d-flex justify-content-end">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-light me-3">Cancel</a>
            <button type="button" class="btn btn-lg btn-primary btn-submit">
                <span class="indicator-label">Save</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
@endsection
