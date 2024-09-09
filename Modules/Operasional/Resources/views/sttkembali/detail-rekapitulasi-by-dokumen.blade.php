@extends('template.document2')

@section('data')
    @include('filter.filter-' . 'rekapitulasi-stt-kembali')
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
    </style>

    <div class="col text-center mb-3">
        <h4>Detail Rekapitulasi Status Barang</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>

    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Kode STT</th>
                    <th>Tanggal</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Alamat Penerima</th>
                    <th>Kg</th>
                    <th>KgV</th>
                    <th>M3</th>
                    <th>Koli</th>
                    <th>Omset</th>
                    <th>Count Stat</th>
                    <th>Nama Status</th>
                    <th>Tgl Status</th>
                    <th>Selisih Hari</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekapStatusDokumen as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->kode_stt }}</td>
                        <td>{{ dateindo($item->tgl_masuk) }}</td>
                        <td>{{ $item->pengirim_nm }}</td>
                        <td>{{ $item->penerima_nm }}</td>
                        <td>{{ $item->penerima_alm }}</td>
                        <td>{{ $item->n_berat }}</td>
                        <td>{{ $item->n_volume }}</td>
                        <td>{{ $item->n_kubik }}</td>
                        <td>{{ $item->n_koli }}</td>
                        <td>{{ $item->c_total }}</td>
                        <td>1</td>
                        <td>{{ ucwords(strtolower($item->nm_status)) }}</td>
                        <td>{{ dateindo($item->tgl_update) }}</td>
                        <td>{{ $item->selisih_hari }}</td>
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
@endsection
