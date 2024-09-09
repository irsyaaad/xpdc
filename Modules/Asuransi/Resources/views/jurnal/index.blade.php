@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
    {{-- <a href="{{ url(Request::segment(1)) . '/create' }}" class="btn btn-sm btn-primary fw-bold"><i
            class="ki-outline ki-plus fs-5 me-1 text-white-500"></i>Tambah {{ $page_title }}</a> --}}
@endsection
@section('content')
    {{-- @include('operasional::stt.filter-stt') --}}
    <div class="card mb-5 mb-xl-8">
        <div class="card-header py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-kt-ecommerce-order-filter="search" id="search"
                        class="form-control form-control-solid w-550px ps-12" placeholder="Search Order">
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-data">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th>No</th>
                            <th>No Bukti</th>
                            <th>Reff</th>
                            <th class="min-w-100px">Tanggal</th>
                            <th>ID AC</th>
                            <th class="min-w-250px">Nama AC</th>
                            <th class="min-w-250px">Keterangan</th>
                            <th class="min-w-150px">Debet</th>
                            <th class="min-w-150px">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_debit = 0;
                            $total_kredit = 0;
                            $no = 0;
                        @endphp

                        @foreach ($data as $key => $value)
                            <tr>
                                <td>{{ $no += 1 }}</td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        {{ $value->id_detail }}
                                    </span>
                                </td>
                                <td>{{ $value->reff }}</td>
                                <td>
                                    @if (isset($value->tgl_transaksi))
                                        <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                            {{ date('d-M-Y', strtotime($value->tgl_transaksi)) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->id_debet))
                                            {{ $value->id_debet }}
                                        @endif
                                    </span>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->id_kredit))
                                            {{ $value->id_kredit }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->nama_debet))
                                            {{ $value->nama_debet }}
                                        @endif
                                    </span>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->nama_kredit))
                                            {{ $value->nama_kredit }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->info_debet))
                                            {{ $value->info_debet }}
                                        @endif
                                    </span>
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->info_kredit))
                                            {{ $value->info_kredit }}
                                        @endif
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->total_debet))
                                            {{ number_format($value->total_debet, 0, ',', '.') }}
                                        @endif
                                    </span>
                                    @php
                                        $total_debit += $value->total_debet;
                                    @endphp
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">0</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">0</span>
                                    @php
                                        $total_kredit += $value->total_kredit;
                                    @endphp
                                    <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                        @if (isset($value->total_kredit))
                                            {{ number_format($value->total_kredit, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" class="text-center">
                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                    TOTAL
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                    {{ number_format($total_debit, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">
                                    {{ number_format($total_kredit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
