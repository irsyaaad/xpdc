@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
    <a href="javascript:void(0)" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal"
        data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button"><i
            class="ki-outline ki-plus fs-5 me-1 text-white-500"></i>Tambah {{ $page_title }}</a>
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
                                <select name="f_id_tipe_kirim" id="f_id_tipe_kirim"
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
                                <th class="rounded-start">Nama Perusahaan</th>
                                <th>Harga Jual</th>
                                <th>Harga Beli</th>
                                <th>Min Harga Pertanggungan</th>
                                <th>Charge Min</th>
                                <th class="text-end rounded-end">Action</th>
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
                                                    {{ isset($value->perusahaan_asuransi->nm_perush_asuransi) ? ucwords(strtolower($value->perusahaan_asuransi->nm_perush_asuransi)) : ' ' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->harga_jual) ? $value->harga_jual : '' }} %
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->harga_beli) ? $value->harga_beli : '' }} %
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->min_harga_pertanggungan) ? toRupiah($value->min_harga_pertanggungan) : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            Jual :
                                            {{ isset($value->charge_min_jual) ? toRupiah($value->charge_min_jual) : '' }}
                                        </span>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            Beli :
                                            {{ isset($value->charge_min_beli) ? toRupiah($value->charge_min_beli) : '' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-warning btn-edit"
                                            data-url="{{ url(Request::segment(1), $value->id_tarif) }}"
                                            data-id_perush_asuransi="{{ $value->id_perush_asuransi }}"
                                            data-harga_beli="{{ $value->harga_beli }}"
                                            data-harga_jual="{{ $value->harga_jual }}"
                                            data-min_harga_pertanggungan="{{ $value->min_harga_pertanggungan }}"
                                            data-charge_min_jual="{{ $value->charge_min_jual }}"
                                            data-charge_min_beli="{{ $value->charge_min_beli }}"
                                            >
                                            <span><i class="fa fa-edit"></i></span> Edit
                                        </a>

                                        <a href="#" class="btn btn-sm btn-danger btn-delete" type="button"
                                            data-url="{{ url(Request::segment(1)) . '/' . $value->id_tarif }}">
                                            <span><i class="fa fa-times"></i></span> Hapus
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
    <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tambah {{ $page_title }}</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                        id="kt_modal_create_app_stepper">
                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <form class="form" novalidate="novalidate" method="POST"
                                action="{{ url(Request::segment(1)) }}" id="kt_modal_create_app_form">
                                <input type="hidden" name="_method" id="_method" value="">
                                @csrf
                                <div class="current" data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Nama Perusahaan Asuransi</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Nama Cara Bayar">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <select name="id_perush_asuransi" id="id_perush_asuransi"
                                                class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Pilih Perusahaan Asuransi" data-allow-clear="true">
                                                @foreach ($perusahaan_asuransi as $key => $value)
                                                    <option value="{{ $value->id_perush_asuransi }}">
                                                        {{ $value->nm_perush_asuransi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Harga Jual (Dalam Persen)</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number" step="any"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="harga_jual" id="harga_jual" placeholder="Ex: 0.2"
                                                    value="" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Harga Beli (Dalam Persen)</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number" step="any"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="harga_beli" id="harga_beli" placeholder="Ex: 0.15"
                                                    value="" required />
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Min Harga Pertanggungan</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Charge Min">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <input type="number" class="form-control form-control-lg form-control-solid"
                                                name="min_harga_pertanggungan" id="min_harga_pertanggungan"
                                                placeholder="100000000" value="" required />
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Charge Min Jual</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number" step="any"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="charge_min_jual" id="charge_min_jual" placeholder="Ex: 200000"
                                                    value="" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Charge Min Beli</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number" step="any"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="charge_min_beli" id="charge_min_beli" placeholder="Ex: 120000"
                                                    value="" required />
                                            </div>
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
