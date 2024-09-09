@extends('template.document2')

@section('data')
    @include('filter.filter-' . Request::segment(1))
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
    </style>

    <div class="col text-center mb-3">
        <h4>SLA DM Trucking</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Region Tujuan</th>
                    <th rowspan="2">STT</th>
                    <th rowspan="2">Koli</th>
                    <th colspan="5">Rata Hari STT - Dimuat</th>
                    <th colspan="5">Rata Hari Dimuat - Tiba</th>
                    <th colspan="5">Rata Hari Tiba - Dooring</th>
                    <th colspan="5">Dooring - Diterima</th>
                </tr>
                <tr>
                    {{-- STT - Dimuat --}}
                    <th>Muat</th>
                    <th>Def | Lib</th>
                    <th>Dif</th>
                    <th>OK</th>
                    <th>%</th>
                    {{-- Dimuat - Tiba --}}
                    <th>Tiba</th>
                    <th>Def | Lib</th>
                    <th>Dif</th>
                    <th>OK</th>
                    <th>%</th>
                    {{-- Tiba - Dooring --}}
                    <th>Dooring</th>
                    <th>Def | Lib</th>
                    <th>Dif</th>
                    <th>OK</th>
                    <th>%</th>
                    {{-- Dooring - Diterima --}}
                    <th>Terima</th>
                    <th>Def | Lib</th>
                    <th>Dif</th>
                    <th>OK</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_dibuat_ke_berangkat = 0;
                    $total_dif_dibuat_ke_berangkat = 0;
                    $total_dibuat_ke_berangkat_ok = 0;
                    $total_dibuat_ke_berangkat_persen = 0;

                    $total_berangkat_ke_tiba = 0;
                    $total_dif_berangkat_ke_tiba = 0;
                    $total_berangkat_ke_tiba_ok = 0;
                    $total_berangkat_ke_tiba_persen = 0;

                    $total_tiba_ke_dooring = 0;
                    $total_dif_tiba_ke_dooring = 0;
                    $total_tiba_ke_dooring_ok = 0;
                    $total_tiba_ke_dooring_persen = 0;

                    $total_dooring_ke_sampai = 0;
                    $total_dif_dooring_ke_sampai = 0;
                    $total_dooring_ke_sampai_ok = 0;
                    $total_dooring_ke_sampai_persen = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><a href="{{ route('show-detail-sla-dm-trucking', [
                            'wilayah' => $value->wilayah,
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                        ]) }}"
                                style="color:black;">{{ $value->wilayah }}</a></td>
                        <td>{{ $value->stt }}</td>
                        <td>{{ $value->koli }}</td>
                        {{-- STT - Dimuat --}}
                        <td>{{ $value->dibuat_ke_berangkat }}</td>
                        <td>3 | {{ $value->minggu_dibuat_ke_berangkat }}</td>
                        <td>{{ $value->dibuat_ke_berangkat - 3 }}</td>
                        <td>{{ $value->dibuat_ke_berangkat_ok }}</td>
                        <td>{{ ($value->dibuat_ke_berangkat_ok && $value->stt) > 0 ? round(($value->dibuat_ke_berangkat_ok / $value->stt) * 100, 2) : '0' }}
                        </td>
                        @php
                            $total_dibuat_ke_berangkat += $value->dibuat_ke_berangkat;
                            $total_dif_dibuat_ke_berangkat += $value->dibuat_ke_berangkat - 3;
                            $total_dibuat_ke_berangkat_ok += $value->dibuat_ke_berangkat_ok;
                            $total_dibuat_ke_berangkat_persen +=
                                ($value->dibuat_ke_berangkat_ok && $value->stt) > 0
                                    ? round(($value->dibuat_ke_berangkat_ok / $value->stt) * 100, 2)
                                    : '0';
                        @endphp
                        {{-- STT - Dimuat --}}
                        <td>{{ $value->berangkat_ke_tiba }}</td>
                        <td>3 | {{ $value->minggu_berangkat_ke_tiba }}</td>
                        <td>{{ $value->berangkat_ke_tiba - 3 }}</td>
                        <td>{{ $value->berangkat_ke_tiba_ok }}</td>
                        <td>{{ ($value->berangkat_ke_tiba_ok && $value->stt) > 0 ? round(($value->berangkat_ke_tiba_ok / $value->stt) * 100, 2) : '0' }}
                        </td>
                        @php
                            $total_berangkat_ke_tiba += $value->berangkat_ke_tiba;
                            $total_dif_berangkat_ke_tiba += $value->berangkat_ke_tiba - 3;
                            $total_berangkat_ke_tiba_ok += $value->berangkat_ke_tiba_ok;
                            $total_berangkat_ke_tiba_persen +=
                                ($value->berangkat_ke_tiba_ok && $value->stt) > 0
                                    ? round(($value->berangkat_ke_tiba_ok / $value->stt) * 100, 2)
                                    : '0';
                        @endphp
                        {{-- STT - Dimuat --}}
                        <td>{{ $value->tiba_ke_dooring }}</td>
                        <td>3 | {{ $value->minggu_tiba_ke_dooring }}</td>
                        <td>{{ $value->tiba_ke_dooring - 3 }}</td>
                        <td>{{ $value->tiba_ke_dooring_ok }}</td>
                        <td>{{ ($value->tiba_ke_dooring_ok && $value->stt) > 0 ? round(($value->tiba_ke_dooring_ok / $value->stt) * 100, 2) : '0' }}
                        </td>
                        @php
                            $total_tiba_ke_dooring += $value->tiba_ke_dooring;
                            $total_dif_tiba_ke_dooring += $value->tiba_ke_dooring - 3;
                            $total_tiba_ke_dooring_ok += $value->tiba_ke_dooring_ok;
                            $total_tiba_ke_dooring_persen +=
                                ($value->tiba_ke_dooring_ok && $value->stt) > 0
                                    ? round(($value->tiba_ke_dooring_ok / $value->stt) * 100, 2)
                                    : '0';
                        @endphp
                        {{-- STT - Dimuat --}}
                        <td>{{ $value->dooring_ke_sampai }}</td>
                        <td>3 | {{ $value->minggu_dooring_ke_sampai }}</td>
                        <td>{{ $value->dooring_ke_sampai - 3 }}</td>
                        <td>{{ $value->dooring_ke_sampai_ok }}</td>
                        <td>{{ ($value->dooring_ke_sampai_ok && $value->stt) > 0 ? round(($value->dooring_ke_sampai_ok / $value->stt) * 100, 2) : '0' }}
                        </td>
                        @php
                            $total_dooring_ke_sampai += $value->dooring_ke_sampai;
                            $total_dif_dooring_ke_sampai += $value->dooring_ke_sampai - 3;
                            $total_dooring_ke_sampai_ok += $value->dooring_ke_sampai_ok;
                            $total_dooring_ke_sampai_persen +=
                                ($value->dooring_ke_sampai_ok && $value->stt) > 0
                                    ? round(($value->dooring_ke_sampai_ok / $value->stt) * 100, 2)
                                    : '0';
                        @endphp
                    </tr>
                @endforeach
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td colspan="4"></td>
                    <td>{{ $total_dibuat_ke_berangkat > 0 && count($data) > 0 ? round($total_dibuat_ke_berangkat / count($data), 2) : 0 }}
                    </td>
                    <td></td>
                    <td>{{ $total_dif_dibuat_ke_berangkat > 0 && count($data) > 0 ? round($total_dif_dibuat_ke_berangkat / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_dibuat_ke_berangkat_ok > 0 && count($data) > 0 ? round($total_dibuat_ke_berangkat_ok / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_dibuat_ke_berangkat_persen > 0 && count($data) ? round($total_dibuat_ke_berangkat_persen / count($data), 2) : 0 }}
                    </td>

                    <td>{{ $total_berangkat_ke_tiba > 0 && count($data) > 0 ? round($total_berangkat_ke_tiba / count($data), 2) : 0 }}
                    </td>
                    <td></td>
                    <td>{{ $total_dif_berangkat_ke_tiba > 0 && count($data) > 0 ? round($total_dif_berangkat_ke_tiba / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_berangkat_ke_tiba_ok > 0 && count($data) > 0 ? round($total_berangkat_ke_tiba_ok / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_berangkat_ke_tiba_persen > 0 && count($data) > 0 ? round($total_berangkat_ke_tiba_persen / count($data), 2) : 0 }}
                    </td>

                    <td>{{ $total_tiba_ke_dooring > 0 && count($data) > 0 ? round($total_tiba_ke_dooring / count($data), 2) : 0 }}
                    </td>
                    <td></td>
                    <td>{{ $total_dif_tiba_ke_dooring > 0 && count($data) > 0 ? round($total_dif_tiba_ke_dooring / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_tiba_ke_dooring_ok > 0 && count($data) > 0 ? round($total_tiba_ke_dooring_ok / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_tiba_ke_dooring_persen > 0 && count($data) > 0 ? round($total_tiba_ke_dooring_persen / count($data), 2) : 0 }}
                    </td>

                    <td>{{ $total_dooring_ke_sampai > 0 && count($data) > 0 ? round($total_dooring_ke_sampai / count($data), 2) : 0 }}
                    </td>
                    <td></td>
                    <td>{{ $total_dif_dooring_ke_sampai > 0 && count($data) > 0 ? round($total_dif_dooring_ke_sampai / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_dooring_ke_sampai_ok > 0 && count($data) > 0 ? round($total_dooring_ke_sampai_ok / count($data), 2) : 0 }}
                    </td>
                    <td>{{ $total_dooring_ke_sampai_persen > 0 && count($data) > 0 ? round($total_dooring_ke_sampai_persen / count($data), 2) : 0 }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
