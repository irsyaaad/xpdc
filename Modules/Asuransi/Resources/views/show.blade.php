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
                            <div class="fw-bold fs-3 text-gray-800 mb-8">#{{ $data->id_stt }}</div>
                            <div class="row g-5 mb-11">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tanggal Berangkat:</div>
                                    <div class="fw-bold fs-6 text-gray-800">{{ dateindo($data->tgl_berangkat) }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tanggal Sampai:</div>
                                    <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">{{ dateindo($data->tgl_sampai) }}</span>
                                        <span class="fs-7 text-danger d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-danger me-2"></span>Due in 7 days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Pelanggan:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->pelanggan->nm_perush) ? ucwords(strtolower($data->pelanggan->nm_perush)) : '' }}
                                    </div>
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ isset($data->pelanggan->alamat) ? ucwords(strtolower($data->pelanggan->alamat)) : '' }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Nama Pengirim:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->nm_pengirim) ? ucwords(strtolower($data->nm_pengirim)) : '' }}.
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Nama Kapal:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->nm_kapal) ? ucwords($data->nm_kapal) : '' }}.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">NOPOL/NO CONTAINER:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->no_identity) ? ucwords($data->no_identity) : '' }}.
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Asal:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->asal->nama_wil) ? ucwords($data->asal->nama_wil) : $data->asal }}.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tujuan:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->tujuan->nama_wil) ? ucwords($data->tujuan->nama_wil) : $data->tujuan }}.
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Tipe Barang:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->tipebarang->nm_tipe_kirim) ? ucwords($data->tipebarang->nm_tipe_kirim) : $data->nm_tipe_barang }}.
                                    </div>
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ isset($data->qty) ? ucwords(strtolower($data->qty)) : '' }} Koli
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Harga:</div>
                                    <span class="fw-bold fs-6 badge badge-light-primary me-2">
                                        {{ isset($data->nominal_jual) ? toRupiah($data->nominal_jual) : '' }}
                                    </span>
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        Hpp : {{ isset($data->nominal_beli) ? toRupiah($data->nominal_beli) : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="row g-5 mb-12">
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Broker:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->perush_asuransi->nm_perush_asuransi) ? ucwords($data->perush_asuransi->nm_perush_asuransi) : '' }}.
                                    </div>
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ isset($data->perush_asuransi->alamat) ? ucwords($data->perush_asuransi->alamat) : '' }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Harga Pertanggungan:</div>
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ isset($data->harga_pertanggungan) ? toRupiah($data->harga_pertanggungan) : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- {{ dd($data) }} --}}
                <div class="m-0">
                    <div
                        class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                        <div class="mb-8">
                            <span class="badge badge-light-success me-2">Approved</span>
                            <span class="badge badge-light-warning">Pending Payment</span>
                        </div>
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">INVOICE DETAILS</h6>
                        @if (isset($invoice))
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Kode Invoice:</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $invoice->kode_invoice ?? '' }}</div>
                            </div>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Nama Pelanggan:</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $invoice->pelanggan->nm_perush ?? '' }}
                                    <br />{{ isset($invoice->pelanggan->telp) ? $invoice->pelanggan->telp : '' }}
                                </div>
                            </div>
                            <div class="mb-15">
                                <div class="fw-semibold text-gray-600 fs-7">Jangka Waktu:</div>
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                    @php
                                        if (isset($invoice)) {
                                            $date1 = new DateTime($invoice->inv_j_tempo);
                                            $date2 = new DateTime($invoice->tgl);
                                            $interval = $date1->diff($date2);
                                            echo $interval->days;
                                        }
                                    @endphp
                                    <span class="fs-7 text-danger d-flex align-items-center">
                                        <span class="bullet bullet-dot bg-danger mx-2"></span>Due in 7 days</span>
                                </div>
                            </div>
                            <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">RINCIAN PEMBAYARAN</h6>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Total:</div>
                                <div class="fw-bold text-gray-800 fs-6">
                                    {{ isset($data->nominal_jual) ? toRupiah($data->nominal_jual) : '' }}</div>
                            </div>
                            <div class="mb-6">
                                <div class="fw-semibold text-gray-600 fs-7">Bayar:</div>
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                    {{ isset($bayar) ? toRupiah($bayar) : '' }}
                                    <span class="fs-7 text-success d-flex align-items-center">
                                </div>
                            </div>
                            <div class="m-0">
                                <div class="fw-semibold text-gray-600 fs-7">Kurang:</div>
                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                    {{ isset($bayar) ? toRupiah($data->nominal_jual - $bayar) : $data->nominal_jual }}
                                    <span class="fs-7 text-success d-flex align-items-center">
                                </div>
                            </div>
                        @else
                            <div class="fw-semibold text-gray-600 fs-7">Belum Dibuatkan Invoice</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
