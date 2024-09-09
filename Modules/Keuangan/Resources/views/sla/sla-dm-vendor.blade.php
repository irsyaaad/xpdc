@extends('template.document2')

@section('data')
    @include('filter.filter-' . Request::segment(1))
    <style>
        th {
            text-align: center;
        }
    </style>

    <div class="col text-center mb-3">
        <h4>SLA DM Vendor</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Vendor</th>
                    <th rowspan="2">DM</th>
                    <th rowspan="2">STT</th>
                    <th rowspan="2">Koli</th>
                    <th colspan="5">Muat</th>
                    <th colspan="5">Selesai</th>
                </tr>
                <tr>
                    {{-- STT - Dimuat --}}
                    <th>Diff</th>
                    <th>Def</th>
                    <th>Selisih</th>
                    <th>OK</th>
                    <th>%</th>
                    {{-- Dimuat - Tiba --}}
                    <th>Diff</th>
                    <th>Def</th>
                    <th>Selisih</th>
                    <th>OK</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $def = 3;
                    $grand_total_data = 0;
                    $grand_total_dm = 0;
                    $grand_total_stt = 0;
                    $grand_total_koli = 0;
                    $grand_total_diff_muat = 0;
                    $grand_total_selisih_muat = 0;
                    $grand_total_ok_muat = 0;
                    $grand_total_diff_selesai = 0;
                    $grand_total_selisih_selesai = 0;
                    $grand_total_ok_selesai = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                        <td></td>
                        <td colspan="14">{{ $key }}</td>
                    </tr>
                    @php
                        $total_dm = 0;
                        $total_stt = 0;
                        $total_koli = 0;
                        $total_diff_muat = 0;
                        $total_selisih_muat = 0;
                        $total_ok_muat = 0;
                        $total_diff_selesai = 0;
                        $total_selisih_selesai = 0;
                        $total_ok_selesai = 0;
                    @endphp
                    @foreach ($value as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><a href="{{ route('show-detail-sla-dm-vendor', [
                                'vendor' => $item->id_ven,
                                'wilayah' => $item->wilayah,
                                'dr_tgl' => $filter['dr_tgl'],
                                'sp_tgl' => $filter['sp_tgl'],
                            ]) }}"
                                    style="color:black;">{{ $item->wilayah }}</a></td>
                            <td>{{ $item->id_dm }}</td>
                            <td>{{ $item->stt }}</td>
                            <td>{{ $item->koli }}</td>
                            <td>{{ $item->dibuat_ke_berangkat }}</td>
                            <td>{{ $def }}</td>
                            <td>{{ $item->dibuat_ke_berangkat - $def }}</td>
                            <td>{{ $item->dibuat_ke_berangkat_ok }}</td>
                            <td>{{ ($item->dibuat_ke_berangkat_ok && $item->stt) > 0 ? round(($item->dibuat_ke_berangkat_ok / $item->stt) * 100, 2) : '0' }}
                            </td>
                            {{-- Selesai --}}
                            <td>{{ $item->berangkat_sampai }}</td>
                            <td>{{ $def }}</td>
                            <td>{{ $item->berangkat_sampai - $def }}</td>
                            <td>{{ $item->berangkat_selesai_ok }}</td>
                            <td>{{ ($item->berangkat_selesai_ok && $item->stt) > 0 ? round(($item->berangkat_selesai_ok / $item->stt) * 100, 2) : '0' }}
                            </td>
                        </tr>
                        @php
                            $total_dm += $item->id_dm;
                            $total_stt += $item->stt;
                            $total_koli += $item->koli;
                            $total_diff_muat += $item->dibuat_ke_berangkat;
                            $total_selisih_muat += $item->dibuat_ke_berangkat - $def;
                            $total_ok_muat += $item->dibuat_ke_berangkat_ok;
                            $total_diff_selesai += $item->berangkat_sampai;
                            $total_selisih_selesai += $item->berangkat_sampai - $def;
                            $total_ok_selesai += $item->berangkat_selesai_ok;

                            $grand_total_data += 1;
                            $grand_total_dm += $item->id_dm;
                            $grand_total_stt += $item->stt;
                            $grand_total_koli += $item->koli;
                            $grand_total_diff_muat += $item->dibuat_ke_berangkat;
                            $grand_total_selisih_muat += $item->dibuat_ke_berangkat - $def;
                            $grand_total_ok_muat += $item->dibuat_ke_berangkat_ok;
                            $grand_total_diff_selesai += $item->berangkat_sampai;
                            $grand_total_selisih_selesai += $item->berangkat_sampai - $def;
                            $grand_total_ok_selesai += $item->berangkat_selesai_ok;
                        @endphp
                    @endforeach
                    <tr style="background-color: rgb(243, 243, 243)" class="tr-bold">
                        <td colspan="2" class="text-center">Total</td>
                        <td>{{ $total_dm }}</td>
                        <td>{{ $total_stt }}</td>
                        <td>{{ $total_koli }}</td>
                        {{-- Muat --}}
                        <td>{{ $total_diff_muat }}</td>
                        <td>{{ $def }}</td>
                        <td>{{ $total_selisih_muat }}</td>
                        <td>{{ $total_ok_muat }}</td>
                        <td></td>
                        {{-- Selesai --}}
                        <td>{{ $total_diff_selesai }}</td>
                        <td>{{ $def }}</td>
                        <td>{{ $total_selisih_selesai }}</td>
                        <td>{{ $total_ok_selesai }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr style="background-color: rgb(230, 226, 226)" class="tr-bold">
                    <td colspan="2" class="text-center">Total</td>
                    <td>{{ $grand_total_dm }}</td>
                    <td>{{ $grand_total_stt }}</td>
                    <td>{{ $grand_total_koli }}</td>
                    {{-- Muat --}}
                    <td>{{ $grand_total_diff_muat > 0 && $grand_total_data > 0 ? round($grand_total_diff_muat / $grand_total_data) : 0 }}
                    </td>
                    <td>{{ $def }}</td>
                    <td>{{ $grand_total_selisih_muat > 0 && $grand_total_data > 0 ? round($grand_total_selisih_muat / $grand_total_data) : 0 }}
                    </td>
                    <td>{{ $grand_total_ok_muat > 0 && $grand_total_data > 0 ? round($grand_total_ok_muat / $grand_total_data) : 0 }}
                    </td>
                    <td></td>
                    {{-- Selesai --}}
                    <td>{{ $grand_total_diff_selesai > 0 && $grand_total_data > 0 ? round($grand_total_diff_selesai / $grand_total_data) : 0 }}
                    </td>
                    <td>{{ $def }}</td>
                    <td>{{ $grand_total_selisih_selesai > 0 && $grand_total_data > 0 ? round($grand_total_selisih_selesai / $grand_total_data) : 0 }}
                    </td>
                    <td>{{ $grand_total_ok_selesai > 0 && $grand_total_data > 0 ? round($grand_total_ok_selesai / $grand_total_data) : 0 }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
