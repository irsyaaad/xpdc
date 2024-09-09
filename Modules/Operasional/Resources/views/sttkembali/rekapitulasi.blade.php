@extends('template.document2')

@section('data')
    @include('filter.filter-' . Request::segment(1))
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }

        td {
            padding-right: 5px !important;
            text-align: right;
        }
    </style>

    <div class="col text-center mb-3">
        <h4>Rekapitulasi Status Dokumen Kembali</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th colspan="2">Nama Region</th>
                    <th>STT</th>
                    <th>Kembali</th>
                    <th>%</th>
                    <th>Belum</th>
                    <th>%</th>
                    <th>Rata Hari</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                        <td colspan="8" class="text-left">{{ $key }}</td>
                    </tr>
                    @php
                        $total_stt = 0;
                        $total_kembali = 0;
                        $total_belum = 0;
                        $arr_kembali = [];
                        $arr_belum = [];
                        $arr_rata_rata = [];
                    @endphp
                    @foreach ($data[$key] as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-left"><a
                                    href="{{ route('detail-rekapitulasi-stt-kembali', [
                                        'id_tujuan' => $item->id_tujuan,
                                        'dr_tgl' => $filter['dr_tgl'],
                                        'sp_tgl' => $filter['sp_tgl'],
                                    ]) }}"
                                    style="color:black;">{{ $item->wilayah }}</a></td>
                            <td>{{ $item->total_stt }}</td>
                            <td>{{ $item->stt_kembali }}</td>
                            <td>{{ $item->total_stt != 0 && $item->stt_kembali != 0 ? round(($item->stt_kembali / $item->total_stt) * 100, 2) : 0 }}
                            </td>
                            <td>{{ $item->stt_belum }}</td>
                            <td>{{ $item->total_stt != 0 && $item->stt_belum != 0 ? round(($item->stt_belum / $item->total_stt) * 100, 2) : 0 }}
                            </td>
                            <td>{{ $item->rata_rata_hari }}</td>
                        </tr>
                        @php
                            $total_stt += $item->total_stt;
                            $total_kembali += $item->stt_kembali;
                            $total_belum += $item->stt_belum;
                            $arr_kembali[] =
                                $item->total_stt != 0 && $item->stt_kembali != 0
                                    ? round(($item->stt_kembali / $item->total_stt) * 100, 2)
                                    : 0;
                            $arr_belum[] =
                                $item->total_stt != 0 && $item->stt_belum != 0
                                    ? round(($item->stt_belum / $item->total_stt) * 100, 2)
                                    : 0;
                            $arr_rata_rata[] = $item->rata_rata_hari;
                        @endphp
                    @endforeach
                    <tr style="background-color: rgb(238, 233, 233)" class="tr-bold">
                        <td colspan="2"></td>
                        <td>{{ $total_stt }}</td>
                        <td>{{ $total_kembali }}</td>
                        <td>{{ round(array_sum($arr_kembali) / count($arr_kembali), 2) }}</td>
                        <td>{{ $total_belum }}</td>
                        <td>{{ round(array_sum($arr_belum) / count($arr_belum), 2) }}</td>
                        <td>{{ round(array_sum($arr_rata_rata) / count($arr_rata_rata), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
