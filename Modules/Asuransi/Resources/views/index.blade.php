@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
    <a href="{{ url(Request::segment(1)) . '/create' }}" class="btn btn-sm btn-primary fw-bold"><i
            class="ki-outline ki-plus fs-5 me-1 text-white-500"></i>Tambah {{ $page_title }}</a>
@endsection
@section('content')
    @include('asuransi::filter-asuransi')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-data">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-125px rounded-start">Kode STT</th>
                            <th class="min-w-300px">Pelanggan</th>
                            <th class="min-w-300px">Asal</th>
                            <th class="min-w-250px">Tujuan</th>
                            <th class="min-w-250px">Nominal</th>
                            <th class="min-w-100px text-end rounded-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-white">
                                                <i class="ki-outline ki-book fs-2x text-primary"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="{{ url(Request::segment(1)) . '/' . $value->id_asuransi . '/show' }}"
                                                class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ strtoupper($value->id_stt) }}</a>
                                            <span
                                                class="badge badge-light-success fs-8 fw-bold">{{ isset($value->nm_pengirim) ? $value->nm_pengirim : '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->pelanggan->nm_perush) ? ucwords(strtolower($value->pelanggan->nm_perush)) : '-' }}</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->asal->nama_wil) ? ucwords(strtolower($value->asal->nama_wil)) : ucwords(strtolower($value->asal)) }}</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Berangkat :
                                        {{ isset($value->tgl_berangkat) ? dateindo($value->tgl_berangkat) : '-' }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->tujuan->nama_wil) ? ucwords(strtolower($value->tujuan->nama_wil)) : ucwords(strtolower($value->tujuan)) }}</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Est. Sampai :
                                        {{ isset($value->tgl_sampai) ? dateindo($value->tgl_sampai) : '-' }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->nominal_jual) ? toRupiah($value->nominal_jual) : '-' }}</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Beli :
                                        {{ isset($value->nominal_beli) ? toRupiah($value->nominal_beli) : '-' }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold"
                                        data-kt-menu="true">
                                        <div class="menu-item" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-start">
                                            <a href="#" class="menu-link py-3">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-switch fs-3"><span class="path1"></span><span
                                                            class="path2"></span><span class="path3"></span><span
                                                            class="path4"></span></i>
                                                </span>
                                                <span class="menu-title">Action</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                                <div class="menu-item">
                                                    <a href="{{ url(Request::segment(1) . '/' . $value->id_asuransi . '/edit') }}"
                                                        class="menu-link px-1 py-3">
                                                        <span class="menu-bullet me-2">
                                                            <span class="ki-outline ki-pencil fs-5"></span>
                                                        </span>
                                                        <span class="menu-title">Edit</span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a href="{{ url(Request::segment(1) . '/' . $value->id_asuransi . '/cetak_pdf') }}"
                                                        class="menu-link px-1 py-3" target="_blank" rel="nofollow">
                                                        <span class="menu-bullet me-2">
                                                            <span class="ki-outline ki-printer"></span>
                                                        </span>
                                                        <span class="menu-title">Cetak</span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a href="javascript:void(0)" class="btn menu-link px-1 py-3 btn-bayar"
                                                        data-id-perush-asuransi="{{ $value->broker }}"
                                                        data-nominal-beli="{{ $value->nominal_beli }}"
                                                        data-id-asuransi="{{ $value->id_asuransi }}"
                                                        data-nm-broker="{{ $value->perush_asuransi->nm_perush_asuransi }}"
                                                        data-url="{{ url('bayar-broker-asuransi') }}">
                                                        <span class="menu-bullet me-2">
                                                            <span class="bi bi-cash fs-5"></span>
                                                        </span>
                                                        <span class="menu-title">Bayar Broker</span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a href="#" class="menu-link px-1 py-3 btn-delete" type="button"
                                                        data-url="{{ url(Request::segment(1)) . '/' . $value->id_asuransi }}">
                                                        <span class="menu-bullet me-2">
                                                            <span class="ki-outline ki-trash"></span>
                                                        </span>
                                                        <span class="menu-title">Hapus</span>
                                                        <script>
                                                            var APP_URL = {!! json_encode(url('/')) !!};
                                                            var TOKEN = "{{ csrf_token() }}";
                                                        </script>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('template.new-paginator')
        </div>
    </div>
    <div class="modal fade" id="modal-bayar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Bayar Broker</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                        id="kt_modal_create_app_stepper">
                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <form class="form" novalidate="novalidate" method="POST"
                                action="{{ url(Request::segment(1)) }}" id="bayar_invoice">
                                <input type="hidden" name="_method" id="_method" value="">
                                <input type="hidden" name="id_asuransi" id="id_asuransi" value="">
                                @csrf
                                <div class="current" data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Akun Bayar</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Nama Cara Bayar">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg form-control-solid"
                                                id="nm_broker" readonly>
                                            <input type="hidden" id="id_perush_asuransi" name="id_perush_asuransi">
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Nama Pembayar</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Nama Cara Bayar">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="nm_bayar" id="nm_bayar" placeholder="Ex: Bastian"
                                                    value="" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">No Refernsi</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Nama Cara Bayar">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="no_referensi" id="no_referensi" placeholder="Ex: 021-xxx"
                                                    value="" required />
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Tanggal Bayar</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Nama Cara Bayar">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="date"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="tgl_bayar" id="tgl_bayar" placeholder="Ex: Bahan Bangunan"
                                                    value="" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Nominal</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Nama Cara Bayar">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid" name="n_bayar"
                                                    id="n_bayar" placeholder="Ex: " value="" required />
                                            </div>
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Akun Bayar</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Nama Cara Bayar">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <select name="akun" id="akun"
                                                class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Pilih Tipe Kirim" data-allow-clear="true">
                                                <option value="1010">KAS</option>
                                                <option value="1011">BANK</option>
                                            </select>
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Info</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Info">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg form-control-solid"
                                                name="info" id="info" placeholder="Ex: " value=""
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-stack pt-10">
                                    <div class="me-2">
                                        <button type="button" class="btn btn-lg btn-light-primary me-3"
                                            data-kt-stepper-action="previous">
                                            <i class="ki-outline ki-arrow-left fs-3 me-1"></i>Back</button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-lg btn-primary btn-submit">
                                            <span class="indicator-label">Submit
                                                <i class="ki-outline ki-file-added fs-3 ms-2 me-0"></i></span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
