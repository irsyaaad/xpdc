@extends('templatev2.defaultlayout')
@section('content')
    <form class="m-form m-form--fit m-form--label-align-right" method="POST"
        action="@if (Request::segment(2) == 'create') {{ url('jurnal-keluar-asuransi') }}@else{{ route('jurnal-keluar-update', $data->id_pengeluaran) }} @endif"
        enctype="multipart/form-data">
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
                                <span>Akun</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <select id="id_ac" class="form-select form-select-solid" name="id_ac"
                                data-kt-select2="true" data-placeholder="Pilih Akun">
                                <option value=""></option>
                                @foreach ($debit as $item)
                                    <option value="{{ $item->id_ac }}"
                                        {{ isset($data->id_ac) && $data->id_ac == $item->id_ac ? 'selected' : '' }}>
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
                                value="@if(old('tgl_masuk') != null) {{ old('tgl_masuk') }}@elseif(isset($data->tgl_keluar)){{ $data->tgl_keluar }}@endif"
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
                                <span class="required">Terima Dari</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="terima_dr" id="terima_dr"
                                value="@if (old('terima_dr') != null) {{ old('terima_dr') }}@elseif(isset($data->terima_dr)){{ $data->terima_dr }} @endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('terima_dr'))
                                <label style="color: red">
                                    {{ $errors->first('terima_dr') }}
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
        <br>
        <div class="card card-flush h-lg-100" id="detail">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
                <div class="card-title">
                    <i class="ki-outline ki-badge fs-1 me-2"></i> Detail
                </div>
            </div>
            <div class="card-body pt-5" id="detail-pemasukan">
                @if (isset($detail))
                    @foreach ($detail as $key => $detail)
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Akun</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                            <i class="ki-outline ki-information fs-7"></i>
                                        </span>
                                    </label>
                                    <select id="id_ac_detail" class="form-select form-select-solid id_ac_select" name="id_ac_detail[]"
                                        data-placeholder="Pilih Akun"
                                        data-akun=" {{ json_encode($akun) }}">
                                        <option value=""></option>
                                        @foreach ($akun as $item)
                                            <option value="{{ $item->id_ac }}" {{ isset($detail->id_ac) && $detail->id_ac == $item->id_ac ? 'selected' : '' }}>{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span class="required">Nominal</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                            <i class="ki-outline ki-information fs-7"></i>
                                        </span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" name="harga[]"
                                        id="harga[]"
                                        value="@if(old('harga') != null) {{ old('harga') }}@elseif(isset($detail->total)){{ $detail->total }}@endif"
                                        required="required" maxlength="100" />
                                    @if ($errors->has('harga'))
                                        <label style="color: red">
                                            {{ $errors->first('harga') }}
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span class="required">Keterangan</span>
                                        <span class="ms-1" data-bs-toggle="tooltip" title="Diterima dari.">
                                            <i class="ki-outline ki-information fs-7"></i>
                                        </span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" name="detail_info[]"
                                        id="detail_info[]"
                                        value="@if(old('info') != null) {{ old('info') }}@elseif(isset($detail->info)){{ $detail->info }}@endif"
                                        required="required" maxlength="100" />
                                    @if ($errors->has('info'))
                                        <label style="color: red">
                                            {{ $errors->first('info') }}
                                        </label>
                                    @endif
                                </div>
                            </div>
                            @if ($key == 0)
                                <button type="button" id="add-detail" class="btn btn-sm btn-primary col-md-2 mb-8 mt-11">Tambah</button>
                            @else
                                <button type="button" id="remove-detail" class="btn btn-sm btn-danger col-md-2 mb-8 mt-11 delete">Delete</button>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="separator mb-6"></div>
        <div class="d-flex justify-content-end">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-light me-3">Cancel</a>
            <button type="submit" data-kt-contacts-type="submit" class="btn btn-primary">
                <span class="indicator-label">Save</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
@endsection
