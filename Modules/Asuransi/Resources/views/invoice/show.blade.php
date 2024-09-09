@extends('templatev2.defaultlayout')
@section('toolbar_action')
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning fw-bold" style="margin-right : 5px">
        <i class="ki-outline ki-arrow-left text-white-500"></i>
    </a>
    <a href="{{ url(Request::segment(1) . '/add-stt/' . $data->id_invoice) }}" class="btn btn-sm btn-primary fw-bold">
        <i class="ki-outline ki-plus fs-5 me-1 text-white-500"></i>Tambah Data
    </a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body py-20">
            <div class="mw-lg-950px mx-auto w-100">
                <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                    <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                    <div class="text-sm-end">
                        <a href="#" class="d-block mw-150px ms-sm-auto">
                            <img alt="Logo" src="assets/media/svg/brand-logos/lloyds-of-london-logo.svg"
                                class="w-100" />
                        </a>
                        <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                            <div>{{ $perusahaan->alamat }}</div>
                            <div>{{ ucwords(strtolower($perusahaan->wilayah->nama_wil)) }}</div>
                        </div>
                    </div>
                </div>
                <div class="pb-12">
                    <div class="d-flex flex-column gap-7 gap-md-10">
                        <div class="fw-bold fs-2">{{ $data->pelanggan->nm_perush }}
                            <span class="fs-6">({{ $data->pelanggan->email }})</span>,
                            <br />
                            <span class="text-muted fs-5">Berikut ini adalah detail tagihan untuk anda.</span>
                        </div>
                        <div class="separator"></div>
                        <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                            <div class="flex-root d-flex flex-column">
                                <span class="text-muted">Kode Invoice</span>
                                <span class="fs-5">#{{ $data->kode_invoice }}</span>
                            </div>
                            <div class="flex-root d-flex flex-column">
                                <span class="text-muted">Tanggal Dikirim</span>
                                <span class="fs-5">{{ dateindo($data->tgl) }}</span>
                            </div>
                            <div class="flex-root d-flex flex-column">
                                <span class="text-muted">Tanggal Jatuh Tempo</span>
                                <span class="fs-5">{{ dateindo($data->inv_j_tempo) }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                            <div class="flex-root d-flex flex-column">
                                <span class="text-muted">Billing Address</span>
                                <span class="fs-6">{{ $data->pelanggan->alamat }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-column">
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-detail">
                                    <thead>
                                        <tr class="border-bottom fs-6 fw-bold text-muted">
                                            <th class="ps-4 min-w-125px rounded-start">Kode STT</th>
                                            <th>Asal</th>
                                            <th>Tujuan</th>
                                            <th class="text-end">Nominal</th>
                                            <th>Bayar</th>
                                            <th class="text-end"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @php
                                            $total = 0;
                                            $total_bayar = 0;
                                        @endphp
                                        @foreach ($detail as $value)
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->asuransi->id_stt) ? $value->asuransi->id_stt : '-' }}</a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                        {{ isset($value->asuransi->nm_pengirim) ? $value->asuransi->nm_pengirim : '-' }}</span>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->asuransi->asal) ? ucwords(strtolower($value->asuransi->asal)) : '-' }}</a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Berangkat :
                                                        {{ isset($value->asuransi->tgl_berangkat) ? dateindo($value->asuransi->tgl_berangkat) : '-' }}</span>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->asuransi->tujuan) ? ucwords(strtolower($value->asuransi->tujuan)) : '-' }}</a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Est. Sampai
                                                        :
                                                        {{ isset($value->asuransi->tgl_sampai) ? dateindo($value->asuransi->tgl_sampai) : '-' }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="javascript:void(0)"
                                                        class="text-dark fw-bold text-hover-primary d-block mb-1 fs-4">{{ isset($value->asuransi->nominal_jual) ? toRupiah($value->asuransi->nominal_jual) : '-' }}</a>
                                                </td>
                                                <td>
                                                    {{ toRupiah($value->bayar) }}
                                                </td>
                                                <td class="text-end">
                                                    @if ($bayar == null)
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-sm btn-danger btn-delete" type="button"
                                                            data-url="{{ url(Request::segment(1)) . '/' . $value->id_detail . '/delete-stt' }}">
                                                            <span><i class="fa fa-trash"></i></span>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $total += isset($value->asuransi->nominal_jual)
                                                    ? $value->asuransi->nominal_jual
                                                    : 0;

                                                $total_bayar += isset($value->bayar)
                                                    ? $value->bayar
                                                    : 0;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-end">Subtotal</td>
                                            <td class="text-end">{{ toRupiah($total) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">Dibayar</td>
                                            <td class="text-end">{{ toRupiah($total_bayar) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="fs-3 text-dark fw-bold text-end">Grand Total</td>
                                            <td class="text-dark fs-3 fw-bolder text-end">{{ toRupiah($total - $total_bayar) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">
                    <div class="my-1 me-5">
                        <button type="button" class="btn btn-success my-1 me-12" onclick="window.print();">Print
                            Invoice</button>
                        <button type="button" class="btn btn-light-success my-1">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
