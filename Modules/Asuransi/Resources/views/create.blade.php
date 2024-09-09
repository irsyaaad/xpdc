@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-dm" aria-expanded="false" aria-controls="filter-dm">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
@endsection

@section('content')
    <div class="card card-flush h-lg-100" id="kt_contacts_main">
        <div class="card-header pt-7" id="kt_chat_contacts_header">
            <div class="card-title">
                <i class="ki-outline ki-badge fs-1 me-2"></i>
            </div>
        </div>
        <div class="card-body pt-5">
            <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                action="@if (Request::segment(2) == 'create') {{ url('asuransi') }}@else{{ route('update', $data->id_asuransi) }} @endif"
                enctype="multipart/form-data">
                @if (Request::segment(3) == 'edit')
                    {{ method_field('PUT') }}
                @endif
                @csrf
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Kode STT / No DM</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Nama Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="id_stt_dm" id="id_stt_dm"
                                value="@if (old('id_stt_dm') != null) {{ old('id_stt_dm') }}@elseif(isset($data->id_stt)){{ $data->id_stt }} @endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('id_stt_dm'))
                                <label style="color: red">
                                    {{ $errors->first('id_stt_dm') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mt-12">
                            <button type="button" class="btn btn-primary mb-2" id="btn-search">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Pelanggan</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <select id="id_pelanggan" class="form-select form-select-solid" name="id_pelanggan"
                                data-kt-select2="true" data-placeholder="Pilih Pelanggan">
                                <option value=""></option>
                                @foreach ($perusahaan as $item)
                                    <option value="{{ $item->id_perush }}"
                                        {{ isset($data->id_pelanggan) && $data->id_pelanggan == $item->id_perush ? 'selected' : '' }}>
                                        {{ $item->nm_perush }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Nama Pengirim</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Nama Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="nm_pengirim"
                                id="nm_pengirim"
                                value="@if (old('nm_pengirim') != null) {{ old('nm_pengirim') }}@elseif(isset($data->nm_pengirim)){{ $data->nm_pengirim }} @endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('nm_pelanggan'))
                                <label style="color: red">
                                    {{ $errors->first('nm_pelanggan') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Asal</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Grup Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            {{-- <select id="id_asal" class="form-select form-select-solid" name="id_asal"
                                data-kt-select2="true" data-placeholder="Pilih Asal">
                                @if (isset($data->id_asal))
                                    <option value="{{ $data->id_asal }}">{{ $data->asal->nama_wil }}</option>
                                @endif
                            </select> --}}
                            <input type="text" class="form-control form-control-solid" name="asal" id="asal"
                                value="@if (old('asal') != null) {{ old('asal') }}@elseif(isset($data->asal)){{ $data->asal }} @endif"
                                required="required" maxlength="100" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Tujuan</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Grup Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            {{-- <select id="id_tujuan" class="form-select form-select-solid" name="id_tujuan"
                                data-kt-select2="true" data-placeholder="Pilih Tujuan">
                                @if (isset($data->id_tujuan))
                                    <option value="{{ $data->id_tujuan }}">{{ $data->tujuan->nama_wil }}</option>
                                @endif
                            </select> --}}
                            <input type="text" class="form-control form-control-solid" name="tujuan" id="tujuan"
                                value="@if (old('tujuan') != null) {{ old('tujuan') }}@elseif(isset($data->tujuan)){{ $data->tujuan }} @endif"
                                required="required" maxlength="100" />
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Nama Kapal</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Nama Kapal.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="nm_kapal" id="nm_kapal"
                                value="@if (old('nm_kapal') != null) {{ old('nm_kapal') }}@elseif(isset($data->nm_kapal)){{ $data->nm_kapal }} @endif">
                            @if ($errors->has('nm_kapal'))
                                <label style="color: red">
                                    {{ $errors->first('nm_kapal') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">No Identity (NOPOL/NO. CONTAINER/NO. SERI)</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan No Container.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="no_identity"
                                id="no_identity"
                                value="@if (old('no_identity') != null) {{ old('no_identity') }}@elseif(isset($data->no_identity)){{ $data->no_identity }} @endif">
                            @if ($errors->has('no_identity'))
                                <label style="color: red">
                                    {{ $errors->first('no_identity') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Tgl Berangkat</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Contact Person.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="date" class="form-control form-control-solid" name="tgl_berangkat"
                                id="tgl_berangkat"
                                value="@if(old('tgl_berangkat') != null) {{ old('nm_kontak') }}@elseif(isset($data->tgl_berangkat)){{ $data->tgl_berangkat }}@endif">
                            @if ($errors->has('tgl_berangkat'))
                                <label style="color: red">
                                    {{ $errors->first('tgl_berangkat') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Tgl Sampai</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Contact Person Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="date" class="form-control form-control-solid" name="tgl_sampai"
                                id="tgl_sampai"
                                value="@if(old('tgl_sampai') != null) {{ old('tgl_sampai') }}@elseif(isset($data->tgl_sampai)){{ $data->tgl_sampai }}@endif">
                            @if ($errors->has('tgl_sampai'))
                                <label style="color: red">
                                    {{ $errors->first('tgl_sampai') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Tipe Barang</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Grup Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            {{-- <select id="id_tipe_barang" class="form-select form-select-solid" name="id_tipe_barang"
                                data-kt-select2="true" data-placeholder="Pilih Tipe Barang">
                                @if (isset($data->id_tipe_barang))
                                    <option value="{{ $data->id_tipe_barang }}">{{ $data->tipebarang->nm_tipe_kirim }}
                                    </option>
                                @endif
                            </select> --}}
                            <input type="text" class="form-control form-control-solid" name="nm_tipe_barang" id="nm_tipe_barang"
                                value="@if (old('nm_tipe_barang') != null) {{ old('nm_tipe_barang') }}@elseif(isset($data->nm_tipe_barang)){{ $data->nm_tipe_barang }} @endif"
                                required="required" maxlength="100" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Qty</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Nama Pelanggan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="number" class="form-control form-control-solid" name="qty" id="qty"
                                value="@if(old('qty') != null){{ old('qty') }}@elseif(isset($data->qty)){{ $data->qty }}@endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('qty'))
                                <label style="color: red">
                                    {{ $errors->first('qty') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Broker</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Pilih Broker.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <select id="broker" class="form-select form-select-solid" name="broker"
                                data-kt-select2="true" data-placeholder="Pilih Broker">
                                <option value=""></option>
                                @foreach ($perush_asuransi as $item)
                                    <option value="{{ $item->id_perush_asuransi }}"
                                        data-harga_jual="{{ $item->tarif->harga_jual }}"
                                        data-harga_beli="{{ $item->tarif->harga_beli }}"
                                        data-min_harga_pertanggungan="{{ $item->tarif->min_harga_pertanggungan }}"
                                        data-charge_min_jual="{{ $item->tarif->charge_min_jual }}"
                                        data-charge_min_beli="{{ $item->tarif->charge_min_beli }}"
                                        {{ isset($data->broker) && $data->broker == $item->id_perush_asuransi ? 'selected' : '' }}>
                                        {{ $item->nm_perush_asuransi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Harga Pertanggungan</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Harga Pertanggungan.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="number" class="form-control form-control-solid" name="harga_pertanggungan"
                                id="harga_pertanggungan"
                                value="@if(old('harga_pertanggungan') != null){{ old('harga_pertanggungan') }}@elseif(isset($data->harga_pertanggungan)){{ $data->harga_pertanggungan }}@endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('harga_pertanggungan'))
                                <label style="color: red">
                                    {{ $errors->first('harga_pertanggungan') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Harga Jual</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Harga Jual.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="number" class="form-control form-control-solid" name="nominal_jual"
                                id="nominal_jual"
                                value="@if(old('nominal_jual') != null) {{ old('nominal_jual') }}@elseif(isset($data->nominal_jual)){{ $data->nominal_jual }}@endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('nominal_jual'))
                                <label style="color: red">
                                    {{ $errors->first('nominal_jual') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Harga Beli</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Harga Beli.">
                                    <i class="ki-outline ki-information fs-7"></i>
                                </span>
                            </label>
                            <input type="number" class="form-control form-control-solid" name="nominal_beli"
                                id="nominal_beli"
                                value="@if(old('nominal_beli') != null) {{ old('nominal_beli') }}@elseif(isset($data->nominal_beli)){{ $data->nominal_beli }}@endif"
                                required="required" maxlength="100" />
                            @if ($errors->has('nominal_beli'))
                                <label style="color: red">
                                    {{ $errors->first('nominal_beli') }}
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Keterangan</span>
                        <span class="ms-1" data-bs-toggle="tooltip" title="Masukkan Keterangan">
                            <i class="ki-outline ki-information fs-7"></i>
                        </span>
                    </label>
                    <textarea class="form-control form-control-solid" name="keterangan" id="keterangan">
@if (old('keterangan') != null)
{{ old('keterangan') }}
@elseif(isset($data->keterangan))
{{ $data->keterangan }}
@endif
</textarea>
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
        </div>
    </div>
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!};
        var TOKEN = "{{ csrf_token() }}";
    </script>
@endsection
