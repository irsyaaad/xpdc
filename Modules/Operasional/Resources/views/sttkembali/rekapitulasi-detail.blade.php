@extends('template.document2')

@section('data')
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }

        td {
            padding-right: 5px !important;
        }
    </style>

    <div class="col text-center mb-3">
        <h4>Detail Rekapitulasi Status Dokumen Kembali</h4>
        <h5>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
        <h4>( {{ $wilayah->nama_wil }} )</h4>
    </div>
    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Kode STT</th>
                    <th>Tgl STT</th>
                    <th>Nama Pengirim</th>
                    <th>Tgl Terima</th>
                    <th>Lama Hari</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr style="color : {{ isset($item->tgl) ? 'green' : 'red' }}">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->kode_stt }}</td>
                        <td>{{ isset($item->tgl_masuk) ? dateindo($item->tgl_masuk) : '-' }}</td>
                        <td>{{ ucwords(strtolower($item->pengirim_nm)) }}</td>
                        <td>{{ isset($item->tgl) ? dateindo($item->tgl) : '-' }}</td>
                        <td>{{ $item->lama_hari }}</td>
                        <td>{{ isset($item->tgl) ? 'Sudah di Terima' : 'Belum di Terima' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
