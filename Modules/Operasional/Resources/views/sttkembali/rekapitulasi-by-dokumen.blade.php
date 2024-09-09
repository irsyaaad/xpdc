@extends('template.document2')

@section('data')
    @include('filter.filter-' . 'rekapitulasi-stt-kembali')
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
        <h4>Rekapitulasi Status Barang</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Status Dokumen STT</th>
                    <th colspan="2">STT</th>
                    <th rowspan="2">Rata Hari</th>
                    <th rowspan="2">Persen</th>
                </tr>
                <tr>
                    <th>Terbit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekapStatusBarang as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><a href="{{ route('detail-rekapitulasi-stt-kembali-by-dokumen', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            'kode_status' => $item->kode_status
                        ]) }}"
                                style="color:black;">{{ ucwords(strtolower($item->status)) }}</a></td>
                        <td>{{ $item->total_stt_terbit }}</td>
                        <td>{{ $item->total_stt_kembali }}</td>
                        <td>{{ $item->rata_total_hari }}</td>
                        <td>
                            {{ $item->total_stt_terbit != 0 && $item->total_stt_kembali != 0 ? round(($item->total_stt_kembali / $item->total_stt_terbit) * 100, 2) : 0 }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <ul>
        <li>Periode Status Dokumen STT adalah tanggal masuk STT</li>
        <li>Rata hari dihitung dari tanggal masuk STT sampai tanggal terakhir status diupdate</li>
        <li>Persen adalah STT berstatus / STT Terbit</li>
    </ul>

    <br>
    <hr>
    <div class="col text-center mb-3">
        <h4>Rekapitulasi Status Dokumen Kembali</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Status Dokumen STT</th>
                    <th colspan="2">STT</th>
                    <th rowspan="2">Rata Hari</th>
                    <th rowspan="2">Persen</th>
                </tr>
                <tr>
                    <th>Terbit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekapStatusDokumen as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->total_stt_terbit }}</td>
                        <td>{{ $item->total_stt_kembali }}</td>
                        <td>{{ $item->rata_total_hari }}</td>
                        <td>
                            {{ $item->total_stt_terbit != 0 && $item->total_stt_kembali != 0 ? round(($item->total_stt_kembali / $item->total_stt_terbit) * 100, 2) : 0 }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <ul>
        <li>Periode Status Dokumen STT adalah tanggal masuk STT</li>
        <li>Rata hari dihitung dari tanggal masuk STT sampai tanggal terakhir status Dokumen</li>
    </ul>
@endsection
