@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
            <i class="ki-duotone ki-black-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body p-lg-20">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                    <div class="mt-n1">
                        <div class="m-0">
                            <div class="fw-bold fs-3 text-gray-800 mb-8">
                                #{{ $data->kode_pendapatan }}({{ isset($data->debet->nama) ? $data->debet->nama : '' }})
                            </div>
                            <div class="row g-5 mb-11">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tanggal Masuk:</div>
                                    <div class="fw-bold fs-6 text-gray-800">{{ dateindo($data->tgl_masuk) }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tanggal Dibuat:</div>
                                    <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">{{ dateindo($data->created_at) }}</span>
                                        <span class="fs-7 text-danger d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-danger me-2"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Diterima Dari:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->terima_dr) ? ucwords(strtolower($data->terima_dr)) : '' }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Admin:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->user->nm_user) ? ucwords(strtolower($data->user->nm_user)) : '' }}.
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Harga:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->c_total) ? toRupiah($data->c_total) : '' }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Info:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->info) ? $data->info : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- {{ dd($data) }} --}}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body p-lg-20">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                    <div class="mt-n1">
                        <div class="m-0">
                            <div class="fw-bold fs-3 text-gray-800 mb-8">
                                #Detail
                            </div>
                            <div class="row g-5 mb-11">
                                @foreach ($detail as $key => $value)
                                    <div class="col-sm-4">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">
                                            #{{ $key + 1 }} Nama Akun:</div>
                                        <div class="fw-bold fs-6 text-gray-800">
                                            {{ isset($value->akun->nama) ? '(' . $value->akun->id_ac . ') ' . strtoupper($value->akun->nama) : '' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Nominal:</div>
                                        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                            <span
                                                class="pe-2">{{ isset($value->total) ? toRupiah($value->total) : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Keterangan:</div>
                                        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                            <span class="pe-2">{{ isset($value->info) ? $value->info : '-' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- {{ dd($data) }} --}}
            </div>
        </div>
    </div>
@endsection
