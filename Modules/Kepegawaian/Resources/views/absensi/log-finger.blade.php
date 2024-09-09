@extends('template.document2')

@section('data')
    <div class="col-md-12 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>
    <div class="container">
        <table class="table-borderless" width="100%">
            <tr>
                <td>Nama Karyawan</td>
                <td>:</td>
                <td>{{ $karyawan->nm_karyawan }}</td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td>{{ $karyawan->nm_perush }}</td>
            </tr>
            <tr>
                <td>ID Finger</td>
                <td>:</td>
                <td>{{ $karyawan->id_finger }}</td>
            </tr>
            <tr>
                <td>ID CLoud</td>
                <td>:</td>
                <td>{{ $karyawan->cloud_id }}</td>
            </tr>
            <tr>
                <td>Mesin Finger</td>
                <td>:</td>
                <td>{{ $karyawan->nm_mesin }}</td>
            </tr>
        </table>
    </div>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                @php
                    foreach ($finger as $key => $value) {
                        header('Content-Type: application/json');
                        echo '<pre>' .
                            json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
                            '</pre>';
                    }
                @endphp
            </div>
        </div>
    </div>
@endsection
