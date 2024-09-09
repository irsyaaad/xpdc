@extends('template.document2')

@section('data')
<div class="row">
    <table class="table table-responsive table-bordered" id="tableasal">
        <thead style="background-color : grey; color: #fff">
            <tr>
                <th rowspan="2">No. </th>
                <th rowspan="2">No. STT</th>
                <th rowspan="2">Tgl Masuk</th>
                <th rowspan="2">Perusahaan Asal</th>
                <th rowspan="2">Pelanggan</th>
                <th rowspan="2">Pengirim</th>
                <th colspan="4"  class="text-center">Jumlah</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
                <th>Koli</th>
                <th>Berat</th>
                <th>Volume</th>
                <th>Kubik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ ($key+1) }}</td>
                    <td>{{ strtoupper($value->kode_stt) }}</td>
                    <td>{{ dateindo($value->tgl_masuk) }}</td>
                    <td>{{ $value->nm_perush }}</td>
                    <td>{{ $value->nm_pelanggan }}</td>
                    <td>{{ $value->pengirim_nm }}</td>
                    <td>{{ $value->n_koli }}</td>
                    <td>{{ $value->n_berat }}</td>
                    <td>{{ $value->n_volume }}</td>
                    <td>{{ $value->n_kubik }}</td>
                    <td>
                        <a href="{{ url(Request::segment(1)."/import/".$value->id_stt) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Import</a>
                    </td>
                </tr>
            @endforeach
            @if($data == null)
            <tr>
                <td colspan="8" class="text-center">
                    <b>Data Kosong</b>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection