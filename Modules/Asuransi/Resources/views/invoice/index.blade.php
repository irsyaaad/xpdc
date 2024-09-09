@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-invoice" aria-expanded="false" aria-controls="filter-invoice">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
    <a href="{{ Request::segment(1) . '/create' }}" class="btn btn-sm btn-primary fw-bold">
        <i class="ki-outline ki-plus fs-5 me-1 text-white-500"></i>Tambah Invoice
    </a>
@endsection

@section('content')
    <div class="row mt-5">
        <div class="card mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-stt">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-250px rounded-start">No. Invoice</th>
                                <th class="min-w-175px">Nama Pelanggan</th>
                                <th class="min-w-250px">Tanggal Penagihan</th>
                                <th class="min-w-175px">Total</th>
                                <th class="min-w-175px">Sisa</th>
                                <th class="min-w-100px text-end rounded-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr @if (isset($value->sisa) && $value->sisa != 0 and $value->inv_j_tempo < date('Y-m-d')) class="text-danger" @endif>
                                    <td>
                                        <a href="{{ url(Request::segment(1) . '/' . $value->id_invoice . '/show') }}">
                                            <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                @if (isset($value->kode_invoice))
                                                    {{ strtoupper($value->kode_invoice) }}
                                                @endif
                                            </span>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                {{ $value->created_at }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex justify-content-start flex-column">
                                                <span
                                                    class="text-dark fw-bold d-block mb-1 fs-6">{{ strtoupper($value->pelanggan->nm_perush) }}</span>
                                                <span
                                                    class="text-muted fw-semibold text-muted d-block fs-7">{{ strtoupper($value->pelanggan->telp) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                            @if (isset($value->tgl))
                                                {{ daydate($value->tgl) . ', ' . dateindo($value->tgl) }}
                                            @endif
                                        </span>
                                        <span
                                            class="mt-2 badge badge-light-{{ $value->sisa != 0 && $value->inv_j_tempo < date('Y-m-d') ? 'danger' : 'primary' }} fs-7 fw-bold">Jatuh
                                            Tempo :
                                            @if (isset($value->inv_j_tempo))
                                                {{ daydate($value->inv_j_tempo) . ', ' . dateindo($value->inv_j_tempo) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                            {{ toNumber($value->total) }}
                                        </span>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">
                                            Dibayar : {{ toNumber($value->total_bayar) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                            {{ toNumber($value->total - $value->total_bayar) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold"
                                            data-kt-menu="true">
                                            <div class="menu-item" data-kt-menu-trigger="hover"
                                                data-kt-menu-placement="right-start">
                                                <a href="#" class="menu-link py-3">
                                                    <span class="menu-icon">
                                                        <i class="ki-duotone ki-switch fs-3"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span><span class="path4"></span></i>
                                                    </span>
                                                    <span class="menu-title">Action</span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                                <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                                    <div class="menu-item">
                                                        <a href="{{ url(Request::segment(1) . '/' . $value->id_invoice . '/show') }}"
                                                            class="menu-link px-1 py-3">
                                                            <span class="menu-bullet me-2">
                                                                <span class="bi bi-eye fs-5"></span>
                                                            </span>
                                                            <span class="menu-title"> Detail</span>
                                                        </a>
                                                    </div>
                                                    @if ($value->bayar == null)
                                                        <div class="menu-item">
                                                            <a href="{{ url(Request::segment(1) . '/' . $value->id_invoice . '/edit') }}"
                                                                class="menu-link px-1 py-3">
                                                                <span class="menu-bullet me-2">
                                                                    <span class="ki-outline ki-pencil fs-5"></span>
                                                                </span>
                                                                <span class="menu-title"> Edit</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if ($value->total > $value->bayar)
                                                        <div class="menu-item">
                                                            <a href="javascript:void(0)"
                                                                class="btn menu-link px-1 py-3 btn-bayar"
                                                                data-sisa="{{ $value->total - $value->bayar }}"
                                                                data-kode-invoice="{{ $value->kode_invoice }}"
                                                                data-nm-pelanggan="{{ $value->pelanggan->nm_perush }}"
                                                                data-id-pelanggan="{{ $value->id_pelanggan }}"
                                                                data-url="{{ url('invoice-asuransi') . '/' . $value->id_invoice . '/bayar' }}">
                                                                <span class="menu-bullet me-2">
                                                                    <span class="bi bi-cash fs-5"></span>
                                                                </span>
                                                                <span class="menu-title">Bayar</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="menu-item">
                                                        <a href="{{ url(Request::segment(1) . '/' . $value->id_invoice . '/cetak') }}"
                                                            class="menu-link px-1 py-3" target="_blank">
                                                            <span class="menu-bullet me-2">
                                                                <span class="bi bi-printer fs-5"></span>
                                                            </span>
                                                            <span class="menu-title"> Cetak Invoice</span>
                                                        </a>
                                                    </div>
                                                    @if ($value->bayar == null)
                                                        <div class="menu-item">
                                                            <a href="javacscript:void(0)"
                                                                class="btn menu-link px-1 py-3 btn-delete"
                                                                data-id-invoice="{{ $value->id_invoice }}"
                                                                data-url="{{ url(Request::segment(1) . '/' . $value->id_invoice) . '/delete' }}"><span
                                                                    class="menu-bullet me-2">
                                                                    <span class="bi bi-trash fs-5"></span>
                                                                </span>
                                                                <span class="menu-title">Delete</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('template.new-paginator')
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
