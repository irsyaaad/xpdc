@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="collapse mb-5" id="filter-data">
            <div class="card card-body">
                <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label class="fs-6 form-label fw-bold text-dark">Tipe Kirim :</label>
                                <select name="f_id_invoice_pay" id="f_id_invoice_pay"
                                    class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                    data-placeholder="Pilih Tipe Kirim" data-allow-clear="true">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-10">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                    data-placement="bottom" title="Refresh Data"><span><i
                                            class="fa fa-refresh"></i></span></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-data">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="rounded-start">No Kwitansi</th>
                                <th>No STT/DM</th>
                                <th>No Referensi</th>
                                <th>Info</th>
                                <th class="min-w-150px">Nominal</th>
                                <th class="min-w-150px text-end rounded-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-2">
                                                <span class="symbol-label bg-white">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-dark fw-bold d-block mb-1 fs-6">
                                                    {{ isset($value->no_kwitansi) ? $value->no_kwitansi : ' ' }}
                                                </span>
                                                <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                    {{ isset($value->tgl_bayar) ? dateindo($value->tgl_bayar) : ' ' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->asuransi->id_stt) ? $value->asuransi->id_stt : ' ' }}
                                        </span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">
                                            {{ isset($value->pelanggan->nm_perush) ? $value->pelanggan->nm_perush : ' ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->no_bayar) ? $value->no_bayar : ' ' }}
                                        </span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">
                                            Dibayar oleh : {{ isset($value->nm_bayar) ? $value->nm_bayar : ' ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->info) ? $value->info : ' ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->n_bayar) ? toRupiah($value->n_bayar) : ' ' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-warning btn-edit"
                                            data-tgl-bayar="{{ $value->tgl_bayar }}"
                                            data-nm-bayar="{{ $value->nm_bayar }}"
                                            data-no-bayar="{{ $value->no_bayar }}"
                                            data-n-bayar="{{ $value->n_bayar }}"
                                            data-info="{{ $value->info }}"
                                            data-id-pelanggan="{{ $value->id_pelanggan }}"
                                            data-tgl-bayar="{{ $value->tgl_bayar }}"
                                            data-id-asuransi="{{ $value->id_asuransi }}"
                                            data-url="{{ url(Request::segment(1), $value->id_asuransi_pay) }}">
                                            <span><i class="fa fa-edit"></i></span>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-danger btn-delete" type="button"
                                            data-url="{{ url(Request::segment(1)) . '/' . $value->id_asuransi_pay }}">
                                            <span><i class="fa fa-trash"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('template.new-paginator')
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-bayar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Bayar Invoice</h2>
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
                                <input type="hidden" name="id_pelanggan" id="id_pelanggan" value="">
                                <input type="hidden" name="id_asuransi" id="id_asuransi" value="">
                                @csrf
                                <div class="current" data-kt-stepper-element="content">
                                    <div class="w-100">
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
                                        <button type="submit" class="btn btn-lg btn-primary">
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
