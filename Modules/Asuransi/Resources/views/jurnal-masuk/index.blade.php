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
    <div class="card mb-5 mb-xl-8">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-data">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-125px rounded-start">No. Transaksi</th>
                            <th class="min-w-300px">Perkiraan Akun</th>
                            <th class="min-w-300px">Terima / Admin</th>
                            <th class="min-w-250px">Nominal</th>
                            <th class="min-w-250px">Keterangan</th>
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
                                            <a href="{{ url(Request::segment(1)) . '/' . $value->id_pendapatan . '/show' }}"
                                                class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ strtoupper($value->kode_pendapatan) }}</a>
                                            <span
                                                class="badge badge-light-success fs-8 fw-bold">{{ isset($value->tgl_masuk) ? dateindo($value->tgl_masuk) : '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->debet->nama) ? ucwords(strtolower(' ( ' . $value->debet->id_ac . ' ) ' . $value->debet->nama)) : '-' }}</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->terima_dr) ? ucwords(strtolower($value->terima_dr)) : '' }}</a>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Admin :
                                        {{ isset($value->user->nm_user) ? $value->user->nm_user : '-' }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->c_total) ? toRupiah($value->c_total) : '' }}</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->info) ? $value->info : '-' }}</a>
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
                                                    <a href="{{ url(Request::segment(1) . '/' . $value->id_pendapatan . '/edit') }}"
                                                        class="menu-link px-1 py-3">
                                                        <span class="menu-bullet me-2">
                                                            <span class="ki-outline ki-pencil fs-5"></span>
                                                        </span>
                                                        <span class="menu-title">Edit</span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a href="#" class="menu-link px-1 py-3 btn-delete" type="button"
                                                        data-url="{{ url(Request::segment(1)) . '/' . $value->id_pendapatan }}">
                                                        <span class="menu-bullet me-2">
                                                            <span class="ki-outline ki-trash"></span>
                                                        </span>
                                                        <span class="menu-title">Hapus</span>
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
@endsection
