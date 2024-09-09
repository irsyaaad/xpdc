@extends('templatev2.defaultlayout')

@section('content')
    <form action="{{ url(Request::segment(1)) . '/save-draft/' . Request::segment(3) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 text-end">
                <input type="hidden" name="id_invoice" value="{{ Request::segment(3) }}">
                <button class="btn btn-sm btn-success" type="submit" ttile="Tambah">
                    <i class="fa fa-plus"></i>
                </button>
                <a href="{{ url(Request::segment(1) . '/' . Request::segment(3) . '/show') }}"
                    class="btn btn-sm btn-warning" title="Kembali"><i class="fa fa-reply"></i></a>
            </div>
        </div>
        <br>
        {{-- <input type="text" class="form-control" id="search" placeholder="Type to search"> --}}
        <div class="row">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table-stt">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-125px rounded-start">Kode STT</th>
                                    <th>Pelanggan</th>
                                    <th>Asal</th>
                                    <th>Tujuan</th>
                                    <th>Nominal</th>
                                    <th class="text-center rounded-end">
                                        <label>
                                            <input type="checkbox" value="1" id="c_all" name="c_all"> <b>Pilih
                                                Semua</b>
                                        </label>
                                    </th>
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
                                                class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->asal->nama_wil) ? ucwords(strtolower($value->asal->nama_wil)) : '-' }}</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">Berangkat :
                                                {{ isset($value->tgl_berangkat) ? dateindo($value->tgl_berangkat) : '-' }}</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->tujuan->nama_wil) ? ucwords(strtolower($value->tujuan->nama_wil)) : '-' }}</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">Est. Sampai :
                                                {{ isset($value->tgl_sampai) ? dateindo($value->tgl_sampai) : '-' }}</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">{{ isset($value->nominal_jual) ? toRupiah($value->nominal_jual) : '-' }}</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">Beli :
                                                {{ isset($value->nominal_beli) ? toRupiah($value->nominal_beli) : '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" id="id_stt_{{ $key }}" type="checkbox" name="id_asuransi[]" value="{{ $value->id_asuransi }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
