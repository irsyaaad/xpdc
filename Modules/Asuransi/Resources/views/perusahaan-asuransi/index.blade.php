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
                                <th>Alamat</th>
                                <th>Contact Person</th>
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
                                                    {{ isset($value->nm_perush_asuransi) ? ucwords(strtolower($value->nm_perush_asuransi)) : ' ' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block mb-1 fs-6">
                                            {{ isset($value->alamat) ? ucwords(strtolower($value->alamat)) : ' ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->cp) ? ucwords(strtolower($value->cp)) : '' }}</a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">
                                            {{ isset($value->no_cp) ? $value->no_cp : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-warning btn-edit"
                                            data-url="{{ url(Request::segment(1), $value->id_perush_asuransi) }}"
                                            data-temp="{{ json_encode($value) }}">
                                            <span><i class="fa fa-edit"></i></span> Edit
                                        </a>

                                        <a href="#" class="btn btn-sm btn-danger btn-delete" type="button"
                                            data-url="{{ url(Request::segment(1)) . '/' . $value->id_perush_asuransi }}">
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
                                            <input type="text" class="form-control form-control-lg form-control-solid"
                                                name="nm_perush_asuransi" id="nm_perush_asuransi"
                                                placeholder="Ex: PT. Kalibesar Raya Utama" value="" required />
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Jenis Asuransi</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <select name="jenis_asuransi" id="jenis_asuransi"
                                                    class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                    data-placeholder="Jenis Asuransi" data-allow-clear="true">
                                                    <option value="">-- Pilih Jenis Asuransi --</option>
                                                    <option value="Construction Insurance">Construction Insurance</option>
                                                    <option value="Electronic & Heavy Equipment Insurance">Electronic &
                                                        Heavy
                                                        Equipment Insurance</option>
                                                    <option value="Health Insurance">Health Insurance</option>
                                                    <option value="Liability Insurance">Liability Insurance</option>
                                                    <option value="Marine Cargo & Hull Insurance">Marine Cargo & Hull
                                                        Insurance
                                                    </option>
                                                    <option value="Motor Vehicle Insurance">Motor Vehicle Insurance
                                                    </option>
                                                    <option value="Neon Sign Insurance">Neon Sign Insurance</option>
                                                    <option value="Property Insurance">Property Insurance</option>
                                                    <option value="Surety Bond">Surety Bond</option>
                                                    <option value="Travel Insurance">Travel Insurance</option>
                                                    <option value="Asuransi Kredit Usaha">Asuransi Kredit Usaha</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Jenis Resiko yang Dicover</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="Jenis Asuransi">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <select name="jenis_resiko" id="jenis_resiko"
                                                    class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                    data-placeholder="Jenis Asuransi" data-allow-clear="true">
                                                    <option value="">-- Pilih Jenis Resiko --</option>
                                                    <option value="ICCA">ICCA</option>
                                                    <option value="ICCB">ICCB</option>
                                                    <option value="ICCC">ICCC</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                <span class="required">Alamat</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Alamat">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <textarea name="alamat" class="form-control form-control-lg form-control-solid" id="alamat" cols="30"
                                                rows="3"></textarea>
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-4">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">No Fax</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="fax">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid" name="fax"
                                                    id="fax" placeholder="Ex: 032300xxx" value="" required />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">email</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="email">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="email"
                                                    class="form-control form-control-lg form-control-solid" name="email"
                                                    id="email" placeholder="Ex: info@kbru.com" value=""
                                                    required />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">NPWP</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="npwp">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid" name="npwp"
                                                    id="npwp" placeholder="Ex: 03232013xxx" value=""
                                                    required />
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">Contact Person</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="email">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid" name="cp"
                                                    id="cp" placeholder="Ex: Rudy Hartanto" value=""
                                                    required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                                    <span class="required">No. Contact Person</span>
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="email">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span>
                                                </label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid" name="no_cp"
                                                    id="no_cp" placeholder="Ex: 085755xxx" value="" required />
                                            </div>
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="d-flex align-items-center fs-5 fw-semibold mb-4">
                                                <span class="required">Status</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Select your app category">
                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                </span>
                                            </label>
                                            <div class="fv-row">
                                                <label class="d-flex flex-stack cursor-pointer">
                                                    <span class="d-flex align-items-center me-2">
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label bg-light-success">
                                                                <i class="ki-outline ki-check fs-1 text-success"></i>
                                                            </span>
                                                        </span>
                                                        <span class="d-flex flex-column">
                                                            <span class="fw-bold fs-6">Is Aktif?</span>
                                                            <span class="fs-7 text-muted">Centang Jika {{ $page_title }}
                                                                ini
                                                                aktif</span>
                                                        </span>
                                                    </span>
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" name="is_aktif"
                                                            id="is_aktif" value="1" />
                                                    </span>
                                                </label>
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
